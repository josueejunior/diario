<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalhes da Edição') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <a href="{{ route('edicoes.index') }}" class="text-blue-600 hover:text-blue-900">
                            &larr; Voltar para lista
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Edição {{ $edicao->numero }}</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('edicoes.edit', $edicao) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                Editar
                            </a>
                            @if (!$edicao->publicado)
                                <form method="POST" action="{{ route('edicoes.publicar', $edicao) }}">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        Publicar
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('edicoes.assinar', $edicao) }}">
                                    @csrf
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Assinar Digitalmente
                                    </button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('edicoes.destroy', $edicao) }}" onsubmit="return confirm('Tem certeza que deseja excluir esta edição?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-semibold text-gray-700 mb-2">Informações Gerais</h4>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Número</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $edicao->numero }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Data</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $edicao->data->format('d/m/Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tipo</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($edicao->tipo) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if ($edicao->publicado)
                                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Publicado</span>
                                        @else
                                            <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">Rascunho</span>
                                        @endif
                                    </dd>
                                </div>
                                @if ($edicao->publicado)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Data de Publicação</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $edicao->data_publicacao->format('d/m/Y H:i:s') }}</dd>
                                </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tamanho</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ number_format($edicao->tamanho / 1024, 2) }} KB</dd>
                                </div>
                            </dl>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-700 mb-2">Assinatura Digital</h4>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Hash</dt>
                                    <dd class="mt-1 text-sm text-gray-900 break-all">{{ $edicao->hash }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Signatário</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $edicao->signatario }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Autoridade Certificadora</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $edicao->ac }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Algoritmo</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $edicao->algoritmo }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Carimbo de Tempo</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $edicao->carimbo_tempo->format('d/m/Y H:i:s') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    @if ($edicao->descricao)
                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-700 mb-2">Descrição</h4>
                        <p class="text-gray-900">{{ $edicao->descricao }}</p>
                    </div>
                    @endif

                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-700 mb-2">Arquivo PDF</h4>
                        @if ($edicao->caminho_arquivo)
                            <a href="{{ Storage::url($edicao->caminho_arquivo) }}" target="_blank" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z"/>
                                </svg>
                                <span>Ver PDF</span>
                            </a>
                        @else
                            <p class="text-red-600">Nenhum arquivo disponível.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Matérias Desta Edição</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">Número</th>
                                    <th class="py-2 px-4 border-b text-left">Título</th>
                                    <th class="py-2 px-4 border-b text-left">Tipo</th>
                                    <th class="py-2 px-4 border-b text-left">Órgão</th>
                                    <th class="py-2 px-4 border-b text-left">Status</th>
                                    <th class="py-2 px-4 border-b text-left">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($materias as $materia)
                                    <tr>
                                        <td class="py-2 px-4 border-b">{{ $materia->numero }}</td>
                                        <td class="py-2 px-4 border-b">{{ Str::limit($materia->titulo, 40) }}</td>
                                        <td class="py-2 px-4 border-b">{{ $materia->tipo->nome ?? 'N/A' }}</td>
                                        <td class="py-2 px-4 border-b">{{ $materia->orgao->nome ?? 'N/A' }}</td>
                                        <td class="py-2 px-4 border-b">
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
                                        </td>
                                        <td class="py-2 px-4 border-b">
                                            <a href="{{ route('materias.show', $materia) }}" class="text-blue-600 hover:text-blue-900">Ver</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-4 px-4 border-b text-center">Nenhuma matéria encontrada nesta edição.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $materias->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
