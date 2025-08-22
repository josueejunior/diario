@extends('layouts.adminlte')

@section('title', 'Informativos')
@section('page-title', 'Informativos')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bullhorn mr-2"></i>
                        Gerenciar Informativos
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.informativos.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Novo Informativo
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="filtro-tipo">Tipo</label>
                            <select class="form-control" id="filtro-tipo">
                                <option value="">Todos</option>
                                <option value="noticia">Notícia</option>
                                <option value="comunicado">Comunicado</option>
                                <option value="aviso">Aviso</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filtro-status">Status</label>
                            <select class="form-control" id="filtro-status">
                                <option value="">Todos</option>
                                <option value="ativo">Ativo</option>
                                <option value="inativo">Inativo</option>
                                <option value="agendado">Agendado</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>&nbsp;</label><br>
                            <button class="btn btn-info" onclick="filtrar()">
                                <i class="fas fa-search"></i> Filtrar
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Tipo</th>
                                    <th>Status</th>
                                    <th>Período</th>
                                    <th>Destaque</th>
                                    <th>Autor</th>
                                    <th width="150">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <strong>Portal do Diário Oficial Atualizado</strong>
                                        <br>
                                        <small class="text-muted">Novas funcionalidades disponíveis...</small>
                                    </td>
                                    <td><span class="badge badge-info">Notícia</span></td>
                                    <td><span class="badge badge-success">Ativo</span></td>
                                    <td>
                                        <small>
                                            <strong>Início:</strong> 22/08/2025<br>
                                            <strong>Fim:</strong> 30/08/2025
                                        </small>
                                    </td>
                                    <td>
                                        <i class="fas fa-star text-warning"></i>
                                    </td>
                                    <td>
                                        <small>Admin<br>
                                        <span class="text-muted">há 2 horas</span></small>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Manutenção Programada</strong>
                                        <br>
                                        <small class="text-muted">Sistema indisponível das 02h às 04h...</small>
                                    </td>
                                    <td><span class="badge badge-warning">Comunicado</span></td>
                                    <td><span class="badge badge-secondary">Agendado</span></td>
                                    <td>
                                        <small>
                                            <strong>Início:</strong> 25/08/2025<br>
                                            <strong>Fim:</strong> 25/08/2025
                                        </small>
                                    </td>
                                    <td>-</td>
                                    <td>
                                        <small>Admin<br>
                                        <span class="text-muted">ontem</span></small>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function filtrar() {
    // Implementar filtro
    console.log('Filtrando informativos...');
}
</script>
@endpush
