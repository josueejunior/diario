@extends('layouts.portal-standalone')

@section('title', 'Edição ' . $edicao->numero . ' - Diário Oficial')
@section('description', 'Visualize a edição ' . $edicao->numero . ' do Diário Oficial de ' . $edicao->data->format('d/m/Y'))

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
            <span class="text-gray-700 dark:text-gray-300">Edição {{ $edicao->numero }}</span>
        </div>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Content -->
        <div class="lg:col-span-2">
            
            <!-- Edition Header -->
            <div class="bg-white dark:bg-slate-700 rounded-lg shadow-lg overflow-hidden mb-6 wl_shadow_3">
                <div class="bg-gradient-to-r from-[#17639D] to-blue-700 dark:from-slate-600 dark:to-slate-700 px-6 py-8">
                    <div class="text-white">
                        <h1 class="text-3xl font-bold mb-3">Edição {{ $edicao->numero }}</h1>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4 text-blue-100">
                            <span class="flex items-center gap-2">
                                <i class="fas fa-calendar-alt"></i>
                                {{ $edicao->data->format('d \de F \de Y') }}
                            </span>
                            <span class="flex items-center gap-2">
                                <i class="fas fa-file-alt"></i>
                                {{ $edicao->materias->count() }} matérias
                            </span>
                            @if($edicao->visualizacoes_count)
                            <span class="flex items-center gap-2">
                                <i class="fas fa-eye"></i>
                                {{ $edicao->visualizacoes_count }} visualizações
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Featured Materials -->
            @if($edicao->materias->count() > 0)
            <div class="bg-white dark:bg-slate-700 rounded-lg shadow-lg overflow-hidden mb-6 wl_shadow_3">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-600">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                            <i class="fas fa-list text-[#17639D] dark:text-blue-400"></i>
                            Matérias desta Edição
                        </h2>
                        <a href="{{ route('portal.edicoes.materias', $edicao) }}" 
                           class="text-[#17639D] dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-medium transition-colors">
                            Ver todas →
                        </a>
                    </div>
                </div>

                <div class="divide-y divide-gray-200 dark:divide-slate-600">
                    @foreach($edicao->materias->take(5) as $materia)
                    <div class="p-6 hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                        <div class="flex items-start gap-4">
                            
                            <!-- Type Badge -->
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
                            
                            <div class="flex-shrink-0 {{ $typeStyle['bg'] }} px-3 py-1 rounded-full">
                                <i class="fas fa-file-alt {{ $typeStyle['icon'] }} text-sm"></i>
                                <span class="ml-2 font-medium text-gray-700 dark:text-gray-300 text-sm uppercase tracking-wide">
                                    {{ $materia->tipo->nome ?? 'Documento' }}
                                </span>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white leading-tight mb-2">
                                    <a href="{{ route('portal.materias.show', $materia) }}" 
                                       class="hover:text-[#17639D] dark:hover:text-blue-400 transition-colors">
                                        {{ $materia->titulo }}
                                    </a>
                                </h3>
                                
                                <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed mb-3">
                                    {{ Str::limit(strip_tags($materia->texto), 150) }}
                                </p>

                                <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                                    @if($materia->orgao)
                                    <span class="flex items-center gap-1">
                                        <i class="fas fa-building text-xs"></i>
                                        {{ $materia->orgao->nome }}
                                    </span>
                                    @endif
                                    <span class="flex items-center gap-1">
                                        <i class="fas fa-calendar text-xs"></i>
                                        {{ $materia->data->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="flex-shrink-0">
                                <a href="{{ route('portal.materias.show', $materia) }}" 
                                   class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 dark:bg-slate-600 dark:hover:bg-slate-500 text-gray-700 dark:text-gray-300 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                    <i class="fas fa-eye"></i>
                                    Ver
                                </a>
                            </div>

                        </div>
                    </div>
                    @endforeach
                </div>

                @if($edicao->materias->count() > 5)
                <div class="px-6 py-4 bg-gray-50 dark:bg-slate-800 border-t border-gray-200 dark:border-slate-600">
                    <div class="text-center">
                        <a href="{{ route('portal.edicoes.materias', $edicao) }}" 
                           class="inline-flex items-center gap-2 bg-[#17639D] hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                            <i class="fas fa-list"></i>
                            Ver todas as {{ $edicao->materias->count() }} matérias
                        </a>
                    </div>
                </div>
                @endif
            </div>
            @endif

        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="sticky top-6 space-y-6">

                <!-- Edition Information -->
                <div class="bg-white dark:bg-slate-700 rounded-lg shadow-lg overflow-hidden wl_shadow_3">
                    <div class="bg-[#17639D] dark:bg-slate-600 px-4 py-3">
                        <h3 class="font-semibold text-white flex items-center gap-2">
                            <i class="fas fa-info-circle"></i>
                            Informações da Edição
                        </h3>
                    </div>
                    <div class="p-4 space-y-4">
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Número da Edição</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $edicao->numero }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Data de Publicação</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-calendar-alt text-gray-400"></i>
                                    {{ $edicao->data->format('d \de F \de Y') }}
                                </div>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total de Matérias</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-file-alt text-gray-400"></i>
                                    {{ $edicao->materias->count() }} matérias
                                </div>
                            </dd>
                        </div>

                        @if($edicao->visualizacoes_count)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Visualizações</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-eye text-gray-400"></i>
                                    {{ $edicao->visualizacoes_count }}
                                </div>
                            </dd>
                        </div>
                        @endif
                        
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white dark:bg-slate-700 rounded-lg shadow-lg overflow-hidden wl_shadow_3">
                    <div class="bg-gray-100 dark:bg-slate-600 px-4 py-3">
                        <h3 class="font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                            <i class="fas fa-tools"></i>
                            Ações
                        </h3>
                    </div>
                    <div class="p-4 space-y-3">
                        
                        @if($edicao->materias->count() > 0)
                        <a href="{{ route('portal.edicoes.materias', $edicao) }}" 
                           class="w-full inline-flex items-center justify-center gap-2 bg-[#17639D] hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-medium transition-colors">
                            <i class="fas fa-list"></i>
                            Ver Todas as Matérias
                        </a>
                        @endif

                        @if($edicao->arquivo_pdf)
                        <a href="{{ $edicao->arquivo_pdf }}" target="_blank"
                           class="w-full inline-flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 dark:bg-red-600 dark:hover:bg-red-700 text-white px-4 py-3 rounded-lg font-medium transition-colors">
                            <i class="fas fa-file-pdf"></i>
                            Download PDF
                        </a>
                        @endif

                        <button onclick="copyToClipboard('{{ url()->current() }}')" 
                                class="w-full inline-flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 dark:bg-slate-600 dark:hover:bg-slate-500 text-gray-700 dark:text-gray-300 px-4 py-3 rounded-lg font-medium transition-colors">
                            <i class="fas fa-link"></i>
                            Copiar Link
                        </button>

                        <button onclick="printDocument()" 
                                class="w-full inline-flex items-center justify-center gap-2 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 text-blue-700 dark:text-blue-300 px-4 py-3 rounded-lg font-medium transition-colors">
                            <i class="fas fa-print"></i>
                            Imprimir
                        </button>
                        
                    </div>
                </div>

                <!-- Navigation -->
                @php
                    $previousEdicao = \App\Models\Edicao::where('data', '<', $edicao->data)->orderBy('data', 'desc')->first();
                    $nextEdicao = \App\Models\Edicao::where('data', '>', $edicao->data)->orderBy('data', 'asc')->first();
                @endphp

                @if($previousEdicao || $nextEdicao)
                <div class="bg-white dark:bg-slate-700 rounded-lg shadow-lg overflow-hidden wl_shadow_3">
                    <div class="bg-emerald-100 dark:bg-emerald-900/30 px-4 py-3">
                        <h3 class="font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                            <i class="fas fa-arrow-left-right"></i>
                            Navegação
                        </h3>
                    </div>
                    <div class="p-4 space-y-3">
                        
                        @if($previousEdicao)
                        <a href="{{ route('portal.edicoes.show', $previousEdicao) }}" 
                           class="w-full flex items-center gap-3 p-3 bg-gray-50 dark:bg-slate-800 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                            <i class="fas fa-chevron-left text-gray-400"></i>
                            <div class="flex-1 text-left">
                                <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Anterior</div>
                                <div class="text-sm font-medium text-gray-800 dark:text-white">Edição {{ $previousEdicao->numero }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $previousEdicao->data->format('d/m/Y') }}</div>
                            </div>
                        </a>
                        @endif

                        @if($nextEdicao)
                        <a href="{{ route('portal.edicoes.show', $nextEdicao) }}" 
                           class="w-full flex items-center gap-3 p-3 bg-gray-50 dark:bg-slate-800 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                            <div class="flex-1 text-left">
                                <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Próxima</div>
                                <div class="text-sm font-medium text-gray-800 dark:text-white">Edição {{ $nextEdicao->numero }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $nextEdicao->data->format('d/m/Y') }}</div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                        @endif
                        
                    </div>
                </div>
                @endif

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
