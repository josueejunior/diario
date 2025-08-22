@extends('layouts.adminlte')

@section('title', 'Unidades/Departamentos')
@section('page-title', 'Unidades/Departamentos')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-sitemap mr-2"></i>
                        Gerenciar Unidades e Departamentos
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalNovaUnidade">
                            <i class="fas fa-plus"></i> Nova Unidade
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Sigla</th>
                                    <th>Responsável</th>
                                    <th>Contato</th>
                                    <th>Status</th>
                                    <th width="150">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <strong>Secretaria de Administração</strong>
                                        <br>
                                        <small class="text-muted">Responsável pela gestão administrativa</small>
                                    </td>
                                    <td><span class="badge badge-info">SEMAD</span></td>
                                    <td>
                                        <strong>João Silva</strong>
                                        <br>
                                        <small class="text-muted">Secretário</small>
                                    </td>
                                    <td>
                                        <small>
                                            <i class="fas fa-envelope"></i> semad@augustinopolis.to.gov.br<br>
                                            <i class="fas fa-phone"></i> (63) 3484-1234
                                        </small>
                                    </td>
                                    <td><span class="badge badge-success">Ativo</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-info" title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" title="Desativar">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Secretaria de Saúde</strong>
                                        <br>
                                        <small class="text-muted">Gestão da saúde pública municipal</small>
                                    </td>
                                    <td><span class="badge badge-info">SESAU</span></td>
                                    <td>
                                        <strong>Maria Santos</strong>
                                        <br>
                                        <small class="text-muted">Secretária</small>
                                    </td>
                                    <td>
                                        <small>
                                            <i class="fas fa-envelope"></i> sesau@augustinopolis.to.gov.br<br>
                                            <i class="fas fa-phone"></i> (63) 3484-5678
                                        </small>
                                    </td>
                                    <td><span class="badge badge-success">Ativo</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-info" title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" title="Desativar">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Controladoria Geral</strong>
                                        <br>
                                        <small class="text-muted">Controle interno e auditoria</small>
                                    </td>
                                    <td><span class="badge badge-info">CGM</span></td>
                                    <td>
                                        <strong>Carlos Oliveira</strong>
                                        <br>
                                        <small class="text-muted">Controlador</small>
                                    </td>
                                    <td>
                                        <small>
                                            <i class="fas fa-envelope"></i> cgm@augustinopolis.to.gov.br<br>
                                            <i class="fas fa-phone"></i> (63) 3484-9012
                                        </small>
                                    </td>
                                    <td><span class="badge badge-warning">Inativo</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-info" title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-success" title="Ativar">
                                            <i class="fas fa-check"></i>
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

<!-- Modal Nova Unidade -->
<div class="modal fade" id="modalNovaUnidade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nova Unidade/Departamento</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="formNovaUnidade" method="POST" action="{{ route('admin.unidades.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nome">Nome da Unidade *</label>
                                <input type="text" class="form-control" id="nome" name="nome" 
                                       placeholder="Ex: Secretaria de Educação" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sigla">Sigla</label>
                                <input type="text" class="form-control" id="sigla" name="sigla" 
                                       placeholder="Ex: SEMED" maxlength="10">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3" 
                                  placeholder="Descreva as responsabilidades desta unidade..."></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="responsavel">Responsável</label>
                                <input type="text" class="form-control" id="responsavel" name="responsavel" 
                                       placeholder="Nome do responsável">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       placeholder="email@augustinopolis.to.gov.br">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telefone">Telefone</label>
                                <input type="text" class="form-control" id="telefone" name="telefone" 
                                       placeholder="(63) 3484-0000" data-mask="(00) 0000-0000">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ativo">Status</label>
                                <select class="form-control" id="ativo" name="ativo">
                                    <option value="1" selected>Ativo</option>
                                    <option value="0">Inativo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" form="formNovaUnidade" class="btn btn-primary">Salvar Unidade</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#telefone').mask('(00) 0000-0000');
});

$('#formNovaUnidade').on('submit', function(e) {
    e.preventDefault();
    // Implementar criação
    console.log('Criando nova unidade...');
    $('#modalNovaUnidade').modal('hide');
});
</script>
@endpush
