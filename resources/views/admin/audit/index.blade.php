@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">📋 Trilha de Auditoria</h1>
                <div>
                    <a href="{{ route('admin.audit.dashboard') }}" class="btn btn-outline-info">
                        <i class="fas fa-chart-bar"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.audit.export', request()->query()) }}" class="btn btn-success">
                        <i class="fas fa-download"></i> Exportar
                    </a>
                </div>
            </div>

            <!-- Filtros -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">🔍 Filtros</h5>
                </div>
                <div class="card-body">
                    <form method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="user_id" class="form-label">Usuário</label>
                                <select name="user_id" id="user_id" class="form-select">
                                    <option value="">Todos os usuários</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="action" class="form-label">Ação</label>
                                <select name="action" id="action" class="form-select">
                                    <option value="">Todas as ações</option>
                                    @foreach($actions as $action)
                                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                            {{ ucfirst($action) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="resource_type" class="form-label">Tipo de Recurso</label>
                                <select name="resource_type" id="resource_type" class="form-select">
                                    <option value="">Todos os tipos</option>
                                    @foreach($resourceTypes as $type)
                                        <option value="{{ $type }}" {{ request('resource_type') == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="date_from" class="form-label">De</label>
                                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>

                            <div class="col-md-2">
                                <label for="date_to" class="form-label">Até</label>
                                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>

                            <div class="col-md-1 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="ip_address" class="form-label">IP Address</label>
                                <input type="text" name="ip_address" id="ip_address" class="form-control" 
                                       placeholder="Ex: 192.168.1.1" value="{{ request('ip_address') }}">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <a href="{{ route('admin.audit.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i> Limpar Filtros
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Logs -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        📊 Logs de Auditoria 
                        <span class="badge badge-secondary">{{ $logs->total() }} registros</span>
                    </h5>
                </div>
                <div class="card-body">
                    @if($logs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Data/Hora</th>
                                        <th>Usuário</th>
                                        <th>Ação</th>
                                        <th>Recurso</th>
                                        <th>IP</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($logs as $log)
                                        <tr>
                                            <td>
                                                <span class="text-muted small">{{ $log->created_at->format('d/m/Y') }}</span><br>
                                                <span class="font-monospace">{{ $log->created_at->format('H:i:s') }}</span>
                                            </td>
                                            <td>
                                                @if($log->user)
                                                    <span class="badge badge-info">{{ $log->user->name }}</span>
                                                @else
                                                    <span class="badge badge-secondary">Sistema</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    @switch($log->action)
                                                        @case('created') badge-success @break
                                                        @case('updated') badge-warning @break
                                                        @case('deleted') badge-danger @break
                                                        @case('published') badge-primary @break
                                                        @case('signed') badge-info @break
                                                        @default badge-secondary
                                                    @endswitch
                                                ">
                                                    {{ ucfirst($log->action) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($log->resource_type)
                                                    <strong>{{ $log->resource_type }}</strong>
                                                    @if($log->resource_id)
                                                        <br><span class="text-muted small">#{{ $log->resource_id }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="font-monospace small">{{ $log->ip_address }}</span>
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    @if($log->status_code >= 200 && $log->status_code < 300) badge-success
                                                    @elseif($log->status_code >= 300 && $log->status_code < 400) badge-warning
                                                    @elseif($log->status_code >= 400) badge-danger
                                                    @else badge-secondary
                                                    @endif
                                                ">
                                                    {{ $log->status_code }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.audit.show', $log) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> Ver
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginação -->
                        <div class="d-flex justify-content-center">
                            {{ $logs->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-search fa-3x text-muted"></i>
                            <h5 class="mt-3">Nenhum log encontrado</h5>
                            <p class="text-muted">Ajuste os filtros para encontrar logs específicos.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
