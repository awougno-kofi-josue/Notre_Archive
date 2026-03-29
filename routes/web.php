<?php

use App\Http\Controllers\Admin\NiveauAdminController;
use App\Http\Controllers\Admin\ParcoursAdminController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DocumentController;
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

/*
|--------------------------------------------------------------------------
| Dashboard utilisateur
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    $documentsCount = Document::count();
    $parcoursCount = Parcours::count();
    $niveauxCount = Niveau::count();
    $usersCount = User::count();

    return view('dashboard', compact(
        'documentsCount',
        'parcoursCount',
        'niveauxCount',
        'usersCount'
    ));
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| Espace utilisateur connecte
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::resource('documents', DocumentController::class);
    Route::get('/documents/{id}/view', [DocumentController::class, 'view'])->name('documents.view');
    Route::get('/documents/{id}/download', [DocumentController::class, 'download'])->name('documents.download');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Espace admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard', [
            'documentsCount' => Document::count(),
            'parcoursCount'  => Parcours::count(),
            'niveauxCount'   => Niveau::count(),
            'usersCount'     => User::count(),
            'messagesCount'  => Message::count(),
        ]);
    })->name('dashboard');

    Route::resource('parcours', ParcoursAdminController::class)->except('show');
    Route::resource('niveaux', NiveauAdminController::class)->except('show');
    Route::get('/users', [UserAdminController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/document-access', [UserAdminController::class, 'toggleDocumentAccess'])->name('users.document-access.toggle');

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
