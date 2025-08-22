<!DOCTYPE html>
<html class="" lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="x-ua-compatible" content="ie=edge"/>
    <meta property="og:type" content="article"/>
    <meta property="og:site_name" content="Diário Eletrônico - Prefeitura Municipal"/>
    <meta property="og:locale" content="pt_BR"/>
    <meta name="twitter:card" content="summary_large_image"/>
    <title>Diário Oficial - Sistema de Publicação Eletrônica</title>
    <meta name="description" content="Sistema de Publicação - Diário Eletrônico"/>
    <meta name="robots" content="index, follow"/>
    <meta property="og:title" content="Diário Oficial - Sistema de Publicação Eletrônica"/>
    <meta property="og:description" content="Sistema de Publicação - Diário Eletrônico"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- PDF.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    </script>
    
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
        
        .pdf-preview-container {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }
        
        .pdf-preview-container:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
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
        
        .calendar-day {
            transition: all 0.2s ease;
        }
        
        .calendar-day:hover {
            transform: scale(1.1);
        }
        
        .has-edition {
            position: relative;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.8;
            }
        }
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
                    <li class="border-l pl-4 border-blue-300/30 border-b-2 border-b-transparent py-2 px-4 hover:border-b-blue-200 dark:hover:border-b-slate-400 hover:text-blue-200 dark:hover:text-slate-300 transition-colors cursor-pointer">
                        <span class="uppercase text-sm">Matérias</span>
                    </li>
                </a>
                <a href="{{ route('portal.edicoes.index') }}">
                    <li class="border-l pl-4 border-blue-300/30 border-b-2 border-b-transparent py-2 px-4 hover:border-b-blue-200 dark:hover:border-b-slate-400 hover:text-blue-200 dark:hover:text-slate-300 transition-colors cursor-pointer">
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

<main class="main_content">
    <div class="container mx-auto px-6 py-6">
        <div class="flex flex-col lg:flex-row gap-6">
            
            <!-- Main Content Area -->
            <main class="w-full">
                <!-- Edição Mais Recente -->
                @if($edicoesRecentes && $edicoesRecentes->count() > 0)
                @php $edicaoRecente = $edicoesRecentes->first() @endphp
                <div class="py-6 rounded-t-lg">
                    <div class="info-head dark:bg-gradient-to-br from-slate-900 to-slate-700 dark:text-stone-400">
                        <div class="m-0 w-full px-4 py-3.5 rounded-t-lg dark:rounded-none dark:text-slate-300 flex items-center justify-center gap-2">
                            <span class="font-bold text-gray-600 dark:text-stone-400 text-[18px]">
                                {{ $edicaoRecente->numero }}ª Edição de {{ $edicaoRecente->data->locale('pt_BR')->isoFormat('dddd, DD [de] MMMM [de] YYYY') }}
                            </span>
                        </div>
                        <div class="rounded-lg dark:rounded-none px-2.5 -mt-2 gap-3 flex items-center justify-center dark:py-1">
                            <div class="flex items-center">
                                <div class="text-sm font-medium text-gray-600 dark:text-slate-500 flex items-center">
                                    <div class="rounded-full px-2 py-1 text-sm font-semibold bg-emerald-200/40 dark:bg-white/20 text-emerald-700 dark:text-emerald-400/90 inline-flex items-center mr-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" class="main-grid-item-icon text-gray-400 mr-2" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                        {{ str_pad($edicaoRecente->visualizacoes_count ?? 22, 3, '0', STR_PAD_LEFT) }}
                                    </div>
                                    Visualizações
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-4">
                                <div class="text-sm font-medium text-gray-600 dark:text-slate-500 flex items-center">
                                    <div class="rounded-full px-2 py-1 text-sm font-semibold bg-emerald-200/40 dark:bg-white/20 text-emerald-700 dark:text-emerald-400/90 inline-flex items-center mr-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" class="main-grid-item-icon text-gray-400 mr-2" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                            <circle cx="12" cy="12" r="10" />
                                            <polyline points="8 12 12 16 16 12" />
                                            <line x1="12" x2="12" y1="8" y2="16" />
                                        </svg>
                                        {{ str_pad($edicaoRecente->downloads_count ?? 10, 3, '0', STR_PAD_LEFT) }}
                                    </div>
                                    Downloads
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-700 pb-6">
                        <div class="px-4">
                            <p class="text-center text-gray-500 pb-4 text-sm pt-4 dark:text-gray-400">Visualização da Edição Mais Recente</p>
                            
                            @if($edicaoRecente->caminho_arquivo)
                                <!-- PDF Viewer Completo -->
                                <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-lg overflow-hidden shadow-lg">
                                    <div class="bg-gray-800 text-white px-4 py-2 flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-file-pdf text-red-400"></i>
                                            <span class="font-medium">Edição {{ $edicaoRecente->numero }} - {{ $edicaoRecente->data->format('d/m/Y') }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <button onclick="zoomOut()" class="px-2 py-1 bg-gray-600 hover:bg-gray-500 rounded text-sm">
                                                <i class="fas fa-search-minus"></i>
                                            </button>
                                            <span id="zoom-level" class="text-sm">100%</span>
                                            <button onclick="zoomIn()" class="px-2 py-1 bg-gray-600 hover:bg-gray-500 rounded text-sm">
                                                <i class="fas fa-search-plus"></i>
                                            </button>
                                            <a href="{{ Storage::url($edicaoRecente->caminho_arquivo) }}" target="_blank" 
                                               class="px-3 py-1 bg-red-600 hover:bg-red-500 rounded text-sm">
                                                <i class="fas fa-external-link-alt mr-1"></i>
                                                Abrir
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <!-- PDF Canvas Container -->
                                    <div class="relative bg-gray-200 dark:bg-gray-700" style="height: 600px; overflow: auto;" id="pdf-container">
                                        <div class="flex justify-center items-center h-full" id="loading-indicator">
                                            <div class="text-center">
                                                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                                                <p class="text-gray-600 dark:text-gray-400">Carregando PDF...</p>
                                            </div>
                                        </div>
                                        <canvas id="pdf-canvas" class="mx-auto hidden"></canvas>
                                    </div>
                                    
                                    <!-- Controles de Navegação -->
                                    <div class="bg-gray-100 dark:bg-gray-800 px-4 py-2 flex items-center justify-between border-t">
                                        <div class="flex items-center gap-2">
                                            <button onclick="prevPage()" id="prev-page" class="px-3 py-1 bg-blue-600 hover:bg-blue-500 text-white rounded text-sm">
                                                <i class="fas fa-chevron-left mr-1"></i>
                                                Anterior
                                            </button>
                                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                                Página <span id="page-num">1</span> de <span id="page-count">-</span>
                                            </span>
                                            <button onclick="nextPage()" id="next-page" class="px-3 py-1 bg-blue-600 hover:bg-blue-500 text-white rounded text-sm">
                                                Próxima
                                                <i class="fas fa-chevron-right ml-1"></i>
                                            </button>
                                        </div>
                                        <a href="{{ route('portal.edicoes.show', $edicaoRecente) }}" 
                                           class="px-4 py-2 bg-green-600 hover:bg-green-500 text-white rounded text-sm font-medium">
                                            <i class="fas fa-eye mr-1"></i>
                                            Ver Detalhes
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="text-center">
                                    <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-12 mx-auto max-w-md">
                                        <i class="fas fa-file-pdf text-6xl text-red-300 dark:text-red-700 mb-4"></i>
                                        <p class="text-gray-500 dark:text-gray-400">PDF em preparação</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-8 text-center">
                    <i class="fas fa-newspaper text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">Nenhuma edição encontrada</h3>
                    <p class="text-gray-500 dark:text-gray-400">Ainda não há edições publicadas no sistema.</p>
                </div>
                @endif
            </main>
            
            <!-- Sidebar -->
            <aside class="w-full lg:w-96">
                <div class="sticky top-6 space-y-4">
                    
                    <!-- Calendar -->
                    <div class="bg-white dark:bg-slate-600 rounded-lg shadow-lg overflow-hidden">
                        <div class="bg-[#16649D] dark:bg-slate-600 px-4 py-3 flex items-center gap-2">
                            <i class="fas fa-calendar text-white bg-white/20 p-2 rounded"></i>
                            <span class="font-semibold text-white dark:text-slate-300">Calendário de Publicações</span>
                        </div>
                        <div class="p-4">
                            @php
                                $calendarDate = isset($dataCalendario) ? $dataCalendario : \Carbon\Carbon::now();
                                $startOfMonth = $calendarDate->copy()->startOfMonth();
                                $endOfMonth = $calendarDate->copy()->endOfMonth();
                                $startCalendar = $startOfMonth->copy()->startOfWeek();
                                $endCalendar = $endOfMonth->copy()->endOfWeek();
                                
                                // Criar array com datas das edições para este mês
                                $diasComEdicoes = [];
                                if(isset($edicoesCalendario)) {
                                    foreach($edicoesCalendario as $data => $edicoes) {
                                        $dataCarbon = \Carbon\Carbon::parse($data);
                                        if($dataCarbon->isSameMonth($calendarDate)) {
                                            $diasComEdicoes[$dataCarbon->day] = $edicoes->count();
                                        }
                                    }
                                }
                                
                                $mesAnterior = $calendarDate->copy()->subMonth();
                                $proximoMes = $calendarDate->copy()->addMonth();
                            @endphp
                            
                            <!-- Navegação do mês -->
                            <div class="flex items-center justify-between mb-4">
                                <a href="{{ route('home', ['mes' => $mesAnterior->month, 'ano' => $mesAnterior->year]) }}" 
                                   class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                                    <i class="fas fa-chevron-left text-gray-600 dark:text-gray-400"></i>
                                </a>
                                
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 text-center">
                                    {{ $calendarDate->locale('pt_BR')->isoFormat('MMMM [de] YYYY') }}
                                </h4>
                                
                                <a href="{{ route('home', ['mes' => $proximoMes->month, 'ano' => $proximoMes->year]) }}" 
                                   class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                                    <i class="fas fa-chevron-right text-gray-600 dark:text-gray-400"></i>
                                </a>
                            </div>
                            
                            <!-- Cabeçalho dos dias da semana -->
                            <div class="grid grid-cols-7 gap-1 mb-2">
                                @foreach(['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'] as $dia)
                                <div class="text-center text-xs font-medium text-gray-500 dark:text-gray-400 py-2">
                                    {{ $dia }}
                                </div>
                                @endforeach
                            </div>
                            
                            <!-- Dias do calendário -->
                            <div class="grid grid-cols-7 gap-1">
                                @php
                                    $currentDay = $startCalendar->copy();
                                @endphp
                                
                                @while($currentDay->lte($endCalendar))
                                    @php
                                        $isCurrentMonth = $currentDay->isSameMonth($calendarDate);
                                        $isToday = $currentDay->isToday();
                                        $hasEdicao = $isCurrentMonth && isset($diasComEdicoes[$currentDay->day]);
                                        $numEdicoes = $hasEdicao ? $diasComEdicoes[$currentDay->day] : 0;
                                    @endphp
                                    
                                    <div class="relative calendar-day group">
                                        <div class="aspect-square flex items-center justify-center text-sm font-medium rounded-lg transition-all duration-200
                                            @if($isToday)
                                                bg-blue-600 text-white shadow-lg
                                            @elseif($hasEdicao)
                                                bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-700 hover:bg-green-200 dark:hover:bg-green-900/50 cursor-pointer has-edition
                                            @elseif($isCurrentMonth)
                                                text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 cursor-pointer
                                            @else
                                                text-gray-400 dark:text-gray-600
                                            @endif
                                        ">
                                            {{ $currentDay->day }}
                                        </div>
                                        
                                        @if($hasEdicao)
                                            <div class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold shadow-lg z-10">
                                                {{ $numEdicoes }}
                                            </div>
                                            
                                            <!-- Tooltip -->
                                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-20">
                                                <div class="bg-gray-800 text-white text-xs rounded-lg py-2 px-3 whitespace-nowrap shadow-lg">
                                                    {{ $numEdicoes }} edição(ões) em {{ $currentDay->format('d/m') }}
                                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-800"></div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    @php
                                        $currentDay->addDay();
                                    @endphp
                                @endwhile
                            </div>
                            
                            <!-- Legenda -->
                            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                                <div class="flex items-center justify-center gap-4 text-xs">
                                    <div class="flex items-center gap-1">
                                        <div class="w-3 h-3 bg-blue-600 rounded-full"></div>
                                        <span class="text-gray-600 dark:text-gray-400">Hoje</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <div class="w-3 h-3 bg-green-100 border border-green-200 rounded-full"></div>
                                        <span class="text-gray-600 dark:text-gray-400">Com publicação</span>
                                    </div>
                                </div>
                            </div>
                            
                            @if(isset($edicoesCalendario) && $edicoesCalendario->count() > 0)
                            <!-- Últimas publicações -->
                            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                                <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Publicações Recentes:</h5>
                                <div class="space-y-1">
                                    @foreach($edicoesCalendario->take(3) as $data => $edicoes)
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-gray-600 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($data)->format('d/m') }}
                                        </span>
                                        <span class="text-green-600 dark:text-green-400 font-medium">
                                            {{ $edicoes->count() }} edição(ões)
                                        </span>
                                    </div>
                                    @endforeach
                                </div>
                                
                                <div class="text-center mt-3">
                                    <a href="{{ route('portal.edicoes.index') }}" class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                        Ver todas as edições →
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Authentication Checker -->
                    <div class="bg-white dark:bg-slate-600 rounded-lg shadow-lg overflow-hidden">
                        <div class="bg-[#16649D] dark:bg-slate-600 px-4 py-3 flex items-center gap-2">
                            <i class="fas fa-shield-check text-white bg-white/20 p-2 rounded"></i>
                            <span class="font-semibold text-white dark:text-slate-300">Verificar Autenticidade</span>
                        </div>
                        <div class="p-4 bg-blue-50 dark:bg-slate-700/90">
                            <form action="{{ route('portal.verificar') }}" method="post">
                                @csrf
                                <div class="flex">
                                    <input name="codigo" 
                                           type="text" 
                                           class="flex-1 px-4 py-2 text-gray-900 border-2 border-white/30 rounded-l-lg bg-gray-50 focus:outline-0 dark:bg-slate-900 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                                           placeholder="Código da Edição">
                                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-r-lg transition-colors">
                                        Verificar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if(isset($edicaoRecente) && $edicaoRecente && $edicaoRecente->assinatura)
                    <!-- Digital Signature -->
                    <div class="bg-white dark:bg-slate-600 rounded-lg shadow-lg p-4">
                        <div class="text-center mb-4">
                            <div class="inline-flex items-center bg-green-100 dark:bg-green-900/30 px-3 py-1 rounded-full">
                                <i class="fas fa-certificate text-green-600 dark:text-green-400 mr-2"></i>
                                <span class="text-sm font-medium text-green-700 dark:text-green-300">Assinatura Digital Válida</span>
                            </div>
                        </div>
                        <div class="bg-slate-100 dark:bg-slate-800 p-4 rounded-lg space-y-3">
                            <div>
                                <div class="text-sm text-gray-500 dark:text-slate-400">Signatário</div>
                                <div class="text-sm font-medium text-emerald-600 dark:text-emerald-400">
                                    {{ $edicaoRecente->assinatura->signatario }}
                                </div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 dark:text-slate-400">Autoridade Certificadora</div>
                                <div class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                    {{ $edicaoRecente->assinatura->ac }}
                                </div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 dark:text-slate-400">Algoritmo</div>
                                <div class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ $edicaoRecente->assinatura->algoritmo }}
                                </div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 dark:text-slate-400">Carimbo do tempo</div>
                                <div class="flex items-center text-sm font-medium text-emerald-600 dark:text-emerald-400">
                                    <i class="fas fa-clock mr-2"></i>
                                    <span>{{ $edicaoRecente->assinatura->carimbo_tempo->format('d/m/Y H:i:s') }}</span>
                                    <img class="ml-3" width="40" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBmaWxsPSIjMEY5Qjc2Ii8+CjwvZXZnPgo=" alt="ICP">
                                </div>
                            </div>
                            <div class="lg:hidden">
                                <div class="text-sm text-gray-500 dark:text-slate-400">Hash SHA-256</div>
                                <p class="text-xs text-amber-600 dark:text-amber-400 font-mono break-all">
                                    {{ $edicaoRecente->assinatura->hash }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @else
                    <!-- Placeholder quando não há assinatura -->
                    <div class="bg-white dark:bg-slate-600 rounded-lg shadow-lg p-4">
                        <div class="text-center mb-4">
                            <div class="inline-flex items-center bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full">
                                <i class="fas fa-certificate text-gray-400 mr-2"></i>
                                <span class="text-sm font-medium text-gray-500 dark:text-slate-400">Assinatura Digital</span>
                            </div>
                        </div>
                        <div class="bg-slate-100 dark:bg-slate-800 p-4 rounded-lg text-center">
                            <i class="fas fa-file-signature text-3xl text-gray-400 dark:text-gray-500 mb-3"></i>
                            <p class="text-sm text-gray-500 dark:text-slate-400">Aguardando assinatura digital</p>
                        </div>
                    </div>
                    @endif
                </div>
            </aside>
        </div>
    </div>
</main>

<!-- Document Types Navigation -->
<div class="bg-white dark:bg-slate-700 border-t border-gray-200 dark:border-slate-600 sticky top-0 z-40">
    <div class="container mx-auto px-6">
        <div class="flex items-center py-4 border-b-4 border-[#17639D] overflow-x-auto scrollbar-hide">
            <div class="flex space-x-6 min-w-max">
                <a href="{{ route('portal.documentos.portarias') }}" 
                   class="flex items-center gap-2 px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-[#17639D] dark:hover:text-blue-400 transition-all duration-300 group whitespace-nowrap rounded-lg hover:bg-gray-50 dark:hover:bg-slate-600">
                    <i class="fas fa-file-alt text-amber-500 group-hover:scale-110 transition-transform duration-200"></i>
                    <span class="font-medium">Portarias</span>
                </a>
                <a href="{{ route('portal.documentos.decretos') }}" 
                   class="flex items-center gap-2 px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-[#17639D] dark:hover:text-blue-400 transition-all duration-300 group whitespace-nowrap rounded-lg hover:bg-gray-50 dark:hover:bg-slate-600">
                    <i class="fas fa-file-alt text-blue-500 group-hover:scale-110 transition-transform duration-200"></i>
                    <span class="font-medium">Decretos</span>
                </a>
                <a href="{{ route('portal.documentos.leis') }}" 
                   class="flex items-center gap-2 px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-[#17639D] dark:hover:text-blue-400 transition-all duration-300 group whitespace-nowrap rounded-lg hover:bg-gray-50 dark:hover:bg-slate-600">
                    <i class="fas fa-file-alt text-green-500 group-hover:scale-110 transition-transform duration-200"></i>
                    <span class="font-medium">Leis</span>
                </a>
                <a href="{{ route('portal.documentos.resolucoes') }}" 
                   class="flex items-center gap-2 px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-[#17639D] dark:hover:text-blue-400 transition-all duration-300 group whitespace-nowrap rounded-lg hover:bg-gray-50 dark:hover:bg-slate-600">
                    <i class="fas fa-file-alt text-purple-500 group-hover:scale-110 transition-transform duration-200"></i>
                    <span class="font-medium">Resoluções</span>
                </a>
                <a href="{{ route('portal.documentos.editais') }}" 
                   class="flex items-center gap-2 px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-[#17639D] dark:hover:text-blue-400 transition-all duration-300 group whitespace-nowrap rounded-lg hover:bg-gray-50 dark:hover:bg-slate-600">
                    <i class="fas fa-bullhorn text-red-500 group-hover:scale-110 transition-transform duration-200"></i>
                    <span class="font-medium">Editais</span>
                </a>
                <a href="{{ route('portal.materias.index') }}" 
                   class="flex items-center gap-2 px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-[#17639D] dark:hover:text-blue-400 transition-all duration-300 group whitespace-nowrap rounded-lg hover:bg-gray-50 dark:hover:bg-slate-600 border-l-2 border-gray-200 dark:border-slate-600 ml-2 pl-6">
                    <i class="fas fa-folder text-indigo-500 group-hover:scale-110 transition-transform duration-200"></i>
                    <span class="font-medium">Todos os Documentos</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Editions -->
<div class="bg-gray-50 dark:bg-slate-800 py-8">
    <div class="container mx-auto px-6">
        <div class="bg-[#16649D] dark:bg-slate-600 px-4 py-3 flex items-center gap-2 rounded-t-lg">
            <i class="fas fa-database text-gray-200 bg-white/20 p-2 rounded"></i>
            <span class="font-semibold text-white dark:text-slate-300">Diário Oficial</span>
            <span class="text-sm text-blue-200 pl-3 border-l-4 ml-3 border-emerald-200 dark:text-emerald-400">Últimas Edições</span>
        </div>
        
        <div class="space-y-2">
            @foreach($edicoesRecentes as $edicao)
            <div class="p-4 bg-white dark:bg-slate-700 shadow-sm rounded-md wl_shadow_3 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
                <div class="flex gap-3 items-center flex-1">
                    <i class="fas fa-newspaper text-2xl text-gray-600 dark:text-gray-400"></i>
                    <div class="flex-1">
                        <div class="flex gap-2 items-center flex-wrap">
                            <h5 class="text-sm font-semibold text-gray-700 dark:text-slate-400">
                                Edição Nº {{ $edicao->numero }} de {{ $edicao->data->format('l, d \de F \de Y') }}
                                @if($edicao->extra)
                                    <span class="rounded px-2 py-1 uppercase bg-indigo-200 text-gray-500 inline-flex items-center text-xs ml-2">
                                        Extra
                                    </span>
                                @endif
                            </h5>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-2 flex-wrap">
                    <div class="flex items-center gap-1 px-2 py-1 text-xs font-medium">
                        <i class="fas fa-eye text-gray-500"></i>
                        <span class="text-gray-500">{{ $edicao->visualizacoes_count ?? 0 }}</span>
                    </div>
                    
                    <a href="{{ route('portal.edicoes.materias', $edicao) }}">
                        <button class="bg-emerald-700 dark:bg-slate-800 hover:bg-emerald-600 dark:hover:bg-slate-700 rounded px-3 py-2 text-white shadow text-xs font-medium flex items-center gap-2 transition-colors">
                            <i class="fas fa-file-alt"></i>
                            <span>Matérias</span>
                        </button>
                    </a>
                    
                    <a href="{{ route('portal.edicoes.show', $edicao) }}">
                        <button class="bg-[#17639D] dark:bg-slate-800 hover:bg-blue-600 dark:hover:bg-slate-700 rounded px-3 py-2 text-white shadow text-xs font-medium flex items-center gap-2 transition-colors">
                            <span>Visualizar</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="bg-white dark:bg-slate-700 py-12">
    <div class="container mx-auto px-6">
        <div class="bg-[#17639C] dark:bg-slate-800 text-center py-3 px-4 text-white uppercase font-semibold tracking-wide rounded-t-lg">
            Diário Oficial da Prefeitura Municipal
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 pt-4">
            <div class="bg-white dark:bg-slate-600 p-6 shadow-lg text-center rounded-lg">
                <i class="fas fa-leaf text-4xl text-[#17639C] dark:text-emerald-400 bg-emerald-100 dark:bg-white/10 p-3 rounded-full mb-4"></i>
                <p class="font-semibold text-lg text-slate-600 dark:text-slate-300 mb-2">Sustentabilidade</p>
                <p class="text-gray-700 dark:text-slate-400 text-sm">Trabalhos e processos sem o uso de papel.</p>
            </div>
            <div class="bg-white dark:bg-slate-600 p-6 shadow-lg text-center rounded-lg">
                <i class="fas fa-dollar-sign text-4xl text-[#17639C] dark:text-emerald-400 bg-emerald-100 dark:bg-white/10 p-3 rounded-full mb-4"></i>
                <p class="font-semibold text-lg text-slate-600 dark:text-slate-300 mb-2">Economia</p>
                <p class="text-gray-700 dark:text-slate-400 text-sm">Da criação à assinatura, todos os documentos são feitos digitalmente.</p>
            </div>
            <div class="bg-white dark:bg-slate-600 p-6 shadow-lg text-center rounded-lg">
                <i class="fas fa-eye text-4xl text-[#17639C] dark:text-emerald-400 bg-emerald-100 dark:bg-white/10 p-3 rounded-full mb-4"></i>
                <p class="font-semibold text-lg text-slate-600 dark:text-slate-300 mb-2">Publicidade</p>
                <p class="text-gray-700 dark:text-slate-400 text-sm">Atende o princípio da publicidade com maior transparência.</p>
            </div>
            <div class="bg-white dark:bg-slate-600 p-6 shadow-lg text-center rounded-lg">
                <i class="fas fa-shield-alt text-4xl text-[#17639C] dark:text-emerald-400 bg-emerald-100 dark:bg-white/10 p-3 rounded-full mb-4"></i>
                <p class="font-semibold text-lg text-slate-600 dark:text-slate-300 mb-2">Segurança</p>
                <p class="text-gray-700 dark:text-slate-400 text-sm">Válido, seguro, confiável e certificado.</p>
            </div>
        </div>
    </div>
</div>

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

    // PDF Viewer Variables
    let pdfDoc = null;
    let pageNum = 1;
    let pageRendering = false;
    let pageNumPending = null;
    let scale = 1.0;
    const canvas = document.getElementById('pdf-canvas');
    const ctx = canvas ? canvas.getContext('2d') : null;

    // Load PDF when page loads
    if (canvas && ctx) {
        const pdfUrl = '{{ isset($edicaoRecente) && $edicaoRecente->caminho_arquivo ? Storage::url($edicaoRecente->caminho_arquivo) : "" }}';
        
        if (pdfUrl) {
            pdfjsLib.getDocument(pdfUrl).promise.then(function(pdfDoc_) {
                pdfDoc = pdfDoc_;
                document.getElementById('page-count').textContent = pdfDoc.numPages;
                
                // Hide loading indicator and show canvas
                document.getElementById('loading-indicator').classList.add('hidden');
                canvas.classList.remove('hidden');
                
                // Render the first page
                renderPage(pageNum);
            }).catch(function(error) {
                console.error('Erro ao carregar PDF:', error);
                document.getElementById('loading-indicator').innerHTML = `
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle text-red-500 text-4xl mb-2"></i>
                        <p class="text-gray-600 dark:text-gray-400">Erro ao carregar PDF</p>
                    </div>
                `;
            });
        }
    }

    function renderPage(num) {
        if (!pdfDoc) return;
        
        pageRendering = true;
        
        // Using promise to fetch the page
        pdfDoc.getPage(num).then(function(page) {
            const viewport = page.getViewport({scale: scale});
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            // Render PDF page into canvas context
            const renderContext = {
                canvasContext: ctx,
                viewport: viewport
            };
            
            const renderTask = page.render(renderContext);

            // Wait for rendering to finish
            renderTask.promise.then(function() {
                pageRendering = false;
                if (pageNumPending !== null) {
                    // New page rendering is pending
                    renderPage(pageNumPending);
                    pageNumPending = null;
                }
            });
        });

        // Update page counters
        document.getElementById('page-num').textContent = num;
    }

    function queueRenderPage(num) {
        if (pageRendering) {
            pageNumPending = num;
        } else {
            renderPage(num);
        }
    }

    function prevPage() {
        if (pageNum <= 1) {
            return;
        }
        pageNum--;
        queueRenderPage(pageNum);
    }

    function nextPage() {
        if (pageNum >= pdfDoc.numPages) {
            return;
        }
        pageNum++;
        queueRenderPage(pageNum);
    }

    function zoomIn() {
        scale += 0.25;
        document.getElementById('zoom-level').textContent = Math.round(scale * 100) + '%';
        queueRenderPage(pageNum);
    }

    function zoomOut() {
        if (scale <= 0.5) return;
        scale -= 0.25;
        document.getElementById('zoom-level').textContent = Math.round(scale * 100) + '%';
        queueRenderPage(pageNum);
    }
</script>

</body>
</html>
