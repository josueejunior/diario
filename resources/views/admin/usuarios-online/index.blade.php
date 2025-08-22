@extends('layouts.adminlte')

@section('title', 'Usuários Online')
@section('page-title', 'Usuários Online')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-success">
                            <i class="fas fa-users"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Online</span>
                            <span class="info-box-number">47</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info">
                            <i class="fas fa-user-check"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Logados</span>
                            <span class="info-box-number">12</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning">
                            <i class="fas fa-user-times"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Anônimos</span>
                            <span class="info-box-number">35</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-primary">
                            <i class="fas fa-clock"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tempo Médio</span>
                            <span class="info-box-number">12m</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users mr-2"></i>
                        Usuários Ativos
                    </h3>
                    <div class="card-tools">
                        <button class="btn btn-sm btn-tool" onclick="atualizarLista()">
                            <i class="fas fa-sync"></i>
                        </button>
                        <span class="badge badge-success" id="contador-online">47 online</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Usuário</th>
                                    <th>Página Atual</th>
                                    <th>IP</th>
                                    <th>Tempo Online</th>
                                    <th>Último Acesso</th>
                                </tr>
                            </thead>
                            <tbody id="lista-usuarios">
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name=Admin&color=7F9CF5&background=EBF4FF" 
                                                 class="img-circle mr-2" style="width: 30px; height: 30px;">
                                            <div>
                                                <strong>Admin</strong>
                                                <br>
                                                <small class="text-muted">admin@sistema.com</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <small>
                                            <i class="fas fa-tachometer-alt text-primary"></i>
                                            Dashboard
                                        </small>
                                    </td>
                                    <td>
                                        <code>192.168.1.100</code>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">01:45:30</span>
                                    </td>
                                    <td>
                                        <small>há 2 min</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name=João+Silva&color=28a745&background=d4edda" 
                                                 class="img-circle mr-2" style="width: 30px; height: 30px;">
                                            <div>
                                                <strong>João Silva</strong>
                                                <br>
                                                <small class="text-muted">joao@prefeitura.gov.br</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <small>
                                            <i class="fas fa-newspaper text-info"></i>
                                            Edições
                                        </small>
                                    </td>
                                    <td>
                                        <code>192.168.1.105</code>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">00:23:15</span>
                                    </td>
                                    <td>
                                        <small>há 1 min</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-secret fa-2x text-muted mr-2"></i>
                                            <div>
                                                <strong>Visitante Anônimo</strong>
                                                <br>
                                                <small class="text-muted">Não logado</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <small>
                                            <i class="fas fa-home text-secondary"></i>
                                            Portal Principal
                                        </small>
                                    </td>
                                    <td>
                                        <code>201.50.123.45</code>
                                    </td>
                                    <td>
                                        <span class="badge badge-warning">00:05:42</span>
                                    </td>
                                    <td>
                                        <small>há 30 seg</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-secret fa-2x text-muted mr-2"></i>
                                            <div>
                                                <strong>Visitante Anônimo</strong>
                                                <br>
                                                <small class="text-muted">Não logado</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <small>
                                            <i class="fas fa-search text-primary"></i>
                                            Busca - "portaria"
                                        </small>
                                    </td>
                                    <td>
                                        <code>177.123.45.67</code>
                                    </td>
                                    <td>
                                        <span class="badge badge-warning">00:12:18</span>
                                    </td>
                                    <td>
                                        <small>há 15 seg</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-2"></i>
                        Estatísticas
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="graficoTipoUsuarios" width="400" height="200"></canvas>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-globe mr-2"></i>
                        Páginas Mais Visitadas
                    </h3>
                </div>
                <div class="card-body">
                    <div class="progress-group">
                        Portal Principal
                        <span class="float-right"><b>67</b>/100</span>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-primary" style="width: 67%"></div>
                        </div>
                    </div>
                    <div class="progress-group">
                        Busca de Documentos
                        <span class="float-right"><b>45</b>/100</span>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-info" style="width: 45%"></div>
                        </div>
                    </div>
                    <div class="progress-group">
                        Edições Recentes
                        <span class="float-right"><b>32</b>/100</span>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-success" style="width: 32%"></div>
                        </div>
                    </div>
                    <div class="progress-group">
                        Dashboard Admin
                        <span class="float-right"><b>18</b>/100</span>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-warning" style="width: 18%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let refreshInterval;

$(document).ready(function() {
    inicializarGrafico();
    iniciarAtualizacaoAutomatica();
});

function inicializarGrafico() {
    const ctx = document.getElementById('graficoTipoUsuarios').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Usuários Logados', 'Visitantes Anônimos'],
            datasets: [{
                data: [12, 35],
                backgroundColor: [
                    '#17a2b8',
                    '#ffc107'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom'
            },
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

function iniciarAtualizacaoAutomatica() {
    refreshInterval = setInterval(function() {
        atualizarLista();
    }, 30000); // Atualiza a cada 30 segundos
}

function atualizarLista() {
    // Simular atualização dos dados
    console.log('Atualizando lista de usuários online...');
    
    // Aqui seria feita a requisição AJAX para buscar dados atualizados
    // $.get('/admin/usuarios-online/dados', function(data) {
    //     atualizarTabela(data);
    // });
}

function atualizarTabela(dados) {
    // Implementar atualização da tabela com novos dados
    console.log('Atualizando tabela:', dados);
}

// Parar atualização automática quando sair da página
$(window).on('beforeunload', function() {
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }
});
</script>
@endpush
