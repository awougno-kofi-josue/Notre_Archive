<?php

use App\Http\Controllers\Admin\NiveauAdminController;
use App\Http\Controllers\Admin\ParcoursAdminController;
use App\Http\Controllers\Admin\ParcoursMessageAdminController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Admin\DocumentTypeAdminController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\Moderator\ModeratorController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Models\Document;
use App\Models\Message;
use App\Models\Niveau;
use App\Models\Parcours;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Pages publiques
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

Route::get('/apropos', function () {
    return view('apropos');
})->name('apropos');

Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
Route::get('/forum/{thread}', [ForumController::class, 'show'])->name('forum.show');

/*
|--------------------------------------------------------------------------
| Dashboard utilisateur
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    if (request()->user()?->is_admin) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('documents.index');
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| Espace utilisateur connecte
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::resource('documents', DocumentController::class)->except(['index', 'show', 'edit', 'update']);
    Route::get('/documents/{id}/view', [DocumentController::class, 'view'])->name('documents.view');
    Route::get('/documents/{id}/download', [DocumentController::class, 'download'])->name('documents.download');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    Route::get('/forum/create', [ForumController::class, 'create'])->name('forum.create');
    Route::post('/forum', [ForumController::class, 'store'])->name('forum.store');
    Route::post('/forum/{thread}/replies', [ForumController::class, 'storeReply'])->name('forum.replies.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'is_moderator'])
    ->prefix('moderateur')
    ->name('moderator.')
    ->group(function () {
        Route::get('/', [ModeratorController::class, 'index'])->name('dashboard');
        Route::get('/messages', [ModeratorController::class, 'messages'])->name('messages.index');
        Route::post('/messages', [ModeratorController::class, 'storeMessage'])->name('messages.store');
        Route::patch('/users/{user}/document-access', [ModeratorController::class, 'toggleDocumentAccess'])
            ->name('users.document-access.toggle');
    });

/*
|--------------------------------------------------------------------------
| Espace admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        $documentsCount = Document::count();
        $parcoursCount = Parcours::count();
        $niveauxCount = Niveau::count();
        $usersCount = User::count();
        $messagesCount = Message::count();
        $onlineUsersCount = User::query()
            ->whereNotNull('last_seen_at')
            ->where('last_seen_at', '>=', now()->subMinutes(5))
            ->count();
        $adminsCount = User::query()->where('is_admin', true)->count();
        $moderatorsCount = User::query()
            ->where('is_admin', false)
            ->where('can_manage_documents', true)
            ->count();
        $standardUsersCount = User::query()
            ->where('is_admin', false)
            ->where('can_manage_documents', false)
            ->count();
        $documentsThisMonth = Document::query()
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
        $messagesThisWeek = Message::query()
            ->where('created_at', '>=', now()->copy()->startOfWeek())
            ->count();
        $recentDocuments = Document::query()
            ->with(['parcours', 'niveau', 'user'])
            ->latest()
            ->limit(6)
            ->get();
        $recentUsers = User::query()
            ->with('parcours')
            ->latest()
            ->limit(6)
            ->get();
        $recentMessages = Message::query()
            ->latest()
            ->limit(6)
            ->get();

        return view('admin.dashboard', [
            'documentsCount' => $documentsCount,
            'parcoursCount' => $parcoursCount,
            'niveauxCount' => $niveauxCount,
            'usersCount' => $usersCount,
            'messagesCount' => $messagesCount,
            'onlineUsersCount' => $onlineUsersCount,
            'adminsCount' => $adminsCount,
            'moderatorsCount' => $moderatorsCount,
            'standardUsersCount' => $standardUsersCount,
            'documentsThisMonth' => $documentsThisMonth,
            'messagesThisWeek' => $messagesThisWeek,
            'recentDocuments' => $recentDocuments,
            'recentUsers' => $recentUsers,
            'recentMessages' => $recentMessages,
        ]);
    })->name('dashboard');

    Route::resource('parcours', ParcoursAdminController::class)->except('show');
    Route::resource('niveaux', NiveauAdminController::class)->except('show');
    Route::get('/document-types', [DocumentTypeAdminController::class, 'index'])->name('document-types.index');
    Route::post('/document-types', [DocumentTypeAdminController::class, 'store'])->name('document-types.store');
    Route::delete('/document-types/{documentType}', [DocumentTypeAdminController::class, 'destroy'])->name('document-types.destroy');

    Route::get('/users', [UserAdminController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/parcours', [UserAdminController::class, 'updateParcours'])->name('users.parcours.update');
    Route::patch('/users/{user}/document-access', [UserAdminController::class, 'toggleDocumentAccess'])->name('users.document-access.toggle');
    Route::get('/users/create-admin', [RegisteredUserController::class, 'createAdmin'])->name('users.create-admin');
    Route::post('/users/create-admin', [RegisteredUserController::class, 'storeAdmin'])->name('users.store-admin');
    Route::get('/parcours-messages', [ParcoursMessageAdminController::class, 'index'])->name('parcours-messages.index');
    Route::delete('/parcours-messages/{parcoursMessage}', [ParcoursMessageAdminController::class, 'destroy'])->name('parcours-messages.destroy');

    Route::get('/messages', function () {
        $messages = Message::latest()->paginate(10);
        return view('admin.messages.index', compact('messages'));
    })->name('messages.index');

    Route::delete('/messages/{message}', function (Message $message) {
        $message->delete();
        return redirect()->route('admin.messages.index')->with('success', 'Message supprime avec succes.');
    })->name('messages.destroy');
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
