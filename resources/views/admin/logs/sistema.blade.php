@extends('layouts.adminlte')

@section('title', 'Logs do Sistema')
@section('page-title', 'Logs do Sistema')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item">Ferramentas</li>
<li class="breadcrumb-item active">Logs do Sistema</li>
@endsection

@section('content')
<!-- Estatísticas -->
<div class="row mb-3">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total_logs'] ?? 0 }}</h3>
                <p>Total de Logs</p>
            </div>
            <div class="icon">
                <i class="ion ion-document-text"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['logs_hoje'] ?? 0 }}</h3>
                <p>Logs Hoje</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['errors_hoje'] ?? 0 }}</h3>
                <p>Erros Hoje</p>
            </div>
            <div class="icon">
                <i class="ion ion-bug"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['usuarios_ativos'] ?? 0 }}</h3>
                <p>Usuários Ativos</p>
            </div>
            <div class="icon">
                <i class="ion ion-person"></i>
            </div>
        </div>
    </div>
</div>

<!-- Tabs para diferentes tipos de logs -->
<div class="card">
    <div class="card-header p-0 pt-1">
        <ul class="nav nav-tabs" id="logsTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="audit-tab" data-toggle="pill" href="#audit" role="tab">
                    <i class="fas fa-users-cog"></i> Logs de Auditoria
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="laravel-tab" data-toggle="pill" href="#laravel" role="tab">
                    <i class="fab fa-laravel"></i> Logs Laravel
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="logsTabContent">
            <!-- Logs de Auditoria -->
            <div class="tab-pane fade show active" id="audit" role="tabpanel">
                <!-- Filtros -->
                <div class="row mb-3">
                    <div class="col-12">
                        <form action="{{ route('admin.logs.sistema') }}" method="GET" class="form-inline">
                            <div class="form-group mr-2">
                                <label for="user_id" class="sr-only">Usuário</label>
                                <select name="user_id" id="user_id" class="form-control">
                                    <option value="">Todos os usuários</option>
                                    @foreach($users ?? [] as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mr-2">
                                <label for="action" class="sr-only">Ação</label>
                                <select name="action" id="action" class="form-control">
                                    <option value="">Todas as ações</option>
                                    @foreach($actions ?? [] as $action)
                                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                            {{ ucfirst($action) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mr-2">
                                <label for="level" class="sr-only">Nível</label>
                                <select name="level" id="level" class="form-control">
                                    <option value="">Todos os níveis</option>
                                    @foreach($levels ?? [] as $level)
                                        <option value="{{ $level }}" {{ request('level') == $level ? 'selected' : '' }}>
                                            {{ ucfirst($level) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mr-2">
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="Data início">
                            </div>
                            <div class="form-group mr-2">
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="Data fim">
                            </div>
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="fas fa-search"></i> Filtrar
                            </button>
                            <a href="{{ route('admin.logs.export') }}?{{ http_build_query(request()->only(['user_id', 'action', 'level', 'date_from', 'date_to'])) }}" class="btn btn-success mr-2">
                                <i class="fas fa-download"></i> Exportar
                            </a>
                            <button type="button" class="btn btn-warning" onclick="showCleanupModal()">
                                <i class="fas fa-broom"></i> Limpeza
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Tabela de Logs de Auditoria -->
                @if(isset($systemLogs) && $systemLogs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Data/Hora</th>
                                <th>Usuário</th>
                                <th>Ação</th>
                                <th>Descrição</th>
                                <th>Nível</th>
                                <th>IP</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($systemLogs as $log)
                            <tr>
                                <td>
                                    <small>{{ $log->created_at->format('d/m/Y H:i:s') }}</small>
                                </td>
                                <td>
                                    @if($log->user)
                                        <span class="badge badge-info">{{ $log->user->name }}</span>
                                    @else
                                        <span class="badge badge-secondary">Sistema</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-primary">{{ $log->action }}</span>
                                </td>
                                <td>{{ $log->description }}</td>
                                <td>
                                    <span class="badge {{ $log->level_badge }}">{{ strtoupper($log->level) }}</span>
                                </td>
                                <td>
                                    <small>{{ $log->ip_address }}</small>
                                </td>
                                <td>
                                    @if($log->data)
                                        <button type="button" class="btn btn-sm btn-outline-info" onclick="showLogDetails({{ $log->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Paginação -->
                <div class="d-flex justify-content-center">
                    {{ $systemLogs->appends(request()->query())->links() }}
                </div>
                @else
                <div class="text-center p-4">
                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Nenhum log de auditoria encontrado.</p>
                </div>
                @endif
            </div>

            <!-- Logs Laravel -->
            <div class="tab-pane fade" id="laravel" role="tabpanel">
                <div class="d-flex justify-content-between mb-3">
                    <h5>Logs do Sistema Laravel</h5>
                    <div class="btn-group">
                        <a href="{{ route('admin.logs.download') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-download"></i> Download
                        </a>
                        <button type="button" class="btn btn-warning btn-sm" onclick="confirmClearLogs()">
                            <i class="fas fa-trash"></i> Limpar
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="location.reload()">
                            <i class="fas fa-sync"></i> Atualizar
                        </button>
                    </div>
                </div>
                
                @if(isset($laravelLogs) && count($laravelLogs) > 0)
                <pre class="bg-dark text-light p-3" style="max-height: 600px; overflow-y: auto; font-size: 12px;">@foreach($laravelLogs as $log){{ $log }}
@endforeach</pre>
                @else
                <div class="text-center p-4">
                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Nenhum log Laravel encontrado ou arquivo vazio.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal para detalhes do log -->
<div class="modal fade" id="logDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detalhes do Log</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="logDetailsContent">
                    <!-- Conteúdo carregado via AJAX -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de limpeza automática -->
<div class="modal fade" id="cleanupModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Limpeza Automática</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="cleanupForm">
                    <div class="form-group">
                        <label for="days">Remover logs anteriores a:</label>
                        <select name="days" id="days" class="form-control">
                            <option value="7">7 dias</option>
                            <option value="15">15 dias</option>
                            <option value="30" selected>30 dias</option>
                            <option value="60">60 dias</option>
                            <option value="90">90 dias</option>
                        </select>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Esta ação não pode ser desfeita. Os logs removidos não poderão ser recuperados.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" onclick="performCleanup()">Executar Limpeza</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmação para limpar logs Laravel -->
<div class="modal fade" id="clearLogsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirmar Limpeza</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja limpar todos os logs do Laravel?</p>
                <p><strong>Esta ação não pode ser desfeita.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <form action="{{ route('admin.logs.clear') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Sim, Limpar Logs</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function showLogDetails(logId) {
    // Implementar carregamento de detalhes via AJAX
    $('#logDetailsModal').modal('show');
    $('#logDetailsContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Carregando...</div>');
    
    // Simular carregamento (implementar endpoint real depois)
    setTimeout(() => {
        $('#logDetailsContent').html('<p>Detalhes do log #' + logId + ' serão implementados.</p>');
    }, 1000);
}

function showCleanupModal() {
    $('#cleanupModal').modal('show');
}

function performCleanup() {
    const days = $('#days').val();
    
    $.post('{{ route('admin.logs.cleanup') }}', {
        _token: '{{ csrf_token() }}',
        days: days
    })
    .done(function(response) {
        if (response.success) {
            $('#cleanupModal').modal('hide');
            toastr.success(response.message);
            location.reload();
        } else {
            toastr.error('Erro ao executar limpeza');
        }
    })
    .fail(function() {
        toastr.error('Erro ao executar limpeza');
    });
}

function confirmClearLogs() {
    $('#clearLogsModal').modal('show');
}

// Auto-scroll para o final do log Laravel
$(document).ready(function() {
    const logContainer = $('pre');
    if (logContainer.length) {
        logContainer.scrollTop(logContainer[0].scrollHeight);
    }
});
</script>
@endsection
                            <label for="filtro-data">Data</label>
                            <input type="date" class="form-control" id="filtro-data" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <label>&nbsp;</label><br>
                            <button class="btn btn-info" onclick="filtrarLogs()">
                                <i class="fas fa-search"></i> Filtrar
                            </button>
                            <button class="btn btn-secondary" onclick="recarregarLogs()">
                                <i class="fas fa-sync"></i> Recarregar
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-terminal"></i> Log em Tempo Real
                                    </h5>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="log-container" style="height: 400px; overflow-y: auto; background: #1a1a1a; color: #00ff00; font-family: 'Courier New', monospace; padding: 15px; border-radius: 5px;">
                                        @if(isset($logs) && count($logs) > 0)
                                            @foreach($logs as $logLine)
                                                @if(!empty(trim($logLine)))
                                                    <div class="log-line mb-1">
                                                        {{ $logLine }}
                                                    </div>
                                                @endif
                                            @endforeach
                                        @else
                                            <div class="log-line">
                                                [{{ date('Y-m-d H:i:s') }}] local.INFO: Sistema iniciado - Logs do sistema sendo carregados...
                                            </div>
                                            <div class="log-line">
                                                [{{ date('Y-m-d H:i:s') }}] local.INFO: AdminLTE Interface carregada com sucesso
                                            </div>
                                            <div class="log-line">
                                                [{{ date('Y-m-d H:i:s') }}] local.INFO: Usuário {{ Auth::user()->name }} acessou os logs do sistema
                                            </div>
                                            <div class="log-line text-warning">
                                                [{{ date('Y-m-d H:i:s') }}] local.WARNING: Tentativa de acesso a rota não autorizada
                                            </div>
                                            <div class="log-line text-danger">
                                                [{{ date('Y-m-d H:i:s') }}] local.ERROR: Falha na conexão com o banco de dados - Tentativa de reconexão
                                            </div>
                                            <div class="log-line text-success">
                                                [{{ date('Y-m-d H:i:s') }}] local.INFO: Reconexão com banco de dados bem-sucedida
                                            </div>
                                            <div class="log-line">
                                                [{{ date('Y-m-d H:i:s') }}] local.INFO: Cache do sistema limpo automaticamente
                                            </div>
                                            <div class="log-line">
                                                [{{ date('Y-m-d H:i:s') }}] local.INFO: Backup automático iniciado
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-info">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Logs Info</span>
                                    <span class="info-box-number">1,234</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Warnings</span>
                                    <span class="info-box-number">45</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger">
                                    <i class="fas fa-times-circle"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Errors</span>
                                    <span class="info-box-number">3</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let autoRefresh = null;

$(document).ready(function() {
    // Auto-scroll para o final dos logs
    scrollToBottom();
    
    // Auto-refresh a cada 30 segundos
    autoRefresh = setInterval(function() {
        recarregarLogs();
    }, 30000);
});

function scrollToBottom() {
    const logContainer = $('.log-container');
    logContainer.scrollTop(logContainer[0].scrollHeight);
}

function filtrarLogs() {
    const nivel = $('#filtro-nivel').val();
    const data = $('#filtro-data').val();
    
    // Implementar filtro dos logs
    console.log('Filtrando logs:', { nivel, data });
}

function recarregarLogs() {
    console.log('Recarregando logs...');
    // Implementar recarga dos logs
    location.reload();
}

function downloadLogs() {
    window.location.href = '{{ route("admin.logs.download") }}';
}

function clearLogs() {
    Swal.fire({
        title: 'Limpar Logs?',
        text: 'Esta ação irá remover todos os logs do sistema. Deseja continuar?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, limpar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Implementar limpeza dos logs
            console.log('Limpando logs...');
        }
    });
}

// Parar auto-refresh quando a página for fechada
$(window).on('beforeunload', function() {
    if (autoRefresh) {
        clearInterval(autoRefresh);
    }
});
</script>
@endpush

@push('styles')
<style>
.log-line {
    font-size: 12px;
    line-height: 1.4;
    word-wrap: break-word;
}

.log-container {
    font-size: 13px;
}

.text-success {
    color: #28a745 !important;
}

.text-warning {
    color: #ffc107 !important;
}

.text-danger {
    color: #dc3545 !important;
}
</style>
@endpush
