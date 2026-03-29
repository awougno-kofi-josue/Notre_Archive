<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;

class UserAdminController extends Controller
{
    public function index(): View
    {
        $onlineLimit = now()->subMinutes(5);

        $users = User::query()
            ->withCount('documents')
            ->orderByDesc('last_seen_at')
            ->orderBy('name')
            ->paginate(20);

        $onlineUsersCount = User::query()
            ->whereNotNull('last_seen_at')
            ->where('last_seen_at', '>=', $onlineLimit)
            ->count();

        return view('admin.users.index', compact('users', 'onlineLimit', 'onlineUsersCount'));
    }

    public function toggleDocumentAccess(User $user): RedirectResponse
    {
        if ($user->is_admin) {
            return redirect()
                ->route('admin.users.index')
                ->with('success', 'Cet utilisateur est deja administrateur (controle total).');
        }

        $user->can_manage_documents = ! $user->can_manage_documents;
        $user->save();

        $statusMessage = $user->can_manage_documents
            ? 'Acces de suppression et gestion des documents active pour '.$user->name.'.'
            : 'Acces de suppression et gestion des documents retire pour '.$user->name.'.';

        return redirect()->route('admin.users.index')->with('success', $statusMessage);
    }
}
