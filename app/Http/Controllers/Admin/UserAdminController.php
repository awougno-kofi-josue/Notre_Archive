<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Parcours;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;

class UserAdminController extends Controller
{
    public function index(): View
    {
        $onlineLimit = now()->subMinutes(5);

        $users = User::query()
            ->with('parcours')
            ->withCount('documents')
            ->orderByDesc('last_seen_at')
            ->orderBy('name')
            ->paginate(20);
        $parcoursList = Parcours::query()
            ->orderBy('nom')
            ->get(['id', 'nom']);

        $onlineUsersCount = User::query()
            ->whereNotNull('last_seen_at')
            ->where('last_seen_at', '>=', $onlineLimit)
            ->count();

        return view('admin.users.index', compact('users', 'onlineLimit', 'onlineUsersCount', 'parcoursList'));
    }

    public function toggleDocumentAccess(User $user): RedirectResponse
    {
        if ($user->is_admin) {
            return redirect()
                ->route('admin.users.index')
                ->with('success', 'Cet utilisateur est deja administrateur (controle total).');
        }

        if (! $user->can_manage_documents && $user->parcours_id === null) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Impossible de donner l acces moderateur: cet utilisateur n a pas de parcours.');
        }

        $user->can_manage_documents = ! $user->can_manage_documents;
        $user->save();

        $statusMessage = $user->can_manage_documents
            ? 'Acces moderateur active pour '.$user->name.'.'
            : 'Acces moderateur retire pour '.$user->name.'.';

        return redirect()->route('admin.users.index')->with('success', $statusMessage);
    }

    public function updateParcours(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'parcours_id' => ['nullable', 'exists:parcours,id'],
        ]);

        $parcoursId = $validated['parcours_id'] ?? null;
        $user->parcours_id = $parcoursId;

        $moderatorAccessRemoved = false;
        if (! $user->is_admin && $parcoursId === null && $user->can_manage_documents) {
            $user->can_manage_documents = false;
            $moderatorAccessRemoved = true;
        }

        $user->save();

        $statusMessage = $parcoursId
            ? 'Parcours mis a jour pour '.$user->name.'.'
            : 'Parcours retire pour '.$user->name.'.';

        if ($moderatorAccessRemoved) {
            $statusMessage .= ' Acces moderateur retire automatiquement.';
        }

        return redirect()->route('admin.users.index')->with('success', $statusMessage);
    }
}
