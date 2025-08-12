<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Painel Administrativo</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="flex h-screen bg-enhanced">
            <!-- Sidebar -->
            <aside class="z-20 hidden w-64 overflow-y-auto bg-white paper-effect md:block flex-shrink-0">
                <x-admin-sidebar />
            </aside>
            
            <!-- Mobile sidebar -->
            <div x-data="{ isSideMenuOpen: false }" class="flex flex-col flex-1 w-full">
                <header class="z-10 py-4 bg-white shadow-md paper-effect md:hidden">
                    <div class="flex items-center justify-between h-full px-6 relative z-10">
                        <button
                            @click="isSideMenuOpen = !isSideMenuOpen"
                            class="p-1 mr-5 -ml-1 rounded-md md:hidden focus:outline-none focus:shadow-outline-purple"
                            aria-label="Menu"
                        >
                            <svg
                                class="w-6 h-6"
                                aria-hidden="true"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>
                        </button>
                        
                        <div class="text-lg font-semibold">
                            {{ config('app.name', 'Laravel') }} - Admin
                        </div>
                        
                        <!-- User dropdown -->
                        <div class="relative">
                            <button
                                class="flex items-center text-sm font-medium text-gray-700 rounded-full hover:text-gray-900 focus:outline-none"
                                @click="$refs.userMenu.classList.toggle('hidden')"
                                type="button"
                                aria-label="User menu"
                            >
                                <img
                                    class="w-8 h-8 rounded-full"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF"
                                    alt=""
                                    aria-hidden="true"
                                />
                                <svg class="w-4 h-4 ml-1" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            
                            <div
                                x-ref="userMenu"
                                class="absolute right-0 w-48 py-1 mt-2 origin-top-right bg-white rounded-md shadow-lg hidden"
                                role="menu"
                                aria-orientation="vertical"
                                aria-labelledby="user-menu"
                            >
                                <a
                                    href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem"
                                >
                                    Meu Perfil
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a
                                        href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                        role="menuitem"
                                    >
                                        Sair
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>
                
                <!-- Mobile sidebar -->
                <div
                    x-show="isSideMenuOpen"
                    x-transition:enter="transition ease-in-out duration-150"
                    x-transition:enter-start="opacity-0 transform -translate-x-20"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in-out duration-150"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0 transform -translate-x-20"
                    @click.away="isSideMenuOpen = false"
                    @keydown.escape="isSideMenuOpen = false"
                    class="fixed inset-y-0 left-0 z-20 w-64 overflow-y-auto bg-white md:hidden"
                >
                    <x-admin-sidebar />
                </div>
                
                <main class="h-full overflow-y-auto">
                    <!-- Page Heading -->
                    @if (isset($header))
                        <header class="bg-white shadow">
                            <div class="px-4 py-6 mx-auto md:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endif
                    
                    <!-- Page Content -->
                    <div class="container px-4 py-6 mx-auto md:px-8">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
