@extends('layouts.adminlte')

@section('title', 'Ajustes Gerais')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1><i class="fas fa-cog mr-2"></i>Ajustes Gerais</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Ajustes Gerais</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <form id="formAjustesGerais">
        @csrf
        <div class="row">
            <!-- Sistema -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="card-title">
                            <i class="fas fa-cogs mr-1"></i>
                            Configurações do Sistema
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nome_sistema">Nome do Sistema:</label>
                            <input type="text" class="form-control" name="nome_sistema" id="nome_sistema" value="{{ $configuracoes['nome_sistema'] ?? 'Diário Oficial' }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="descricao_sistema">Descrição:</label>
                            <textarea class="form-control" name="descricao_sistema" id="descricao_sistema" rows="3">{{ $configuracoes['descricao_sistema'] ?? '' }}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="versao_sistema">Versão:</label>
                            <input type="text" class="form-control" name="versao_sistema" id="versao_sistema" value="{{ $configuracoes['versao_sistema'] ?? '1.0.0' }}" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="timezone">Fuso Horário:</label>
                            <select class="form-control" name="timezone" id="timezone">
                                <option value="America/Sao_Paulo" {{ ($configuracoes['timezone'] ?? '') == 'America/Sao_Paulo' ? 'selected' : '' }}>Brasília (UTC-3)</option>
                                <option value="America/Manaus" {{ ($configuracoes['timezone'] ?? '') == 'America/Manaus' ? 'selected' : '' }}>Manaus (UTC-4)</option>
                                <option value="America/Rio_Branco" {{ ($configuracoes['timezone'] ?? '') == 'America/Rio_Branco' ? 'selected' : '' }}>Rio Branco (UTC-5)</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="idioma">Idioma:</label>
                            <select class="form-control" name="idioma" id="idioma">
                                <option value="pt_BR" {{ ($configuracoes['idioma'] ?? '') == 'pt_BR' ? 'selected' : '' }}>Português (Brasil)</option>
                                <option value="en_US" {{ ($configuracoes['idioma'] ?? '') == 'en_US' ? 'selected' : '' }}>English (US)</option>
                                <option value="es_ES" {{ ($configuracoes['idioma'] ?? '') == 'es_ES' ? 'selected' : '' }}>Español</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="modo_manutencao" id="modo_manutencao" value="1" {{ ($configuracoes['modo_manutencao'] ?? false) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="modo_manutencao">Modo Manutenção</label>
                            </div>
                            <small class="text-muted">Quando ativo, apenas administradores podem acessar o sistema</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Segurança -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-danger">
                        <h3 class="card-title">
                            <i class="fas fa-shield-alt mr-1"></i>
                            Configurações de Segurança
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="tentativas_login">Tentativas de Login:</label>
                            <input type="number" class="form-control" name="tentativas_login" id="tentativas_login" value="{{ $configuracoes['tentativas_login'] ?? 5 }}" min="3" max="10">
                            <small class="text-muted">Máximo de tentativas antes do bloqueio</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="tempo_bloqueio">Tempo de Bloqueio (minutos):</label>
                            <input type="number" class="form-control" name="tempo_bloqueio" id="tempo_bloqueio" value="{{ $configuracoes['tempo_bloqueio'] ?? 15 }}" min="5" max="60">
                        </div>
                        
                        <div class="form-group">
                            <label for="sessao_timeout">Timeout da Sessão (minutos):</label>
                            <input type="number" class="form-control" name="sessao_timeout" id="sessao_timeout" value="{{ $configuracoes['sessao_timeout'] ?? 120 }}" min="30" max="480">
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="forcar_https" id="forcar_https" value="1" {{ ($configuracoes['forcar_https'] ?? false) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="forcar_https">Forçar HTTPS</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="auditoria_ativa" id="auditoria_ativa" value="1" {{ ($configuracoes['auditoria_ativa'] ?? true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="auditoria_ativa">Auditoria Ativa</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="two_factor_auth" id="two_factor_auth" value="1" {{ ($configuracoes['two_factor_auth'] ?? false) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="two_factor_auth">Autenticação de Dois Fatores</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Performance -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success">
                        <h3 class="card-title">
                            <i class="fas fa-tachometer-alt mr-1"></i>
                            Performance e Cache
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="cache_timeout">Timeout do Cache (minutos):</label>
                            <input type="number" class="form-control" name="cache_timeout" id="cache_timeout" value="{{ $configuracoes['cache_timeout'] ?? 60 }}" min="5" max="1440">
                        </div>
                        
                        <div class="form-group">
                            <label for="max_upload_size">Tamanho Máximo de Upload (MB):</label>
                            <input type="number" class="form-control" name="max_upload_size" id="max_upload_size" value="{{ $configuracoes['max_upload_size'] ?? 10 }}" min="1" max="100">
                        </div>
                        
                        <div class="form-group">
                            <label for="max_execution_time">Tempo Máximo de Execução (segundos):</label>
                            <input type="number" class="form-control" name="max_execution_time" id="max_execution_time" value="{{ $configuracoes['max_execution_time'] ?? 30 }}" min="10" max="300">
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="cache_ativo" id="cache_ativo" value="1" {{ ($configuracoes['cache_ativo'] ?? true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="cache_ativo">Cache Ativo</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="compressao_gzip" id="compressao_gzip" value="1" {{ ($configuracoes['compressao_gzip'] ?? true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="compressao_gzip">Compressão GZip</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="button" class="btn btn-warning btn-block" onclick="limparCache()">
                                <i class="fas fa-broom"></i> Limpar Cache
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Logs e Backup -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info">
                        <h3 class="card-title">
                            <i class="fas fa-database mr-1"></i>
                            Logs e Backup
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nivel_log">Nível de Log:</label>
                            <select class="form-control" name="nivel_log" id="nivel_log">
                                <option value="debug" {{ ($configuracoes['nivel_log'] ?? '') == 'debug' ? 'selected' : '' }}>Debug</option>
                                <option value="info" {{ ($configuracoes['nivel_log'] ?? '') == 'info' ? 'selected' : '' }}>Info</option>
                                <option value="notice" {{ ($configuracoes['nivel_log'] ?? '') == 'notice' ? 'selected' : '' }}>Notice</option>
                                <option value="warning" {{ ($configuracoes['nivel_log'] ?? '') == 'warning' ? 'selected' : '' }}>Warning</option>
                                <option value="error" {{ ($configuracoes['nivel_log'] ?? '') == 'error' ? 'selected' : '' }}>Error</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="retencao_logs">Retenção de Logs (dias):</label>
                            <input type="number" class="form-control" name="retencao_logs" id="retencao_logs" value="{{ $configuracoes['retencao_logs'] ?? 30 }}" min="7" max="365">
                        </div>
                        
                        <div class="form-group">
                            <label for="backup_automatico">Backup Automático:</label>
                            <select class="form-control" name="backup_automatico" id="backup_automatico">
                                <option value="diario" {{ ($configuracoes['backup_automatico'] ?? '') == 'diario' ? 'selected' : '' }}>Diário</option>
                                <option value="semanal" {{ ($configuracoes['backup_automatico'] ?? '') == 'semanal' ? 'selected' : '' }}>Semanal</option>
                                <option value="mensal" {{ ($configuracoes['backup_automatico'] ?? '') == 'mensal' ? 'selected' : '' }}>Mensal</option>
                                <option value="desabilitado" {{ ($configuracoes['backup_automatico'] ?? '') == 'desabilitado' ? 'selected' : '' }}>Desabilitado</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="retencao_backups">Retenção de Backups (dias):</label>
                            <input type="number" class="form-control" name="retencao_backups" id="retencao_backups" value="{{ $configuracoes['retencao_backups'] ?? 90 }}" min="7" max="365">
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="logs_ativos" id="logs_ativos" value="1" {{ ($configuracoes['logs_ativos'] ?? true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="logs_ativos">Logs Ativos</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="button" class="btn btn-info btn-block" onclick="criarBackup()">
                                <i class="fas fa-download"></i> Criar Backup Agora
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Notificações -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-warning">
                        <h3 class="card-title">
                            <i class="fas fa-bell mr-1"></i>
                            Notificações
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="email_admin">E-mail do Administrador:</label>
                            <input type="email" class="form-control" name="email_admin" id="email_admin" value="{{ $configuracoes['email_admin'] ?? '' }}">
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="notif_novos_usuarios" id="notif_novos_usuarios" value="1" {{ ($configuracoes['notif_novos_usuarios'] ?? true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="notif_novos_usuarios">Novos Usuários</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="notif_erros_sistema" id="notif_erros_sistema" value="1" {{ ($configuracoes['notif_erros_sistema'] ?? true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="notif_erros_sistema">Erros do Sistema</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="notif_backup_completo" id="notif_backup_completo" value="1" {{ ($configuracoes['notif_backup_completo'] ?? true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="notif_backup_completo">Backup Completo</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="notif_edicoes_publicadas" id="notif_edicoes_publicadas" value="1" {{ ($configuracoes['notif_edicoes_publicadas'] ?? true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="notif_edicoes_publicadas">Edições Publicadas</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Informações do Sistema -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-secondary">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle mr-1"></i>
                            Informações do Sistema
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="info-box">
                            <span class="info-box-icon bg-primary"><i class="fas fa-server"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Servidor</span>
                                <span class="info-box-number">{{ php_uname('s') }} {{ php_uname('r') }}</span>
                            </div>
                        </div>
                        
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-code"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">PHP</span>
                                <span class="info-box-number">{{ PHP_VERSION }}</span>
                            </div>
                        </div>
                        
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-database"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Database</span>
                                <span class="info-box-number">{{ $db_version ?? 'N/A' }}</span>
                            </div>
                        </div>
                        
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-memory"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Memória</span>
                                <span class="info-box-number">{{ ini_get('memory_limit') }}</span>
                            </div>
                        </div>
                        
                        <div class="form-group mt-3">
                            <button type="button" class="btn btn-secondary btn-block" onclick="verificarAtualizacoes()">
                                <i class="fas fa-sync"></i> Verificar Atualizações
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Botões de Ação -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="button" class="btn btn-secondary" onclick="resetarConfiguracoes()">
                                    <i class="fas fa-undo"></i> Resetar Configurações
                                </button>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Salvar Configurações
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Form ajustes gerais
    $('#formAjustesGerais').on('submit', function(e) {
        e.preventDefault();
        
        // Mostrar loading
        Swal.fire({
            title: 'Salvando...',
            text: 'Por favor, aguarde.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        $.ajax({
            url: '{{ route("admin.ajustes-gerais.update") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                Swal.close();
                if(response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: response.message,
                        timer: 3000
                    });
                }
            },
            error: function(xhr) {
                Swal.close();
                let message = 'Erro ao salvar configurações.';
                if(xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: message
                });
            }
        });
    });
    
    // Alertas para configurações críticas
    $('#modo_manutencao').change(function() {
        if($(this).is(':checked')) {
            Swal.fire({
                icon: 'warning',
                title: 'Atenção!',
                text: 'O modo manutenção bloqueará o acesso de todos os usuários, exceto administradores.',
                confirmButtonText: 'Entendi'
            });
        }
    });
    
    $('#forcar_https').change(function() {
        if($(this).is(':checked')) {
            Swal.fire({
                icon: 'info',
                title: 'HTTPS',
                text: 'Certifique-se de que o servidor possui certificado SSL válido.',
                confirmButtonText: 'OK'
            });
        }
    });
});

function limparCache() {
    Swal.fire({
        title: 'Limpar Cache?',
        text: 'Esta ação pode afetar temporariamente a performance do sistema.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, limpar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/admin/cache/clear',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Cache Limpo!',
                        text: 'O cache foi limpo com sucesso.',
                        timer: 3000
                    });
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: 'Erro ao limpar cache.'
                    });
                }
            });
        }
    });
}

function criarBackup() {
    Swal.fire({
        title: 'Criar Backup?',
        text: 'Esta operação pode demorar alguns minutos.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, criar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Criando Backup...',
                text: 'Por favor, aguarde.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            $.ajax({
                url: '/admin/backup/create',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.close();
                    Swal.fire({
                        icon: 'success',
                        title: 'Backup Criado!',
                        text: 'O backup foi criado com sucesso.',
                        timer: 3000
                    });
                },
                error: function() {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: 'Erro ao criar backup.'
                    });
                }
            });
        }
    });
}

function resetarConfiguracoes() {
    Swal.fire({
        title: 'Resetar Configurações?',
        text: 'Esta ação restaurará todas as configurações para os valores padrão!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, resetar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/admin/ajustes-gerais/reset',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Resetado!',
                        text: 'As configurações foram restauradas.',
                        timer: 3000
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: 'Erro ao resetar configurações.'
                    });
                }
            });
        }
    });
}

function verificarAtualizacoes() {
    Swal.fire({
        title: 'Verificando...',
        text: 'Verificando atualizações disponíveis.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    $.ajax({
        url: '/admin/sistema/check-updates',
        method: 'GET',
        success: function(response) {
            Swal.close();
            if(response.updates_available) {
                Swal.fire({
                    icon: 'info',
                    title: 'Atualizações Disponíveis',
                    text: `Versão ${response.latest_version} disponível.`,
                    confirmButtonText: 'OK'
                });
            } else {
                Swal.fire({
                    icon: 'success',
                    title: 'Sistema Atualizado',
                    text: 'Você está usando a versão mais recente.',
                    timer: 3000
                });
            }
        },
        error: function() {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'Erro ao verificar atualizações.'
            });
        }
    });
}
</script>
@stop
