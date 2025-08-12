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
    <!-- Cabeçalho dividido em 3 seções -->
    <!-- 1. Barra superior de acessibilidade -->
    <header>
        <div class="bg-gray-800 text-white py-1">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center text-sm">
                    <div class="flex space-x-4">
                        <a href="#" class="hover:text-gray-300 transition-colors flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Acessibilidade
                        </a>
                        <a href="#" class="hover:text-gray-300 transition-colors flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                            Contato
                        </a>
                    </div>
                    <div class="flex space-x-4">
                        <button class="hover:text-gray-300 transition-colors">A+</button>
                        <button class="hover:text-gray-300 transition-colors">A-</button>
                        <button class="hover:text-gray-300 transition-colors">Alto Contraste</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 2. Seção do logo e informações principais -->
        <div class="bg-blue-800 text-white py-4">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <a href="{{ route('portal.home') }}" class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <div>
                                <div class="text-2xl font-bold">Diário Oficial</div>
                                <div class="text-sm text-blue-200">Publicações Oficiais</div>
                            </div>
                        </a>
                    </div>
                    
                    <div class="hidden md:flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-sm text-blue-200">{{ now()->format('l, d \de F \de Y') }}</div>
                            <div class="text-xs">Última atualização: {{ now()->format('H:i') }}</div>
                        </div>
                        @auth
                            <a href="{{ route('dashboard') }}" class="bg-white text-blue-800 px-4 py-2 rounded-md hover:bg-blue-100 transition-colors">
                                Área Administrativa
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="bg-white text-blue-800 px-4 py-2 rounded-md hover:bg-blue-100 transition-colors">
                                Login
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 3. Barra de navegação principal -->
        <div class="bg-blue-700 shadow-md">
            <div class="container mx-auto px-4">
                <!-- Menu de navegação desktop -->
                <nav class="hidden md:flex items-center py-3">
                    <a href="{{ route('portal.home') }}" class="text-white hover:text-blue-200 transition-colors px-4 py-1 font-medium">
                        Início
                    </a>
                    <a href="{{ route('portal.edicoes.index') }}" class="text-white hover:text-blue-200 transition-colors px-4 py-1 font-medium">
                        Edições
                    </a>
                    <a href="{{ route('portal.materias.index') }}" class="text-white hover:text-blue-200 transition-colors px-4 py-1 font-medium">
                        Matérias
                    </a>
                    <a href="{{ route('portal.verificar') }}" class="text-white hover:text-blue-200 transition-colors px-4 py-1 font-medium">
                        Verificar Autenticidade
                    </a>
                </nav>
                
                <!-- Menu móvel toggle -->
                <div class="md:hidden py-2 flex justify-end">
                    <button id="menu-toggle" class="text-white focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Menu móvel expansível -->
            <div id="mobile-menu" class="hidden md:hidden container mx-auto px-4 py-2 bg-blue-700">
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
