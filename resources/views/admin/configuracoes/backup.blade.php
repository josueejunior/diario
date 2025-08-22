@extends('layouts.adminlte')

@section('title', 'Gerenciamento de Backup')
@section('page-title', 'Gerenciamento de Backup')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item">Configurações</li>
<li class="breadcrumb-item active">Backup</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-database mr-2"></i>
                    Backups Existentes
                </h3>
                <div class="card-tools">
                    <form action="{{ route('admin.backup.create') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Deseja criar um novo backup?')">
                            <i class="fas fa-plus mr-1"></i>
                            Criar Backup
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="card-body">
                @if($backups->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nome do Arquivo</th>
                                    <th>Tamanho</th>
                                    <th>Data de Criação</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($backups as $backup)
                                <tr>
                                    <td>
                                        <i class="fas fa-file-archive text-info mr-2"></i>
                                        {{ $backup['nome'] }}
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">
                                            {{ number_format($backup['tamanho'] / 1024 / 1024, 2) }} MB
                                        </span>
                                    </td>
                                    <td>
                                        {{ date('d/m/Y H:i:s', $backup['data_criacao']) }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.backup.download', $backup['nome']) }}" 
                                               class="btn btn-success btn-sm" title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <form action="{{ route('admin.backup.delete', $backup['nome']) }}" 
                                                  method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                        onclick="return confirm('Tem certeza que deseja excluir este backup?')"
                                                        title="Excluir">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-database fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Nenhum backup encontrado</h5>
                        <p class="text-muted">Clique em "Criar Backup" para gerar o primeiro backup do sistema.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-cog mr-2"></i>
                    Configurações de Backup
                </h3>
            </div>
            
            <div class="card-body">
                <form action="{{ route('admin.configuracoes.geral.update') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="backup_automatico">Backup Automático</label>
                                <select class="form-control" id="backup_automatico" name="backup_automatico">
                                    <option value="0">Desabilitado</option>
                                    <option value="diario">Diário</option>
                                    <option value="semanal">Semanal</option>
                                    <option value="mensal">Mensal</option>
                                </select>
                                <small class="form-text text-muted">
                                    Frequência do backup automático.
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="backup_retencao">Retenção (dias)</label>
                                <input type="number" class="form-control" id="backup_retencao" 
                                       name="backup_retencao" value="30" min="1" max="365">
                                <small class="form-text text-muted">
                                    Quantos dias manter os backups antigos.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="backup_incluir_arquivos" 
                               name="backup_incluir_arquivos" value="1">
                        <label class="form-check-label" for="backup_incluir_arquivos">
                            Incluir arquivos do sistema
                        </label>
                        <small class="form-text text-muted d-block">
                            Além do banco de dados, incluir também arquivos enviados e configurações.
                        </small>
                    </div>

                    <hr>

                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-save mr-1"></i>
                        Salvar Configurações
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-broom mr-2"></i>
                    Limpeza do Sistema
                </h3>
            </div>
            
            <div class="card-body">
                <p class="text-sm">
                    Ferramentas para otimização e limpeza do sistema.
                </p>
                
                <div class="d-grid gap-2">
                    <form action="{{ route('admin.sistema.cache.clear') }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-block" 
                                onclick="return confirm('Deseja limpar o cache do sistema?')">
                            <i class="fas fa-broom mr-1"></i>
                            Limpar Cache
                        </button>
                    </form>

                    <form action="{{ route('admin.sistema.optimize') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-block"
                                onclick="return confirm('Deseja otimizar o sistema?')">
                            <i class="fas fa-rocket mr-1"></i>
                            Otimizar Sistema
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Informações Importantes
                </h3>
            </div>
            
            <div class="card-body">
                <h6>Sobre Backups</h6>
                <ul class="text-sm">
                    <li>Backups incluem todo o banco de dados</li>
                    <li>Arquivos de upload não são incluídos por padrão</li>
                    <li>Mantenha backups em local seguro</li>
                    <li>Teste a restauração periodicamente</li>
                </ul>

                <hr>

                <h6>Limpeza de Cache</h6>
                <p class="text-sm">
                    Limpa cache de configurações, rotas e views. 
                    Use após mudanças importantes no sistema.
                </p>

                <hr>

                <h6>Otimização</h6>
                <p class="text-sm">
                    Gera cache otimizado para melhor performance. 
                    Recomendado após atualizações.
                </p>
            </div>
        </div>

        <div class="card card-light">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-2"></i>
                    Estatísticas do Sistema
                </h3>
            </div>
            
            <div class="card-body">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-info">
                        <i class="fas fa-database"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Espaço em Disco</span>
                        <span class="info-box-number">
                            @php
                                $free = disk_free_space('/');
                                $total = disk_total_space('/');
                                $used = $total - $free;
                                $percent = round(($used / $total) * 100, 1);
                            @endphp
                            {{ $percent }}%
                        </span>
                    </div>
                </div>

                <div class="progress mb-3">
                    <div class="progress-bar" role="progressbar" style="width: {{ $percent }}%" 
                         aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>

                <p class="text-sm">
                    <strong>Usado:</strong> {{ number_format($used / 1024 / 1024 / 1024, 2) }} GB<br>
                    <strong>Livre:</strong> {{ number_format($free / 1024 / 1024 / 1024, 2) }} GB<br>
                    <strong>Total:</strong> {{ number_format($total / 1024 / 1024 / 1024, 2) }} GB
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Atualizar progresso do backup
    $('.create-backup-btn').click(function() {
        $(this).prop('disabled', true);
        $(this).html('<i class="fas fa-spinner fa-spin mr-1"></i> Criando...');
    });
});
</script>
@endpush
