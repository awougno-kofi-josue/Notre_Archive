<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Niveau;
use App\Models\Parcours;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class DocumentController extends Controller
{
    private const LOSSLESS_THRESHOLD_BYTES = 500 * 1024;
    private const MAX_UPLOAD_KB = 5120;

    public function index(Request $request)
    {
        $parcoursList = Parcours::all();
        $anneesList = Niveau::with('parcours')->get();

        $query = Document::with(['parcours', 'niveau']);

        if ($request->filled('parcours_id')) {
            $query->where('parcours_id', $request->parcours_id);
        }

        if ($request->filled('annee_id')) {
            $query->where('niveau_id', $request->annee_id);
        }

        $documents = $query->paginate(9)->withQueryString();

        return view('documents.index', compact('documents', 'parcoursList', 'anneesList'));
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
            'niveau_id.exists' => 'L annee selectionnee ne correspond pas au parcours choisi.',
        ]);

        [$path, $compressed] = $this->storeUploadedFileWithLosslessCompression($request->file('fichier'));

        Document::create([
            'titre' => $validated['titre'],
            'description' => $validated['description'],
            'fichier' => $path,
            'niveau_id' => $validated['niveau_id'],
            'parcours_id' => $validated['parcours_id'],
        ]);

        $message = $compressed
            ? 'Document cree avec succes (compression lossless appliquee pour taille > 500 Ko).'
            : 'Document cree avec succes.';

        return redirect()->route('documents.index')->with('success', $message);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }

    public function download(string $id)
    {
        $document = Document::findOrFail($id);

        return $this->buildFileResponse($document, true);
    }

    public function view(string $id)
    {
        $document = Document::findOrFail($id);

        return $this->buildFileResponse($document, false);
    }

    public function apiIndex(Request $request)
    {
        $query = Document::with(['parcours', 'niveau']);

        if ($request->filled('parcours_id')) {
            $query->where('parcours_id', $request->parcours_id);
        }

        if ($request->filled('niveau_id')) {
            $query->where('niveau_id', $request->niveau_id);
        }

        return response()->json($query->paginate(9));
    }

    public function apiStore(Request $request)
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
            'niveau_id.exists' => 'L annee selectionnee ne correspond pas au parcours choisi.',
        ]);

        [$path] = $this->storeUploadedFileWithLosslessCompression($request->file('fichier'));

        $document = Document::create([
            'titre' => $validated['titre'],
            'description' => $validated['description'],
            'fichier' => $path,
            'niveau_id' => $validated['niveau_id'],
            'parcours_id' => $validated['parcours_id'],
        ]);

        return response()->json([
            'message' => 'Document cree avec succes',
            'data' => $document,
        ], 201);
    }

    public function apiShow(string $id)
    {
        return response()->json(Document::with(['parcours', 'niveau'])->findOrFail($id));
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
            [$path] = $this->storeUploadedFileWithLosslessCompression($request->file('fichier'));
            $document->fichier = $path;
        }

        $document->fill(collect($validated)->except('fichier')->toArray());
        $document->save();

        return response()->json([
            'message' => 'Document mis a jour avec succes',
            'data' => $document,
        ]);
    }

    public function apiDestroy(string $id)
    {
        $document = Document::findOrFail($id);
        $document->delete();

        return response()->json([
            'message' => 'Document supprime avec succes',
        ]);
    }

    public function apiDownload(string $id)
    {
        $document = Document::findOrFail($id);

        return $this->buildFileResponse($document, true);
    }

    public function apiView(string $id)
    {
        $document = Document::findOrFail($id);

        return $this->buildFileResponse($document, false);
    }

    /**
     * @return array{0:string,1:bool}
     */
    private function storeUploadedFileWithLosslessCompression(UploadedFile $file): array
    {
        $originalPath = $file->store('documents', 'public');
        $size = $file->getSize() ?? 0;

        if ($size <= self::LOSSLESS_THRESHOLD_BYTES) {
            return [$originalPath, false];
        }

        $absolutePath = Storage::disk('public')->path($originalPath);
        $content = @file_get_contents($absolutePath);

        if ($content === false) {
            return [$originalPath, false];
        }

        $compressed = gzencode($content, 9, ZLIB_ENCODING_GZIP);

        if ($compressed === false || strlen($compressed) >= strlen($content)) {
            return [$originalPath, false];
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: 'bin');
        $baseName = pathinfo($file->hashName(), PATHINFO_FILENAME);
        $compressedPath = 'documents/'.$baseName.'.'.$extension.'.gz';

        Storage::disk('public')->put($compressedPath, $compressed);
        Storage::disk('public')->delete($originalPath);

        return [$compressedPath, true];
    }

    private function buildFileResponse(Document $document, bool $asDownload): Response
    {
        $disk = Storage::disk('public');
        $path = $document->fichier;

        if (! $disk->exists($path)) {
            abort(404);
        }

        if (! $this->isCompressedGzip($path)) {
            $absolutePath = $disk->path($path);
            return $asDownload ? response()->download($absolutePath) : response()->file($absolutePath);
        }

        $compressedBinary = $disk->get($path);
        $decodedBinary = gzdecode($compressedBinary);

        if ($decodedBinary === false) {
            abort(500, 'Impossible de decompresser le fichier.');
        }

        $originalFilename = $this->extractOriginalFilename($path);
        $mimeType = $this->detectMimeFromFilename($originalFilename);
        $disposition = ($asDownload ? 'attachment' : 'inline').'; filename="'.$originalFilename.'"';

        return response($decodedBinary, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => $disposition,
        ]);
    }

    private function isCompressedGzip(string $path): bool
    {
        return str_ends_with(strtolower($path), '.gz');
    }

    private function extractOriginalFilename(string $path): string
    {
        $basename = basename($path);

        if ($this->isCompressedGzip($basename)) {
            return substr($basename, 0, -3);
        }

        return $basename;
    }

    private function detectMimeFromFilename(string $filename): string
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        return match ($extension) {
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'webp' => 'image/webp',
            default => 'application/octet-stream',
        };
    }
}
