@extends('layouts.portal-standalone')

@section('title', 'Matérias da Edição ' . $edicao->numero . ' - Diário Oficial')
@section('description', 'Visualize todas as matérias da edição ' . $edicao->numero . ' do Diário Oficial de ' . $edicao->data->format('d/m/Y'))

@section('content')
<div class="container mx-auto px-6 py-6">
    
    <!-- Breadcrumbs -->
    <nav class="mb-6">
        <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
            <a href="{{ route('home') }}" class="hover:text-[#17639D] dark:hover:text-blue-400 transition-colors">
                <i class="fas fa-home"></i>
                Início
            </a>
            <i class="fas fa-chevron-right text-xs"></i>
            <a href="{{ route('portal.edicoes.index') }}" class="hover:text-[#17639D] dark:hover:text-blue-400 transition-colors">
                Edições
            </a>
            <i class="fas fa-chevron-right text-xs"></i>
            <a href="{{ route('portal.edicoes.show', $edicao) }}" class="hover:text-[#17639D] dark:hover:text-blue-400 transition-colors">
                Edição {{ $edicao->numero }}
            </a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-gray-700 dark:text-gray-300">Matérias</span>
        </div>
    </nav>

    <!-- Edition Header -->
    <div class="bg-white dark:bg-slate-700 rounded-lg shadow-lg overflow-hidden mb-6 wl_shadow_3">
        <div class="bg-gradient-to-r from-[#17639D] to-blue-700 dark:from-slate-600 dark:to-slate-700 px-6 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between text-white">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Edição {{ $edicao->numero }}</h1>
                    <div class="flex items-center gap-4 text-blue-100">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-calendar-alt"></i>
                            {{ $edicao->data->format('d \de F \de Y') }}
                        </span>
                        <span class="flex items-center gap-2">
                            <i class="fas fa-file-alt"></i>
                            {{ $materias->total() }} matérias
                        </span>
                    </div>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('portal.edicoes.show', $edicao) }}" 
                       class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-arrow-left"></i>
                        Voltar à Edição
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Materias List -->
    <div class="space-y-4">
        @forelse($materias as $materia)
        <div class="bg-white dark:bg-slate-700 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 wl_shadow_3">
            <div class="p-6">
                <div class="flex flex-col lg:flex-row lg:items-start gap-4">
                    
                    <!-- Type Badge -->
                    <div class="flex-shrink-0">
                        @php
                            $typeColors = [
                                'portaria' => ['icon' => 'text-amber-500', 'bg' => 'bg-amber-100 dark:bg-amber-900/30', 'border' => 'border-amber-200 dark:border-amber-700'],
                                'decreto' => ['icon' => 'text-blue-500', 'bg' => 'bg-blue-100 dark:bg-blue-900/30', 'border' => 'border-blue-200 dark:border-blue-700'],
                                'lei' => ['icon' => 'text-green-500', 'bg' => 'bg-green-100 dark:bg-green-900/30', 'border' => 'border-green-200 dark:border-green-700'],
                                'resolucao' => ['icon' => 'text-purple-500', 'bg' => 'bg-purple-100 dark:bg-purple-900/30', 'border' => 'border-purple-200 dark:border-purple-700'],
                                'edital' => ['icon' => 'text-red-500', 'bg' => 'bg-red-100 dark:bg-red-900/30', 'border' => 'border-red-200 dark:border-red-700']
                            ];
                            $typeStyle = $typeColors[$materia->tipo->slug ?? 'portaria'] ?? $typeColors['portaria'];
                        @endphp
                        
                        <div class="flex items-center gap-2 {{ $typeStyle['bg'] }} {{ $typeStyle['border'] }} border px-3 py-1 rounded-full">
                            <i class="fas fa-file-alt {{ $typeStyle['icon'] }} text-sm"></i>
                            <span class="font-medium text-gray-700 dark:text-gray-300 text-sm uppercase tracking-wide">
                                {{ $materia->tipo->nome ?? 'Documento' }}
                            </span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        
                        <!-- Title and Date -->
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 mb-3">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white leading-tight">
                                <a href="{{ route('portal.materias.show', $materia) }}" 
                                   class="hover:text-[#17639D] dark:hover:text-blue-400 transition-colors">
                                    {{ $materia->titulo }}
                                </a>
                            </h3>
                            <div class="flex-shrink-0 text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ $materia->data->format('d/m/Y') }}
                            </div>
                        </div>

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
                        </div>

                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2">
                        <a href="{{ route('portal.materias.show', $materia) }}" 
                           class="inline-flex items-center gap-2 bg-[#17639D] hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-eye"></i>
                            Ver Matéria
                        </a>
                    </div>

                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-slate-700 rounded-lg shadow-lg p-12 text-center wl_shadow_3">
            <div class="text-gray-400 dark:text-gray-500 mb-4">
                <i class="fas fa-file-alt text-4xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-600 dark:text-gray-300 mb-2">
                Nenhuma matéria encontrada
            </h3>
            <p class="text-gray-500 dark:text-gray-400">
                Esta edição não possui matérias cadastradas.
            </p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($materias->hasPages())
    <div class="mt-8">
        <div class="bg-white dark:bg-slate-700 rounded-lg shadow-lg p-6 wl_shadow_3">
            {{ $materias->links() }}
        </div>
    </div>
    @endif

</div>
@endsection
