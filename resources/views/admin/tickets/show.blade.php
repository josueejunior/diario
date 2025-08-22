@extends('layouts.adminlte')

@section('title', 'Ticket #' . $id)
@section('page-title', 'Detalhes do Ticket #' . $id)

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item">Suporte</li>
<li class="breadcrumb-item"><a href="{{ route('admin.tickets.meus') }}">Meus Tickets</a></li>
<li class="breadcrumb-item active">Ticket #{{ $id }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Detalhes do Ticket -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-ticket-alt mr-2"></i>
                    Problema no upload de arquivos PDF
                </h3>
                <div class="card-tools">
                    <span class="badge badge-primary">Em Andamento</span>
                </div>
            </div>
            
            <div class="card-body">
                <!-- Descrição inicial -->
                <div class="timeline">
                    <div class="time-label">
                        <span class="bg-blue">22 Ago 2025</span>
                    </div>
                    
                    <div>
                        <i class="fas fa-user bg-green"></i>
                        <div class="timeline-item">
                            <span class="time">
                                <i class="fas fa-clock"></i> 14:30
                            </span>
                            <h3 class="timeline-header">
                                <strong>Elaine Maria Ferreira Costa</strong> abriu este ticket
                            </h3>
                            <div class="timeline-body">
                                <p><strong>Categoria:</strong> <span class="badge badge-info">Técnico</span></p>
                                <p><strong>Prioridade:</strong> <span class="badge badge-warning">Alta</span></p>
                                <hr>
                                <p>Estou tentando fazer upload de arquivos PDF para as edições do diário, mas o sistema está retornando erro 500. O problema começou hoje pela manhã após a atualização do sistema.</p>
                                
                                <p><strong>Passos para reproduzir:</strong></p>
                                <ol>
                                    <li>Acesso a página de Nova Edição</li>
                                    <li>Preencho todos os campos obrigatórios</li>
                                    <li>Seleciono um arquivo PDF (testei com arquivos de 2MB e 10MB)</li>
                                    <li>Clico em "Salvar"</li>
                                    <li>Sistema exibe erro 500</li>
                                </ol>
                                
                                <p><strong>Navegador:</strong> Chrome 137.0.0.0</p>
                                <p><strong>Sistema Operacional:</strong> Windows 11</p>
                                
                                <div class="mt-3">
                                    <strong>Arquivo anexado:</strong>
                                    <a href="#" class="btn btn-sm btn-outline-primary ml-2">
                                        <i class="fas fa-paperclip"></i> screenshot_erro.png
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <i class="fas fa-user-cog bg-yellow"></i>
                        <div class="timeline-item">
                            <span class="time">
                                <i class="fas fa-clock"></i> 15:45
                            </span>
                            <h3 class="timeline-header">
                                <strong>Suporte Técnico</strong> respondeu
                            </h3>
                            <div class="timeline-body">
                                <p>Olá Elaine,</p>
                                <p>Obrigado por reportar este problema. Estamos investigando a questão do upload de arquivos PDF. Aparentemente houve um problema de configuração após a última atualização.</p>
                                <p>Enquanto isso, você pode tentar:</p>
                                <ul>
                                    <li>Limpar o cache do navegador</li>
                                    <li>Tentar com arquivos menores (máximo 5MB)</li>
                                    <li>Usar o navegador Firefox como alternativa</li>
                                </ul>
                                <p>Vou atualizar você assim que tivermos uma solução definitiva.</p>
                                <p><strong>Status alterado para:</strong> <span class="badge badge-primary">Em Andamento</span></p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <i class="fas fa-clock bg-gray"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Responder -->
        <div class="card" id="responder">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-reply mr-2"></i>
                    Adicionar Resposta
                </h3>
            </div>
            
            <div class="card-body">
                <form onsubmit="adicionarResposta(event)">
                    <div class="form-group">
                        <label for="resposta">Sua resposta:</label>
                        <textarea class="form-control" id="resposta" name="resposta" rows="5" 
                                  placeholder="Digite sua resposta ou atualize o status do ticket..."></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="novoStatus">Alterar Status:</label>
                                <select class="form-control" id="novoStatus" name="status">
                                    <option value="">Manter atual</option>
                                    <option value="aberto">Aberto</option>
                                    <option value="em_andamento" selected>Em Andamento</option>
                                    <option value="aguardando">Aguardando Resposta</option>
                                    <option value="resolvido">Resolvido</option>
                                    <option value="fechado">Fechado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="arquivo">Anexar Arquivo:</label>
                                <input type="file" class="form-control-file" id="arquivo" name="arquivo">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane mr-1"></i>
                            Enviar Resposta
                        </button>
                        <a href="{{ route('admin.tickets.meus') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-arrow-left mr-1"></i>
                            Voltar para Lista
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Informações do Ticket -->
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Informações do Ticket</h3>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-4">ID:</dt>
                    <dd class="col-sm-8">#{{ $id }}</dd>
                    
                    <dt class="col-sm-4">Status:</dt>
                    <dd class="col-sm-8"><span class="badge badge-primary">Em Andamento</span></dd>
                    
                    <dt class="col-sm-4">Prioridade:</dt>
                    <dd class="col-sm-8"><span class="badge badge-warning">Alta</span></dd>
                    
                    <dt class="col-sm-4">Categoria:</dt>
                    <dd class="col-sm-8"><span class="badge badge-info">Técnico</span></dd>
                    
                    <dt class="col-sm-4">Criado:</dt>
                    <dd class="col-sm-8">22/08/2025 às 14:30</dd>
                    
                    <dt class="col-sm-4">Atualizado:</dt>
                    <dd class="col-sm-8">22/08/2025 às 15:45</dd>
                    
                    <dt class="col-sm-4">Responsável:</dt>
                    <dd class="col-sm-8">Suporte Técnico</dd>
                </dl>
            </div>
        </div>
        
        <!-- Ações Rápidas -->
        <div class="card card-warning card-outline">
            <div class="card-header">
                <h3 class="card-title">Ações Rápidas</h3>
            </div>
            <div class="card-body">
                <div class="btn-group-vertical btn-block">
                    <button class="btn btn-outline-success btn-sm" onclick="alterarStatus('resolvido')">
                        <i class="fas fa-check mr-1"></i>
                        Marcar como Resolvido
                    </button>
                    <button class="btn btn-outline-danger btn-sm" onclick="alterarStatus('fechado')">
                        <i class="fas fa-times mr-1"></i>
                        Fechar Ticket
                    </button>
                    <button class="btn btn-outline-warning btn-sm" onclick="alterarPrioridade()">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Alterar Prioridade
                    </button>
                    <button class="btn btn-outline-info btn-sm" onclick="encaminharTicket()">
                        <i class="fas fa-share mr-1"></i>
                        Encaminhar
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Histórico de Alterações -->
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title">Histórico</h3>
            </div>
            <div class="card-body p-2">
                <div class="list-group list-group-flush">
                    <div class="list-group-item p-2">
                        <small class="text-muted">22/08 às 15:45</small><br>
                        <strong>Status:</strong> Aberto → Em Andamento
                    </div>
                    <div class="list-group-item p-2">
                        <small class="text-muted">22/08 às 14:30</small><br>
                        <strong>Ticket criado</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function adicionarResposta(event) {
    event.preventDefault();
    
    const resposta = document.getElementById('resposta').value;
    const status = document.getElementById('novoStatus').value;
    
    if (!resposta.trim()) {
        alert('Por favor, digite uma resposta.');
        return;
    }
    
    // Simular envio da resposta
    alert('Resposta adicionada com sucesso!');
    location.reload();
}

function alterarStatus(novoStatus) {
    const statusTexto = {
        'resolvido': 'Resolvido',
        'fechado': 'Fechado',
        'aberto': 'Aberto',
        'em_andamento': 'Em Andamento'
    };
    
    if (confirm(`Tem certeza que deseja alterar o status para "${statusTexto[novoStatus]}"?`)) {
        // Implementar alteração de status via AJAX
        alert(`Status alterado para "${statusTexto[novoStatus]}"`);
        location.reload();
    }
}

function alterarPrioridade() {
    const prioridades = ['baixa', 'normal', 'alta', 'critica'];
    const prioridadeAtual = 'alta';
    
    let novaPrioridade = prompt('Nova prioridade (baixa, normal, alta, critica):', prioridadeAtual);
    
    if (novaPrioridade && prioridades.includes(novaPrioridade.toLowerCase())) {
        alert(`Prioridade alterada para "${novaPrioridade}"`);
        location.reload();
    }
}

function encaminharTicket() {
    const responsavel = prompt('Encaminhar para (nome do responsável):');
    
    if (responsavel) {
        alert(`Ticket encaminhado para "${responsavel}"`);
        location.reload();
    }
}

// Atualizar página automaticamente a cada 2 minutos
setInterval(() => {
    location.reload();
}, 120000);
</script>
@endpush
