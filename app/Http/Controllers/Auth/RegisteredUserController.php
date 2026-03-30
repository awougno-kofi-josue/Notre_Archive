<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Parcours;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    // -------------------------------
    // Web Routes
    // -------------------------------

    /**
     * Affiche le formulaire d'inscription (web)
     */
    public function create(): View
    {
        $parcoursList = Parcours::query()->orderBy('nom')->get();

        return view('auth.register', compact('parcoursList'));
    }

    /**
     * Traite l'inscription web
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'parcours_id' => ['required', 'exists:parcours,id'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => false,
            'parcours_id' => $request->integer('parcours_id'),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    /**
     * Affiche le formulaire de creation d'un compte admin.
     * Accessible uniquement depuis l'espace admin.
     */
    public function createAdmin(): View
    {
        return view('admin.users.create-admin');
    }

    /**
     * Cree un nouvel administrateur.
     */
    public function storeAdmin(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => true,
            'can_manage_documents' => true,
        ]);

        event(new Registered($admin));

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Nouvel administrateur cree avec succes.');
    }

    /**
     * Formulaire public de creation admin (protege par cle secrete).
     */
    public function createPublicAdmin(): View
    {
        return view('auth.register-admin');
    }

    /**
     * Creation d'un admin via route publique securisee.
     */
    public function storePublicAdmin(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'admin_key' => ['required', 'string'],
        ]);

        $expectedKey = (string) config('app.admin_register_key', '');
        $givenKey = (string) $request->input('admin_key');

        if ($expectedKey === '' || ! hash_equals($expectedKey, $givenKey)) {
            return back()
                ->withErrors(['admin_key' => 'Cle admin invalide.'])
                ->withInput($request->except(['password', 'password_confirmation', 'admin_key']));
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => true,
            'can_manage_documents' => true,
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('admin.dashboard');
    }

    // -------------------------------
    // API Routes
    // -------------------------------

    /**
     * Traite l'inscription API
     */
    public function storeApi(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:6',
            'parcours_id' => 'required|exists:parcours,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'is_admin' => false,
            'parcours_id' => $request->integer('parcours_id'),
        ]);

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Inscription réussie',
            'user' => $user,
            'token' => $token,
        ], 201);
    }
}
