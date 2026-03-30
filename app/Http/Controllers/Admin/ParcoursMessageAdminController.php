<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParcoursMessage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ParcoursMessageAdminController extends Controller
{
    public function index(): View
    {
        $messages = ParcoursMessage::query()
            ->with(['sender', 'parcours'])
            ->latest()
            ->paginate(20);

        return view('admin.parcours-messages.index', compact('messages'));
    }

    public function destroy(ParcoursMessage $parcoursMessage): RedirectResponse
    {
        $parcoursMessage->delete();

        return redirect()->route('admin.parcours-messages.index')->with('success', 'Message de parcours supprime.');
    }
}
