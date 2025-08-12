@extends('layouts.portal')

@section('title', 'Diário Oficial - Home')

@push('styles')
<style>
    .pdf-viewer {
        width: 100%;
        height: 800px;
        border: none;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .pdf-viewer:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }
    
    .pdf-container {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 2rem;
        border-radius: 16px;
        position: relative;
        overflow: hidden;
    }
    
    .pdf-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23cbd5e1' fill-opacity='0.05' fill-rule='evenodd'%3E%3Cpath d='m0 40l40-40h-40v40zm40 0v-40h-40l40 40z'/%3E%3C/g%3E%3C/svg%3E");
        pointer-events: none;
    }
    
    .dropdown-container {
        position: relative;
        display: inline-block;
    }
    
    .dropdown-button {
        background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        transition: all 0.3s ease;
    }
    
    .dropdown-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
    }
    
    .dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        max-height: 300px;
        overflow-y: auto;
        margin-top: 8px;
        display: none;
    }
    
    .dropdown-menu.show {
        display: block;
        animation: slideDown 0.3s ease;
    }
    
    .dropdown-item {
        padding: 12px 16px;
        cursor: pointer;
        transition: background-color 0.2s ease;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .dropdown-item:last-child {
        border-bottom: none;
    }
    
    .dropdown-item:hover {
        background-color: #f8fafc;
    }
    
    .dropdown-item.selected {
        background-color: #eff6ff;
        color: #1e40af;
        font-weight: 600;
    }
    
    .hero-section {
        text-align: center;
        margin-bottom: 3rem;
    }
    
    .hero-title {
        font-size: 2.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1rem;
    }
    
    .hero-subtitle {
        font-size: 1.125rem;
        color: #64748b;
        margin-bottom: 2rem;
    }
    
    .stats-container {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin: 2rem 0;
        flex-wrap: wrap;
    }
    
    .stat-item {
        background: white;
        padding: 1rem 2rem;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        text-align: center;
        min-width: 120px;
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #1e40af;
        display: block;
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: #64748b;
        margin-top: 0.25rem;
    }
    
    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-top: 2rem;
        flex-wrap: wrap;
    }
    
    .action-button {
        padding: 12px 24px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .action-button.primary {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }
    
    .action-button.secondary {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3);
    }
    
    .action-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2rem;
        }
        
        .pdf-viewer {
            height: 600px;
        }
        
        .stats-container {
            gap: 1rem;
        }
        
        .stat-item {
            min-width: 100px;
            padding: 0.75rem 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl">
    <!-- Seção Hero -->
    <div class="hero-section">
        <h1 class="hero-title">Diário Oficial Municipal</h1>
        <p class="hero-subtitle">Acesso rápido e transparente aos atos oficiais do município</p>
        
        @if(isset($edicaoRecente))
            <!-- Stats da Edição Atual -->
            <div class="stats-container">
                <div class="stat-item">
                    <span class="stat-number">{{ $edicaoRecente->numero }}</span>
                    <div class="stat-label">Edição Atual</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ str_pad($edicaoRecente->visualizacoes_count ?? $edicaoRecente->visualizacoes ?? 0, 3, '0', STR_PAD_LEFT) }}</span>
                    <div class="stat-label">Visualizações</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ str_pad($edicaoRecente->downloads_count ?? $edicaoRecente->downloads ?? 0, 3, '0', STR_PAD_LEFT) }}</span>
                    <div class="stat-label">Downloads</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ $edicaoRecente->materias()->count() ?? 0 }}</span>
                    <div class="stat-label">Matérias</div>
                </div>
            </div>
            
            <!-- Dropdown de Seleção de Edição -->
            <div class="dropdown-container">
                <button class="dropdown-button" onclick="toggleDropdown()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 3a1 1 0 012 0v5.5a.5.5 0 001 0V4a1 1 0 112 0v4.5a.5.5 0 001 0V6a1 1 0 112 0v6a2 2 0 01-2 2h-5a2 2 0 01-2-2V3z" clip-rule="evenodd" />
                    </svg>
                    <span id="selected-edition">{{ $edicaoRecente->numero }}ª Edição - {{ $edicaoRecente->data_publicacao ? $edicaoRecente->data_publicacao->format('d/m/Y') : $edicaoRecente->data->format('d/m/Y') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <div class="dropdown-menu" id="dropdown-menu">
                    @foreach($edicoes->take(10) as $edicao)
                        <div class="dropdown-item {{ $loop->first ? 'selected' : '' }}" 
                             onclick="selectEdition({{ $edicao->id }}, '{{ $edicao->numero }}ª Edição - {{ $edicao->data_publicacao ? $edicao->data_publicacao->format('d/m/Y') : $edicao->data->format('d/m/Y') }}', '{{ route('portal.edicoes.pdf', $edicao) }}')">
                            <div>
                                <div class="font-semibold">Edição Nº {{ $edicao->numero }}</div>
                                <div class="text-sm text-gray-600">{{ $edicao->data_publicacao ? $edicao->data_publicacao->format('d/m/Y') : $edicao->data->format('d/m/Y') }}</div>
                            </div>
                            @if($loop->first)
                                <div class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Atual</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    
    @if(isset($edicaoRecente))
        <!-- Container do PDF Centralizado -->
        <div class="pdf-container">
            <div class="text-center mb-4">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">
                    {{ $edicaoRecente->numero }}ª Edição - {{ $edicaoRecente->data_publicacao->locale('pt-BR')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                </h2>
                <p class="text-gray-600">Visualização da primeira página • Clique no PDF para navegar</p>
            </div>
            
            <div class="relative">
                <iframe 
                    id="pdf-viewer"
                    src="{{ route('portal.edicoes.pdf', $edicaoRecente) }}#toolbar=1&navpanes=1&scrollbar=1&page=1&view=FitH" 
                    class="pdf-viewer"
                    title="Visualização do Diário Oficial - Edição {{ $edicaoRecente->numero }}">
                    <p>Seu navegador não suporta a visualização de PDFs. 
                       <a href="{{ route('portal.edicoes.pdf', $edicaoRecente) }}" class="text-blue-600 hover:underline">Clique aqui para baixar o arquivo</a>.
                    </p>
                </iframe>
            </div>
            
            <!-- Botões de Ação -->
            <div class="action-buttons">
                <a href="{{ route('portal.edicoes.pdf', $edicaoRecente) }}" 
                   target="_blank" 
                   class="action-button primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    Ler Edição Completa
                </a>
                
                <a href="{{ route('portal.edicoes.pdf', $edicaoRecente) }}" 
                   download 
                   class="action-button secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Download PDF
                </a>
                
                <a href="{{ route('portal.edicoes.show', $edicaoRecente) }}" 
                   class="action-button secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Ver Detalhes
                </a>
                
                <a href="{{ route('portal.verificar') }}" 
                   class="action-button secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Verificar Autenticidade
                </a>
            </div>
        </div>
        
        <!-- Seção de Edições Anteriores (compacta) -->
        <div class="bg-white rounded-lg shadow-lg p-6 mt-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">Edições Anteriores</h2>
                <a href="{{ route('portal.edicoes.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                    Ver todas
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($edicoes->skip(1)->take(6) as $edicao)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-semibold text-gray-800">Edição Nº {{ $edicao->numero }}</h3>
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                                {{ $edicao->data_publicacao ? $edicao->data_publicacao->format('d/m/Y') : $edicao->data->format('d/m/Y') }}
                            </span>
                        </div>
                        
                        <div class="flex space-x-2 mt-3">
                            <a href="{{ route('portal.edicoes.show', $edicao) }}" 
                               class="flex-1 text-center px-3 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition-colors">
                                Visualizar
                            </a>
                            <a href="{{ route('portal.edicoes.pdf', $edicao) }}" 
                               class="px-3 py-2 bg-gray-600 text-white text-xs rounded hover:bg-gray-700 transition-colors">
                                PDF
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="text-center py-12 bg-white rounded-lg shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <h3 class="text-2xl font-semibold text-gray-800 mb-2">Nenhuma edição disponível</h3>
            <p class="text-gray-600">Não há edições publicadas para exibição no momento.</p>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fechar dropdown ao clicar fora
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('dropdown-menu');
        const button = document.querySelector('.dropdown-button');
        
        if (!button.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.remove('show');
        }
    });
    
    // Melhorar a experiência do iframe
    const pdfViewer = document.getElementById('pdf-viewer');
    if (pdfViewer) {
        pdfViewer.addEventListener('load', function() {
            // PDF carregado com sucesso
            console.log('PDF carregado');
        });
        
        pdfViewer.addEventListener('error', function() {
            // Erro ao carregar PDF
            console.error('Erro ao carregar PDF');
        });
    }
});

function toggleDropdown() {
    const dropdown = document.getElementById('dropdown-menu');
    dropdown.classList.toggle('show');
}

function selectEdition(editionId, editionLabel, pdfUrl) {
    // Atualizar o texto do botão
    document.getElementById('selected-edition').textContent = editionLabel;
    
    // Atualizar o iframe
    const pdfViewer = document.getElementById('pdf-viewer');
    if (pdfViewer) {
        pdfViewer.src = pdfUrl + '#toolbar=1&navpanes=1&scrollbar=1&page=1&view=FitH';
    }
    
    // Atualizar os botões de ação
    const actionButtons = document.querySelectorAll('.action-button');
    actionButtons.forEach(button => {
        if (button.href && button.href.includes('/pdf')) {
            if (button.hasAttribute('download')) {
                button.href = pdfUrl; // Download
            } else if (button.target === '_blank') {
                button.href = pdfUrl; // Ler completo
            }
        }
    });
    
    // Remover classe selected de todos os itens
    document.querySelectorAll('.dropdown-item').forEach(item => {
        item.classList.remove('selected');
    });
    
    // Adicionar classe selected ao item clicado
    event.target.closest('.dropdown-item').classList.add('selected');
    
    // Fechar dropdown
    document.getElementById('dropdown-menu').classList.remove('show');
    
    // Scroll suave até o PDF
    document.querySelector('.pdf-container').scrollIntoView({ 
        behavior: 'smooth',
        block: 'center'
    });
}

// Adicionar efeitos de hover nos botões
document.addEventListener('DOMContentLoaded', function() {
    const actionButtons = document.querySelectorAll('.action-button');
    actionButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});

// Adicionar loading state para o iframe
function showPdfLoading() {
    const pdfViewer = document.getElementById('pdf-viewer');
    if (pdfViewer) {
        pdfViewer.style.filter = 'blur(2px)';
        pdfViewer.style.opacity = '0.7';
        
        // Remover loading após 2 segundos
        setTimeout(() => {
            pdfViewer.style.filter = 'none';
            pdfViewer.style.opacity = '1';
        }, 2000);
    }
}

// Adicionar animação de entrada
document.addEventListener('DOMContentLoaded', function() {
    const elements = document.querySelectorAll('.hero-section, .pdf-container, .stat-item');
    elements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            element.style.transition = 'all 0.6s ease';
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endpush
@endsection
