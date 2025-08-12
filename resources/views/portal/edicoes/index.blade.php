@extends('layouts.portal')

@section('title', 'Diário Oficial - Edições Recentes')

@push('styles')
<style>
    .calendar {
        width: 100%;
    }
    .calendar th {
        text-align: center;
        padding: 0.5rem;
    }
    .calendar td {
        text-align: center;
        padding: 0.5rem;
    }
    .calendar .has-edicao {
        background-color: #dbeafe;
        color: #1e40af;
        border-radius: 50%;
        cursor: pointer;
        font-weight: 600;
    }
    .calendar .today {
        border: 2px solid #3b82f6;
    }
    .pdf-preview {
        width: 100%;
        height: 600px;
        border: none;
    }
    .date-selector {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    .date-selector button {
        background: transparent;
        border: none;
        cursor: pointer;
        font-size: 1.25rem;
        color: #4b5563;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Diário Oficial</h1>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Coluna da Esquerda - Edição mais recente e Prévia do PDF -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                @if(isset($edicaoRecente))
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold text-gray-700">
                            Edição Nº {{ $edicaoRecente->numero }} - {{ $edicaoRecente->data_publicacao->format('d/m/Y') }}
                        </h2>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('portal.edicoes.show', $edicaoRecente) }}" 
                               class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm">
                                Detalhes
                            </a>
                            <a href="{{ route('portal.edicoes.pdf', $edicaoRecente) }}"
                               class="px-3 py-1 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors text-sm">
                                Download
                            </a>
                        </div>
                    </div>
                    
                    <!-- Prévia do PDF -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <iframe src="{{ route('portal.edicoes.pdf', $edicaoRecente) }}" class="pdf-preview"></iframe>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h3 class="mt-2 text-xl font-medium text-gray-900">Nenhuma edição disponível</h3>
                        <p class="mt-1 text-sm text-gray-500">Não há edições publicadas para exibição.</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Coluna da Direita - Calendário e Verificação -->
        <div class="lg:col-span-1">
            <!-- Calendário -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Calendário de Publicações</h2>
                
                <div class="date-selector">
                    <button class="prev-month" onclick="changeMonth(-1)">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <span class="month-year font-medium">{{ $dataAtual->format('F Y') }}</span>
                    <button class="next-month" onclick="changeMonth(1)">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
                
                <table class="calendar">
                    <thead>
                        <tr>
                            <th>Dom</th>
                            <th>Seg</th>
                            <th>Ter</th>
                            <th>Qua</th>
                            <th>Qui</th>
                            <th>Sex</th>
                            <th>Sáb</th>
                        </tr>
                    </thead>
                    <tbody id="calendar-body">
                        <!-- Preenchido via JavaScript -->
                    </tbody>
                </table>
                
                <div class="mt-4 text-sm">
                    <div class="flex items-center mb-2">
                        <span class="w-3 h-3 inline-block mr-2 bg-blue-100 border border-blue-300 rounded-full"></span>
                        <span>Dias com publicações</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-3 h-3 inline-block mr-2 border-2 border-blue-500 rounded-full"></span>
                        <span>Hoje</span>
                    </div>
                </div>
            </div>
            
            <!-- Verificação de Autenticidade -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Verificação de Autenticidade</h2>
                <p class="text-gray-600 mb-4">
                    Para verificar a autenticidade de um documento, utilize a ferramenta de verificação.
                </p>
                <a href="{{ route('portal.verificar') }}" 
                   class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors inline-block text-center">
                    Verificar Documento
                </a>
            </div>
        </div>
    </div>
    
    <!-- Lista de Edições Recentes -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Edições Recentes</h2>
        
        @if($edicoes->isEmpty())
            <div class="bg-gray-100 p-4 rounded-md text-center">
                <p class="text-gray-600">Nenhuma edição publicada ainda.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($edicoes as $edicao)
                    <div class="border border-gray-200 rounded-lg hover:shadow-lg transition-shadow duration-300 bg-white">
                        <div class="p-5">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">
                                Edição Nº {{ $edicao->numero }}
                            </h3>
                            <p class="text-gray-600 mb-3">
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mr-2">
                                    {{ $edicao->data_publicacao->format('d/m/Y') }}
                                </span>
                            </p>
                            
                            @if($edicao->descricao)
                                <p class="text-gray-600 mb-4 text-sm">
                                    {{ \Illuminate\Support\Str::limit($edicao->descricao, 100) }}
                                </p>
                            @endif
                            
                            <div class="flex space-x-3 mt-4">
                                <a href="{{ route('portal.edicoes.show', $edicao) }}" 
                                   class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm">
                                    Visualizar
                                </a>
                                
                                <a href="{{ route('portal.edicoes.pdf', $edicao) }}"
                                   class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors text-sm">
                                    Download PDF
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-6">
                {{ $edicoes->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        renderCalendar();
    });
    
    // Dados de edições por data (a partir do backend)
    const datasComEdicoes = @json($datasEdicoes);
    const mesesAnteriores = @json($mesesAnteriores);
    let dataAtual = new Date('{{ $dataAtual->format('Y-m-d') }}');
    
    function renderCalendar() {
        const year = dataAtual.getFullYear();
        const month = dataAtual.getMonth();
        
        document.querySelector('.month-year').textContent = new Date(year, month, 1).toLocaleDateString('pt-BR', { month: 'long', year: 'numeric' });
        
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        
        const calendarBody = document.getElementById('calendar-body');
        calendarBody.innerHTML = '';
        
        let date = 1;
        const today = new Date();
        
        // Cria as linhas do calendário
        for (let i = 0; i < 6; i++) {
            // Cria uma linha
            const row = document.createElement('tr');
            
            // Cria as células da linha
            for (let j = 0; j < 7; j++) {
                const cell = document.createElement('td');
                
                if (i === 0 && j < firstDay.getDay()) {
                    // Células vazias antes do primeiro dia do mês
                    cell.textContent = '';
                } else if (date > lastDay.getDate()) {
                    // Células vazias após o último dia do mês
                    break;
                } else {
                    // Células com datas
                    cell.textContent = date;
                    
                    // Formata a data para verificar se há edições
                    const currentDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
                    
                    // Verifica se é o dia atual
                    if (today.getDate() === date && today.getMonth() === month && today.getFullYear() === year) {
                        cell.classList.add('today');
                    }
                    
                    // Verifica se há edições na data
                    if (datasComEdicoes.includes(currentDate)) {
                        cell.classList.add('has-edicao');
                        cell.onclick = function() {
                            window.location.href = '{{ route("portal.edicoes.index") }}?data=' + currentDate;
                        };
                    }
                    
                    date++;
                }
                
                row.appendChild(cell);
            }
            
            calendarBody.appendChild(row);
            
            // Se já preenchemos todos os dias, saímos do loop
            if (date > lastDay.getDate()) {
                break;
            }
        }
    }
    
    function changeMonth(step) {
        dataAtual.setMonth(dataAtual.getMonth() + step);
        renderCalendar();
    }
</script>
@endpush
@endsection
