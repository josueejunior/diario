@extends('layouts.adminlte')

@section('title', 'Configurações de E-mail')
@section('page-title', 'Configurações de E-mail')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item">Configurações</li>
<li class="breadcrumb-item active">E-mail</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-envelope mr-2"></i>
                    Configurações de E-mail
                </h3>
            </div>
            
            <form action="{{ route('admin.configuracoes.email.update') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="mail_mailer">Provedor de E-mail</label>
                        <select class="form-control @error('mail_mailer') is-invalid @enderror" 
                                id="mail_mailer" name="mail_mailer" required>
                            <option value="smtp" {{ old('mail_mailer', $configuracoes['mail_mailer'] ?? 'smtp') == 'smtp' ? 'selected' : '' }}>
                                SMTP
                            </option>
                            <option value="sendmail" {{ old('mail_mailer', $configuracoes['mail_mailer'] ?? '') == 'sendmail' ? 'selected' : '' }}>
                                Sendmail
                            </option>
                            <option value="mailgun" {{ old('mail_mailer', $configuracoes['mail_mailer'] ?? '') == 'mailgun' ? 'selected' : '' }}>
                                Mailgun
                            </option>
                            <option value="ses" {{ old('mail_mailer', $configuracoes['mail_mailer'] ?? '') == 'ses' ? 'selected' : '' }}>
                                Amazon SES
                            </option>
                            <option value="postmark" {{ old('mail_mailer', $configuracoes['mail_mailer'] ?? '') == 'postmark' ? 'selected' : '' }}>
                                Postmark
                            </option>
                        </select>
                        @error('mail_mailer')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div id="smtp-config" style="{{ old('mail_mailer', $configuracoes['mail_mailer'] ?? 'smtp') == 'smtp' ? '' : 'display:none;' }}">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="mail_host">Servidor SMTP</label>
                                    <input type="text" class="form-control @error('mail_host') is-invalid @enderror" 
                                           id="mail_host" name="mail_host" 
                                           value="{{ old('mail_host', $configuracoes['mail_host'] ?? '') }}" 
                                           placeholder="smtp.gmail.com">
                                    @error('mail_host')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="mail_port">Porta</label>
                                    <input type="number" class="form-control @error('mail_port') is-invalid @enderror" 
                                           id="mail_port" name="mail_port" 
                                           value="{{ old('mail_port', $configuracoes['mail_port'] ?? '587') }}" 
                                           placeholder="587" min="1" max="65535">
                                    @error('mail_port')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mail_username">Usuário</label>
                                    <input type="text" class="form-control @error('mail_username') is-invalid @enderror" 
                                           id="mail_username" name="mail_username" 
                                           value="{{ old('mail_username', $configuracoes['mail_username'] ?? '') }}" 
                                           placeholder="usuario@dominio.com">
                                    @error('mail_username')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mail_password">Senha</label>
                                    <input type="password" class="form-control @error('mail_password') is-invalid @enderror" 
                                           id="mail_password" name="mail_password" 
                                           value="{{ old('mail_password', $configuracoes['mail_password'] ?? '') }}" 
                                           placeholder="••••••••">
                                    @error('mail_password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="mail_encryption">Criptografia</label>
                            <select class="form-control @error('mail_encryption') is-invalid @enderror" 
                                    id="mail_encryption" name="mail_encryption">
                                <option value="">Nenhuma</option>
                                <option value="tls" {{ old('mail_encryption', $configuracoes['mail_encryption'] ?? 'tls') == 'tls' ? 'selected' : '' }}>
                                    TLS
                                </option>
                                <option value="ssl" {{ old('mail_encryption', $configuracoes['mail_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>
                                    SSL
                                </option>
                            </select>
                            @error('mail_encryption')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <h5>Configurações do Remetente</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mail_from_address">E-mail do Remetente</label>
                                <input type="email" class="form-control @error('mail_from_address') is-invalid @enderror" 
                                       id="mail_from_address" name="mail_from_address" 
                                       value="{{ old('mail_from_address', $configuracoes['mail_from_address'] ?? '') }}" 
                                       required placeholder="noreply@dominio.com">
                                @error('mail_from_address')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mail_from_name">Nome do Remetente</label>
                                <input type="text" class="form-control @error('mail_from_name') is-invalid @enderror" 
                                       id="mail_from_name" name="mail_from_name" 
                                       value="{{ old('mail_from_name', $configuracoes['mail_from_name'] ?? '') }}" 
                                       required placeholder="Diário Oficial">
                                @error('mail_from_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
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
                    Teste de E-mail
                </h3>
            </div>
            
            <form action="{{ route('admin.configuracoes.email.test') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="email_teste">E-mail de Teste</label>
                        <input type="email" class="form-control @error('email_teste') is-invalid @enderror" 
                               id="email_teste" name="email_teste" 
                               value="{{ old('email_teste', auth()->user()->email) }}" 
                               required placeholder="teste@dominio.com">
                        @error('email_teste')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            Um e-mail de teste será enviado para este endereço.
                        </small>
                    </div>
                </div>
                
                <div class="card-footer">
                    <button type="submit" class="btn btn-info btn-block">
                        <i class="fas fa-paper-plane mr-1"></i>
                        Enviar Teste
                    </button>
                </div>
            </form>
        </div>

        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-2"></i>
                    Configurações Comuns
                </h3>
            </div>
            <div class="card-body">
                <h6>Gmail</h6>
                <ul class="list-unstyled mb-3">
                    <li><strong>Servidor:</strong> smtp.gmail.com</li>
                    <li><strong>Porta:</strong> 587</li>
                    <li><strong>Criptografia:</strong> TLS</li>
                </ul>

                <h6>Outlook/Hotmail</h6>
                <ul class="list-unstyled mb-3">
                    <li><strong>Servidor:</strong> smtp-mail.outlook.com</li>
                    <li><strong>Porta:</strong> 587</li>
                    <li><strong>Criptografia:</strong> TLS</li>
                </ul>

                <h6>Yahoo</h6>
                <ul class="list-unstyled">
                    <li><strong>Servidor:</strong> smtp.mail.yahoo.com</li>
                    <li><strong>Porta:</strong> 587</li>
                    <li><strong>Criptografia:</strong> TLS</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#mail_mailer').change(function() {
        if ($(this).val() === 'smtp') {
            $('#smtp-config').show();
        } else {
            $('#smtp-config').hide();
        }
    });
});
</script>
@endpush
