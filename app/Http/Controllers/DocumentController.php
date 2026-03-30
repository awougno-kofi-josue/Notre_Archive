<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Niveau;
use App\Models\Parcours;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class DocumentController extends Controller
{
    // On peut augmenter la limite car Cloudinary gère très bien les gros fichiers
    private const MAX_UPLOAD_KB = 10240; // 10 Mo

    public function index(Request $request)
    {
        $parcoursList = Parcours::all();
        $anneesList = Niveau::with('parcours')->get();
        $usersList = collect();
        $canFilterByUser = (bool) ($request->user()?->is_admin || $request->user()?->can_manage_documents);

        if ($canFilterByUser) {
            $usersList = User::query()->select('id', 'name')->orderBy('name')->get();
        }

        $query = Document::with(['parcours', 'niveau', 'user']);

        // Filtres
        if ($request->filled('parcours_id')) {
            $query->where('parcours_id', $request->parcours_id);
        }
        if ($request->filled('annee_id')) {
            $query->where('niveau_id', $request->annee_id);
        }
        if ($canFilterByUser && $request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $documents = $query->latest()->paginate(9)->withQueryString();

        return view('documents.index', compact('documents', 'parcoursList', 'anneesList', 'usersList', 'canFilterByUser'));
    }

    public function create()
    {
        $parcours = Parcours::all();
        $annees = Niveau::with('parcours')->get();
        return view('documents.create', compact('parcours', 'annees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'fichier' => 'required|file|mimes:pdf,doc,docx,png,jpg,jpeg,webp|max:'.self::MAX_UPLOAD_KB,
            'parcours_id' => 'required|exists:parcours,id',
            'niveau_id' => [
                'required',
                Rule::exists('niveaux', 'id')->where(function ($query) use ($request) {
                    $query->where('parcours_id', $request->input('parcours_id'));
                }),
            ],
        ], [
            'niveau_id.exists' => 'L\'année sélectionnée ne correspond pas au parcours choisi.',
        ]);

        // Upload vers Cloudinary dans le dossier 'notre_archive'
       $file = $request->file('fichier');
            $result = Cloudinary::upload($file->getRealPath(), [
                'folder' => 'notre_archive'
            ]);
            $path = $result->getSecurePath();
            
        Document::create([
            'titre' => $validated['titre'],
            'description' => $validated['description'],
            'fichier' => $path, // On stocke l'URL directe
            'niveau_id' => $validated['niveau_id'],
            'parcours_id' => $validated['parcours_id'],
            'user_id' => $request->user()?->id,
        ]);

        return redirect()->route('documents.index')->with('success', 'Document créé et sauvegardé sur Cloudinary.');
    }

    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);

        // Note: Pour supprimer sur Cloudinary, il faudrait stocker le public_id.
        // Ici, on supprime au moins l'entrée en base de données.
        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Document supprimé avec succès.');
    }

    // Visionnage et Téléchargement : On redirige simplement vers l'URL Cloudinary
    public function view(string $id)
    {
        $document = Document::findOrFail($id);
        return redirect()->away($document->fichier);
    }

    public function download(string $id)
    {
        $document = Document::findOrFail($id);
        // On redirige vers l'URL, le navigateur gérera selon le type de fichier
        return redirect()->away($document->fichier);
    }

    // --- SECTION API ---

    public function apiIndex(Request $request)
    {
        $query = Document::with(['parcours', 'niveau']);
        if ($request->filled('parcours_id')) $query->where('parcours_id', $request->parcours_id);
        if ($request->filled('niveau_id')) $query->where('niveau_id', $request->niveau_id);

        return response()->json($query->paginate(9));
    }

    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'fichier' => 'required|file|mimes:pdf,doc,docx,png,jpg,jpeg,webp|max:'.self::MAX_UPLOAD_KB,
            'parcours_id' => 'required|exists:parcours,id',
            'niveau_id' => 'required|exists:niveaux,id',
        ]);

        $result = $request->file('fichier')->storeOnCloudinary('notre_archive');
        
        $document = Document::create([
            'titre' => $validated['titre'],
            'description' => $validated['description'],
            'fichier' => $result->getSecurePath(),
            'niveau_id' => $validated['niveau_id'],
            'parcours_id' => $validated['parcours_id'],
            'user_id' => $request->user()?->id,
        ]);

        return response()->json(['message' => 'Document créé avec succès', 'data' => $document], 201);
    }

    public function apiUpdate(Request $request, string $id)
    {
        $document = Document::findOrFail($id);

        $validated = $request->validate([
            'titre' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'fichier' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg,webp|max:'.self::MAX_UPLOAD_KB,
            'niveau_id' => 'sometimes|exists:niveaux,id',
            'parcours_id' => 'sometimes|exists:parcours,id',
        ]);

        if ($request->hasFile('fichier')) {
            $result = $request->file('fichier')->storeOnCloudinary('notre_archive');
            $document->fichier = $result->getSecurePath();
        }

        $document->update($request->except('fichier'));

        return response()->json(['message' => 'Document mis à jour', 'data' => $document]);
    }

    public function apiDestroy(string $id)
    {
        $document = Document::findOrFail($id);
        $document->delete();
        return response()->json(['message' => 'Document supprimé']);
    }
}