@extends('layouts.app')

@section('title', 'Receber Notificações - Diário Oficial')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white text-center py-4">
                    <h2 class="mb-0">📧 Receber Notificações</h2>
                    <p class="mb-0 opacity-90">Mantenha-se atualizado com as publicações do Diário Oficial</p>
                </div>
                
                <div class="card-body p-5">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('portal.subscriptions.store') }}">
                        @csrf

                        <!-- Informações de Contato -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-bold">
                                    <i class="fas fa-envelope text-primary"></i> Email *
                                </label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" 
                                       required
                                       placeholder="seu@email.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-bold">
                                    <i class="fab fa-whatsapp text-success"></i> WhatsApp (opcional)
                                </label>
                                <input type="tel" 
                                       name="phone" 
                                       id="phone" 
                                       class="form-control form-control-lg @error('phone') is-invalid @enderror" 
                                       value="{{ old('phone') }}"
                                       placeholder="(11) 99999-9999">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Para receber alertas instantâneos</small>
                            </div>
                        </div>

                        <!-- Tipos de Notificação -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">🔔 O que você deseja receber?</h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="notify_new_editions" 
                                               id="notify_new_editions"
                                               {{ old('notify_new_editions') ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="notify_new_editions">
                                            📰 Novas Edições
                                        </label>
                                        <div class="text-muted small">Receba quando uma nova edição for publicada</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="notify_new_materials" 
                                               id="notify_new_materials"
                                               {{ old('notify_new_materials') ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="notify_new_materials">
                                            📄 Novas Matérias
                                        </label>
                                        <div class="text-muted small">Receba quando novos atos forem publicados</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros por Tipo -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">🏷️ Tipos de Atos (opcional)</h5>
                            <p class="text-muted small mb-3">Deixe em branco para receber todos os tipos</p>
                            
                            <div class="row">
                                @foreach(\App\Models\Tipo::all() as $tipo)
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="notify_types[]" 
                                               value="{{ $tipo->id }}" 
                                               id="tipo_{{ $tipo->id }}"
                                               {{ in_array($tipo->id, old('notify_types', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="tipo_{{ $tipo->id }}">
                                            {{ $tipo->nome }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Filtros por Órgão -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">🏢 Órgãos (opcional)</h5>
                            <p class="text-muted small mb-3">Deixe em branco para receber de todos os órgãos</p>
                            
                            <div class="row">
                                @foreach(\App\Models\Orgao::all() as $orgao)
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="notify_organs[]" 
                                               value="{{ $orgao->id }}" 
                                               id="orgao_{{ $orgao->id }}"
                                               {{ in_array($orgao->id, old('notify_organs', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="orgao_{{ $orgao->id }}">
                                            {{ $orgao->sigla }} - {{ $orgao->nome }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Palavras-chave -->
                        <div class="mb-4">
                            <label for="keywords" class="form-label fw-bold">
                                <i class="fas fa-search text-warning"></i> Palavras-chave (opcional)
                            </label>
                            <textarea name="keywords" 
                                      id="keywords" 
                                      class="form-control @error('keywords') is-invalid @enderror" 
                                      rows="3"
                                      placeholder="Ex: licitação, concurso, nomeação (separe por vírgulas)">{{ old('keywords') }}</textarea>
                            @error('keywords')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Receba notificações apenas de publicações que contenham essas palavras</small>
                        </div>

                        <!-- Termos e Condições -->
                        <div class="mb-4">
                            <div class="alert alert-info">
                                <h6 class="fw-bold">📋 Informações Importantes:</h6>
                                <ul class="mb-0 small">
                                    <li>Você receberá um email de confirmação</li>
                                    <li>Pode cancelar a qualquer momento</li>
                                    <li>Seus dados são protegidos conforme a LGPD</li>
                                    <li>Máximo de 5 notificações por dia por email</li>
                                    <li>WhatsApp: máximo 2 alertas urgentes por dia</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-bell"></i> Cadastrar Notificações
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Links úteis -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title">🔧 Já tem notificações?</h5>
                            <p class="card-text">Gerencie suas preferências</p>
                            <form method="POST" action="{{ route('portal.subscriptions.request') }}" class="d-inline">
                                @csrf
                                <div class="input-group mb-3">
                                    <input type="email" 
                                           name="email" 
                                           class="form-control" 
                                           placeholder="Seu email"
                                           required>
                                    <button type="submit" class="btn btn-outline-primary">
                                        Enviar Link
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title">📧 Problemas com verificação?</h5>
                            <p class="card-text">Reenvie o email de confirmação</p>
                            <form method="POST" action="{{ route('portal.subscriptions.resend') }}" class="d-inline">
                                @csrf
                                <div class="input-group mb-3">
                                    <input type="email" 
                                           name="email" 
                                           class="form-control" 
                                           placeholder="Seu email"
                                           required>
                                    <button type="submit" class="btn btn-outline-warning">
                                        Reenviar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
}

.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Máscara para telefone
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
            e.target.value = value;
        });
    }

    // Validação de email em tempo real
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('blur', function(e) {
            const email = e.target.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                e.target.classList.add('is-invalid');
                e.target.nextElementSibling?.remove();
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                feedback.textContent = 'Por favor, insira um email válido';
                e.target.parentNode.appendChild(feedback);
            } else {
                e.target.classList.remove('is-invalid');
                e.target.nextElementSibling?.remove();
            }
        });
    }
});
</script>
@endpush
@endsection
