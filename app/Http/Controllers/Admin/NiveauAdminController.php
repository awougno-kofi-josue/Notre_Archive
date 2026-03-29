<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Niveau;
use App\Models\Parcours;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NiveauAdminController extends Controller
{
    public function index(): View
    {
        $niveaux = Niveau::with('parcours')->latest()->get();

        return view('admin.niveaux.index', compact('niveaux'));
    }

    public function create(): View
    {
        $parcours = Parcours::orderBy('nom')->get();

        return view('admin.niveaux.create', compact('parcours'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'parcours_id' => 'required|exists:parcours,id',
        ]);

        Niveau::create($validated);

        return redirect()->route('admin.niveaux.index')
            ->with('success', 'Annee creee avec succes.');
    }

    public function edit(Niveau $niveau): View
    {
        $parcours = Parcours::orderBy('nom')->get();

        return view('admin.niveaux.edit', compact('niveau', 'parcours'));
    }

    public function update(Request $request, Niveau $niveau): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'parcours_id' => 'required|exists:parcours,id',
        ]);

        $niveau->update($validated);

        return redirect()->route('admin.niveaux.index')
            ->with('success', 'Annee mise a jour avec succes.');
    }

    public function destroy(Niveau $niveau): RedirectResponse
    {
        $niveau->delete();

        return redirect()->route('admin.niveaux.index')
            ->with('success', 'Annee supprimee avec succes.');
    }
}
