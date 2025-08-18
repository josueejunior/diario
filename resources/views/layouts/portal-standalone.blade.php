<!DOCTYPE html>
<html class="" lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="x-ua-compatible" content="ie=edge"/>
    <meta property="og:type" content="article"/>
    <meta property="og:site_name" content="Diário Eletrônico - Prefeitura Municipal"/>
    <meta property="og:locale" content="pt_BR"/>
    <meta name="twitter:card" content="summary_large_image"/>
    <title>@yield('title', 'Diário Oficial - Sistema de Publicação Eletrônica')</title>
    <meta name="description" content="@yield('description', 'Sistema de Publicação - Diário Eletrônico')"/>
    <meta name="robots" content="index, follow"/>
    <meta property="og:title" content="@yield('og_title', 'Diário Oficial - Sistema de Publicação Eletrônica')"/>
    <meta property="og:description" content="@yield('og_description', 'Sistema de Publicação - Diário Eletrônico')"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #fff;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #17639D;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .glow-dark {
            position: fixed;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.3;
            z-index: -1;
        }
        
        .move {
            animation: float 20s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(120deg); }
            66% { transform: translate(-20px, 20px) rotate(240deg); }
        }
        
        .header__bg-container::after {
            background: linear-gradient(135deg, #17639D 0%, #1E7AB8 100%);
        }
        
        .info-head {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border: 1px solid #cbd5e1;
        }
        
        .cover_img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            cursor: pointer;
        }
        
        .cover_img:hover {
            transform: scale(1.02);
        }
        
        .wl_shadow_2 {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .wl_shadow_3 {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
        }
        
        .dark .bg-emerald-200\/40 {
            background-color: rgba(16, 185, 129, 0.2);
        }
        
        .dark .text-emerald-400\/90 {
            color: rgba(52, 211, 153, 0.9);
        }

        /* Custom scrollbar */
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        @stack('styles')
    </style>
</head>
<body class="dark:bg-slate-700">

<!-- Preloader -->
<div class="preloader">
    <div class="spinner"></div>
</div>

<!-- Background Effects -->
<div class="transition duration-75 pointer-events-none glow-dark fixed top-20 left-10 bg-blue-400 move" style="animation-delay: -1s;"></div>
<div class="transition duration-300 pointer-events-none glow-dark fixed top-40 right-20 bg-emerald-400 move" style="animation-delay: -6s;"></div>

<!-- Header -->
<header class="header">
    <!-- Top Bar -->
    <div class="bg-gray-800 text-white py-2 dark:bg-slate-500">
        <div class="container mx-auto px-6">
            <div class="flex justify-between items-center text-sm">
                <div class="flex space-x-4">
                    <button id="dark-mode-toggle" class="hover:text-gray-300 transition-colors flex items-center">
                        <i class="fas fa-sun mr-1"></i>
                        <span class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-gray-400 transition-colors duration-200 ease-in-out">
                            <span class="dark-toggle pointer-events-none inline-block h-5 w-5 translate-x-0 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                        </span>
                        <i class="fas fa-moon ml-1"></i>
                    </button>
                </div>
                <div class="flex space-x-4">
                    <span class="text-gray-300">{{ now()->format('d/m/Y') }}</span>
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-300 hover:text-white transition-colors">
                            <i class="fas fa-user mr-1"></i> Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors">
                            <i class="fas fa-user mr-1"></i> Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Logo Section -->
    <div class="bg-white py-6 dark:bg-slate-600">
        <div class="container mx-auto px-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-600 p-3 rounded-lg">
                        <i class="fas fa-newspaper text-white text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Diário Oficial</h1>
                        <p class="text-gray-600 dark:text-gray-300">Prefeitura Municipal</p>
                    </div>
                </div>
                
                <!-- Mobile Menu Toggle -->
                <div class="lg:hidden">
                    <button id="mobile-menu-toggle" class="text-gray-700 dark:text-gray-300">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Section -->
    <div class="bg-[#17639D] py-4 dark:bg-slate-600">
        <div class="container mx-auto px-6">
            <div class="flex justify-end">
                <form class="w-full max-w-2xl" method="GET" action="{{ route('home.buscar') }}">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" 
                               name="q" 
                               class="block w-full pl-10 pr-20 py-3 text-sm text-gray-900 border-2 border-white/30 rounded-lg bg-gray-50 focus:outline-0 dark:bg-gray-900 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                               placeholder="Buscar no Diário" 
                               value="{{ request('q') }}" />
                        <button type="submit" 
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-slate-500 hover:bg-blue-600 text-white px-4 py-1.5 rounded-lg text-sm font-medium transition-colors">
                            Buscar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</header>

<!-- Navigation -->
<div class="bg-[#17639D] text-gray-200 border-b border-blue-300 dark:bg-slate-700 dark:border-none hidden lg:block">
    <div class="container mx-auto">
        <nav class="font-semibold">
            <ul class="flex items-center">
                <a href="{{ route('home') }}">
                    <li class="py-2 px-4 border-b-2 border-b-transparent hover:border-b-blue-200 dark:hover:border-b-slate-400 hover:text-blue-200 dark:hover:text-slate-300 transition-colors cursor-pointer {{ request()->routeIs('home') ? 'border-b-blue-200 text-blue-200' : '' }}">
                        <span class="uppercase text-sm">Início</span>
                    </li>
                </a>
                <a href="{{ route('portal.materias.index') }}">
                    <li class="border-l pl-4 border-blue-300/30 border-b-2 border-b-transparent py-2 px-4 hover:border-b-blue-200 dark:hover:border-b-slate-400 hover:text-blue-200 dark:hover:text-slate-300 transition-colors cursor-pointer {{ request()->routeIs('portal.materias.*') ? 'border-b-blue-200 text-blue-200' : '' }}">
                        <span class="uppercase text-sm">Matérias</span>
                    </li>
                </a>
                <a href="{{ route('portal.edicoes.index') }}">
                    <li class="border-l pl-4 border-blue-300/30 border-b-2 border-b-transparent py-2 px-4 hover:border-b-blue-200 dark:hover:border-b-slate-400 hover:text-blue-200 dark:hover:text-slate-300 transition-colors cursor-pointer {{ request()->routeIs('portal.edicoes.*') ? 'border-b-blue-200 text-blue-200' : '' }}">
                        <span class="uppercase text-sm">Edições</span>
                    </li>
                </a>
                <a href="#">
                    <li class="border-l pl-4 border-blue-300/30 border-b-2 border-b-transparent py-2 px-4 hover:border-b-blue-200 dark:hover:border-b-slate-400 hover:text-blue-200 dark:hover:text-slate-300 transition-colors cursor-pointer">
                        <span class="uppercase text-sm">Regulamentação</span>
                    </li>
                </a>
            </ul>
        </nav>
    </div>
</div>

<!-- Mobile Navigation -->
<div id="mobile-menu" class="lg:hidden bg-[#17639D] text-white hidden">
    <div class="container mx-auto px-6 py-4">
        <ul class="space-y-2">
            <li><a href="{{ route('home') }}" class="block py-2 hover:text-blue-200">Início</a></li>
            <li><a href="{{ route('portal.materias.index') }}" class="block py-2 hover:text-blue-200">Matérias</a></li>
            <li><a href="{{ route('portal.edicoes.index') }}" class="block py-2 hover:text-blue-200">Edições</a></li>
            <li><a href="#" class="block py-2 hover:text-blue-200">Regulamentação</a></li>
        </ul>
    </div>
</div>

<!-- Document Types Navigation -->
<div class="bg-white dark:bg-slate-700 border-t border-gray-200 dark:border-slate-600">
    <div class="container mx-auto px-6">
        <div class="flex items-center py-3 overflow-x-auto scrollbar-hide">
            <div class="flex space-x-4 min-w-max">
                <a href="{{ route('portal.documentos.portarias') }}" 
                   class="flex items-center gap-2 px-3 py-2 text-gray-700 dark:text-gray-300 hover:text-[#17639D] dark:hover:text-blue-400 transition-all duration-300 group whitespace-nowrap rounded-lg hover:bg-gray-50 dark:hover:bg-slate-600">
                    <i class="fas fa-file-alt text-amber-500 group-hover:scale-110 transition-transform duration-200"></i>
                    <span class="font-medium text-sm">Portarias</span>
                </a>
                <a href="{{ route('portal.documentos.decretos') }}" 
                   class="flex items-center gap-2 px-3 py-2 text-gray-700 dark:text-gray-300 hover:text-[#17639D] dark:hover:text-blue-400 transition-all duration-300 group whitespace-nowrap rounded-lg hover:bg-gray-50 dark:hover:bg-slate-600">
                    <i class="fas fa-file-alt text-blue-500 group-hover:scale-110 transition-transform duration-200"></i>
                    <span class="font-medium text-sm">Decretos</span>
                </a>
                <a href="{{ route('portal.documentos.leis') }}" 
                   class="flex items-center gap-2 px-3 py-2 text-gray-700 dark:text-gray-300 hover:text-[#17639D] dark:hover:text-blue-400 transition-all duration-300 group whitespace-nowrap rounded-lg hover:bg-gray-50 dark:hover:bg-slate-600">
                    <i class="fas fa-file-alt text-green-500 group-hover:scale-110 transition-transform duration-200"></i>
                    <span class="font-medium text-sm">Leis</span>
                </a>
                <a href="{{ route('portal.documentos.resolucoes') }}" 
                   class="flex items-center gap-2 px-3 py-2 text-gray-700 dark:text-gray-300 hover:text-[#17639D] dark:hover:text-blue-400 transition-all duration-300 group whitespace-nowrap rounded-lg hover:bg-gray-50 dark:hover:bg-slate-600">
                    <i class="fas fa-file-alt text-purple-500 group-hover:scale-110 transition-transform duration-200"></i>
                    <span class="font-medium text-sm">Resoluções</span>
                </a>
                <a href="{{ route('portal.documentos.editais') }}" 
                   class="flex items-center gap-2 px-3 py-2 text-gray-700 dark:text-gray-300 hover:text-[#17639D] dark:hover:text-blue-400 transition-all duration-300 group whitespace-nowrap rounded-lg hover:bg-gray-50 dark:hover:bg-slate-600">
                    <i class="fas fa-bullhorn text-red-500 group-hover:scale-110 transition-transform duration-200"></i>
                    <span class="font-medium text-sm">Editais</span>
                </a>
                <a href="{{ route('portal.materias.index') }}" 
                   class="flex items-center gap-2 px-3 py-2 text-gray-700 dark:text-gray-300 hover:text-[#17639D] dark:hover:text-blue-400 transition-all duration-300 group whitespace-nowrap rounded-lg hover:bg-gray-50 dark:hover:bg-slate-600 border-l-2 border-gray-200 dark:border-slate-600 ml-2 pl-4">
                    <i class="fas fa-folder text-indigo-500 group-hover:scale-110 transition-transform duration-200"></i>
                    <span class="font-medium text-sm">Todos os Documentos</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<main class="main_content">
    @yield('content')
</main>

<!-- Footer -->
<footer class="bg-[#256FAB] dark:bg-slate-900 pt-12 pb-6">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <div class="flex items-start gap-4">
                <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBmaWxsPSIjMEY5Qjc2Ii8+CjwvZXZnPgo=" alt="Certificação Digital" class="flex-shrink-0">
                <div>
                    <p class="text-sm text-gray-200 dark:text-slate-400">
                        Os Atos Oficiais publicados neste site são assinados digitalmente por autoridade certificadora credenciada no âmbito da Infraestrutura de Chaves Públicas Brasileira - ICP-Brasil.
                    </p>
                    <ul class="text-gray-300 mt-4 space-y-1">
                        <li><a class="hover:underline transition-colors dark:text-slate-400" href="#" title="Ouvidoria">Ouvidoria</a></li>
                        <li><a class="hover:underline transition-colors dark:text-slate-400" href="#" title="e-SIC">e-SIC</a></li>
                        <li><a class="hover:underline transition-colors dark:text-slate-400" href="#" title="Website">Website</a></li>
                        <li><a class="hover:underline transition-colors dark:text-slate-400" href="#" title="Dados Abertos">Dados Abertos</a></li>
                    </ul>
                </div>
            </div>
            <div>
                <p class="text-gray-300 dark:text-slate-400 font-bold mb-2">Prefeitura Municipal</p>
                <div class="space-y-2 text-gray-300 dark:text-slate-400">
                    <p class="flex items-center">
                        <i class="fas fa-map-marker-alt bg-white/20 p-1 rounded mr-3"></i>
                        Rua Principal, 123 - Centro - CEP: 12345-678
                    </p>
                    <p class="flex items-center">
                        <i class="fas fa-phone bg-white/20 p-1 rounded mr-3"></i>
                        (11) 1234-5678
                    </p>
                    <p class="flex items-center">
                        <i class="fas fa-envelope bg-white/20 p-1 rounded mr-3"></i>
                        diario@prefeitura.gov.br
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Copyright -->
<div class="bg-[#16649D] dark:bg-slate-700 py-4 text-gray-300 text-sm">
    <div class="container mx-auto px-6 dark:text-slate-400/80">
        <div class="text-center">
            <div class="mb-2">© Copyright {{ date('Y') }} - Prefeitura Municipal</div>
            <div class="flex items-center justify-center space-x-4">
                <a class="hover:underline" href="#">Termos de uso</a>
                <span>|</span>
                <a class="hover:underline" href="#">Política de Privacidade</a>
            </div>
        </div>
    </div>
</div>

<script>
    // Remove preloader
    window.addEventListener('load', function() {
        document.querySelector('.preloader').style.display = 'none';
    });

    // Dark mode toggle
    document.getElementById('dark-mode-toggle').addEventListener('click', function() {
        document.documentElement.classList.toggle('dark');
        const toggle = document.querySelector('.dark-toggle');
        toggle.classList.toggle('translate-x-5');
    });

    // Mobile menu toggle
    document.getElementById('mobile-menu-toggle').addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });
</script>

@stack('scripts')

</body>
</html>
