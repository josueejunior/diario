@extends('layouts.portal')

@section('title', 'Edição Nº ' . $edicao->numero . ' - Diário Oficial')

@push('styles')
<style>
    .edition-hero {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);
    }
    
    .info-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .auth-code {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border: 2px dashed #cbd5e1;
    }
    
    .floating-action {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        z-index: 40;
    }
    
    .floating-menu {
        display: none;
        position: absolute;
        bottom: 100%;
        right: 0;
        margin-bottom: 0.5rem;
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        padding: 0.5rem;
        min-width: 200px;
    }
    
    .floating-menu.show {
        display: block;
        animation: fadeInUp 0.3s ease;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .signature-badge {
        background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
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
@endpush

@section('content')
<!-- Hero Section da Edição -->
<section class="edition-hero text-white py-16 mb-8">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Breadcrumb -->
            <nav class="text-blue-100 mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 text-sm">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="hover:text-white transition-colors">
                            <i class="fas fa-home mr-1"></i>
                            Início
                        </a>
                    </li>
                    <li class="inline-flex items-center">
                        <i class="fas fa-chevron-right mx-2 text-xs"></i>
                        <a href="{{ route('portal.edicoes.index') }}" class="hover:text-white transition-colors">
                            Edições
                        </a>
                    </li>
                    <li class="inline-flex items-center">
                        <i class="fas fa-chevron-right mx-2 text-xs"></i>
                        <span>Edição {{ $edicao->numero }}</span>
                    </li>
                </ol>
            </nav>
            
            <div class="mb-6">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Edição Nº {{ $edicao->numero }}
                </h1>
                <p class="text-xl text-blue-100">
                    Publicada em {{ $edicao->data_publicacao ? $edicao->data_publicacao->format('d/m/Y') : $edicao->data->format('d/m/Y') }}
                </p>
                
                @if($edicao->assinatura)
                    <div class="inline-flex items-center mt-4 px-4 py-2 signature-badge text-white rounded-full text-sm font-medium">
                        <i class="fas fa-certificate mr-2"></i>
                        Digitalmente Assinado
                    </div>
                @endif
            </div>
            
            <!-- Actions -->
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('portal.edicoes.materias', $edicao) }}" 
                   class="inline-flex items-center px-6 py-3 bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition-all duration-300 font-semibold shadow-lg">
                    <i class="fas fa-list mr-2"></i>
                    Ver {{ $edicao->materias->count() }} Matéria(s)
                </a>
                
                @if($edicao->caminho_arquivo && file_exists(storage_path('app/public/' . $edicao->caminho_arquivo)))
                    <a href="{{ asset('storage/' . $edicao->caminho_arquivo) }}" 
                       target="_blank"
                       class="inline-flex items-center px-6 py-3 bg-transparent border-2 border-white text-white rounded-lg hover:bg-white hover:text-blue-600 transition-all duration-300 font-semibold">
                        <i class="fas fa-eye mr-2"></i>
                        Visualizar PDF
                    </a>
                    
                    <a href="{{ asset('storage/' . $edicao->caminho_arquivo) }}" 
                       download
                       class="inline-flex items-center px-6 py-3 bg-transparent border-2 border-white text-white rounded-lg hover:bg-white hover:text-blue-600 transition-all duration-300 font-semibold">
                        <i class="fas fa-download mr-2"></i>
                        Download PDF
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>

<div class="container mx-auto px-6 py-8">
    <!-- Informações Principais -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        <!-- Informações da Edição -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg p-8 info-card">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                    Informações da Edição
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Número da Edição</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $edicao->numero }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Data de Publicação</h3>
                        <p class="text-lg font-semibold text-gray-700">
                            {{ $edicao->data_publicacao ? $edicao->data_publicacao->format('d/m/Y H:i') : $edicao->data->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Total de Matérias</h3>
                        <p class="text-lg font-semibold text-green-600">{{ $edicao->materias->count() }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Status</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $edicao->publicado ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            <i class="fas fa-{{ $edicao->publicado ? 'check-circle' : 'clock' }} mr-1"></i>
                            {{ $edicao->publicado ? 'Publicado' : 'Em Análise' }}
                        </span>
                    </div>
                </div>
                
                @if($edicao->descricao)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Descrição</h3>
                        <div class="prose max-w-none">
                            <p class="text-gray-700 leading-relaxed">{{ $edicao->descricao }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Sidebar de Ações e Informações -->
        <div class="space-y-8">
            <!-- Assinatura Digital -->
            @if($edicao->assinatura)
                <div class="bg-green-50 border border-green-200 rounded-xl p-6 info-card">
                    <h3 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                        <i class="fas fa-certificate mr-2"></i>
                        Assinatura Digital
                    </h3>
                    
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="font-medium text-green-700">Signatário:</span>
                            <p class="text-green-600">{{ $edicao->assinatura->signatario }}</p>
                        </div>
                        
                        <div>
                            <span class="font-medium text-green-700">CPF:</span>
                            <p class="text-green-600">{{ $edicao->assinatura->cpf_signatario }}</p>
                        </div>
                        
                        @if($edicao->assinatura->cargo_signatario)
                            <div>
                                <span class="font-medium text-green-700">Cargo:</span>
                                <p class="text-green-600">{{ $edicao->assinatura->cargo_signatario }}</p>
                            </div>
                        @endif
                        
                        <div>
                            <span class="font-medium text-green-700">Data da Assinatura:</span>
                            <p class="text-green-600">{{ $edicao->assinatura->carimbo_tempo->format('d/m/Y H:i:s') }}</p>
                        </div>
                        
                        <div>
                            <span class="font-medium text-green-700">Hash:</span>
                            <p class="font-mono text-xs bg-green-100 p-2 rounded break-all">{{ $edicao->assinatura->hash }}</p>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Código de Autenticidade -->
            <div class="bg-white rounded-xl shadow-lg p-6 info-card">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-shield-alt mr-2 text-blue-500"></i>
                    Verificação de Autenticidade
                </h3>
                
                <div class="auth-code p-4 rounded-lg mb-4">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Código de Verificação</p>
                    <p class="font-mono text-sm text-gray-800 break-all select-all">{{ $edicao->hash }}</p>
                </div>
                
                <p class="text-sm text-gray-600 mb-4">
                    Utilize este código para verificar a autenticidade e integridade do documento.
                </p>
                
                <a href="{{ route('portal.verificar') }}?codigo={{ $edicao->hash }}" 
                   class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm font-medium">
                    <i class="fas fa-search mr-2"></i>
                    Verificar Documento
                </a>
            </div>
            
            <!-- Estatísticas -->
            <div class="bg-white rounded-xl shadow-lg p-6 info-card">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-chart-bar mr-2 text-purple-500"></i>
                    Estatísticas
                </h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Visualizações</span>
                        <span class="font-semibold text-gray-800">{{ $edicao->downloads->count() }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Downloads</span>
                        <span class="font-semibold text-gray-800">{{ $edicao->downloads->count() }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-gray-600">Matérias</span>
                        <span class="font-semibold text-green-600">{{ $edicao->materias->count() }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Prévia das Matérias -->
    @if($edicao->materias->count() > 0)
        <section class="mb-12">
            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-file-alt text-blue-500 mr-3"></i>
                        Matérias desta Edição
                    </h2>
                    <a href="{{ route('portal.edicoes.materias', $edicao) }}" 
                       class="text-blue-600 hover:text-blue-800 font-medium text-sm transition-colors">
                        Ver todas →
                    </a>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($edicao->materias->take(6) as $materia)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-300">
                            <div class="flex items-center justify-between mb-2">
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded">
                                    {{ $materia->tipo->nome }}
                                </span>
                                <span class="text-xs text-gray-500">{{ $materia->numero }}</span>
                            </div>
                            
                            <h3 class="font-medium text-gray-900 mb-2 line-clamp-2">
                                {{ $materia->titulo }}
                            </h3>
                            
                            <p class="text-sm text-gray-600 mb-3 line-clamp-3">
                                {{ Str::limit(strip_tags($materia->texto), 100) }}
                            </p>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">{{ $materia->orgao->sigla }}</span>
                                <a href="{{ route('portal.materias.show', $materia) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Ler mais →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($edicao->materias->count() > 6)
                    <div class="text-center mt-6">
                        <a href="{{ route('portal.edicoes.materias', $edicao) }}" 
                           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium">
                            <i class="fas fa-list mr-2"></i>
                            Ver todas as {{ $edicao->materias->count() }} matérias
                        </a>
                    </div>
                @endif
            </div>
        </section>
    @endif
    
    <!-- Visualizador de PDF -->
    @if($edicao->caminho_arquivo && file_exists(storage_path('app/public/' . $edicao->caminho_arquivo)))
        <section class="mb-12">
            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-file-pdf text-red-500 mr-3"></i>
                        Visualização do PDF
                    </h2>
                    <div class="flex space-x-3">
                        <a href="{{ asset('storage/' . $edicao->caminho_arquivo) }}" 
                           target="_blank"
                           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors text-sm font-medium">
                            <i class="fas fa-external-link-alt mr-2"></i>
                            Abrir em nova aba
                        </a>
                        <a href="{{ asset('storage/' . $edicao->caminho_arquivo) }}" 
                           download
                           class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                            <i class="fas fa-download mr-2"></i>
                            Download
                        </a>
                    </div>
                </div>
                
                <div class="aspect-[4/3] bg-gray-100 rounded-lg overflow-hidden">
                    <iframe 
                        src="{{ asset('storage/' . $edicao->caminho_arquivo) }}#toolbar=1&navpanes=1&scrollbar=1"
                        class="w-full h-full"
                        title="PDF da Edição {{ $edicao->numero }}">
                    </iframe>
                </div>
            </div>
        </section>
    @endif
    
    <!-- Navegação entre Edições -->
    <section class="mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-arrow-left mr-2 text-gray-400"></i>
                Outras Edições
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @php
                    $anterior = \App\Models\Edicao::where('numero', '<', $edicao->numero)
                                                  ->orderBy('numero', 'desc')
                                                  ->first();
                    $proxima = \App\Models\Edicao::where('numero', '>', $edicao->numero)
                                                  ->orderBy('numero', 'asc')
                                                  ->first();
                @endphp
                
                @if($anterior)
                    <a href="{{ route('portal.edicoes.show', $anterior) }}" 
                       class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="flex-shrink-0 mr-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chevron-left text-blue-600"></i>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Edição Anterior</p>
                            <p class="text-sm text-gray-600">Nº {{ $anterior->numero }} - {{ $anterior->data->format('d/m/Y') }}</p>
                        </div>
                    </a>
                @endif
                
                @if($proxima)
                    <a href="{{ route('portal.edicoes.show', $proxima) }}" 
                       class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="flex-shrink-0 mr-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chevron-right text-blue-600"></i>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Próxima Edição</p>
                            <p class="text-sm text-gray-600">Nº {{ $proxima->numero }} - {{ $proxima->data->format('d/m/Y') }}</p>
                        </div>
                    </a>
                @endif
                
                @if(!$anterior && !$proxima)
                    <div class="col-span-2 text-center py-8 text-gray-500">
                        <i class="fas fa-info-circle text-2xl mb-2"></i>
                        <p>Esta é a única edição disponível no momento.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>

<!-- Botão Flutuante de Ações -->
<div class="floating-action">
    <div class="floating-menu" id="floating-menu">
        <div class="space-y-2">
            <a href="{{ route('portal.edicoes.materias', $edicao) }}" 
               class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors text-sm">
                <i class="fas fa-list mr-3 text-blue-500"></i>
                Ver Matérias
            </a>
            
            @if($edicao->caminho_arquivo)
                <a href="{{ asset('storage/' . $edicao->caminho_arquivo) }}" 
                   target="_blank"
                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors text-sm">
                    <i class="fas fa-eye mr-3 text-purple-500"></i>
                    Visualizar PDF
                </a>
                
                <a href="{{ asset('storage/' . $edicao->caminho_arquivo) }}" 
                   download
                   class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors text-sm">
                    <i class="fas fa-download mr-3 text-green-500"></i>
                    Download PDF
                </a>
            @endif
            
            <a href="{{ route('portal.verificar') }}?codigo={{ $edicao->hash }}" 
               class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors text-sm">
                <i class="fas fa-shield-alt mr-3 text-orange-500"></i>
                Verificar
            </a>
            
            <button onclick="shareEdition()" 
                    class="flex items-center w-full px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors text-sm">
                <i class="fas fa-share-alt mr-3 text-blue-500"></i>
                Compartilhar
            </button>
        </div>
    </div>
    
    <button id="floating-btn" 
            class="w-14 h-14 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 transition-all duration-300 flex items-center justify-center hover:shadow-xl">
        <i class="fas fa-ellipsis-v"></i>
    </button>
</div>

@push('scripts')
<script>
    // Floating menu
    document.getElementById('floating-btn').addEventListener('click', function() {
        const menu = document.getElementById('floating-menu');
        menu.classList.toggle('show');
    });
    
    // Fechar menu ao clicar fora
    document.addEventListener('click', function(e) {
        const menu = document.getElementById('floating-menu');
        const btn = document.getElementById('floating-btn');
        
        if (!menu.contains(e.target) && !btn.contains(e.target)) {
            menu.classList.remove('show');
        }
    });
    
    // Função de compartilhar
    function shareEdition() {
        if (navigator.share) {
            navigator.share({
                title: 'Edição {{ $edicao->numero }} - Diário Oficial',
                text: 'Confira a Edição {{ $edicao->numero }} do Diário Oficial publicada em {{ $edicao->data->format("d/m/Y") }}',
                url: window.location.href
            });
        } else {
            // Fallback para navegadores que não suportam Web Share API
            const url = window.location.href;
            navigator.clipboard.writeText(url).then(() => {
                alert('Link copiado para a área de transferência!');
            });
        }
    }
</script>
@endpush

@endsection