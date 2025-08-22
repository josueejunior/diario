@extends('layouts.adminlte')

@section('title', 'Configurações WhatsApp')
@section('page-title', 'Configurações WhatsApp')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item">Configurações</li>
<li class="breadcrumb-item active">WhatsApp</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fab fa-whatsapp mr-2"></i>
                    Configurações WhatsApp API
                </h3>
            </div>
            
            <form action="{{ route('admin.configuracoes.whatsapp.update') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="whatsapp_ativo" name="whatsapp_ativo" value="1"
                                   {{ old('whatsapp_ativo', $configuracoes['whatsapp_ativo'] ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="whatsapp_ativo">
                                <strong>Ativar Notificações via WhatsApp</strong>
                            </label>
                        </div>
                        <small class="form-text text-muted">
                            Ative esta opção para permitir o envio de notificações via WhatsApp.
                        </small>
                    </div>

                    <hr>

                    <h5>Configurações da API</h5>

                    <div class="form-group">
                        <label for="whatsapp_api_url">URL da API WhatsApp</label>
                        <input type="url" class="form-control @error('whatsapp_api_url') is-invalid @enderror" 
                               id="whatsapp_api_url" name="whatsapp_api_url" 
                               value="{{ old('whatsapp_api_url', $configuracoes['whatsapp_api_url'] ?? '') }}" 
                               placeholder="https://api.whatsapp.com/send">
                        @error('whatsapp_api_url')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            URL base da API WhatsApp que será utilizada para envio de mensagens.
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="whatsapp_api_token">Token da API</label>
                        <input type="password" class="form-control @error('whatsapp_api_token') is-invalid @enderror" 
                               id="whatsapp_api_token" name="whatsapp_api_token" 
                               value="{{ old('whatsapp_api_token', $configuracoes['whatsapp_api_token'] ?? '') }}" 
                               placeholder="••••••••••••••••••••">
                        @error('whatsapp_api_token')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            Token de autenticação fornecido pelo provedor da API WhatsApp.
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="whatsapp_numero_remetente">Número do Remetente</label>
                        <input type="text" class="form-control @error('whatsapp_numero_remetente') is-invalid @enderror" 
                               id="whatsapp_numero_remetente" name="whatsapp_numero_remetente" 
                               value="{{ old('whatsapp_numero_remetente', $configuracoes['whatsapp_numero_remetente'] ?? '') }}" 
                               placeholder="5511999999999">
                        @error('whatsapp_numero_remetente')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            Número do WhatsApp que será usado como remetente. Formato: código do país + DDD + número (sem espaços ou símbolos).
                        </small>
                    </div>

                    <hr>

                    <h5>Configurações Avançadas</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="whatsapp_timeout">Timeout (segundos)</label>
                                <input type="number" class="form-control @error('whatsapp_timeout') is-invalid @enderror" 
                                       id="whatsapp_timeout" name="whatsapp_timeout" 
                                       value="{{ old('whatsapp_timeout', $configuracoes['whatsapp_timeout'] ?? '30') }}" 
                                       min="5" max="300">
                                @error('whatsapp_timeout')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="whatsapp_max_tentativas">Máximo de Tentativas</label>
                                <input type="number" class="form-control @error('whatsapp_max_tentativas') is-invalid @enderror" 
                                       id="whatsapp_max_tentativas" name="whatsapp_max_tentativas" 
                                       value="{{ old('whatsapp_max_tentativas', $configuracoes['whatsapp_max_tentativas'] ?? '3') }}" 
                                       min="1" max="10">
                                @error('whatsapp_max_tentativas')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="whatsapp_mensagem_padrao">Mensagem Padrão</label>
                        <textarea class="form-control @error('whatsapp_mensagem_padrao') is-invalid @enderror" 
                                  id="whatsapp_mensagem_padrao" name="whatsapp_mensagem_padrao" rows="4"
                                  placeholder="Nova publicação no Diário Oficial: {titulo}">{{ old('whatsapp_mensagem_padrao', $configuracoes['whatsapp_mensagem_padrao'] ?? 'Nova publicação no Diário Oficial: {titulo}') }}</textarea>
                        @error('whatsapp_mensagem_padrao')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            Mensagem padrão que será enviada. Use {titulo}, {data}, {link} como variáveis.
                        </small>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save mr-1"></i>
                        Salvar Configurações
                    </button>
                    <a href="{{ route('admin.configuracoes.geral') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Voltar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Teste WhatsApp
                </h3>
            </div>
            
            <form action="{{ route('admin.configuracoes.whatsapp.test') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="numero_teste">Número de Teste</label>
                        <input type="text" class="form-control @error('numero_teste') is-invalid @enderror" 
                               id="numero_teste" name="numero_teste" 
                               value="{{ old('numero_teste') }}" 
                               required placeholder="5511999999999">
                        @error('numero_teste')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            Número para envio da mensagem de teste (formato internacional).
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="mensagem_teste">Mensagem de Teste</label>
                        <textarea class="form-control" id="mensagem_teste" name="mensagem_teste" rows="3"
                                  placeholder="Esta é uma mensagem de teste do sistema Diário Oficial.">{{ old('mensagem_teste', 'Esta é uma mensagem de teste do sistema Diário Oficial.') }}</textarea>
                    </div>
                </div>
                
                <div class="card-footer">
                    <button type="submit" class="btn btn-info btn-block">
                        <i class="fab fa-whatsapp mr-1"></i>
                        Enviar Teste
                    </button>
                </div>
            </form>
        </div>

        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Importante
                </h3>
            </div>
            <div class="card-body">
                <p class="text-sm">
                    <strong>Para usar o WhatsApp API, você precisa:</strong>
                </p>
                <ul class="text-sm">
                    <li>Uma conta WhatsApp Business verificada</li>
                    <li>Acesso à API do WhatsApp Business</li>
                    <li>Um provedor de API certificado pelo WhatsApp</li>
                    <li>Token de autenticação válido</li>
                </ul>
                
                <hr>
                
                <p class="text-sm">
                    <strong>Provedores Recomendados:</strong>
                </p>
                <ul class="text-sm">
                    <li>Twilio</li>
                    <li>360Dialog</li>
                    <li>MessageBird</li>
                    <li>Chat-API</li>
                </ul>
            </div>
        </div>

        <div class="card card-light">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-code mr-2"></i>
                    Variáveis Disponíveis
                </h3>
            </div>
            <div class="card-body">
                <p class="text-sm">Use estas variáveis na mensagem padrão:</p>
                <ul class="text-sm">
                    <li><code>{titulo}</code> - Título da matéria</li>
                    <li><code>{data}</code> - Data de publicação</li>
                    <li><code>{link}</code> - Link para visualização</li>
                    <li><code>{orgao}</code> - Órgão responsável</li>
                    <li><code>{tipo}</code> - Tipo da matéria</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Máscara para número de telefone
    $('#whatsapp_numero_remetente, #numero_teste').mask('00000000000000', {
        placeholder: '5511999999999',
        translation: {
            '0': {pattern: /[0-9]/}
        }
    });
    
    // Mostrar/ocultar senha do token
    $('.show-password').click(function() {
        var input = $(this).siblings('input');
        var icon = $(this).find('i');
        
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
});
</script>
@endpush
