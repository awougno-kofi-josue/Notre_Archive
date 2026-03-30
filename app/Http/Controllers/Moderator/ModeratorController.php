<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\ParcoursMessage;
use App\Models\User;
use App\Models\UserNotification;
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

        $recentMessages = ParcoursMessage::query()
            ->where('parcours_id', $moderator->parcours_id)
            ->with('sender')
            ->latest()
            ->limit(5)
            ->get();

        return view('moderator.dashboard', compact('moderator', 'parcours', 'documents', 'users', 'recentMessages'));
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

    public function messages(Request $request): View
    {
        $moderator = $request->user();

        $messages = ParcoursMessage::query()
            ->where('parcours_id', $moderator->parcours_id)
            ->with('sender')
            ->latest()
            ->paginate(15);

        $studentsCount = User::query()
            ->where('parcours_id', $moderator->parcours_id)
            ->where('is_admin', false)
            ->where('id', '!=', $moderator->id)
            ->count();

        return view('moderator.messages', compact('messages', 'studentsCount'));
    }

    public function storeMessage(Request $request): RedirectResponse
    {
        $moderator = $request->user();

        $validated = $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:3'],
        ]);

        $parcoursMessage = ParcoursMessage::create([
            'sender_id' => $moderator->id,
            'parcours_id' => $moderator->parcours_id,
            'titre' => $validated['titre'],
            'message' => $validated['message'],
        ]);

        $recipients = User::query()
            ->where('parcours_id', $moderator->parcours_id)
            ->where('is_admin', false)
            ->where('id', '!=', $moderator->id)
            ->get(['id']);

        foreach ($recipients as $recipient) {
            UserNotification::create([
                'user_id' => $recipient->id,
                'titre' => '[Parcours] '.$parcoursMessage->titre,
                'message' => $parcoursMessage->message,
                'link' => route('documents.index'),
                'is_read' => false,
            ]);
        }

        return redirect()->route('moderator.messages.index')
            ->with('success', 'Message envoye aux etudiants de votre parcours.');
    }
}
