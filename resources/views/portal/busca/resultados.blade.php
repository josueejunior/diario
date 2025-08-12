@extends('layouts.portal')

@section('title', "Resultados da Busca: {$query}")

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Cabeçalho dos Resultados -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">
                Resultados da Busca
            </h1>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-blue-800">
                    <i class="fas fa-search mr-2"></i>
                    Você pesquisou por: <strong>"{{ $query }}"</strong>
                </p>
                <p class="text-blue-600 text-sm mt-1">
                    {{ $materias->total() + $edicoes->count() }} resultado(s) encontrado(s)
                </p>
            </div>
        </div>

        <!-- Filtros de Resultado -->
        <div class="mb-6">
            <div class="flex flex-wrap gap-2">
                <button class="filter-btn active" data-filter="all">
                    <i class="fas fa-list mr-1"></i>
                    Todos ({{ $materias->total() + $edicoes->count() }})
                </button>
                @if($materias->total() > 0)
                    <button class="filter-btn" data-filter="materias">
                        <i class="fas fa-file-alt mr-1"></i>
                        Matérias ({{ $materias->total() }})
                    </button>
                @endif
                @if($edicoes->count() > 0)
                    <button class="filter-btn" data-filter="edicoes">
                        <i class="fas fa-newspaper mr-1"></i>
                        Edições ({{ $edicoes->count() }})
                    </button>
                @endif
            </div>
        </div>

        <!-- Resultados das Matérias -->
        @if($materias->total() > 0)
            <div class="results-section" id="materias-section">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-file-alt text-blue-600 mr-2"></i>
                    Matérias Encontradas
                    <span class="ml-2 text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded-full">{{ $materias->total() }}</span>
                </h2>

                <div class="space-y-6">
                    @foreach($materias as $materia)
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 p-6">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center space-x-2">
                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                        {{ $materia->tipo->nome }}
                                    </span>
                                    <span class="text-gray-500 text-sm">
                                        {{ $materia->orgao->sigla }}
                                    </span>
                                    <span class="text-gray-400 text-sm">•</span>
                                    <span class="text-gray-500 text-sm">
                                        {{ $materia->data->format('d/m/Y') }}
                                    </span>
                                </div>
                                <span class="text-xs text-gray-400">
                                    Nº {{ $materia->numero }}
                                </span>
                            </div>

                            <h3 class="text-xl font-semibold text-gray-900 mb-3 hover:text-blue-600">
                                <a href="{{ route('portal.materias.show', $materia) }}">
                                    {{ $materia->titulo }}
                                </a>
                            </h3>

                            <div class="text-gray-600 mb-4">
                                <p class="line-clamp-3">
                                    {!! Str::limit(strip_tags($materia->texto), 200) !!}
                                </p>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <i class="fas fa-building mr-1"></i>
                                        {{ $materia->orgao->nome }}
                                    </span>
                                </div>
                                <a href="{{ route('portal.materias.show', $materia) }}" 
                                   class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium text-sm transition-colors duration-200">
                                    <span>Ler na íntegra</span>
                                    <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginação das Matérias -->
                @if($materias->hasPages())
                    <div class="mt-8">
                        {{ $materias->appends(['q' => $query])->links() }}
                    </div>
                @endif
            </div>
        @endif

        <!-- Resultados das Edições -->
        @if($edicoes->count() > 0)
            <div class="results-section mt-12" id="edicoes-section">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-newspaper text-blue-600 mr-2"></i>
                    Edições Encontradas
                    <span class="ml-2 text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded-full">{{ $edicoes->count() }}</span>
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($edicoes as $edicao)
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 p-6">
                            <div class="text-center">
                                <div class="bg-blue-100 rounded-full p-4 mx-auto mb-4 w-16 h-16 flex items-center justify-center">
                                    <i class="fas fa-newspaper text-2xl text-blue-600"></i>
                                </div>
                                
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                    Edição {{ $edicao->numero }}
                                </h3>
                                
                                <p class="text-gray-600 mb-2">
                                    {{ $edicao->data->format('d/m/Y') }}
                                </p>
                                
                                <div class="text-sm text-gray-500 mb-4">
                                    <span class="flex items-center justify-center">
                                        <i class="fas fa-file-alt mr-1"></i>
                                        {{ $edicao->materias()->count() }} matéria(s)
                                    </span>
                                </div>

                                <div class="flex space-x-2 justify-center">
                                    <a href="{{ route('portal.edicoes.show', $edicao) }}" 
                                       class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition-colors duration-200">
                                        <i class="fas fa-eye mr-1"></i>
                                        Ver
                                    </a>
                                    @if($edicao->caminho_arquivo)
                                        <a href="{{ asset('storage/' . $edicao->caminho_arquivo) }}" 
                                           download
                                           class="inline-flex items-center px-3 py-2 bg-gray-600 text-white text-xs font-medium rounded hover:bg-gray-700 transition-colors duration-200">
                                            <i class="fas fa-download mr-1"></i>
                                            PDF
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Nenhum Resultado Encontrado -->
        @if($materias->total() == 0 && $edicoes->count() == 0)
            <div class="text-center py-12">
                <div class="bg-gray-100 rounded-full p-6 mx-auto mb-4 w-24 h-24 flex items-center justify-center">
                    <i class="fas fa-search text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-2">
                    Nenhum resultado encontrado
                </h3>
                <p class="text-gray-600 mb-6">
                    Não foi possível encontrar resultados para "<strong>{{ $query }}</strong>".
                </p>
                <div class="space-y-2 text-sm text-gray-500">
                    <p><strong>Dicas de pesquisa:</strong></p>
                    <ul class="list-disc list-inside space-y-1 text-left max-w-md mx-auto">
                        <li>Verifique a ortografia das palavras</li>
                        <li>Tente usar termos mais gerais</li>
                        <li>Use palavras-chave diferentes</li>
                        <li>Pesquise por número da edição ou data (dd/mm/aaaa)</li>
                    </ul>
                </div>
                <div class="mt-8">
                    <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-300 font-semibold">
                        <i class="fas fa-home mr-2"></i>
                        Voltar ao Início
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.filter-btn {
    @apply px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition-all duration-200;
}

.filter-btn.active {
    @apply bg-blue-600 text-white border-blue-600;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.results-section {
    animation: fadeInUp 0.5s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const sections = document.querySelectorAll('.results-section');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            // Atualizar botões ativos
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Mostrar/esconder seções
            sections.forEach(section => {
                if (filter === 'all') {
                    section.style.display = 'block';
                } else if (section.id === filter + '-section') {
                    section.style.display = 'block';
                } else {
                    section.style.display = 'none';
                }
            });
        });
    });
});
</script>
@endpush
