@props(['pdfUrl', 'edicao'])

<div class="pdf-preview-container bg-gray-50 dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden mx-auto max-w-md hover:border-blue-400 dark:hover:border-blue-500 transition-colors">
    <div class="relative">
        <!-- PDF Preview Canvas -->
        <canvas id="pdf-canvas-{{ $edicao->id }}" class="w-full h-auto max-h-96 object-cover"></canvas>
        
        <!-- Loading overlay -->
        <div id="pdf-loading-{{ $edicao->id }}" class="absolute inset-0 bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
            <div class="text-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-2"></div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Carregando PDF...</p>
            </div>
        </div>
        
        <!-- Error state -->
        <div id="pdf-error-{{ $edicao->id }}" class="absolute inset-0 bg-gray-100 dark:bg-gray-700 flex-col items-center justify-center p-6 hidden">
            <i class="fas fa-file-pdf text-4xl text-red-500 mb-3"></i>
            <p class="text-sm text-gray-600 dark:text-gray-400 text-center mb-3">Não foi possível carregar o preview</p>
            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">
                    Edição {{ $edicao->numero }}
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-3">
                    {{ $edicao->data->locale('pt_BR')->isoFormat('DD [de] MMMM [de] YYYY') }}
                </p>
                <div class="flex items-center justify-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                    <span class="flex items-center gap-1">
                        <i class="fas fa-eye"></i>
                        {{ $edicao->visualizacoes_count ?? 0 }}
                    </span>
                    <span class="flex items-center gap-1">
                        <i class="fas fa-download"></i>
                        {{ $edicao->downloads_count ?? 0 }}
                    </span>
                </div>
                <div class="mt-4 px-4 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-full text-sm font-medium">
                    <i class="fas fa-arrow-right mr-1"></i>
                    Clique para visualizar PDF
                </div>
            </div>
        </div>
        
        <!-- Overlay with edition info -->
        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
            <div class="text-white text-center">
                <h3 class="text-sm font-semibold mb-1">Edição {{ $edicao->numero }}</h3>
                <p class="text-xs opacity-90">{{ $edicao->data->format('d/m/Y') }}</p>
                <div class="flex items-center justify-center gap-3 text-xs mt-2">
                    <span class="flex items-center gap-1">
                        <i class="fas fa-eye"></i>
                        {{ $edicao->visualizacoes_count ?? 0 }}
                    </span>
                    <span class="flex items-center gap-1">
                        <i class="fas fa-download"></i>
                        {{ $edicao->downloads_count ?? 0 }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadPdfPreview('{{ $pdfUrl }}', {{ $edicao->id }});
});

function loadPdfPreview(pdfUrl, edicaoId) {
    const canvas = document.getElementById(`pdf-canvas-${edicaoId}`);
    const loading = document.getElementById(`pdf-loading-${edicaoId}`);
    const error = document.getElementById(`pdf-error-${edicaoId}`);
    
    // Verifica se PDF.js está disponível
    if (typeof pdfjsLib === 'undefined') {
        console.log('PDF.js não está carregado, carregando dinamicamente...');
        
        // Carrega PDF.js dinamicamente
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js';
        script.onload = function() {
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
            renderPdf(pdfUrl, canvas, loading, error);
        };
        script.onerror = function() {
            showError(loading, error);
        };
        document.head.appendChild(script);
    } else {
        renderPdf(pdfUrl, canvas, loading, error);
    }
}

function renderPdf(pdfUrl, canvas, loading, error) {
    pdfjsLib.getDocument(pdfUrl).promise.then(function(pdf) {
        // Pega a primeira página
        pdf.getPage(1).then(function(page) {
            const viewport = page.getViewport({ scale: 1 });
            const context = canvas.getContext('2d');
            
            // Calcula escala para caber no container
            const containerWidth = canvas.parentElement.clientWidth;
            const scale = Math.min(containerWidth / viewport.width, 400 / viewport.height);
            const scaledViewport = page.getViewport({ scale: scale });
            
            canvas.height = scaledViewport.height;
            canvas.width = scaledViewport.width;
            
            const renderContext = {
                canvasContext: context,
                viewport: scaledViewport
            };
            
            page.render(renderContext).promise.then(function() {
                loading.style.display = 'none';
            });
        });
    }).catch(function(error) {
        console.error('Erro ao carregar PDF:', error);
        showError(loading, error);
    });
}

function showError(loading, error) {
    loading.style.display = 'none';
    error.classList.remove('hidden');
    error.classList.add('flex');
}
</script>
