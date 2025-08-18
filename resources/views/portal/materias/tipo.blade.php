@extends('layouts.portal-standalone')

@section('title', $titulo . ' - Diário Oficial')
@section('description', 'Navegue por todos os documentos do tipo ' . $tipo . ' publicados no Diário Oficial da Prefeitura Municipal')

@section('content')
<div class="container mx-auto px-6 py-6">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-4">
            @php
                $typeIcons = [
                    'Portaria' => ['ieon' => 'fas fa-file-alt', 'color' => 'text-amber-500'],
                    'Decreto' => ['icon' => 'fas fa-file-alt', 'color' => 'text-blue-500'],
                    'Lei' => ['icon' => 'fas fa-file-alt', 'color' => 'text-green-500'],
                    'Resolução' => ['icon' => 'fas fa-file-alt', 'color' => 'text-purple-500'],
                    'Edital' => ['icon' => 'fas fa-bullhorn', 'color' => 'text-red-500']
                ];
                $iconData = $typeIcons[$tipo] ?? ['icon' => 'fas fa-file-alt', 'color' => 'text-gray-500'];
            @endphp
            <i class="{{ $iconData['icon'] }} text-2xl {{ $iconData['color'] }}"></i>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $titulo }}</h1>
        </div>
        <p class="text-gray-600 dark:text-gray-300">
            Navegue por todos os documentos do tipo {{ $tipo }} publicados no Diário Oficial
        </p>
    </div>

    <!-- Search Section -->
    <div class="bg-white dark:bg-slate-700 rounded-lg shadow-lg p-6 mb-6 wl_shadow_3">
        <form method="GET" action="{{ url()->current() }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search Input -->
            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Buscar em {{ $titulo }}
                </label>
                <div class="relative">
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Digite palavras-chave para buscar..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:text-white dark:placeholder-gray-400">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-end">
                <button type="submit" 
                        class="w-full bg-[#17639D] hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-search"></i>
                    Buscar
                </button>
            </div>
        </form>
    </div>

    <!-- Results Count -->
    <div class="mb-6">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            @if($materias->total() > 0)
                Encontrados {{ $materias->total() }} documentos do tipo {{ $tipo }}
                @if(request('search'))
                    para "<strong>{{ request('search') }}</strong>"
                @endif
            @else
                Nenhum documento encontrado
                @if(request('search'))
                    para "<strong>{{ request('search') }}</strong>"
                @endif
            @endif
        </p>
    </div>

    <!-- Materials List -->
    <div class="space-y-4">
        @forelse($materias as $materia)
        <div class="bg-white dark:bg-slate-700 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow wl_shadow_3">
            <div class="p-6">
                <div class="flex items-start justify-between gap-4">
                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <!-- Type Badge and Date -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">
                            <div class="flex items-center gap-3">
                                @php
                                    $typeColors = [
                                        'portaria' => ['icon' => 'text-amber-500', 'bg' => 'bg-amber-100 dark:bg-amber-900/30'],
                                        'decreto' => ['icon' => 'text-blue-500', 'bg' => 'bg-blue-100 dark:bg-blue-900/30'],
                                        'lei' => ['icon' => 'text-green-500', 'bg' => 'bg-green-100 dark:bg-green-900/30'],
                                        'resolucao' => ['icon' => 'text-purple-500', 'bg' => 'bg-purple-100 dark:bg-purple-900/30'],
                                        'edital' => ['icon' => 'text-red-500', 'bg' => 'bg-red-100 dark:bg-red-900/30']
                                    ];
                                    $typeStyle = $typeColors[$materia->tipo->slug ?? 'portaria'] ?? $typeColors['portaria'];
                                @endphp
                                
                                <div class="flex items-center gap-2 {{ $typeStyle['bg'] }} px-3 py-1 rounded-full">
                                    <i class="fas fa-file-alt {{ $typeStyle['icon'] }} text-sm"></i>
                                    <span class="font-medium text-gray-700 dark:text-gray-300 text-sm uppercase tracking-wide">
                                        {{ $materia->tipo->nome ?? 'Documento' }}
                                    </span>
                                </div>
                                
                                @if($materia->numero)
                                <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                                    Nº {{ $materia->numero }}
                                </span>
                                @endif
                            </div>
                            
                            <div class="flex-shrink-0 text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ $materia->data->format('d/m/Y') }}
                            </div>
                        </div>

                        <!-- Title -->
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white leading-tight mb-3">
                            <a href="{{ route('portal.materias.show', $materia) }}" 
                               class="hover:text-[#17639D] dark:hover:text-blue-400 transition-colors">
                                {{ $materia->titulo }}
                            </a>
                        </h3>

                        <!-- Summary -->
                        <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed mb-4">
                            {{ Str::limit(strip_tags($materia->texto), 200) }}
                        </p>

                        <!-- Meta Info -->
                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                            @if($materia->orgao)
                            <span class="flex items-center gap-1">
                                <i class="fas fa-building text-xs"></i>
                                {{ $materia->orgao->nome }}
                            </span>
                            @endif
                            
                            <span class="flex items-center gap-1">
                                <i class="fas fa-eye text-xs"></i>
                                {{ $materia->visualizacoes_count ?? 0 }} visualizações
                            </span>
                            
                            @if($materia->edicoes->first())
                            <span class="flex items-center gap-1">
                                <i class="fas fa-newspaper text-xs"></i>
                                Edição {{ $materia->edicoes->first()->numero }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="flex-shrink-0">
                        <a href="{{ route('portal.materias.show', $materia) }}" 
                           class="inline-flex items-center gap-2 bg-[#17639D] hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-eye"></i>
                            Ver {{ $tipo }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-slate-700 rounded-lg shadow-lg p-12 text-center wl_shadow_3">
            <div class="text-gray-400 dark:text-gray-500 mb-4">
                <i class="{{ $iconData['icon'] }} text-4xl {{ $iconData['color'] }}"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-600 dark:text-gray-300 mb-2">
                @if(request('search'))
                    Nenhum documento encontrado
                @else
                    Nenhum {{ $tipo }} encontrado
                @endif
            </h3>
            <p class="text-gray-500 dark:text-gray-400 mb-4">
                @if(request('search'))
                    Não foram encontrados documentos do tipo {{ $tipo }} que contenham os termos buscados.
                @else
                    Ainda não há documentos do tipo {{ $tipo }} publicados.
                @endif
            </p>
            <a href="{{ route('portal.materias.index') }}" 
               class="inline-flex items-center gap-2 text-[#17639D] dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium">
                <i class="fas fa-arrow-left"></i>
                Ver todos os documentos
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($materias->hasPages())
    <div class="mt-8">
        <div class="bg-white dark:bg-slate-700 rounded-lg shadow-lg p-6 wl_shadow_3">
            {{ $materias->appends(request()->query())->links() }}
        </div>
    </div>
    @endif

</div>
@endsection
