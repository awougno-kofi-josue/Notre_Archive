<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Niveau;
use App\Models\Parcours;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class DocumentController extends Controller
{
    private const MAX_UPLOAD_KB = 10240; // Augmenté à 10Mo car Cloudinary encaisse mieux

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
                Rule::exists('niveaux', 'id')->where(fn ($q) => $q->where('parcours_id', $request->parcours_id)),
            ],
        ]);

        // --- LOGIQUE CLOUDINARY ---
        // On upload directement sur Cloudinary dans un dossier nommé 'notre_archive'
        $result = $request->file('fichier')->storeOnCloudinary('notre_archive');
        $path = $result->getSecurePath(); // C'est l'URL complète https://...
        // --------------------------

        Document::create([
            'titre' => $validated['titre'],
            'description' => $validated['description'],
            'fichier' => $path, // On stocke l'URL directe
            'niveau_id' => $validated['niveau_id'],
            'parcours_id' => $validated['parcours_id'],
            'user_id' => $request->user()?->id,
        ]);

        return redirect()->route('documents.index')->with('success', 'Document archivé avec succès sur Cloudinary.');
    }

    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);

        // Optionnel : Supprimer aussi sur Cloudinary
        // Cloudinary::destroy($document->getPublicId()); 

        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Document supprimé avec succès.');
    }

    /**
     * Pour l'affichage ou le téléchargement, on redirige vers l'URL Cloudinary
     */
    public function view(string $id)
    {
        $document = Document::findOrFail($id);
        return redirect()->away($document->fichier);
    }

    public function download(string $id)
    {
        $document = Document::findOrFail($id);
        // On force le téléchargement via l'URL Cloudinary
        return redirect()->away($document->fichier);
    }

    // --- API PART ---

    public function apiStore(Request $request)
    {
        // ... (Même logique que store mais retourner du JSON)
        $result = $request->file('fichier')->storeOnCloudinary('notre_archive');
        
        $document = Document::create([
            'titre' => $request->titre,
            'description' => $request->description,
            'fichier' => $result->getSecurePath(),
            'niveau_id' => $request->niveau_id,
            'parcours_id' => $request->parcours_id,
            'user_id' => $request->user()?->id,
        ]);

        return response()->json(['message' => 'Success', 'data' => $document], 201);
    }
}