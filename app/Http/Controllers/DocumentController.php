<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Niveau;
use App\Models\Parcours;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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

    // ─── Front ───────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $parcoursList    = Parcours::all();
        $anneesList      = Niveau::with('parcours')->get();
        $usersList       = collect();
        $canFilterByUser = (bool) ($request->user()?->is_admin || $request->user()?->can_manage_documents);

        if ($canFilterByUser) {
            $usersList = User::query()->select('id', 'name')->orderBy('name')->get();
        }

        $query = Document::with(['parcours', 'niveau', 'user']);

        if ($request->filled('parcours_id'))                 $query->where('parcours_id', $request->parcours_id);
        if ($request->filled('annee_id'))                    $query->where('niveau_id',   $request->annee_id);
        if ($canFilterByUser && $request->filled('user_id')) $query->where('user_id',     $request->user_id);

        $documents = $query->latest()->paginate(9)->withQueryString();

        return view('documents.index', compact(
            'documents', 'parcoursList', 'anneesList', 'usersList', 'canFilterByUser'
        ));
    }

    public function create()
    {
        $parcours = Parcours::all();
        $annees   = Niveau::with('parcours')->get();

        return view('documents.create', compact('parcours', 'annees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre'       => 'required|string|max:255',
            'description' => 'required|string',
            'fichier'     => 'required|file|mimes:pdf|max:'.self::MAX_UPLOAD_KB,
            'parcours_id' => 'required|exists:parcours,id',
            'niveau_id'   => [
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

        $this->ensureCloudinaryConfigured();

        Document::create([
            'titre'       => $validated['titre'],
            'description' => $validated['description'],
            'fichier'     => $this->cloudinaryUpload($request->file('fichier')),
            'niveau_id'   => $validated['niveau_id'],
            'parcours_id' => $validated['parcours_id'],
            'user_id'     => $request->user()?->id,
        ]);

        return redirect()->route('documents.index')
            ->with('success', 'Document PDF créé et sauvegardé sur Cloudinary.');
    }

    public function destroy(Document $document)
    {
        $document->delete();

        return redirect()->route('documents.index')
            ->with('success', 'Document supprimé avec succès.');
    }

    /**
     * Ouvre le PDF directement depuis l'URL Cloudinary stockée en base.
     */
    public function view(string $id)
    {
        $url = Document::findOrFail($id)->fichier;
        abort_if(! $url, 404, 'Fichier introuvable.');
        return redirect()->away($url);
    }

    /**
     * Télécharge le PDF via l'URL Cloudinary avec fl_attachment.
     */
    public function download(string $id)
    {
        $url = Document::findOrFail($id)->fichier;
        abort_if(! $url, 404, 'Fichier introuvable.');

        // fl_attachment force le téléchargement au lieu de l'affichage
        $downloadUrl = str_replace('/upload/', '/upload/fl_attachment/', $url);
        return redirect()->away($downloadUrl);
    }

    // ─── API ─────────────────────────────────────────────────────────────────

    public function apiIndex(Request $request)
    {
        $query = Document::with(['parcours', 'niveau']);

        if ($request->filled('parcours_id')) $query->where('parcours_id', $request->parcours_id);
        if ($request->filled('niveau_id'))   $query->where('niveau_id',   $request->niveau_id);

        return response()->json($query->paginate(9));
    }

    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'titre'       => 'required|string|max:255',
            'description' => 'required|string',
            'fichier'     => 'required|file|mimes:pdf|max:'.self::MAX_UPLOAD_KB,
            'parcours_id' => 'required|exists:parcours,id',
            'niveau_id'   => 'required|exists:niveaux,id',
        ], [
            'fichier.mimes' => 'Seuls les fichiers PDF sont acceptés.',
            'fichier.max'   => 'Le fichier dépasse 5 Mo. Compressez-le puis réessayez.',
        ]);

        $this->ensureCloudinaryConfigured();

        $document = Document::create([
            'titre'       => $validated['titre'],
            'description' => $validated['description'],
            'fichier'     => $this->cloudinaryUpload($request->file('fichier')),
            'niveau_id'   => $validated['niveau_id'],
            'parcours_id' => $validated['parcours_id'],
            'user_id'     => $request->user()?->id,
        ]);

        return response()->json(['message' => 'Document créé avec succès', 'data' => $document], 201);
    }

    public function apiUpdate(Request $request, string $id)
    {
        $document = Document::findOrFail($id);

        $request->validate([
            'titre'       => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'fichier'     => 'nullable|file|mimes:pdf|max:'.self::MAX_UPLOAD_KB,
            'niveau_id'   => 'sometimes|exists:niveaux,id',
            'parcours_id' => 'sometimes|exists:parcours,id',
        ], [
            'fichier.mimes' => 'Seuls les fichiers PDF sont acceptés.',
            'fichier.max'   => 'Le fichier dépasse 5 Mo. Compressez-le puis réessayez.',
        ]);

        if ($request->hasFile('fichier')) {
            $this->ensureCloudinaryConfigured();
            $document->fichier = $this->cloudinaryUpload($request->file('fichier'));
        }

        $document->update($request->except('fichier'));

        return response()->json(['message' => 'Document mis à jour', 'data' => $document]);
    }

    public function apiDestroy(string $id)
    {
        Document::findOrFail($id)->delete();

        return response()->json(['message' => 'Document supprimé']);
    }
}
