<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DocumentTypeAdminController extends Controller
{
    public function index(): View
    {
        $documentTypes = DocumentType::query()
            ->withCount('documents')
            ->orderBy('nom')
            ->paginate(20);

        return view('admin.document-types.index', compact('documentTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:120', 'unique:document_types,nom'],
        ]);

        DocumentType::create($validated);

        return redirect()->route('admin.document-types.index')->with('success', 'Type de document ajoute.');
    }

    public function destroy(DocumentType $documentType): RedirectResponse
    {
        $documentType->delete();

        return redirect()->route('admin.document-types.index')->with('success', 'Type de document supprime.');
    }
}
