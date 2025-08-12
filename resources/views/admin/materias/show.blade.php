<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalhes da Matéria') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <a href="{{ route('materias.index') }}" class="text-blue-600 hover:text-blue-900">
                            &larr; Voltar para lista
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">{{ $materia->titulo }}</h3>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('materias.edit', $materia) }}" class="inline-flex items-center px-3 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Editar
                            </a>
                            @if ($materia->status === 'pendente')
                                <form method="POST" action="{{ route('materias.aprovar', $materia) }}" class="inline-block">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Aprovar
                                    </button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('materias.destroy', $materia) }}" class="inline-block" onsubmit="return confirm('Tem certeza que deseja excluir esta matéria?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h4 class="font-semibold text-gray-700 mb-2">Informações Gerais</h4>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Número</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $materia->numero }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Data</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $materia->data->format('d/m/Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tipo</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $materia->tipo->nome ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Órgão</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $materia->orgao->nome ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @switch($materia->status)
                                            @case('pendente')
                                                <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">Pendente</span>
                                                @break
                                            @case('revisao')
                                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">Em Revisão</span>
                                                @break
                                            @case('aprovado')
                                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Aprovado</span>
                                                @break
                                            @default
                                                <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $materia->status }}</span>
                                        @endswitch
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Criado em</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $materia->created_at->format('d/m/Y H:i:s') }}</dd>
                                </div>
                                @if ($materia->status === 'aprovado')
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Aprovado em</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $materia->approved_at->format('d/m/Y H:i:s') }}</dd>
                                </div>
                                @endif
                                @if ($materia->edicao)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Edição</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <a href="{{ route('edicoes.show', $materia->edicao) }}" class="text-blue-600 hover:text-blue-900">
                                            {{ $materia->edicao->numero }} ({{ $materia->edicao->data->format('d/m/Y') }})
                                        </a>
                                    </dd>
                                </div>
                                @endif
                            </dl>
                        </div>

                        @if ($materia->status === 'revisao')
                        <div>
                            <h4 class="font-semibold text-gray-700 mb-2">Notas de Revisão</h4>
                            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded">
                                <p class="text-gray-700">{{ $materia->notas_revisao }}</p>
                            </div>
                            
                            <div class="mt-4">
                                <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" 
                                        onclick="document.getElementById('revisao-form').classList.toggle('hidden')">
                                    Adicionar Nota de Revisão
                                </button>
                                
                                <div id="revisao-form" class="mt-4 hidden">
                                    <form method="POST" action="{{ route('materias.revisar', $materia) }}" class="space-y-4">
                                        @csrf
                                        <div>
                                            <label for="notas_revisao" class="block text-sm font-medium text-gray-700">Nova Nota de Revisão</label>
                                            <textarea name="notas_revisao" id="notas_revisao" rows="4" required
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                                        </div>
                                        <div>
                                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Enviar Revisão
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-700 mb-2">Título</h4>
                        <div class="p-4 bg-gray-50 border border-gray-200 rounded">
                            <p class="text-lg">{{ $materia->titulo }}</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-700 mb-2">Texto da Matéria</h4>
                        <div class="p-4 bg-gray-50 border border-gray-200 rounded">
                            <p class="whitespace-pre-wrap">{{ $materia->texto }}</p>
                        </div>
                    </div>

                    @if ($materia->arquivo)
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-700 mb-2">Arquivo Anexo</h4>
                        <a href="{{ Storage::url($materia->arquivo) }}" target="_blank" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z"/>
                            </svg>
                            <span>Ver Arquivo</span>
                        </a>
                    </div>
                    @endif

                    @if ($materia->status === 'pendente')
                    <div class="mt-8 p-4 bg-yellow-50 border border-yellow-200 rounded">
                        <h4 class="font-semibold text-yellow-800 mb-2">Enviar para Revisão</h4>
                        <p class="text-gray-700 mb-4">Se houver ajustes necessários nesta matéria, envie-a para revisão com as instruções.</p>
                        
                        <form method="POST" action="{{ route('materias.revisar', $materia) }}" class="space-y-4">
                            @csrf
                            <div>
                                <label for="notas_revisao" class="block text-sm font-medium text-gray-700">Notas de Revisão</label>
                                <textarea name="notas_revisao" id="notas_revisao" rows="4" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Descreva os ajustes necessários..."></textarea>
                            </div>
                            <div>
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                    Enviar para Revisão
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
