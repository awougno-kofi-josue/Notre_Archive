<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;

class UserAdminController extends Controller
{
    public function index(): View
    {
        $onlineLimit = now()->subMinutes(5);

        $users = User::query()
            ->orderByDesc('last_seen_at')
            ->orderBy('name')
            ->paginate(20);

        $onlineUsersCount = User::query()
            ->whereNotNull('last_seen_at')
            ->where('last_seen_at', '>=', $onlineLimit)
            ->count();

        return view('admin.users.index', compact('users', 'onlineLimit', 'onlineUsersCount'));
    }
}
