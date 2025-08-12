<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Relatório de Downloads') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('relatorios.index') }}" class="text-blue-600 hover:text-blue-900">
                    &larr; Voltar para relatórios
                </a>
            </div>

            <!-- Filtros -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Filtros</h3>
                    <form action="{{ route('relatorios.downloads') }}" method="GET" class="space-y-4 md:space-y-0 md:flex md:space-x-4">
                        <div class="flex-1">
                            <label for="data_inicio" class="block text-sm font-medium text-gray-700">Data Início</label>
                            <input type="date" id="data_inicio" name="data_inicio" value="{{ request('data_inicio') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div class="flex-1">
                            <label for="data_fim" class="block text-sm font-medium text-gray-700">Data Fim</label>
                            <input type="date" id="data_fim" name="data_fim" value="{{ request('data_fim') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div class="flex-1">
                            <label for="edicao_id" class="block text-sm font-medium text-gray-700">Edição</label>
                            <select id="edicao_id" name="edicao_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Todas as edições</option>
                                @foreach($edicoes as $edicao)
                                    <option value="{{ $edicao->id }}" {{ request('edicao_id') == $edicao->id ? 'selected' : '' }}>
                                        {{ $edicao->numero }} ({{ $edicao->data->format('d/m/Y') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Estatísticas e gráficos -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Gráfico de Downloads por Dia -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold mb-4">Downloads por Dia</h3>
                        <div class="h-64">
                            <!-- Área para o gráfico (usando um canvas para Chart.js) -->
                            <canvas id="downloadsPorDiaChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Gráfico de Downloads por Edição -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold mb-4">Top 10 Edições mais Baixadas</h3>
                        <div class="h-64">
                            <!-- Área para o gráfico (usando um canvas para Chart.js) -->
                            <canvas id="downloadsPorEdicaoChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabela de Downloads -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Lista de Downloads</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Data/Hora
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Edição
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        IP
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Navegador
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Origem
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($downloads as $download)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $download->created_at->format('d/m/Y H:i:s') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $download->edicao->numero }} ({{ $download->edicao->data->format('d/m/Y') }})
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $download->ip }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ Str::limit($download->user_agent, 50) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $download->origem }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Nenhum download registrado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $downloads->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dados para o gráfico de downloads por dia
            const downloadsPorDiaCtx = document.getElementById('downloadsPorDiaChart');
            new Chart(downloadsPorDiaCtx, {
                type: 'line',
                data: {
                    labels: @json($downloadsPorDia->pluck('data')->map(function($data) {
                        return \Carbon\Carbon::parse($data)->format('d/m/Y');
                    })),
                    datasets: [{
                        label: 'Downloads',
                        data: @json($downloadsPorDia->pluck('total')),
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Dados para o gráfico de downloads por edição
            const downloadsPorEdicaoCtx = document.getElementById('downloadsPorEdicaoChart');
            new Chart(downloadsPorEdicaoCtx, {
                type: 'bar',
                data: {
                    labels: @json($downloadsPorEdicao->map(function($item) {
                        return $item->edicao ? "Edição " . $item->edicao->numero : 'Desconhecido';
                    })),
                    datasets: [{
                        label: 'Downloads',
                        data: @json($downloadsPorEdicao->pluck('total')),
                        backgroundColor: 'rgba(59, 130, 246, 0.6)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-admin-layout>
