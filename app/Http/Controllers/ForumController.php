<?php

namespace App\Http\Controllers;

use App\Models\ForumReply;
use App\Models\ForumThread;
use App\Models\UserNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function index(): View
    {
        $threads = ForumThread::query()
            ->with('user')
            ->withCount('replies')
            ->orderByDesc('is_pinned')
            ->latest()
            ->paginate(12);

        return view('forum.index', compact('threads'));
    }

    public function create(): View
    {
        return view('forum.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'contenu' => ['required', 'string', 'min:5'],
        ]);

        $thread = ForumThread::create([
            'user_id' => $request->user()->id,
            'titre' => $validated['titre'],
            'contenu' => $validated['contenu'],
        ]);

        return redirect()->route('forum.show', $thread)->with('success', 'Sujet cree avec succes.');
    }

    public function show(ForumThread $thread): View
    {
        $thread->load([
            'user',
            'replies' => fn ($q) => $q->with('user')->oldest(),
        ]);

        return view('forum.show', compact('thread'));
    }

    public function storeReply(Request $request, ForumThread $thread): RedirectResponse
    {
        $validated = $request->validate([
            'contenu' => ['required', 'string', 'min:2'],
        ]);

        $reply = ForumReply::create([
            'thread_id' => $thread->id,
            'user_id' => $request->user()->id,
            'contenu' => $validated['contenu'],
        ]);

        $participantIds = $thread->replies()
            ->pluck('user_id')
            ->push($thread->user_id)
            ->unique()
            ->filter(fn ($id) => (int) $id !== (int) $request->user()->id);

        foreach ($participantIds as $participantId) {
            UserNotification::create([
                'user_id' => $participantId,
                'titre' => 'Nouvelle reponse sur le forum',
                'message' => $request->user()->name.' a repondu au sujet "'.$thread->titre.'".',
                'link' => route('forum.show', $thread),
                'is_read' => false,
            ]);
        }

        return redirect()
            ->route('forum.show', $thread)
            ->with('success', 'Reponse envoyee.')
            ->withFragment('reply-'.$reply->id);
    }
}
