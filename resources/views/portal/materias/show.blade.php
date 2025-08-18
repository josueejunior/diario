@extends('layouts.portal-standalone')

@section('title', $materia->titulo . ' - Diário Oficial')
@section('description', Str::limit($materia->titulo, 160))

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
            <a href="{{ route('portal.materias.index') }}" class="hover:text-[#17639D] dark:hover:text-blue-400 transition-colors">
                Matérias
            </a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-gray-700 dark:text-gray-300">{{ Str::limit($materia->titulo, 50) }}</span>
        </div>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Content -->
        <div class="lg:col-span-2">
            
            <!-- Document Header -->
            <div class="bg-white dark:bg-slate-700 rounded-lg shadow-lg overflow-hidden mb-6 wl_shadow_3">
                <div class="p-6">
                    
                    <!-- Document Type and Date -->
                    <div class="flex items-center gap-4 mb-4">
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
                        
                        <div class="flex items-center gap-3 {{ $typeStyle['bg'] }} px-4 py-2 rounded-full">
                            <i class="fas fa-file-alt {{ $typeStyle['icon'] }} text-lg"></i>
                            <span class="font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                {{ $materia->tipo->nome ?? 'Documento' }}
                            </span>
                        </div>
                        
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            <i class="fas fa-calendar mr-1"></i>
                            {{ $materia->data->format('d \de F \de Y') }}
                        </div>
                    </div>

                    <!-- Title -->
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-4 leading-tight">
                        {{ $materia->titulo }}
                    </h1>

                    <!-- Summary -->
                    <div class="bg-gray-50 dark:bg-slate-800 rounded-lg p-4 mb-4">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">
                            Conteúdo Resumido
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                            {{ Str::limit(strip_tags($materia->texto), 300) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Document Content -->
            <div class="bg-white dark:bg-slate-700 rounded-lg shadow-lg overflow-hidden wl_shadow_3">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-file-text text-[#17639D] dark:text-blue-400"></i>
                        Conteúdo do Documento
                    </h2>
                    
                    <div class="prose prose-gray dark:prose-invert max-w-none">
                        {!! $materia->texto !!}
                    </div>
                </div>
            </div>

        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="sticky top-6 space-y-6">

                <!-- Document Information -->
                <div class="bg-white dark:bg-slate-700 rounded-lg shadow-lg overflow-hidden wl_shadow_3">
                    <div class="bg-[#17639D] dark:bg-slate-600 px-4 py-3">
                        <h3 class="font-semibold text-white flex items-center gap-2">
                            <i class="fas fa-info-circle"></i>
                            Informações do Documento
                        </h3>
                    </div>
                    <div class="p-4 space-y-4">
                        
                        @if($materia->orgao)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Órgão</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-building text-gray-400"></i>
                                    {{ $materia->orgao->nome }}
                                </div>
                            </dd>
                        </div>
                        @endif

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Data de Publicação</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-calendar-alt text-gray-400"></i>
                                    {{ $materia->data->format('d/m/Y') }}
                                </div>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipo</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-tag text-gray-400"></i>
                                    {{ $materia->tipo->nome ?? 'Não especificado' }}
                                </div>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Visualizações</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-eye text-gray-400"></i>
                                    {{ $materia->visualizacoes_count ?? 0 }}
                                </div>
                            </dd>
                        </div>
                        
                    </div>
                </div>

                <!-- Edition Information -->
                @if($materia->edicoes->first())
                @php $edicao = $materia->edicoes->first(); @endphp
                <div class="bg-white dark:bg-slate-700 rounded-lg shadow-lg overflow-hidden wl_shadow_3">
                    <div class="bg-emerald-600 dark:bg-emerald-700 px-4 py-3">
                        <h3 class="font-semibold text-white flex items-center gap-2">
                            <i class="fas fa-newspaper"></i>
                            Edição do Diário
                        </h3>
                    </div>
                    <div class="p-4">
                        <div class="text-center">
                            <div class="text-lg font-semibold text-gray-800 dark:text-white">
                                Edição {{ $edicao->numero }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                {{ $edicao->data->format('d/m/Y') }}
                            </div>
                            <a href="{{ route('portal.edicoes.show', $edicao) }}" 
                               class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                <i class="fas fa-external-link-alt"></i>
                                Ver Edição Completa
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Sharing Actions -->
                <div class="bg-white dark:bg-slate-700 rounded-lg shadow-lg overflow-hidden wl_shadow_3">
                    <div class="bg-gray-100 dark:bg-slate-600 px-4 py-3">
                        <h3 class="font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                            <i class="fas fa-share-alt"></i>
                            Compartilhar
                        </h3>
                    </div>
                    <div class="p-4">
                        <div class="flex gap-2">
                            <button onclick="copyToClipboard('{{ url()->current() }}')" 
                                    class="flex-1 bg-gray-100 hover:bg-gray-200 dark:bg-slate-600 dark:hover:bg-slate-500 text-gray-700 dark:text-gray-300 px-3 py-2 rounded text-sm font-medium transition-colors flex items-center justify-center gap-2">
                                <i class="fas fa-link"></i>
                                Copiar Link
                            </button>
                            <button onclick="printDocument()" 
                                    class="flex-1 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 text-blue-700 dark:text-blue-300 px-3 py-2 rounded text-sm font-medium transition-colors flex items-center justify-center gap-2">
                                <i class="fas fa-print"></i>
                                Imprimir
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Link copiado para a área de transferência!');
    }).catch(function(err) {
        console.error('Erro ao copiar: ', err);
    });
}

function printDocument() {
    window.print();
}
</script>
@endpush