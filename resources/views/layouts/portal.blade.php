<!DOCTYPE html>
<html lang="pt-BR">
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
    <meta property="og:title" content="@yield('title', 'Diário Oficial - Sistema de Publicação Eletrônica')"/>
    <meta property="og:description" content="@yield('description', 'Sistema de Publicação - Diário Eletrônico')"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Extra CSS -->
    @stack('styles')
    
    <style>
        /* Preloader com animação mais suave */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
            transition: opacity 0.5s ease;
        }
        
        .dark .preloader {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        }
        
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(255, 255, 255, 0.2);
            border-top: 5px solid #17639D;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            box-shadow: 0 4px 20px rgba(23, 99, 157, 0.3);
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Efeitos de fundo aprimorados */
        .glow-dark {
            position: fixed;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.4;
            z-index: -1;
        }
        
        .move {
            animation: float 25s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg) scale(1); }
            25% { transform: translate(50px, -50px) rotate(90deg) scale(1.1); }
            50% { transform: translate(-30px, 30px) rotate(180deg) scale(0.9); }
            75% { transform: translate(40px, -20px) rotate(270deg) scale(1.05); }
        }
        
        /* Glassmorphism aprimorado */
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .dark .glass {
            background: rgba(30, 41, 59, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Hover effects melhorados */
        .hover-lift {
            transition: color 0.3s ease, background-color 0.3s ease, box-shadow 0.3s ease, opacity 0.3s ease;
        }
        
        .hover-lift:hover {
            /* Removido translate/scale para evitar pulos/reflow */
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        /* Animação de entrada suave */
        .fade-in {
            animation: fadeIn 0.8s ease-out forwards;
            opacity: 0;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Botões com gradiente */
        .btn-gradient {
            background: linear-gradient(135deg, #17639D 0%, #1E7AB8 50%, #256FAB 100%);
            background-size: 200% 200%;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .btn-gradient:hover {
            background-position: 100% 0;
            /* Removido translateY para evitar deslocamento */
            box-shadow: 0 10px 25px rgba(23, 99, 157, 0.3);
        }
        .btn-gradient:hover::before {
            left: 100%;
        }
        
        /* Menu navigation com efeitos */
        .nav-item {
            position: relative;
            /* limitar a transição para evitar reflow */
            transition: color 0.3s ease, background-color 0.3s ease;
        }
        
        .nav-item::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #17639D, #1E7AB8);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-item:hover::after,
        .nav-item.active::after {
            width: 100%;
        }
        
        /* Cards com efeito */
        .card-modern {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .dark .card-modern {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .card-modern:hover {
            /* Removido translate/scale para evitar pulos */
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }
        
        /* Scrollbar personalizada */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(45deg, #17639D, #1E7AB8);
            border-radius: 10px;
        }
        
        /* Text gradients */
        .text-gradient {
            background: linear-gradient(135deg, #17639D 0%, #1E7AB8 50%, #256FAB 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Pulse animation */
        .pulse-slow {
            animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .8;
            }
        }
        
        /* Loading skeleton */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }
        
        @keyframes loading {
            0% {
                background-position: 200% 0;
            }
            100% {
                background-position: -200% 0;
            }
        }
        
        /* Dark mode transitions melhorados */
        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
        }
        
        /* Forçar estilos dark mode para elementos específicos */
        .dark .bg-white {
            background-color: rgb(51 65 85) !important;
        }
        
        .dark .text-gray-900 {
            color: rgb(241 245 249) !important;
        }
        
        .dark .text-gray-800 {
            color: rgb(226 232 240) !important;
        }
        
        .dark .text-gray-700 {
            color: rgb(203 213 225) !important;
        }
        
        .dark .text-gray-600 {
            color: rgb(148 163 184) !important;
        }
        
        .dark .border-gray-200 {
            border-color: rgb(51 65 85) !important;
        }
        
        .dark .border-gray-300 {
            border-color: rgb(71 85 105) !important;
        }
        
        .dark .bg-gray-100 {
            background-color: rgb(71 85 105) !important;
        }
        
        .dark .bg-gray-50 {
            background-color: rgb(51 65 85) !important;
        }
        
        /* Botões e cards em dark mode */
        .dark .card-modern {
            background: rgba(30, 41, 59, 0.8) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: rgb(241 245 249) !important;
        }
        
        .dark .btn-gradient {
            background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #475569 100%) !important;
            color: rgb(241 245 249) !important;
        }
        
        /* Inputs em dark mode */
        .dark input[type="text"],
        .dark input[type="email"],
        .dark input[type="password"],
        .dark textarea,
        .dark select {
            background-color: rgb(30 41 59) !important;
            border-color: rgb(71 85 105) !important;
            color: rgb(241 245 249) !important;
        }
        
        .dark input[type="text"]::placeholder,
        .dark input[type="email"]::placeholder,
        .dark input[type="password"]::placeholder,
        .dark textarea::placeholder {
            color: rgb(148 163 184) !important;
        }
        
        /* Links em dark mode */
        .dark a {
            color: rgb(147 197 253) !important;
        }
        
        .dark a:hover {
            color: rgb(191 219 254) !important;
        }
        
        /* Forçar cores específicas para elementos persistentes */
        .dark .text-gradient {
            background: linear-gradient(135deg, #60a5fa 0%, #93c5fd 50%, #bfdbfe 100%) !important;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            background-clip: text !important;
        }
        
        /* Shadow em dark mode */
        .dark .shadow,
        .dark .shadow-sm,
        .dark .shadow-md,
        .dark .shadow-lg,
        .dark .shadow-xl {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5) !important;
        }
        
        /* Elementos específicos que não estão mudando */
        .dark .bg-white\/95 {
            background-color: rgba(30, 41, 59, 0.95) !important;
        }
        
        .dark .bg-white\/90 {
            background-color: rgba(30, 41, 59, 0.9) !important;
        }
        
        .dark .text-black {
            color: rgb(241 245 249) !important;
        }
        
        /* Botões e elementos interativos */
        .dark button {
            color: rgb(241 245 249) !important;
        }
        
        .dark .hover\:bg-gray-50:hover {
            background-color: rgb(71 85 105) !important;
        }
        
        .dark .hover\:bg-gray-100:hover {
            background-color: rgb(51 65 85) !important;
        }
        
        /* Garantir que os elementos de edição/visualização mudem */
        .dark .edition-card,
        .dark .materia-card,
        .dark .document-item {
            background-color: rgb(30 41 59) !important;
            color: rgb(241 245 249) !important;
            border-color: rgb(71 85 105) !important;
        }
        
        .dark .edition-title,
        .dark .materia-title {
            color: rgb(241 245 249) !important;
        }
        
        .dark .edition-date,
        .dark .materia-date {
            color: rgb(148 163 184) !important;
        }
        
        .dark .edition-stats,
        .dark .materia-stats {
            color: rgb(148 163 184) !important;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-slate-700">

<!-- Preloader -->
<div class="preloader">
    <div class="spinner"></div>
</div>

<!-- Background Effects -->
<div class="transition duration-75 pointer-events-none glow-dark fixed top-20 left-10 bg-blue-400 move pulse-slow" style="animation-delay: -1s;"></div>
<div class="transition duration-300 pointer-events-none glow-dark fixed top-40 right-20 bg-emerald-400 move pulse-slow" style="animation-delay: -6s;"></div>
<div class="transition duration-500 pointer-events-none glow-dark fixed bottom-20 left-1/4 bg-purple-400 move" style="animation-delay: -12s;"></div>
<div class="transition duration-200 pointer-events-none glow-dark fixed top-1/2 right-1/3 bg-pink-400 move pulse-slow" style="animation-delay: -18s;"></div>

<!-- Header -->
<header class="header fade-in">
    <!-- Top Bar -->
    <div class="bg-gray-800/90 backdrop-filter backdrop-blur-sm text-white py-3 dark:bg-slate-500/80 border-b border-white/10">
        <div class="container mx-auto px-6">
            <div class="flex justify-between items-center text-sm">
                <div class="flex space-x-4">
                    <button id="dark-mode-toggle" class="hover:text-gray-300 transition-colors duration-200 flex items-center">
                        <i class="fas fa-sun mr-2 text-yellow-400"></i>
                        <span class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-gray-400/50 backdrop-blur-sm transition-all duration-300 hover:bg-gray-300">
                            <span class="dark-toggle pointer-events-none inline-block h-5 w-5 translate-x-0 transform rounded-full bg-white shadow-lg ring-0 transition duration-300 ease-in-out"></span>
                        </span>
                        <i class="fas fa-moon ml-2 text-blue-300"></i>
                    </button>
                </div>
                <div class="flex space-x-6">
                    <span class="text-gray-300 flex items-center">
                        <i class="fas fa-calendar-alt mr-2 text-blue-400"></i>
                        {{ now()->format('d/m/Y') }}
                    </span>
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-300 hover:text-white transition-colors duration-200 flex items-center">
                            <i class="fas fa-user mr-2"></i> Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors duration-200 flex items-center">
                            <i class="fas fa-user mr-2"></i> Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Logo Section -->
    <div class="bg-white/95 backdrop-filter backdrop-blur-md py-8 dark:bg-slate-600/90 border-b border-gray-200/50 dark:border-slate-500/50">
        <div class="container mx-auto px-6">
            <div class="flex items-center justify-between flex-nowrap">
                <div class="flex items-center space-x-6 hover-lift flex-shrink-0">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-700 p-4 rounded-2xl shadow-lg hover:shadow-xl transition-colors duration-200">
                        <i class="fas fa-newspaper text-white text-4xl"></i>
                    </div>
                    <div class="flex items-baseline space-x-3 whitespace-nowrap">
                        <h1 class="text-3xl lg:text-4xl font-bold text-gradient">Diário Oficial</h1>
                        <span class="text-gray-600 dark:text-gray-300 text-lg font-medium">/ Prefeitura Municipal</span>
                    </div>
                </div>
                
                <!-- Right side: Search on desktop/tablet, mobile menu button on small screens -->
                <div class="flex items-center gap-4 flex-1 min-w-0 justify-end">
                    <form class="hidden md:block w-full max-w-xl" method="GET" action="{{ route('home.buscar') }}">
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <i class="fas fa-search text-gray-400 group-focus-within:text-blue-500 transition-colors duration-300"></i>
                            </div>
                            <input type="text" 
                                   name="q" 
                                   class="block w-full pl-12 pr-28 py-3 text-sm text-gray-900 border border-gray-300 rounded-xl bg-white/90 backdrop-blur-md focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 dark:bg-gray-900/80 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white shadow-md" 
                                   placeholder="Buscar no Diário Oficial..." 
                                   value="{{ request('q') }}" />
                            <button type="submit" 
                                    class="absolute right-2 top-1/2 -translate-y-1/2 btn-gradient text-white px-5 py-2 rounded-lg text-sm font-semibold shadow-md">
                                Buscar
                            </button>
                        </div>
                    </form>

                    <!-- Mobile Menu Toggle -->
                    <div class="md:hidden">
                        <button id="mobile-menu-toggle" class="text-gray-700 dark:text-gray-300 p-3 rounded-lg hover:bg-gray-100/50 dark:hover:bg-slate-500/50 transition-colors duration-200" aria-label="Abrir menu">
                            <i class="fas fa-bars text-2xl"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Navigation -->
<div class="bg-gradient-to-r from-[#17639D] to-[#1E7AB8] text-gray-200 dark:from-slate-700 dark:to-slate-800 dark:border-none hidden lg:block shadow-lg">
    <div class="container mx-auto">
        <nav class="font-semibold">
            <ul class="flex items-center">
                <a href="{{ route('home') }}">
                    <li class="nav-item py-4 px-6 hover:bg-white/10 dark:hover:bg-slate-600/50 transition-colors duration-200 cursor-pointer {{ request()->routeIs('home') ? 'bg-white/20 text-white' : '' }}">
                        <span class="uppercase text-sm font-bold flex items-center">
                            <i class="fas fa-home mr-2"></i>Início
                        </span>
                    </li>
                </a>
                <a href="{{ route('portal.materias.index') }}">
                    <li class="nav-item border-l border-blue-300/30 py-4 px-6 hover:bg-white/10 dark:hover:bg-slate-600/50 transition-colors duration-200 cursor-pointer {{ request()->routeIs('portal.materias.*') ? 'bg-white/20 text-white' : '' }}">
                        <span class="uppercase text-sm font-bold flex items-center">
                            <i class="fas fa-file-text mr-2"></i>Matérias
                        </span>
                    </li>
                </a>
                <a href="{{ route('portal.edicoes.index') }}">
                    <li class="nav-item border-l border-blue-300/30 py-4 px-6 hover:bg-white/10 dark:hover:bg-slate-600/50 transition-colors duration-200 cursor-pointer {{ request()->routeIs('portal.edicoes.*') ? 'bg-white/20 text-white' : '' }}">
                        <span class="uppercase text-sm font-bold flex items-center">
                            <i class="fas fa-book mr-2"></i>Edições
                        </span>
                    </li>
                </a>
                <a href="#">
                    <li class="nav-item border-l border-blue-300/30 py-4 px-6 hover:bg-white/10 dark:hover:bg-slate-600/50 transition-colors duration-200 cursor-pointer">
                        <span class="uppercase text-sm font-bold flex items-center">
                            <i class="fas fa-gavel mr-2"></i>Regulamentação
                        </span>
                    </li>
                </a>
            </ul>
        </nav>
    </div>
</div>

<!-- Mobile Navigation -->
<div id="mobile-menu" class="lg:hidden bg-gradient-to-br from-[#17639D] to-[#1E7AB8] text-white hidden transform transition-all duration-300 ease-in-out shadow-2xl">
    <div class="container mx-auto px-6 py-6">
        <ul class="space-y-3">
            <li>
                <a href="{{ route('home') }}" class="flex items-center py-3 px-4 hover:bg-white/10 rounded-xl transition-colors duration-200">
                    <i class="fas fa-home mr-3 text-blue-300"></i>
                    <span class="font-semibold">Início</span>
                </a>
            </li>
            <li>
                <a href="{{ route('portal.materias.index') }}" class="flex items-center py-3 px-4 hover:bg-white/10 rounded-xl transition-colors duration-200">
                    <i class="fas fa-file-text mr-3 text-green-300"></i>
                    <span class="font-semibold">Matérias</span>
                </a>
            </li>
            <li>
                <a href="{{ route('portal.edicoes.index') }}" class="flex items-center py-3 px-4 hover:bg-white/10 rounded-xl transition-colors duration-200">
                    <i class="fas fa-book mr-3 text-yellow-300"></i>
                    <span class="font-semibold">Edições</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center py-3 px-4 hover:bg-white/10 rounded-xl transition-colors duration-200">
                    <i class="fas fa-gavel mr-3 text-purple-300"></i>
                    <span class="font-semibold">Regulamentação</span>
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- Document Types Navigation -->
<div class="bg-white/95 backdrop-filter backdrop-blur-md dark:bg-slate-700/90 border-t border-gray-200/50 dark:border-slate-600/50 sticky top-0 z-40 shadow-md">
    <div class="container mx-auto px-6">
        <div class="flex items-center py-6 border-b-4 border-gradient-to-r from-[#17639D] to-[#1E7AB8] overflow-x-auto custom-scrollbar">
            <div class="flex space-x-4 min-w-max">
                <a href="{{ route('portal.documentos.portarias') }}" 
                   class="flex items-center gap-3 px-6 py-3 text-gray-700 dark:text-gray-300 hover:text-[#17639D] dark:hover:text-blue-400 transition-colors duration-200 group whitespace-nowrap rounded-2xl hover:bg-gradient-to-br hover:from-amber-50 hover:to-amber-100 dark:hover:from-slate-600 dark:hover:to-slate-500 shadow-sm hover:shadow-md {{ request()->routeIs('portal.documentos.portarias') ? 'bg-gradient-to-br from-amber-50 to-amber-100 dark:from-slate-600 dark:to-slate-500 ring-1 ring-amber-200/60 dark:ring-slate-500/50 text-[#17639D] dark:text-blue-400' : '' }}">
                    <i class="fas fa-file-alt text-amber-500 text-lg w-5 text-center shrink-0"></i>
                    <span class="font-semibold">Portarias</span>
                </a>
                <a href="{{ route('portal.documentos.decretos') }}" 
                   class="flex items-center gap-3 px-6 py-3 text-gray-700 dark:text-gray-300 hover:text-[#17639D] dark:hover:text-blue-400 transition-colors duration-200 group whitespace-nowrap rounded-2xl hover:bg-gradient-to-br hover:from-blue-50 hover:to-blue-100 dark:hover:from-slate-600 dark:hover:to-slate-500 shadow-sm hover:shadow-md {{ request()->routeIs('portal.documentos.decretos') ? 'bg-gradient-to-br from-blue-50 to-blue-100 dark:from-slate-600 dark:to-slate-500 ring-1 ring-blue-200/60 dark:ring-slate-500/50 text-[#17639D] dark:text-blue-400' : '' }}">
                    <i class="fas fa-file-alt text-blue-500 text-lg w-5 text-center shrink-0"></i>
                    <span class="font-semibold">Decretos</span>
                </a>
                <a href="{{ route('portal.documentos.leis') }}" 
                   class="flex items-center gap-3 px-6 py-3 text-gray-700 dark:text-gray-300 hover:text-[#17639D] dark:hover:text-blue-400 transition-colors duration-200 group whitespace-nowrap rounded-2xl hover:bg-gradient-to-br hover:from-green-50 hover:to-green-100 dark:hover:from-slate-600 dark:hover:to-slate-500 shadow-sm hover:shadow-md {{ request()->routeIs('portal.documentos.leis') ? 'bg-gradient-to-br from-green-50 to-green-100 dark:from-slate-600 dark:to-slate-500 ring-1 ring-green-200/60 dark:ring-slate-500/50 text-[#17639D] dark:text-blue-400' : '' }}">
                    <i class="fas fa-file-alt text-green-500 text-lg w-5 text-center shrink-0"></i>
                    <span class="font-semibold">Leis</span>
                </a>
                <a href="{{ route('portal.documentos.resolucoes') }}" 
                   class="flex items-center gap-3 px-6 py-3 text-gray-700 dark:text-gray-300 hover:text-[#17639D] dark:hover:text-blue-400 transition-colors duration-200 group whitespace-nowrap rounded-2xl hover:bg-gradient-to-br hover:from-purple-50 hover:to-purple-100 dark:hover:from-slate-600 dark:hover:to-slate-500 shadow-sm hover:shadow-md {{ request()->routeIs('portal.documentos.resolucoes') ? 'bg-gradient-to-br from-purple-50 to-purple-100 dark:from-slate-600 dark:to-slate-500 ring-1 ring-purple-200/60 dark:ring-slate-500/50 text-[#17639D] dark:text-blue-400' : '' }}">
                    <i class="fas fa-file-alt text-purple-500 text-lg w-5 text-center shrink-0"></i>
                    <span class="font-semibold">Resoluções</span>
                </a>
                <a href="{{ route('portal.documentos.editais') }}" 
                   class="flex items-center gap-3 px-6 py-3 text-gray-700 dark:text-gray-300 hover:text-[#17639D] dark:hover:text-blue-400 transition-colors duration-200 group whitespace-nowrap rounded-2xl hover:bg-gradient-to-br hover:from-red-50 hover:to-red-100 dark:hover:from-slate-600 dark:hover:to-slate-500 shadow-sm hover:shadow-md {{ request()->routeIs('portal.documentos.editais') ? 'bg-gradient-to-br from-red-50 to-red-100 dark:from-slate-600 dark:to-slate-500 ring-1 ring-red-200/60 dark:ring-slate-500/50 text-[#17639D] dark:text-blue-400' : '' }}">
                    <i class="fas fa-bullhorn text-red-500 text-lg w-5 text-center shrink-0"></i>
                    <span class="font-semibold">Editais</span>
                </a>
                <div class="w-px h-8 bg-gray-300 dark:bg-slate-500 mx-2"></div>
                <a href="{{ route('portal.materias.index') }}" 
                   class="flex items-center gap-3 px-6 py-3 text-gray-700 dark:text-gray-300 hover:text-[#17639D] dark:hover:text-blue-400 transition-colors duration-200 group whitespace-nowrap rounded-2xl hover:bg-gradient-to-br hover:from-indigo-50 hover:to-indigo-100 dark:hover:from-slate-600 dark:hover:to-slate-500 shadow-sm hover:shadow-md {{ request()->routeIs('portal.materias.*') ? 'bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-slate-600 dark:to-slate-500 ring-1 ring-indigo-200/60 dark:ring-slate-500/50 text-[#17639D] dark:text-blue-400' : '' }}">
                    <i class="fas fa-folder text-indigo-500 text-lg w-5 text-center shrink-0"></i>
                    <span class="font-semibold">Todos os Documentos</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<main class="main_content">
    @if(session('success'))
        <div class="container mx-auto px-6 pt-6">
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mx-auto px-6 pt-6">
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

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

    @stack('scripts')

    <script>
        // Remove preloader
        window.addEventListener('load', function() {
            const preloader = document.querySelector('.preloader');
            if (preloader) {
                preloader.style.opacity = '0';
                setTimeout(() => {
                    preloader.style.display = 'none';
                }, 500);
            }
        });

        // Dark mode toggle
        function initDarkMode() {
            // Verificar preferência salva ou padrão do sistema
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const toggle = document.querySelector('.dark-toggle');
            
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                applyDarkMode(true);
                if (toggle) toggle.classList.add('translate-x-5');
            } else {
                applyDarkMode(false);
                if (toggle) toggle.classList.remove('translate-x-5');
            }
        }
        
        function applyDarkMode(isDark) {
            const html = document.documentElement;
            const body = document.body;
            
            if (isDark) {
                html.classList.add('dark');
                body.classList.add('dark');
                
                // Forçar aplicação em elementos específicos que podem não estar respondendo
                const elementsToUpdate = [
                    { selector: '.bg-white', class: 'bg-slate-700' },
                    { selector: '.text-gray-900', class: 'text-white' },
                    { selector: '.text-gray-800', class: 'text-gray-200' },
                    { selector: '.text-gray-700', class: 'text-gray-300' },
                    { selector: '.text-gray-600', class: 'text-gray-400' },
                    { selector: '.border-gray-200', class: 'border-slate-600' },
                    { selector: '.border-gray-300', class: 'border-slate-500' },
                    { selector: '.bg-gray-50', class: 'bg-slate-800' },
                    { selector: '.bg-gray-100', class: 'bg-slate-700' }
                ];
                
                elementsToUpdate.forEach(item => {
                    document.querySelectorAll(item.selector).forEach(el => {
                        el.style.setProperty('background-color', '', 'important');
                        el.style.setProperty('color', '', 'important');
                        el.style.setProperty('border-color', '', 'important');
                    });
                });
                
                console.log('Dark mode aplicado com força');
            } else {
                html.classList.remove('dark');
                body.classList.remove('dark');
                
                // Remover estilos forçados
                document.querySelectorAll('*').forEach(el => {
                    el.style.removeProperty('background-color');
                    el.style.removeProperty('color');
                    el.style.removeProperty('border-color');
                });
                
                console.log('Light mode aplicado');
            }
            
            // Forçar re-render mais suave
            requestAnimationFrame(() => {
                html.style.opacity = '0.99';
                requestAnimationFrame(() => {
                    html.style.opacity = '1';
                });
            });
        }

        // Aguardar DOM carregar antes de inicializar
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing dark mode...');
            
            // Inicializar dark mode
            initDarkMode();

            // Toggle dark mode
            const darkModeToggle = document.getElementById('dark-mode-toggle');
            console.log('Dark mode toggle button:', darkModeToggle);
            
            if (darkModeToggle) {
                darkModeToggle.addEventListener('click', function() {
                    console.log('Dark mode toggle clicked');
                    const isDark = document.documentElement.classList.contains('dark');
                    const toggle = document.querySelector('.dark-toggle');
                    console.log('Current mode:', isDark ? 'dark' : 'light');
                    
                    if (isDark) {
                        applyDarkMode(false);
                        if (toggle) toggle.classList.remove('translate-x-5');
                        localStorage.setItem('theme', 'light');
                        console.log('Switched to light mode');
                    } else {
                        applyDarkMode(true);
                        if (toggle) toggle.classList.add('translate-x-5');
                        localStorage.setItem('theme', 'dark');
                        console.log('Switched to dark mode');
                    }
                });
            } else {
                console.error('Dark mode toggle button not found!');
            }

            // Mobile menu toggle
            const mobileToggle = document.getElementById('mobile-menu-toggle');
            if (mobileToggle) {
                mobileToggle.addEventListener('click', function() {
                    const menu = document.getElementById('mobile-menu');
                    if (menu) {
                        menu.classList.toggle('hidden');
                        
                        // Animação suave
                        if (!menu.classList.contains('hidden')) {
                            menu.style.transform = 'translateY(-10px)';
                            menu.style.opacity = '0';
                            setTimeout(() => {
                                menu.style.transform = 'translateY(0)';
                                menu.style.opacity = '1';
                            }, 10);
                        }
                    }
                });
            }
        });

        // Funcionalidade de acessibilidade
        const accessibilityBtn = document.getElementById('accessibility-btn');
        if (accessibilityBtn) {
            accessibilityBtn.addEventListener('click', function() {
                const controls = document.querySelector('.accessibility-controls');
                if (controls) controls.classList.toggle('hidden');
            });
        }

        // Controle de fonte
        let currentFontSize = 16;
        const increaseFontBtn = document.getElementById('increase-font');
        const decreaseFontBtn = document.getElementById('decrease-font');
        
        if (increaseFontBtn) {
            increaseFontBtn.addEventListener('click', function() {
                currentFontSize += 2;
                document.body.style.fontSize = currentFontSize + 'px';
            });
        }
        
        if (decreaseFontBtn) {
            decreaseFontBtn.addEventListener('click', function() {
                if (currentFontSize > 12) {
                    currentFontSize -= 2;
                    document.body.style.fontSize = currentFontSize + 'px';
                }
            });
        }

        // Alto contraste
        const highContrastBtn = document.getElementById('high-contrast');
        if (highContrastBtn) {
            highContrastBtn.addEventListener('click', function() {
                document.body.classList.toggle('high-contrast');
            });
        }

        // Dropdown do menu
        document.querySelectorAll('.dropdown').forEach(dropdown => {
            const button = dropdown.querySelector('button');
            const menu = dropdown.querySelector('.dropdown-menu');
            
            if (button && menu) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    menu.classList.toggle('hidden');
                });
                
                // Fechar dropdown ao clicar fora
                document.addEventListener('click', function(e) {
                    if (!dropdown.contains(e.target)) {
                        menu.classList.add('hidden');
                    }
                });
            }
        });

        // Busca por enter
        const searchInput = document.querySelector('form input[name="q"]');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    this.form.submit();
                }
            });
        }
    </script>
</body>
</html>
