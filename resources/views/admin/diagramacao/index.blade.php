@extends('layouts.adminlte')

@section('title', 'Diagramação')
@section('page-title', 'Diagramação')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-2"></i>
                        Diagramação de Edições
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalNovaEdicao">
                            <i class="fas fa-plus"></i> Nova Edição
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="filtro-data">Data da Edição</label>
                            <input type="date" class="form-control" id="filtro-data">
                        </div>
                        <div class="col-md-3">
                            <label for="filtro-status">Status</label>
                            <select class="form-control" id="filtro-status">
                                <option value="">Todos</option>
                                <option value="rascunho">Rascunho</option>
                                <option value="diagramando">Diagramando</option>
                                <option value="pronto">Pronto</option>
                                <option value="publicado">Publicado</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>&nbsp;</label><br>
                            <button class="btn btn-info" onclick="filtrarEdicoes()">
                                <i class="fas fa-search"></i> Filtrar
                            </button>
                            <button class="btn btn-secondary" onclick="limparFiltros()">
                                <i class="fas fa-times"></i> Limpar
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Número</th>
                                    <th>Matérias</th>
                                    <th>Status</th>
                                    <th>Última Atualização</th>
                                    <th width="200">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>22/08/2025</td>
                                    <td>Nº 001/2025</td>
                                    <td>
                                        <span class="badge badge-info">5 matérias</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-warning">Diagramando</span>
                                    </td>
                                    <td>há 2 horas</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" title="Editar Diagramação">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-success" title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-info" title="Gerar PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <!-- Mais linhas aqui -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nova Edição -->
<div class="modal fade" id="modalNovaEdicao">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nova Edição para Diagramação</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="formNovaEdicao">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Data da Edição</label>
                                <input type="date" class="form-control" name="data_edicao" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Número da Edição</label>
                                <input type="text" class="form-control" name="numero" placeholder="Ex: 001/2025" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Matérias Aprovadas</label>
                        <select class="form-control select2" name="materias[]" multiple required>
                            <option value="1">Portaria nº 001/2025 - Nomeação de Servidor</option>
                            <option value="2">Decreto nº 002/2025 - Regulamentação de Horários</option>
                            <option value="3">Lei nº 003/2025 - Orçamento Anual</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" form="formNovaEdicao" class="btn btn-primary">Criar Edição</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: 'Selecione as matérias',
        allowClear: true
    });
});

function filtrarEdicoes() {
    // Implementar filtro
    console.log('Filtrando edições...');
}

function limparFiltros() {
    $('#filtro-data').val('');
    $('#filtro-status').val('');
}
</script>
@endpush
