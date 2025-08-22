@props(['edicao'])

<div class="bg-white dark:bg-slate-700 rounded-lg shadow-lg overflow-hidden border border-gray-200 dark:border-slate-600">
    <!-- Header -->
    <div class="bg-green-600 dark:bg-green-700 px-4 py-3">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-white/20 rounded-lg">
                <i class="fas fa-shield-check text-white text-lg"></i>
            </div>
            <div>
                <h3 class="font-semibold text-white">Verificação de Autenticidade</h3>
                <p class="text-green-100 text-sm">Documento assinado digitalmente</p>
            </div>
        </div>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- QR Code -->
            <div class="text-center">
                <div class="inline-block p-4 bg-white border-2 border-gray-300 rounded-lg">
                    <div id="qr-code-{{ $edicao->id }}" class="qr-code-container"></div>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-3">
                    Escaneie para verificar autenticidade
                </p>
            </div>

            <!-- Informações da Assinatura -->
            <div class="space-y-4">
                @if($edicao->assinatura)
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Signatário
                    </label>
                    <p class="text-gray-900 dark:text-white font-medium">
                        {{ $edicao->assinatura->signatario }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Autoridade Certificadora
                    </label>
                    <p class="text-gray-900 dark:text-white">
                        {{ $edicao->assinatura->ac }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Hash SHA-256
                    </label>
                    <p class="text-xs font-mono bg-gray-100 dark:bg-gray-800 p-2 rounded border break-all">
                        {{ $edicao->assinatura->hash }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Carimbo de Tempo
                    </label>
                    <p class="text-gray-900 dark:text-white">
                        {{ $edicao->assinatura->carimbo_tempo->format('d/m/Y H:i:s T') }}
                    </p>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-2xl mb-2"></i>
                    <p class="text-gray-600 dark:text-gray-400">
                        Assinatura digital não encontrada
                    </p>
                </div>
                @endif
            </div>
        </div>

        <!-- Ações -->
        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-600">
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('portal.verificar') }}" 
                   class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-center transition-colors">
                    <i class="fas fa-search mr-2"></i>
                    Verificar Documento
                </a>
                
                @if($edicao->caminho_arquivo)
                <a href="{{ Storage::url($edicao->caminho_arquivo) }}" 
                   download
                   class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-center transition-colors">
                    <i class="fas fa-download mr-2"></i>
                    Download PDF
                </a>
                @endif

                <button onclick="copyVerificationUrl({{ $edicao->id }})" 
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-center transition-colors">
                    <i class="fas fa-link mr-2"></i>
                    Copiar Link
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- QR Code Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode/1.5.3/qrcode.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gerar QR Code para verificação
    const verificationUrl = '{{ route("portal.verificar") }}?hash={{ $edicao->assinatura->hash ?? "" }}';
    
    QRCode.toCanvas(document.getElementById('qr-code-{{ $edicao->id }}'), verificationUrl, {
        width: 128,
        height: 128,
        colorDark: '#000000',
        colorLight: '#ffffff',
        margin: 2
    }, function (error) {
        if (error) {
            console.error('Erro ao gerar QR Code:', error);
            document.getElementById('qr-code-{{ $edicao->id }}').innerHTML = 
                '<div class="text-red-500 text-sm">Erro ao gerar QR Code</div>';
        }
    });
});

function copyVerificationUrl(edicaoId) {
    const url = '{{ route("portal.verificar") }}?hash={{ $edicao->assinatura->hash ?? "" }}';
    navigator.clipboard.writeText(url).then(function() {
        // Feedback visual
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check mr-2"></i>Copiado!';
        btn.classList.add('bg-green-700');
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('bg-green-700');
        }, 2000);
    }).catch(function(err) {
        console.error('Erro ao copiar:', err);
        alert('Erro ao copiar link. Tente novamente.');
    });
}
</script>
@endpush
