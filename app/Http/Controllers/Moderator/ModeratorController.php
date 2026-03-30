<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ModeratorController extends Controller
{
    public function index(Request $request): View
    {
        $moderator = $request->user();
        $parcours = $moderator->parcours;

        $documents = Document::query()
            ->with(['niveau', 'user'])
            ->where('parcours_id', $moderator->parcours_id)
            ->latest()
            ->paginate(10, ['*'], 'documents_page')
            ->withQueryString();

        $users = User::query()
            ->withCount('documents')
            ->where('parcours_id', $moderator->parcours_id)
            ->orderBy('name')
            ->paginate(10, ['*'], 'users_page')
            ->withQueryString();

        return view('moderator.dashboard', compact('moderator', 'parcours', 'documents', 'users'));
    }

    public function toggleDocumentAccess(Request $request, User $user): RedirectResponse
    {
        $moderator = $request->user();

        if ($user->is_admin) {
            return redirect()
                ->route('moderator.dashboard')
                ->with('error', 'Vous ne pouvez pas modifier un administrateur.');
        }

        if ((int) $user->parcours_id !== (int) $moderator->parcours_id) {
            return redirect()
                ->route('moderator.dashboard')
                ->with('error', 'Cet utilisateur ne fait pas partie de votre parcours.');
        }

        if ($user->id === $moderator->id && $user->can_manage_documents) {
            return redirect()
                ->route('moderator.dashboard')
                ->with('error', 'Vous ne pouvez pas retirer votre propre acces moderateur.');
        }

        $user->can_manage_documents = ! $user->can_manage_documents;
        $user->save();

        $message = $user->can_manage_documents
            ? 'Acces moderateur active pour '.$user->name.'.'
            : 'Acces moderateur retire pour '.$user->name.'.';

        return redirect()->route('moderator.dashboard')->with('success', $message);
    }
}
