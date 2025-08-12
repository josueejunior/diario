<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Diário Oficial')</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Extra CSS -->
    @stack('styles')
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Cabeçalho -->
    <header class="bg-blue-800 text-white">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <a href="{{ route('portal.home') }}" class="text-2xl font-bold">
                        Diário Oficial
                    </a>
                </div>
                
                <!-- Menu de navegação -->
                <nav class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('portal.edicoes.index') }}" class="text-white hover:text-blue-200 transition-colors">
                        Edições
                    </a>
                    <a href="{{ route('portal.materias.index') }}" class="text-white hover:text-blue-200 transition-colors">
                        Matérias
                    </a>
                    <a href="{{ route('portal.verificar') }}" class="text-white hover:text-blue-200 transition-colors">
                        Verificar Autenticidade
                    </a>
                    
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-white text-blue-800 px-4 py-2 rounded-md hover:bg-blue-100 transition-colors">
                            Área Administrativa
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="bg-white text-blue-800 px-4 py-2 rounded-md hover:bg-blue-100 transition-colors">
                            Login
                        </a>
                    @endauth
                </nav>
                
                <!-- Menu móvel -->
                <div class="md:hidden">
                    <button id="menu-toggle" class="text-white focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Menu móvel expansível -->
            <div id="mobile-menu" class="hidden md:hidden mt-4 pb-2">
                <a href="{{ route('portal.edicoes.index') }}" class="block text-white hover:bg-blue-700 px-2 py-2 rounded-md">
                    Edições
                </a>
                <a href="{{ route('portal.materias.index') }}" class="block text-white hover:bg-blue-700 px-2 py-2 rounded-md">
                    Matérias
                </a>
                <a href="{{ route('portal.verificar') }}" class="block text-white hover:bg-blue-700 px-2 py-2 rounded-md">
                    Verificar Autenticidade
                </a>
                
                @auth
                    <a href="{{ route('dashboard') }}" class="block text-white hover:bg-blue-700 px-2 py-2 rounded-md mt-2">
                        Área Administrativa
                    </a>
                @else
                    <a href="{{ route('login') }}" class="block text-white hover:bg-blue-700 px-2 py-2 rounded-md mt-2">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Conteúdo principal -->
    <main class="flex-1">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Rodapé -->
    <footer class="bg-gray-800 text-white py-6">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-3">Diário Oficial</h3>
                    <p class="text-gray-300 text-sm">
                        Publicação oficial dos atos normativos e administrativos.
                    </p>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-3">Links Úteis</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('portal.edicoes.index') }}" class="text-gray-300 hover:text-white text-sm">
                                Edições
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('portal.verificar') }}" class="text-gray-300 hover:text-white text-sm">
                                Verificar Autenticidade
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-3">Contato</h3>
                    <p class="text-gray-300 text-sm">
                        contato@diariooficial.gov.br
                    </p>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-6 pt-6 text-center text-gray-400 text-sm">
                &copy; {{ date('Y') }} Diário Oficial. Todos os direitos reservados.
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Menu móvel toggle
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
    
    @stack('scripts')
</body>
</html>
