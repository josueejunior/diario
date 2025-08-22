@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">📊 Dashboard de Auditoria</h1>
                <div>
                    <a href="{{ route('admin.audit.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list"></i> Ver Logs
                    </a>
                    <a href="{{ route('admin.audit.export', request()->query()) }}" class="btn btn-success">
                        <i class="fas fa-download"></i> Exportar
                    </a>
                </div>
            </div>

            <!-- Estatísticas Gerais -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">{{ number_format($stats['total_actions']) }}</h4>
                                    <p class="card-text">Total de Ações</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-chart-line fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">{{ number_format($stats['unique_users']) }}</h4>
                                    <p class="card-text">Usuários Únicos</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">{{ number_format($stats['actions_today']) }}</h4>
                                    <p class="card-text">Ações Hoje</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-calendar-day fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">{{ number_format($stats['actions_this_week']) }}</h4>
                                    <p class="card-text">Esta Semana</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-calendar-week fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Gráfico de Atividade Diária -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">📈 Atividade dos Últimos 30 Dias</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="dailyActivityChart" height="100"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Top Ações -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">🎯 Ações Mais Comuns</h5>
                        </div>
                        <div class="card-body">
                            @foreach($topActions as $action)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge badge-secondary">{{ $action->action }}</span>
                                <span class="font-weight-bold">{{ number_format($action->count) }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <!-- Usuários Mais Ativos -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">👥 Usuários Mais Ativos</h5>
                        </div>
                        <div class="card-body">
                            @foreach($topUsers as $userStats)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>{{ $userStats->user->name ?? 'Usuário Removido' }}</span>
                                <span class="badge badge-primary">{{ number_format($userStats->count) }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- IPs Mais Ativos -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">🌐 IPs Mais Ativos</h5>
                        </div>
                        <div class="card-body">
                            @foreach($topIPs as $ipStats)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="font-monospace">{{ $ipStats->ip_address }}</span>
                                <span class="badge badge-info">{{ number_format($ipStats->count) }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Limpeza de Logs -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-warning">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="card-title mb-0">🗑️ Limpeza de Logs Antigos</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.audit.cleanup') }}" onsubmit="return confirm('Tem certeza que deseja remover os logs antigos? Esta ação não pode ser desfeita.')">
                                @csrf
                                <div class="row align-items-end">
                                    <div class="col-md-4">
                                        <label for="days" class="form-label">Remover logs anteriores a:</label>
                                        <select name="days" id="days" class="form-select">
                                            <option value="90">90 dias</option>
                                            <option value="180">6 meses</option>
                                            <option value="365" selected>1 ano</option>
                                            <option value="730">2 anos</option>
                                            <option value="1095">3 anos</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-trash"></i> Limpar Logs
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Gráfico de Atividade Diária
const ctx = document.getElementById('dailyActivityChart').getContext('2d');
const dailyActivityChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [
            @foreach($dailyActivity as $day)
                '{{ \Carbon\Carbon::parse($day->date)->format('d/m') }}',
            @endforeach
        ],
        datasets: [{
            label: 'Ações por Dia',
            data: [
                @foreach($dailyActivity as $day)
                    {{ $day->count }},
                @endforeach
            ],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Atividade de Auditoria'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endpush
@endsection
