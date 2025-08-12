@extends('layouts.portal')

@section('title', 'Diário Oficial - Página Inicial')

@section('content')
<!-- Banner de Destaque - Última Edição -->
<section class="hero-section bg-gradient-to-r from-blue-900 via-blue-800 to-blue-700 text-white relative overflow-hidden">
    <!-- Efeitos de fundo -->
    <div class="absolute inset-0 bg-pattern opacity-10"></div>
    <div class="absolute -top-4 -right-4 w-72 h-72 bg-blue-600 rounded-full opacity-20 blur-3xl"></div>
    <div class="absolute -bottom-8 -left-8 w-96 h-96 bg-blue-500 rounded-full opacity-15 blur-3xl"></div>
    
    <div class="container mx-auto px-6 py-16 relative z-10">
        <div class="max-w-6xl mx-auto">
            @if($ultimaEdicao)
                <div class="text-center mb-8">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">
                        Diário Oficial Municipal
                    </h1>
                    <p class="text-xl md:text-2xl text-blue-100 mb-2">
                        Edição {{ $ultimaEdicao->numero }}
                    </p>
                    <p class="text-lg text-blue-200">
                        {{ $ultimaEdicao->data->format('d/m/Y') }}
                    </p>
                </div>

                <div class="grid md:grid-cols-2 gap-8 items-center">
                    <!-- Prévia do PDF -->
                    <div class="bg-white rounded-lg shadow-2xl p-4">
                        <div class="aspect-[3/4] bg-gray-100 rounded-lg flex items-center justify-center">
                            @if($ultimaEdicao->caminho_arquivo && file_exists(storage_path('app/public/' . $ultimaEdicao->caminho_arquivo)))
                                <iframe 
                                    src="{{ asset('storage/' . $ultimaEdicao->caminho_arquivo) }}#page=1&toolbar=0&navpanes=0&scrollbar=0"
                                    class="w-full h-full rounded-lg"
                                    title="Prévia da Edição {{ $ultimaEdicao->numero }}">
                                </iframe>
                            @else
                                <div class="text-center text-gray-500">
                                    <i class="fas fa-file-pdf text-6xl mb-4"></i>
                                    <p class="text-lg font-medium">Edição {{ $ultimaEdicao->numero }}</p>
                                    <p class="text-sm">{{ $ultimaEdicao->data->format('d/m/Y') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Informações e Ações -->
                    <div class="space-y-6">
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6">
                            <h3 class="text-2xl font-bold mb-4">Última Publicação</h3>
                            <div class="space-y-3 text-blue-100">
                                <p><strong>Edição:</strong> {{ $ultimaEdicao->numero }}</p>
                                <p><strong>Data:</strong> {{ $ultimaEdicao->data->format('d/m/Y') }}</p>
                                <p><strong>Matérias:</strong> {{ $materiasEdicaoAtual }}</p>
                                @if($ultimaEdicao->assinatura)
                                    <p><strong>Assinado por:</strong> {{ $ultimaEdicao->assinatura->signatario }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Botões de Ação -->
                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('portal.edicoes.show', $ultimaEdicao) }}" 
                               class="btn-primary flex items-center space-x-2 px-6 py-3 bg-white text-blue-900 rounded-lg hover:bg-blue-50 transition-all duration-300 font-semibold">
                                <i class="fas fa-eye"></i>
                                <span>Ler Online</span>
                            </a>
                            
                            @if($ultimaEdicao->caminho_arquivo)
                                <a href="{{ asset('storage/' . $ultimaEdicao->caminho_arquivo) }}" 
                                   download
                                   class="btn-secondary flex items-center space-x-2 px-6 py-3 bg-transparent border-2 border-white text-white rounded-lg hover:bg-white hover:text-blue-900 transition-all duration-300 font-semibold">
                                    <i class="fas fa-download"></i>
                                    <span>Baixar PDF</span>
                                </a>
                            @endif
                            
                            <a href="{{ route('portal.edicoes.index') }}" 
                               class="btn-outline flex items-center space-x-2 px-6 py-3 bg-transparent border-2 border-white text-white rounded-lg hover:bg-white hover:text-blue-900 transition-all duration-300 font-semibold">
                                <i class="fas fa-list"></i>
                                <span>Todas as Edições</span>
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">
                        Diário Oficial Municipal
                    </h1>
                    <p class="text-xl text-blue-100">
                        Nenhuma edição publicada ainda
                    </p>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Área de Estatísticas -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Total de Edições -->
                <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition-shadow duration-300">
                    <div class="text-3xl font-bold text-blue-600 mb-2">{{ $totalEdicoes }}</div>
                    <div class="text-gray-600 font-medium">Edições Publicadas</div>
                    <div class="mt-2">
                        <i class="fas fa-newspaper text-2xl text-blue-400"></i>
                    </div>
                </div>

                <!-- Matérias na Edição Atual -->
                <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition-shadow duration-300">
                    <div class="text-3xl font-bold text-green-600 mb-2">{{ $materiasEdicaoAtual }}</div>
                    <div class="text-gray-600 font-medium">Matérias na Última Edição</div>
                    <div class="mt-2">
                        <i class="fas fa-file-alt text-2xl text-green-400"></i>
                    </div>
                </div>

                <!-- Downloads Populares -->
                <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition-shadow duration-300">
                    <div class="text-3xl font-bold text-purple-600 mb-2">{{ $downloadsPopulares->sum('total_downloads') }}</div>
                    <div class="text-gray-600 font-medium">Downloads (30 dias)</div>
                    <div class="mt-2">
                        <i class="fas fa-download text-2xl text-purple-400"></i>
                    </div>
                </div>

                <!-- Seções Populares -->
                <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition-shadow duration-300">
                    <div class="text-3xl font-bold text-orange-600 mb-2">{{ $secoesPopulares->count() }}</div>
                    <div class="text-gray-600 font-medium">Seções Ativas</div>
                    <div class="mt-2">
                        <i class="fas fa-chart-bar text-2xl text-orange-400"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Últimas Publicações -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-6">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Últimas Publicações
                </h2>
                <p class="text-lg text-gray-600">
                    Acompanhe as matérias mais recentes do Diário Oficial
                </p>
            </div>

            @if($ultimasPublicacoes->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($ultimasPublicacoes as $materia)
                        <div class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                            <div class="p-6">
                                <!-- Tipo e Data -->
                                <div class="flex items-center justify-between mb-3">
                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                        {{ $materia->tipo->nome }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ $materia->data->format('d/m/Y') }}
                                    </span>
                                </div>

                                <!-- Título -->
                                <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                    {{ $materia->titulo }}
                                </h3>

                                <!-- Resumo -->
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                    {{ Str::limit(strip_tags($materia->texto), 120) }}
                                </p>

                                <!-- Órgão e Número -->
                                <div class="text-xs text-gray-500 mb-4">
                                    <p><strong>Órgão:</strong> {{ $materia->orgao->sigla }}</p>
                                    <p><strong>Número:</strong> {{ $materia->numero }}</p>
                                </div>

                                <!-- Botão de Ação -->
                                <a href="{{ route('portal.materias.show', $materia) }}" 
                                   class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium text-sm transition-colors duration-200">
                                    <span>Ler na íntegra</span>
                                    <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Botão Ver Mais -->
                <div class="text-center mt-12">
                    <a href="{{ route('portal.materias.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-300 font-semibold">
                        <span>Ver Todas as Matérias</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                    <p class="text-xl text-gray-500">Nenhuma matéria publicada ainda</p>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Calendário de Publicações -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Calendário de Publicações
                </h2>
                <p class="text-lg text-gray-600">
                    Últimas edições publicadas nos últimos 30 dias
                </p>
            </div>

            @if($edicoesCalendario->count() > 0)
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($edicoesCalendario as $data => $edicoes)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-300">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-semibold text-gray-900">
                                        {{ Carbon\Carbon::parse($data)->format('d/m/Y') }}
                                    </h4>
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded">
                                        {{ $edicoes->count() }} edição(ões)
                                    </span>
                                </div>
                                
                                <div class="space-y-2">
                                    @foreach($edicoes as $edicao)
                                        <a href="{{ route('portal.edicoes.show', $edicao) }}" 
                                           class="block text-sm text-blue-600 hover:text-blue-800 hover:underline">
                                            Edição {{ $edicao->numero }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-calendar-alt text-6xl text-gray-300 mb-4"></i>
                    <p class="text-xl text-gray-500">Nenhuma edição publicada nos últimos 30 dias</p>
                </div>
            @endif
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
/* Padrão de fundo para o hero */
.bg-pattern {
    background-image: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
    background-size: 20px 20px;
}

/* Limitador de linhas */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Animações suaves */
.hover\:-translate-y-1:hover {
    transform: translateY(-0.25rem);
}
</style>
@endpush
