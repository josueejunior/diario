<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Diário Oficial')</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Extra CSS -->
    @stack('styles')
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Partículas flutuantes de fundo -->
    <div class="floating-particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>
    
    <!-- Cabeçalho Moderno -->
    <header class="relative bg-white shadow-lg">
        <!-- Barra superior de acessibilidade -->
        <div class="bg-gradient-to-r from-gray-800 to-gray-700 text-white py-2">
            <div class="container mx-auto px-6">
                <div class="flex justify-between items-center text-sm">
                    <div class="flex space-x-4">
                        <button id="accessibility-btn" class="hover:text-gray-300 transition-colors flex items-center group">
                            <i class="fas fa-universal-access mr-1 group-hover:scale-110 transition-transform"></i>
                            Acessibilidade
                        </button>
                        <a href="#" class="hover:text-gray-300 transition-colors flex items-center group">
                            <i class="fas fa-phone mr-1 group-hover:scale-110 transition-transform"></i>
                            Contato
                        </a>
                        <a href="#" class="hover:text-gray-300 transition-colors flex items-center group">
                            <i class="fas fa-info-circle mr-1 group-hover:scale-110 transition-transform"></i>
                            Sobre
                        </a>
                    </div>
                    <div class="flex space-x-4 items-center">
                        <span class="text-gray-300 flex items-center">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            {{ now()->format('d/m/Y') }}
                        </span>
                        <div class="accessibility-controls hidden space-x-2">
                            <button id="increase-font" class="hover:text-gray-300 px-2 py-1 rounded hover:bg-white/10 transition-all">A+</button>
                            <button id="decrease-font" class="hover:text-gray-300 px-2 py-1 rounded hover:bg-white/10 transition-all">A-</button>
                            <button id="high-contrast" class="hover:text-gray-300 px-2 py-1 rounded hover:bg-white/10 transition-all">Alto Contraste</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Header principal -->
        <div class="bg-white border-b border-gray-200 py-4">
            <div class="container mx-auto px-6">
                <div class="flex items-center justify-between">
                    <!-- Logo e Nome -->
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('home') }}" class="flex items-center space-x-3">
                            <div class="bg-blue-600 p-2 rounded-lg">
                                <i class="fas fa-newspaper text-white text-2xl"></i>
                            </div>
                            <div>
                                <h1 class="text-xl font-bold text-gray-900">Diário Oficial</h1>
                                <p class="text-sm text-gray-600">Prefeitura Municipal</p>
                            </div>
                        </a>
                    </div>

                    <!-- Barra de Busca Global -->
                    <div class="flex-1 max-w-2xl mx-8">
                        <form action="{{ route('home.buscar') }}" method="GET" class="relative">
                            <input type="text" 
                                   name="q" 
                                   placeholder="Buscar por palavra-chave, número da edição ou data..."
                                   value="{{ request('q') }}"
                                   class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 placeholder-gray-500">
                            <button type="submit" 
                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Menu rápido -->
                    <nav class="flex items-center space-x-6">
                        <a href="{{ route('home') }}" 
                           class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-200 {{ request()->routeIs('home') ? 'text-blue-600 font-semibold' : '' }}">
                            Início
                        </a>
                        <a href="{{ route('portal.materias.index') }}" 
                           class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-200 {{ request()->routeIs('portal.materias.*') ? 'text-blue-600 font-semibold' : '' }}">
                            Matérias
                        </a>
                        <a href="{{ route('portal.edicoes.index') }}" 
                           class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-200 {{ request()->routeIs('portal.edicoes.*') ? 'text-blue-600 font-semibold' : '' }}">
                            Edições
                        </a>
                        <div class="relative dropdown">
                            <button class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-200 flex items-center">
                                Documentos
                                <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 hidden border border-gray-100">
                                <a href="{{ route('portal.documentos.portarias') }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-file-alt text-amber-500 mr-2"></i>
                                    Portarias
                                </a>
                                <a href="{{ route('portal.documentos.decretos') }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-file-alt text-blue-500 mr-2"></i>
                                    Decretos
                                </a>
                                <a href="{{ route('portal.documentos.leis') }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-file-alt text-green-500 mr-2"></i>
                                    Leis
                                </a>
                                <a href="{{ route('portal.documentos.resolucoes') }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-file-alt text-purple-500 mr-2"></i>
                                    Resoluções
                                </a>
                                <a href="{{ route('portal.documentos.editais') }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-bullhorn text-red-500 mr-2"></i>
                                    Editais
                                </a>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Conteúdo principal com espaçamento para compensar header fixo -->
    <main class="flex-1" style="margin-top: 8rem;">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Rodapé -->
    <footer class="bg-gray-900 text-white mt-16">
        <!-- Seção principal do rodapé -->
        <div class="bg-gray-800 py-12">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Sobre o Diário -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <i class="fas fa-newspaper mr-2 text-blue-400"></i>
                            Diário Oficial
                        </h3>
                        <p class="text-gray-300 text-sm mb-4">
                            Publicação oficial dos atos administrativos da Prefeitura Municipal, 
                            garantindo transparência e acesso à informação pública.
                        </p>
                        <div class="flex space-x-3">
                            <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Links Rápidos -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Links Rápidos</h3>
                        <ul class="space-y-2 text-sm">
                            <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors">Início</a></li>
                            <li><a href="{{ route('portal.edicoes.index') }}" class="text-gray-300 hover:text-white transition-colors">Todas as Edições</a></li>
                            <li><a href="{{ route('portal.materias.index') }}" class="text-gray-300 hover:text-white transition-colors">Matérias</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Verificar Autenticidade</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Arquivo Histórico</a></li>
                        </ul>
                    </div>

                    <!-- Regulamentações -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Regulamentações</h3>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Decretos</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Portarias</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Leis Municipais</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Editais</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Resoluções</a></li>
                        </ul>
                    </div>

                    <!-- Contato -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Contato</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex items-start space-x-2">
                                <i class="fas fa-map-marker-alt text-blue-400 mt-1"></i>
                                <div>
                                    <p class="text-gray-300">Prefeitura Municipal</p>
                                    <p class="text-gray-400">Rua Principal, 123</p>
                                    <p class="text-gray-400">Centro - CEP: 12345-678</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-phone text-blue-400"></i>
                                <span class="text-gray-300">(11) 1234-5678</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-envelope text-blue-400"></i>
                                <span class="text-gray-300">contato@prefeitura.gov.br</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-clock text-blue-400"></i>
                                <span class="text-gray-300">Seg-Sex: 8h às 17h</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barra inferior do rodapé -->
        <div class="bg-gray-900 py-4 border-t border-gray-700">
            <div class="container mx-auto px-6">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="text-gray-400 text-sm mb-2 md:mb-0">
                        © {{ date('Y') }} Prefeitura Municipal. Todos os direitos reservados.
                    </div>
                    <div class="flex space-x-4 text-sm">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">Política de Privacidade</a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">Termos de Uso</a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">Acessibilidade</a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">Mapa do Site</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')

    <script>
        // Funcionalidade de acessibilidade
        document.getElementById('accessibility-btn')?.addEventListener('click', function() {
            const controls = document.querySelector('.accessibility-controls');
            controls.classList.toggle('hidden');
        });

        // Controle de fonte
        let currentFontSize = 16;
        document.getElementById('increase-font')?.addEventListener('click', function() {
            currentFontSize += 2;
            document.body.style.fontSize = currentFontSize + 'px';
        });

        document.getElementById('decrease-font')?.addEventListener('click', function() {
            if (currentFontSize > 12) {
                currentFontSize -= 2;
                document.body.style.fontSize = currentFontSize + 'px';
            }
        });

        // Alto contraste
        document.getElementById('high-contrast')?.addEventListener('click', function() {
            document.body.classList.toggle('high-contrast');
        });

        // Dropdown do menu
        document.querySelectorAll('.dropdown').forEach(dropdown => {
            const button = dropdown.querySelector('button');
            const menu = dropdown.querySelector('.dropdown-menu');
            
            button?.addEventListener('click', function(e) {
                e.preventDefault();
                menu.classList.toggle('hidden');
            });
            
            // Fechar dropdown ao clicar fora
            document.addEventListener('click', function(e) {
                if (!dropdown.contains(e.target)) {
                    menu.classList.add('hidden');
                }
            });
        });

        // Busca por enter
        document.querySelector('form input[name="q"]')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.form.submit();
            }
        });
    </script>
</body>
</html>
