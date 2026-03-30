<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Niveau;
use App\Models\Parcours;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class DocumentController extends Controller
{
    private const MAX_UPLOAD_KB = 5120; // 5 Mo

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function ensureCloudinaryConfigured(): void
    {
        $disk = config('filesystems.disks.cloudinary');

        if (! is_array($disk)) {
            throw ValidationException::withMessages([
                'fichier' => "Cloudinary n'est pas configuré sur ce serveur.",
            ]);
        }

        $hasUrl         = ! empty($disk['url']);
        $hasCredentials = ! empty($disk['cloud']) && ! empty($disk['key']) && ! empty($disk['secret']);

        if (! $hasUrl && ! $hasCredentials) {
            throw ValidationException::withMessages([
                'fichier' => 'Configuration Cloudinary incomplète. Ajoutez CLOUDINARY_URL ou CLOUDINARY_CLOUD_NAME / CLOUDINARY_KEY / CLOUDINARY_SECRET.',
            ]);
        }
    }

    private function cloudinaryUpload(UploadedFile $file): string
    {
        $tmpPath = sys_get_temp_dir() . '/' . uniqid('pdf_', true) . '.pdf';
        copy($file->getRealPath(), $tmpPath);

        try {
            $result = Cloudinary::uploadApi()->upload(
                $tmpPath,
                [
                    'folder'        => 'notre_archive',
                    'resource_type' => 'raw',
                    'access_mode'   => 'public',
                ]
            );
        } finally {
            @unlink($tmpPath);
        }

        return $result['secure_url'];
    }

    private function scopedParcoursIdForUser(?User $user): ?int
    {
        if (! $user || $user->is_admin || $user->parcours_id === null) {
            return null;
        }

        return (int) $user->parcours_id;
    }

    private function canFilterByUser(?User $user): bool
    {
        return (bool) (
            $user?->is_admin
            || ($user?->can_manage_documents && $user?->parcours_id !== null)
        );
    }

    private function shouldAnonymizeDocumentAuthor(?User $user): bool
    {
        if (! $user) {
            return true;
        }

        return ! $user->is_admin && ! $user->can_manage_documents;
    }

    private function ensureParcoursAccess(Request $request, int $parcoursId): void
    {
        $user = $request->user();

        if (! $user || $user->is_admin || $user->parcours_id === null) {
            return;
        }

        if ((int) $user->parcours_id !== $parcoursId) {
            throw ValidationException::withMessages([
                'parcours_id' => 'Vous ne pouvez gérer que les documents de votre parcours.',
            ]);
        }
    }

    /**
     * Vérifie que l'utilisateur connecté peut supprimer ce document.
     * - Admin  : toujours autorisé
     * - Auteur : peut supprimer son propre document
     * - Autres : 403
     */
    private function ensureCanDelete(Request $request, Document $document): void
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'Action non autorisée.');
        }

        if ($user->is_admin || (int) $document->user_id === (int) $user->id) {
            return;
        }

        abort(403, 'Vous ne pouvez supprimer que vos propres documents.');
    }

    // ─── Front ───────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $user             = $request->user();
        $scopedParcoursId = $this->scopedParcoursIdForUser($user);
        $canFilterByUser  = $this->canFilterByUser($user);
        $anonymizeDocumentAuthor = $this->shouldAnonymizeDocumentAuthor($user);

        $parcoursList = $scopedParcoursId
            ? Parcours::query()->whereKey($scopedParcoursId)->get()
            : Parcours::all();

        $anneesList = Niveau::with('parcours')
            ->when($scopedParcoursId, fn ($q) => $q->where('parcours_id', $scopedParcoursId))
            ->get();

        $usersList = collect();
        if ($canFilterByUser) {
            $usersList = User::query()
                ->select('id', 'name')
                ->when($scopedParcoursId, fn ($q) => $q->where('parcours_id', $scopedParcoursId))
                ->orderBy('name')
                ->get();
        }

        $lockedParcours = $scopedParcoursId ? $parcoursList->first() : null;
        $documentTypes  = DocumentType::query()->orderBy('nom')->get();

        $query = Document::with(
            $anonymizeDocumentAuthor
                ? ['parcours', 'niveau', 'documentType']
                : ['parcours', 'niveau', 'user', 'documentType']
        );

        if ($scopedParcoursId) {
            $query->where('parcours_id', $scopedParcoursId);
        }

        if ($request->filled('parcours_id')) {
            $requestedParcoursId = (int) $request->input('parcours_id');
            if (! $scopedParcoursId || $scopedParcoursId === $requestedParcoursId) {
                $query->where('parcours_id', $requestedParcoursId);
            }
        }

        if ($request->filled('annee_id'))           $query->where('niveau_id',        $request->input('annee_id'));
        if ($request->filled('document_type_id'))   $query->where('document_type_id', $request->input('document_type_id'));
        if ($canFilterByUser && $request->filled('user_id')) $query->where('user_id', $request->input('user_id'));

        $documents = $query->latest()->paginate(9)->withQueryString();

        return view('documents.index', compact(
            'documents', 'parcoursList', 'anneesList', 'usersList',
            'documentTypes', 'canFilterByUser', 'lockedParcours', 'anonymizeDocumentAuthor'
        ));
    }

    public function create(Request $request)
    {
        $scopedParcoursId = $this->scopedParcoursIdForUser($request->user());

        $parcours = $scopedParcoursId
            ? Parcours::query()->whereKey($scopedParcoursId)->get()
            : Parcours::all();

        $annees = Niveau::with('parcours')
            ->when($scopedParcoursId, fn ($q) => $q->where('parcours_id', $scopedParcoursId))
            ->get();

        $documentTypes = DocumentType::query()->orderBy('nom')->get();

        return view('documents.create', compact('parcours', 'annees', 'documentTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre'            => 'required|string|max:255',
            'description'      => 'required|string',
            'document_type_id' => 'required|exists:document_types,id',
            'fichier'          => 'required|file|mimes:pdf|max:'.self::MAX_UPLOAD_KB,
            'parcours_id'      => 'required|exists:parcours,id',
            'niveau_id'        => [
                'required',
                Rule::exists('niveaux', 'id')->where(
                    fn ($q) => $q->where('parcours_id', $request->input('parcours_id'))
                ),
            ],
        ], [
            'fichier.mimes'    => 'Seuls les fichiers PDF sont acceptés.',
            'fichier.max'      => 'Le fichier dépasse 5 Mo. Compressez-le puis réessayez.',
            'niveau_id.exists' => "L'année sélectionnée ne correspond pas au parcours choisi.",
        ]);

        $this->ensureParcoursAccess($request, (int) $validated['parcours_id']);
        $this->ensureCloudinaryConfigured();

        Document::create([
            'titre'            => $validated['titre'],
            'description'      => $validated['description'],
            'document_type_id' => $validated['document_type_id'],
            'fichier'          => $this->cloudinaryUpload($request->file('fichier')),
            'niveau_id'        => $validated['niveau_id'],
            'parcours_id'      => $validated['parcours_id'],
            'user_id'          => $request->user()?->id,
        ]);

        return redirect()->route('documents.index')
            ->with('success', 'Document PDF créé et sauvegardé sur Cloudinary.');
    }

    public function destroy(Request $request, Document $document)
    {
        $this->ensureCanDelete($request, $document);
        $document->delete();

        return redirect()->route('documents.index')
            ->with('success', 'Document supprimé avec succès.');
    }

    public function view(string $id)
    {
        $url = Document::findOrFail($id)->fichier;
        abort_if(! $url, 404, 'Fichier introuvable.');

        return redirect()->away($url);
    }

    public function download(string $id)
    {
        $url = Document::findOrFail($id)->fichier;
        abort_if(! $url, 404, 'Fichier introuvable.');

        return redirect()->away(str_replace('/upload/', '/upload/fl_attachment/', $url));
    }

    // ─── API ─────────────────────────────────────────────────────────────────

    public function apiIndex(Request $request)
    {
        $scopedParcoursId = $this->scopedParcoursIdForUser($request->user());
        $query = Document::with(['parcours', 'niveau', 'documentType']);

        if ($scopedParcoursId) {
            $query->where('parcours_id', $scopedParcoursId);
        }

        if ($request->filled('parcours_id')) {
            $requestedParcoursId = (int) $request->input('parcours_id');
            if (! $scopedParcoursId || $scopedParcoursId === $requestedParcoursId) {
                $query->where('parcours_id', $requestedParcoursId);
            }
        }

        if ($request->filled('niveau_id'))         $query->where('niveau_id',        $request->input('niveau_id'));
        if ($request->filled('document_type_id'))  $query->where('document_type_id', $request->input('document_type_id'));

        return response()->json($query->paginate(9));
    }

    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'titre'            => 'required|string|max:255',
            'description'      => 'required|string',
            'document_type_id' => 'required|exists:document_types,id',
            'fichier'          => 'required|file|mimes:pdf|max:'.self::MAX_UPLOAD_KB,
            'parcours_id'      => 'required|exists:parcours,id',
            'niveau_id'        => [
                'required',
                Rule::exists('niveaux', 'id')->where(
                    fn ($q) => $q->where('parcours_id', $request->input('parcours_id'))
                ),
            ],
        ], [
            'fichier.mimes'    => 'Seuls les fichiers PDF sont acceptés.',
            'fichier.max'      => 'Le fichier dépasse 5 Mo. Compressez-le puis réessayez.',
            'niveau_id.exists' => "L'année sélectionnée ne correspond pas au parcours choisi.",
        ]);

        $this->ensureParcoursAccess($request, (int) $validated['parcours_id']);
        $this->ensureCloudinaryConfigured();

        $document = Document::create([
            'titre'            => $validated['titre'],
            'description'      => $validated['description'],
            'document_type_id' => $validated['document_type_id'],
            'fichier'          => $this->cloudinaryUpload($request->file('fichier')),
            'niveau_id'        => $validated['niveau_id'],
            'parcours_id'      => $validated['parcours_id'],
            'user_id'          => $request->user()?->id,
        ]);

        return response()->json(['message' => 'Document créé avec succès', 'data' => $document], 201);
    }

    public function apiUpdate(Request $request, string $id)
    {
        $document = Document::findOrFail($id);
        $this->ensureParcoursAccess($request, (int) $document->parcours_id);

        $validated = $request->validate([
            'titre'            => 'sometimes|string|max:255',
            'description'      => 'sometimes|string',
            'document_type_id' => 'sometimes|exists:document_types,id',
            'fichier'          => 'nullable|file|mimes:pdf|max:'.self::MAX_UPLOAD_KB,
            'niveau_id'        => 'sometimes|exists:niveaux,id',
            'parcours_id'      => 'sometimes|exists:parcours,id',
        ], [
            'fichier.mimes' => 'Seuls les fichiers PDF sont acceptés.',
            'fichier.max'   => 'Le fichier dépasse 5 Mo. Compressez-le puis réessayez.',
        ]);

        if ($request->filled('parcours_id')) {
            $this->ensureParcoursAccess($request, (int) $request->input('parcours_id'));
        }

        if ($request->filled('niveau_id')) {
            $targetParcoursId     = (int) ($request->input('parcours_id', $document->parcours_id));
            $niveauMatchesParcours = Niveau::query()
                ->whereKey($request->input('niveau_id'))
                ->where('parcours_id', $targetParcoursId)
                ->exists();

            if (! $niveauMatchesParcours) {
                throw ValidationException::withMessages([
                    'niveau_id' => "L'année sélectionnée ne correspond pas au parcours choisi.",
                ]);
            }
        }

        if ($request->hasFile('fichier')) {
            $this->ensureCloudinaryConfigured();
            $document->fichier = $this->cloudinaryUpload($request->file('fichier'));
            $document->save();
        }

        $document->update($request->except('fichier'));

        return response()->json(['message' => 'Document mis à jour', 'data' => $document]);
    }

    public function apiDestroy(Request $request, string $id)
    {
        $document = Document::findOrFail($id);
        $this->ensureParcoursAccess($request, (int) $document->parcours_id);
        $this->ensureCanDelete($request, $document);

        $document->delete();

        return response()->json(['message' => 'Document supprimé']);
    }
}
