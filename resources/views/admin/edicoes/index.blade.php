<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edições') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Lista de Edições</h3>
                        <a href="{{ route('edicoes.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Nova Edição
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">Número</th>
                                    <th class="py-2 px-4 border-b text-left">Data</th>
                                    <th class="py-2 px-4 border-b text-left">Tipo</th>
                                    <th class="py-2 px-4 border-b text-left">Status</th>
                                    <th class="py-2 px-4 border-b text-left">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($edicoes as $edicao)
                                    <tr>
                                        <td class="py-2 px-4 border-b">{{ $edicao->numero }}</td>
                                        <td class="py-2 px-4 border-b">{{ $edicao->data->format('d/m/Y') }}</td>
                                        <td class="py-2 px-4 border-b">{{ ucfirst($edicao->tipo) }}</td>
                                        <td class="py-2 px-4 border-b">
                                            @if ($edicao->publicado)
                                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Publicado</span>
                                            @else
                                                <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">Rascunho</span>
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border-b">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('edicoes.show', $edicao) }}" class="text-blue-600 hover:text-blue-900">Ver</a>
                                                <a href="{{ route('edicoes.edit', $edicao) }}" class="text-yellow-600 hover:text-yellow-900">Editar</a>
                                                @if (!$edicao->publicado)
                                                    <form method="POST" action="{{ route('edicoes.publicar', $edicao) }}" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-green-600 hover:text-green-900">Publicar</button>
                                                    </form>
                                                @endif
                                                <form method="POST" action="{{ route('edicoes.destroy', $edicao) }}" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir esta edição?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Excluir</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-4 px-4 border-b text-center">Nenhuma edição encontrada.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $edicoes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
