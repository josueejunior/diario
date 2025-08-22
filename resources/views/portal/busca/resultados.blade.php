@extends('layouts.app')

@section('title', 'Resultados da Busca - Diário Oficial')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar com Filtros -->
        <div class="col-lg-3 col-md-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header">
                    <h5 class="card-title mb-0">🔍 Filtros de Busca</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('home.buscar') }}" id="searchFilters">
                        <!-- Busca por Texto -->
                        <div class="mb-3">
                            <label for="q" class="form-label">Termo de Busca</label>
                            <div class="input-group">
                                <input type="text" 
                                       name="q" 
                                       id="q" 
                                       class="form-control" 
                                       value="{{ $params['q'] ?? '' }}"
                                       placeholder="Digite sua busca..."
                                       autocomplete="off">
                                <button type="button" class="btn btn-outline-secondary" id="clearSearch">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div id="searchSuggestions" class="list-group mt-1" style="display: none;"></div>
                        </div>

                        <!-- Tipo de Documento -->
                        <div class="mb-3">
                            <label for="tipo_id" class="form-label">Tipo de Documento</label>
                            <select name="tipo_id" id="tipo_id" class="form-select">
                                <option value="">Todos os tipos</option>
                                @foreach($tipos as $tipo)
                                    <option value="{{ $tipo->id }}" {{ ($params['tipo_id'] ?? '') == $tipo->id ? 'selected' : '' }}>
                                        {{ $tipo->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Órgão -->
                        <div class="mb-3">
                            <label for="orgao_id" class="form-label">Órgão</label>
                            <select name="orgao_id" id="orgao_id" class="form-select">
                                <option value="">Todos os órgãos</option>
                                @foreach($orgaos as $orgao)
                                    <option value="{{ $orgao->id }}" {{ ($params['orgao_id'] ?? '') == $orgao->id ? 'selected' : '' }}>
                                        {{ $orgao->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Período -->
                        <div class="mb-3">
                            <label class="form-label">Período</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="date" 
                                           name="data_inicio" 
                                           class="form-control form-control-sm" 
                                           value="{{ $params['data_inicio'] ?? '' }}"
                                           placeholder="De">
                                </div>
                                <div class="col-6">
                                    <input type="date" 
                                           name="data_fim" 
                                           class="form-control form-control-sm" 
                                           value="{{ $params['data_fim'] ?? '' }}"
                                           placeholder="Até">
                                </div>
                            </div>
                        </div>

                        <!-- Ano -->
                        <div class="mb-3">
                            <label for="ano" class="form-label">Ano</label>
                            <select name="ano" id="ano" class="form-select">
                                <option value="">Todos os anos</option>
                                @foreach($anos as $ano)
                                    <option value="{{ $ano }}" {{ ($params['ano'] ?? '') == $ano ? 'selected' : '' }}>
                                        {{ $ano }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                            <a href="{{ route('home.buscar') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Limpar Filtros
                            </a>
                        </div>
                    </form>

                    <!-- Estatísticas -->
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="text-muted">📊 Estatísticas</h6>
                        <ul class="list-unstyled small">
                            <li><strong>{{ number_format($searchStats['total_documents']) }}</strong> documentos</li>
                            <li><strong>{{ $searchStats['total_types'] }}</strong> tipos</li>
                            <li><strong>{{ $searchStats['total_organs'] }}</strong> órgãos</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9 col-md-8">
            <!-- Cabeçalho dos Resultados -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1">
                        @if(!empty($params['q']))
                            Resultados para: "{{ $params['q'] }}"
                        @else
                            Resultados da Busca Avançada
                        @endif
                    </h1>
                    <p class="text-muted">
                        {{ $materias->total() }} {{ Str::plural('documento', $materias->total()) }} encontrado{{ $materias->total() > 1 ? 's' : '' }}
                        @if($edicoes->isNotEmpty())
                            + {{ $edicoes->count() }} {{ Str::plural('edição', $edicoes->count()) }}
                        @endif
                    </p>
                </div>
            </div>

            <!-- Sugestões (se não há resultados) -->
            @if($materias->isEmpty() && isset($suggestions) && $suggestions->isNotEmpty())
                <div class="alert alert-info">
                    <h5 class="alert-heading">💡 Nenhum resultado encontrado</h5>
                    <p>Tente buscar por:</p>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($suggestions as $suggestion)
                            <a href="{{ route('home.buscar', ['q' => $suggestion]) }}" class="badge bg-primary text-decoration-none">
                                {{ $suggestion }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Edições Encontradas -->
            @if($edicoes->isNotEmpty())
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">📰 Edições Encontradas</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($edicoes as $edicao)
                                <div class="col-lg-6 mb-3">
                                    <div class="d-flex align-items-center p-3 border rounded">
                                        <div class="me-3">
                                            <i class="fas fa-file-pdf fa-2x text-danger"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <a href="{{ route('portal.edicoes.show', $edicao) }}" class="text-decoration-none">
                                                    Edição {{ $edicao->numero }}
                                                </a>
                                            </h6>
                                            <p class="text-muted small mb-0">
                                                📅 {{ $edicao->data->format('d/m/Y') }} • 
                                                📄 {{ $edicao->materias->count() }} matérias
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Resultados de Matérias -->
            @if($materias->isNotEmpty())
                <div class="row">
                    @foreach($materias as $materia)
                        <div class="col-12 mb-4">
                            <div class="card h-100 search-result-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="d-flex gap-2">
                                            <span class="badge bg-primary">{{ $materia->tipo->nome }}</span>
                                            <span class="badge bg-secondary">{{ $materia->orgao->sigla }}</span>
                                        </div>
                                        <small class="text-muted">{{ $materia->data->format('d/m/Y') }}</small>
                                    </div>
                                    
                                    <h5 class="card-title">
                                        <a href="{{ route('portal.materias.show', $materia) }}" class="text-decoration-none">
                                            {{ $materia->titulo }}
                                        </a>
                                    </h5>
                                    
                                    <p class="card-text">
                                        {{ Str::limit(strip_tags($materia->texto), 200) }}
                                    </p>
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div class="text-muted small">
                                            📄 {{ $materia->numero }} • 
                                            🏢 {{ $materia->orgao->nome }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginação -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $materias->appends(request()->query())->links() }}
                </div>
            @elseif(empty($suggestions) || !isset($suggestions))
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4>Nenhum documento encontrado</h4>
                    <p class="text-muted">Tente ajustar os filtros ou usar termos de busca diferentes.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.search-result-card {
    transition: all 0.3s ease;
    border: 1px solid #e0e0e0;
}

.search-result-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border-color: #007bff;
}

.search-result-card .card-title a {
    color: #333;
}

.search-result-card .card-title a:hover {
    color: #007bff;
}

#searchSuggestions {
    position: absolute;
    z-index: 1000;
    max-height: 200px;
    overflow-y: auto;
}

.sticky-top {
    z-index: 10;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('q');
    const suggestionsContainer = document.getElementById('searchSuggestions');
    const clearButton = document.getElementById('clearSearch');
    
    let searchTimeout;
    
    // Auto-complete search
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        clearTimeout(searchTimeout);
        
        if (query.length < 3) {
            suggestionsContainer.style.display = 'none';
            return;
        }
        
        searchTimeout = setTimeout(() => {
            fetch(`{{ route('home.quick-search') }}?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(results => {
                    if (results.length > 0) {
                        let html = '';
                        results.forEach(result => {
                            html += `
                                <a href="${result.url}" class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>${result.titulo}</strong><br>
                                            <small class="text-muted">${result.tipo} • ${result.orgao} • ${result.data}</small>
                                        </div>
                                        <span class="badge bg-secondary">${result.numero}</span>
                                    </div>
                                </a>
                            `;
                        });
                        suggestionsContainer.innerHTML = html;
                        suggestionsContainer.style.display = 'block';
                    } else {
                        suggestionsContainer.style.display = 'none';
                    }
                })
                .catch(() => {
                    suggestionsContainer.style.display = 'none';
                });
        }, 300);
    });
    
    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !suggestionsContainer.contains(e.target)) {
            suggestionsContainer.style.display = 'none';
        }
    });
    
    // Clear search
    clearButton.addEventListener('click', function() {
        searchInput.value = '';
        suggestionsContainer.style.display = 'none';
        searchInput.focus();
    });
    
    // Auto-submit form when filters change
    document.querySelectorAll('#searchFilters select, #searchFilters input[type="date"]').forEach(element => {
        element.addEventListener('change', function() {
            document.getElementById('searchFilters').submit();
        });
    });
});
</script>
@endpush
@endsection
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
