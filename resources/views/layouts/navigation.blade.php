<x-ad/>
<nav x-data="{ open: false }" class="bg-[#0d1b2a] border-b border-[#c9a84c]/40 shadow-sm">
    @php
        $currentUser = auth()->user();
        $isAdmin = (bool) ($currentUser?->is_admin);
        $isModerator = (bool) ($currentUser?->can_manage_documents && !$currentUser?->is_admin && $currentUser?->parcours_id);
        $unreadNotificationsCount = $currentUser
            ? \App\Models\UserNotification::query()
                ->where('user_id', $currentUser->id)
                ->where('is_read', false)
                ->count()
            : 0;
    @endphp

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ $isAdmin ? route('admin.dashboard') : (auth()->check() ? route('documents.index') : route('home')) }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-white" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @auth
                        <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                            Accueil
                        </x-nav-link>
                        <x-nav-link :href="route('documents.index')" :active="request()->routeIs('documents.*')">
                            Documents
                        </x-nav-link>
                        <x-nav-link :href="route('forum.index')" :active="request()->routeIs('forum.*')">
                            Forum
                        </x-nav-link>
                        <x-nav-link :href="route('notifications.index')" :active="request()->routeIs('notifications.*')">
                            Notifications{{ $unreadNotificationsCount ? ' ('.$unreadNotificationsCount.')' : '' }}
                        </x-nav-link>
                        @if($isModerator)
                            <x-nav-link :href="route('moderator.dashboard')" :active="request()->routeIs('moderator.*')">
                                Moderateur
                            </x-nav-link>
                        @endif
                        @if($isAdmin)
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                                Admin
                            </x-nav-link>
                        @endif
                    @else
                        <x-nav-link :href="route('documents.index')" :active="request()->routeIs('documents.index')">
                            Documents
                        </x-nav-link>
                        <x-nav-link :href="route('forum.index')" :active="request()->routeIs('forum.*')">
                            Forum
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white/90 bg-[#0d1b2a] hover:text-[#c9a84c] focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <button type="submit"
                                        class="block w-full px-4 py-2.5 text-left text-sm text-[#0d1b2a] font-medium hover:bg-[#f7f3ed] hover:text-[#c9a84c] focus:outline-none focus:bg-[#f7f3ed] border-0 border-l-2 border-transparent hover:border-[#c9a84c] bg-transparent transition duration-150 ease-in-out">
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="text-sm text-white/90 hover:text-[#c9a84c] transition">Connexion</a>
                        <a href="{{ route('register') }}" class="text-sm px-3 py-1.5 rounded border border-[#c9a84c] text-[#c9a84c] hover:bg-[#c9a84c] hover:text-[#0d1b2a] transition">Inscription</a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white/80 hover:text-white hover:bg-white/10 focus:outline-none focus:bg-white/10 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-[#0d1b2a]">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                    Accueil
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('documents.index')" :active="request()->routeIs('documents.*')">
                    Documents
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('forum.index')" :active="request()->routeIs('forum.*')">
                    Forum
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('notifications.index')" :active="request()->routeIs('notifications.*')">
                    Notifications{{ $unreadNotificationsCount ? ' ('.$unreadNotificationsCount.')' : '' }}
                </x-responsive-nav-link>
                @if($isModerator)
                    <x-responsive-nav-link :href="route('moderator.dashboard')" :active="request()->routeIs('moderator.*')">
                        Moderateur
                    </x-responsive-nav-link>
                @endif
                @if($isAdmin)
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                        Admin
                    </x-responsive-nav-link>
                @endif
            @else
                <x-responsive-nav-link :href="route('documents.index')" :active="request()->routeIs('documents.index')">
                    Documents
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('forum.index')" :active="request()->routeIs('forum.*')">
                    Forum
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('login')">
                    Connexion
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')">
                    Inscription
                </x-responsive-nav-link>
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="pt-4 pb-1 border-t border-white/10">
                <div class="px-4">
                    <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-white/60">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit"
                                class="block w-full ps-3 pe-4 py-2.5 border-0 border-l-4 border-transparent text-sm font-medium text-white/70 hover:text-white hover:bg-white/5 hover:border-white/30 focus:outline-none transition duration-150 ease-in-out text-left bg-transparent">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>
