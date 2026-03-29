<x-guest-layout>
    <div class="mb-5 text-center">
        <h1 class="text-xl font-semibold text-gray-900">Inscription Administrateur</h1>
        <p class="mt-1 text-sm text-gray-600">
            Cette page est protegee par une cle secrete.
        </p>
    </div>

    <form method="POST" action="{{ route('register.admin.store') }}">
        @csrf

        <div>
            <x-input-label for="name" :value="'Nom'" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="'Email'" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="'Mot de passe'" />
            <x-text-input id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="'Confirmation mot de passe'" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="admin_key" :value="'Cle admin'" />
            <x-text-input id="admin_key" class="block mt-1 w-full"
                          type="text"
                          name="admin_key"
                          :value="old('admin_key')"
                          required autocomplete="off" />
            <x-input-error :messages="$errors->get('admin_key')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
               href="{{ route('login') }}">
                Deja inscrit ?
            </a>

            <x-primary-button class="ms-4">
                Creer le compte admin
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
