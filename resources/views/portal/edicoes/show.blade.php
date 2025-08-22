@extends('layouts.portal-standalone')

@section('title', 'Edição ' . $edicao->numero . ' - Diário Oficial')
@section('description', 'Visualize a edição ' . $edicao->numero . ' do Diário Oficial de ' . $edicao->data->format('d/m/Y'))

@push('styles')
<style>
    .pdf-viewer-container {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    .pdf-controls {
        background: linear-gradient(135deg, #374151 0%, #111827 100%);
    }
    
    #pdf-canvas {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 0 auto;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .dark #pdf-canvas {
        border-color: #4b5563;
    }
</style>
@endpush

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

            <!-- PDF Viewer -->
            @if($edicao->caminho_arquivo)
            <div class="bg-white dark:bg-slate-700 rounded-lg shadow-lg overflow-hidden mb-6 wl_shadow_3 pdf-viewer-container">
                <!-- PDF Controls -->
                <div class="pdf-controls text-white px-4 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-file-pdf text-red-400 text-lg"></i>
                        <div>
                            <h3 class="font-semibold">Visualização do PDF</h3>
                            <p class="text-sm text-gray-300">Edição {{ $edicao->numero }} - {{ $edicao->data->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="zoomOut()" class="px-3 py-1 bg-gray-600 hover:bg-gray-500 rounded text-sm transition-colors">
                            <i class="fas fa-search-minus"></i>
                        </button>
                        <span id="zoom-level" class="text-sm min-w-[50px] text-center">100%</span>
                        <button onclick="zoomIn()" class="px-3 py-1 bg-gray-600 hover:bg-gray-500 rounded text-sm transition-colors">
                            <i class="fas fa-search-plus"></i>
                        </button>
                        <div class="w-px h-6 bg-gray-500"></div>
                        <a href="{{ Storage::url($edicao->caminho_arquivo) }}" target="_blank" 
                           class="px-3 py-1 bg-red-600 hover:bg-red-500 rounded text-sm transition-colors flex items-center gap-1">
                            <i class="fas fa-external-link-alt"></i>
                            <span class="hidden sm:inline">Abrir</span>
                        </a>
                        <a href="{{ Storage::url($edicao->caminho_arquivo) }}" download 
                           class="px-3 py-1 bg-green-600 hover:bg-green-500 rounded text-sm transition-colors flex items-center gap-1">
                            <i class="fas fa-download"></i>
                            <span class="hidden sm:inline">Download</span>
                        </a>
                    </div>
                </div>
                
                <!-- PDF Canvas Container -->
                <div class="relative bg-gray-100 dark:bg-gray-800" id="pdf-container">
                    <!-- Loading Indicator -->
                    <div class="flex justify-center items-center py-12" id="loading-indicator">
                        <div class="text-center">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                            <p class="text-gray-600 dark:text-gray-400">Carregando PDF...</p>
                        </div>
                    </div>
                    
                    <!-- PDF Canvas -->
                    <div class="p-4 hidden" id="canvas-container">
                        <canvas id="pdf-canvas" class="mx-auto"></canvas>
                    </div>
                    
                    <!-- Error Message -->
                    <div class="hidden py-12 text-center" id="error-message">
                        <i class="fas fa-exclamation-triangle text-4xl text-red-500 mb-4"></i>
                        <p class="text-red-600 dark:text-red-400">Erro ao carregar o PDF</p>
                        <button onclick="loadPDF()" class="mt-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded transition-colors">
                            Tentar Novamente
                        </button>
                    </div>
                </div>
                
                <!-- Navigation Controls -->
                <div class="bg-gray-50 dark:bg-slate-800 px-4 py-3 flex items-center justify-between border-t border-gray-200 dark:border-slate-600">
                    <div class="flex items-center gap-3">
                        <button onclick="prevPage()" id="prev-page" class="px-3 py-1 bg-blue-600 hover:bg-blue-500 text-white rounded text-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fas fa-chevron-left mr-1"></i>
                            Anterior
                        </button>
                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                            <span>Página</span>
                            <input type="number" id="page-input" min="1" class="w-16 px-2 py-1 text-center border rounded text-gray-900" value="1">
                            <span>de <span id="page-count">-</span></span>
                        </div>
                        <button onclick="nextPage()" id="next-page" class="px-3 py-1 bg-blue-600 hover:bg-blue-500 text-white rounded text-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            Próxima
                            <i class="fas fa-chevron-right ml-1"></i>
                        </button>
                    </div>
                    
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                        <i class="fas fa-file-pdf text-red-500"></i>
                        <span>{{ number_format($edicao->tamanho / 1024, 1) }} KB</span>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white dark:bg-slate-700 rounded-lg shadow-lg overflow-hidden mb-6 wl_shadow_3">
                <div class="p-8 text-center">
                    <i class="fas fa-file-pdf text-6xl text-red-300 dark:text-red-700 mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">PDF não disponível</h3>
                    <p class="text-gray-500 dark:text-gray-400">O arquivo PDF desta edição ainda não foi carregado no sistema.</p>
                </div>
            </div>
            @endif

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
<!-- PDF.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
</script>

<script>
// PDF.js Variables
let pdfDoc = null;
let pageNum = 1;
let pageRendering = false;
let pageNumPending = null;
let scale = 1.2;
let canvas, ctx;

// Initialize PDF viewer when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing PDF viewer...');
    
    // Check if PDF.js is loaded
    if (typeof pdfjsLib === 'undefined') {
        console.error('PDF.js not loaded');
        showError('PDF.js library not loaded');
        return;
    }
    
    // Get canvas elements
    canvas = document.getElementById('pdf-canvas');
    if (!canvas) {
        console.error('Canvas element not found');
        showError('Canvas element not found');
        return;
    }
    
    ctx = canvas.getContext('2d');
    if (!ctx) {
        console.error('Could not get canvas context');
        showError('Could not get canvas context');
        return;
    }
    
    // Load PDF
    @if($edicao->caminho_arquivo)
        loadPDF();
    @endif
});

function showError(message) {
    const loadingIndicator = document.getElementById('loading-indicator');
    const errorMessage = document.getElementById('error-message');
    
    if (loadingIndicator) loadingIndicator.classList.add('hidden');
    if (errorMessage) {
        errorMessage.classList.remove('hidden');
        const errorText = errorMessage.querySelector('p');
        if (errorText) errorText.textContent = message;
    }
}

function loadPDF() {
    console.log('Loading PDF...');
    
    const loadingIndicator = document.getElementById('loading-indicator');
    const canvasContainer = document.getElementById('canvas-container');
    const errorMessage = document.getElementById('error-message');
    
    // Show loading, hide others
    if (loadingIndicator) loadingIndicator.classList.remove('hidden');
    if (canvasContainer) canvasContainer.classList.add('hidden');
    if (errorMessage) errorMessage.classList.add('hidden');
    
    const url = '{{ $edicao->caminho_arquivo ? Storage::url($edicao->caminho_arquivo) : "" }}';
    console.log('PDF URL:', url);
    
    // Test if URL is accessible
    fetch(url, { method: 'HEAD' })
        .then(response => {
            if (!response.ok) {
                throw new Error('PDF file not accessible: ' + response.status);
            }
            console.log('PDF file is accessible, loading with PDF.js...');
            
            // Load PDF with PDF.js
            const loadingTask = pdfjsLib.getDocument({
                url: url,
                cMapUrl: 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/cmaps/',
                cMapPacked: true
            });
            
            loadingTask.promise.then(function(pdf) {
                console.log('PDF loaded successfully, pages:', pdf.numPages);
                pdfDoc = pdf;
                
                // Update UI
                const pageCountEl = document.getElementById('page-count');
                if (pageCountEl) pageCountEl.textContent = pdfDoc.numPages;
                
                // Show canvas container
                if (loadingIndicator) loadingIndicator.classList.add('hidden');
                if (canvasContainer) canvasContainer.classList.remove('hidden');
                
                // Render first page
                renderPage(pageNum);
                updateNavigation();
                
            }).catch(function(error) {
                console.error('PDF.js loading error:', error);
                showError('Erro ao carregar PDF: ' + error.message);
            });
            
        })
        .catch(function(error) {
            console.error('PDF accessibility error:', error);
            showError('Arquivo PDF não encontrado ou inacessível');
        });
}

function renderPage(num) {
    if (!pdfDoc || !canvas || !ctx) {
        console.error('PDF document or canvas not ready');
        return;
    }
    
    console.log('Rendering page:', num);
    pageRendering = true;
    
    pdfDoc.getPage(num).then(function(page) {
        const viewport = page.getViewport({ scale: scale });
        canvas.height = viewport.height;
        canvas.width = viewport.width;
        
        const renderContext = {
            canvasContext: ctx,
            viewport: viewport
        };
        
        const renderTask = page.render(renderContext);
        
        renderTask.promise.then(function() {
            console.log('Page rendered successfully');
            pageRendering = false;
            if (pageNumPending !== null) {
                renderPage(pageNumPending);
                pageNumPending = null;
            }
        }).catch(function(error) {
            console.error('Page rendering error:', error);
            pageRendering = false;
        });
    }).catch(function(error) {
        console.error('Get page error:', error);
        pageRendering = false;
    });
    
    // Update page input
    const pageInputEl = document.getElementById('page-input');
    if (pageInputEl) pageInputEl.value = num;
}

function queueRenderPage(num) {
    if (pageRendering) {
        pageNumPending = num;
    } else {
        renderPage(num);
    }
}

function prevPage() {
    if (pageNum <= 1) return;
    pageNum--;
    queueRenderPage(pageNum);
    updateNavigation();
}

function nextPage() {
    if (pageNum >= (pdfDoc?.numPages || 1)) return;
    pageNum++;
    queueRenderPage(pageNum);
    updateNavigation();
}

function updateNavigation() {
    const prevBtn = document.getElementById('prev-page');
    const nextBtn = document.getElementById('next-page');
    
    if (prevBtn) {
        prevBtn.disabled = pageNum <= 1;
        prevBtn.classList.toggle('opacity-50', pageNum <= 1);
    }
    
    if (nextBtn) {
        nextBtn.disabled = pageNum >= (pdfDoc?.numPages || 1);
        nextBtn.classList.toggle('opacity-50', pageNum >= (pdfDoc?.numPages || 1));
    }
}

function zoomIn() {
    scale += 0.2;
    const zoomLevelEl = document.getElementById('zoom-level');
    if (zoomLevelEl) zoomLevelEl.textContent = Math.round(scale * 100) + '%';
    queueRenderPage(pageNum);
}

function zoomOut() {
    if (scale > 0.4) {
        scale -= 0.2;
        const zoomLevelEl = document.getElementById('zoom-level');
        if (zoomLevelEl) zoomLevelEl.textContent = Math.round(scale * 100) + '%';
        queueRenderPage(pageNum);
    }
}

// Page input navigation
document.addEventListener('DOMContentLoaded', function() {
    const pageInput = document.getElementById('page-input');
    if (pageInput) {
        pageInput.addEventListener('change', function(e) {
            const newPage = parseInt(e.target.value);
            if (newPage >= 1 && newPage <= (pdfDoc?.numPages || 1)) {
                pageNum = newPage;
                queueRenderPage(pageNum);
                updateNavigation();
            } else {
                e.target.value = pageNum;
            }
        });
    }
});

// Copy and print functions
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
