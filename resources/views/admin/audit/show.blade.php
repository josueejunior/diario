@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">🔍 Detalhes do Log de Auditoria #{{ $auditLog->id }}</h1>
                <a href="{{ route('admin.audit.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>

            <div class="row">
                <!-- Informações Principais -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">📋 Informações Gerais</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">ID do Log:</td>
                                    <td>{{ $auditLog->id }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Data/Hora:</td>
                                    <td>{{ $auditLog->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Usuário:</td>
                                    <td>
                                        @if($auditLog->user)
                                            <span class="badge badge-info">{{ $auditLog->user->name }}</span>
                                            <br><small class="text-muted">ID: {{ $auditLog->user->id }}</small>
                                        @else
                                            <span class="badge badge-secondary">Sistema/Anônimo</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Ação:</td>
                                    <td>
                                        <span class="badge 
                                            @switch($auditLog->action)
                                                @case('created') badge-success @break
                                                @case('updated') badge-warning @break
                                                @case('deleted') badge-danger @break
                                                @case('published') badge-primary @break
                                                @case('signed') badge-info @break
                                                @default badge-secondary
                                            @endswitch
                                        ">
                                            {{ ucfirst($auditLog->action) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Tipo de Recurso:</td>
                                    <td>{{ $auditLog->resource_type ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">ID do Recurso:</td>
                                    <td>{{ $auditLog->resource_id ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Informações Técnicas -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">🌐 Informações Técnicas</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">IP Address:</td>
                                    <td><code>{{ $auditLog->ip_address }}</code></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Método HTTP:</td>
                                    <td>
                                        <span class="badge 
                                            @switch($auditLog->http_method)
                                                @case('GET') badge-info @break
                                                @case('POST') badge-success @break
                                                @case('PUT') badge-warning @break
                                                @case('PATCH') badge-warning @break
                                                @case('DELETE') badge-danger @break
                                                @default badge-secondary
                                            @endswitch
                                        ">
                                            {{ $auditLog->http_method }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Status Code:</td>
                                    <td>
                                        <span class="badge 
                                            @if($auditLog->status_code >= 200 && $auditLog->status_code < 300) badge-success
                                            @elseif($auditLog->status_code >= 300 && $auditLog->status_code < 400) badge-warning
                                            @elseif($auditLog->status_code >= 400) badge-danger
                                            @else badge-secondary
                                            @endif
                                        ">
                                            {{ $auditLog->status_code }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">URL:</td>
                                    <td><code class="small">{{ $auditLog->url }}</code></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Session ID:</td>
                                    <td><code class="small">{{ $auditLog->session_id ?? 'N/A' }}</code></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Agent -->
            @if($auditLog->user_agent)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">🖥️ User Agent</h5>
                        </div>
                        <div class="card-body">
                            <code class="small">{{ $auditLog->user_agent }}</code>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Valores Antigos e Novos -->
            <div class="row mt-4">
                @if($auditLog->old_values)
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">📄 Valores Anteriores</h5>
                        </div>
                        <div class="card-body">
                            <pre class="bg-light p-3 rounded"><code>{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                        </div>
                    </div>
                </div>
                @endif

                @if($auditLog->new_values)
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">📝 Valores Novos</h5>
                        </div>
                        <div class="card-body">
                            <pre class="bg-light p-3 rounded"><code>{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Metadados -->
            @if($auditLog->metadata)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">⚙️ Metadados</h5>
                        </div>
                        <div class="card-body">
                            <pre class="bg-light p-3 rounded"><code>{{ json_encode($auditLog->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Logs Relacionados -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">🔗 Logs Relacionados</h5>
                        </div>
                        <div class="card-body">
                            @php
                            $relatedLogs = \App\Models\AuditLog::where('id', '!=', $auditLog->id)
                                ->where(function($query) use ($auditLog) {
                                    $query->where('session_id', $auditLog->session_id)
                                          ->orWhere('ip_address', $auditLog->ip_address)
                                          ->orWhere(function($q) use ($auditLog) {
                                              if($auditLog->resource_type && $auditLog->resource_id) {
                                                  $q->where('resource_type', $auditLog->resource_type)
                                                    ->where('resource_id', $auditLog->resource_id);
                                              }
                                          });
                                })
                                ->whereBetween('created_at', [
                                    $auditLog->created_at->subHours(1),
                                    $auditLog->created_at->addHours(1)
                                ])
                                ->orderBy('created_at', 'desc')
                                ->limit(10)
                                ->get();
                            @endphp

                            @if($relatedLogs->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Data/Hora</th>
                                                <th>Ação</th>
                                                <th>Recurso</th>
                                                <th>IP</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($relatedLogs as $relatedLog)
                                            <tr>
                                                <td class="small">{{ $relatedLog->created_at->format('d/m/Y H:i:s') }}</td>
                                                <td>
                                                    <span class="badge badge-sm badge-secondary">{{ $relatedLog->action }}</span>
                                                </td>
                                                <td class="small">
                                                    {{ $relatedLog->resource_type }}
                                                    @if($relatedLog->resource_id)
                                                        #{{ $relatedLog->resource_id }}
                                                    @endif
                                                </td>
                                                <td class="small font-monospace">{{ $relatedLog->ip_address }}</td>
                                                <td>
                                                    <a href="{{ route('admin.audit.show', $relatedLog) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">Nenhum log relacionado encontrado.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
