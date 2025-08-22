@extends('layouts.adminlte')

@section('title', 'Meus Tickets')
@section('page-title', 'Meus Tickets de Suporte')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item">Suporte</li>
<li class="breadcrumb-item active">Meus Tickets</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-ticket-alt mr-2"></i>
                    Meus Tickets de Suporte
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#novoTicketModal">
                        <i class="fas fa-plus mr-1"></i>
                        Novo Ticket
                    </button>
                </div>
            </div>
            
            <div class="card-body">
                <!-- Filtros -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select class="form-control" id="filtroStatus">
                            <option value="">Todos os Status</option>
                            <option value="aberto">Aberto</option>
                            <option value="em_andamento">Em Andamento</option>
                            <option value="aguardando">Aguardando Resposta</option>
                            <option value="resolvido">Resolvido</option>
                            <option value="fechado">Fechado</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="filtroPrioridade">
                            <option value="">Todas as Prioridades</option>
                            <option value="baixa">Baixa</option>
                            <option value="normal">Normal</option>
                            <option value="alta">Alta</option>
                            <option value="critica">Crítica</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="filtroTitulo" placeholder="Buscar por título...">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-info btn-block" onclick="filtrarTickets()">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                    </div>
                </div>

                <!-- Tabela de Tickets -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tabelaTickets">
                        <thead>
                            <tr>
                                <th width="8%">#</th>
                                <th width="35%">Título</th>
                                <th width="12%">Categoria</th>
                                <th width="10%">Prioridade</th>
                                <th width="12%">Status</th>
                                <th width="13%">Criado em</th>
                                <th width="10%">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Exemplo de tickets - em produção viria do banco -->
                            <tr>
                                <td><span class="badge badge-secondary">#001</span></td>
                                <td>
                                    <strong>Problema no upload de arquivos PDF</strong>
                                    <br><small class="text-muted">Última atualização: 2 horas atrás</small>
                                </td>
                                <td><span class="badge badge-info">Técnico</span></td>
                                <td><span class="badge badge-warning">Alta</span></td>
                                <td><span class="badge badge-primary">Em Andamento</span></td>
                                <td>22/08/2025<br><small>14:30</small></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="verTicket(1)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-success" onclick="responderTicket(1)">
                                        <i class="fas fa-reply"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="badge badge-secondary">#002</span></td>
                                <td>
                                    <strong>Dúvida sobre assinatura digital</strong>
                                    <br><small class="text-muted">Última atualização: 1 dia atrás</small>
                                </td>
                                <td><span class="badge badge-success">Dúvida</span></td>
                                <td><span class="badge badge-secondary">Normal</span></td>
                                <td><span class="badge badge-success">Resolvido</span></td>
                                <td>21/08/2025<br><small>09:15</small></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="verTicket(2)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="fecharTicket(2)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small class="text-muted">Mostrando 1 a 2 de 2 tickets</small>
                    </div>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item disabled">
                                <span class="page-link">Anterior</span>
                            </li>
                            <li class="page-item active">
                                <span class="page-link">1</span>
                            </li>
                            <li class="page-item disabled">
                                <span class="page-link">Próximo</span>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Novo Ticket -->
<div class="modal fade" id="novoTicketModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus mr-2"></i>
                    Abrir Novo Ticket
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="formNovoTicket" onsubmit="criarTicket(event)">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="categoria">Categoria *</label>
                                <select class="form-control" id="categoria" name="categoria" required>
                                    <option value="">Selecione uma categoria</option>
                                    <option value="tecnico">Problema Técnico</option>
                                    <option value="duvida">Dúvida/Suporte</option>
                                    <option value="funcionalidade">Nova Funcionalidade</option>
                                    <option value="bug">Reportar Bug</option>
                                    <option value="outros">Outros</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="prioridade">Prioridade *</label>
                                <select class="form-control" id="prioridade" name="prioridade" required>
                                    <option value="baixa">Baixa</option>
                                    <option value="normal" selected>Normal</option>
                                    <option value="alta">Alta</option>
                                    <option value="critica">Crítica</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="titulo">Título *</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" 
                               placeholder="Descreva brevemente o problema ou dúvida" required>
                    </div>

                    <div class="form-group">
                        <label for="descricao">Descrição Detalhada *</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="5" 
                                  placeholder="Descreva detalhadamente o problema, incluindo passos para reproduzir (se aplicável)" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="arquivo">Anexar Arquivo (opcional)</label>
                        <input type="file" class="form-control-file" id="arquivo" name="arquivo" 
                               accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                        <small class="text-muted">Formatos aceitos: JPG, PNG, PDF, DOC, DOCX (max 10MB)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane mr-1"></i>
                        Abrir Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function filtrarTickets() {
    const status = document.getElementById('filtroStatus').value;
    const prioridade = document.getElementById('filtroPrioridade').value;
    const titulo = document.getElementById('filtroTitulo').value;
    
    // Implementar filtros via AJAX aqui
    console.log('Filtros:', { status, prioridade, titulo });
}

function verTicket(id) {
    window.location.href = `/admin/tickets/${id}`;
}

function responderTicket(id) {
    window.location.href = `/admin/tickets/${id}#responder`;
}

function fecharTicket(id) {
    if (confirm('Tem certeza que deseja fechar este ticket?')) {
        // Implementar fechamento via AJAX
        console.log('Fechando ticket:', id);
    }
}

function criarTicket(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    // Simular criação de ticket
    alert('Ticket criado com sucesso! ID: #003');
    form.reset();
    $('#novoTicketModal').modal('hide');
    location.reload();
}

// Auto-refresh da página a cada 5 minutos para verificar atualizações
setInterval(() => {
    location.reload();
}, 300000);
</script>
@endpush
