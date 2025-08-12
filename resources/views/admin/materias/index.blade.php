<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Matérias') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Lista de Matérias</h3>
                        <a href="{{ route('materias.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Nova Matéria
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Formulário de Filtros -->
                    <div class="mb-6 bg-gray-50 p-4 rounded">
                        <form action="{{ route('materias.index') }}" method="GET" class="flex flex-wrap gap-4">
                            <div class="flex-1">
                                <label for="search" class="block text-sm font-medium text-gray-700">Busca</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            
                            <div class="w-1/4">
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Todos</option>
                                    <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                                    <option value="revisao" {{ request('status') == 'revisao' ? 'selected' : '' }}>Em Revisão</option>
                                    <option value="aprovado" {{ request('status') == 'aprovado' ? 'selected' : '' }}>Aprovado</option>
                                </select>
                            </div>
                            
                            <div class="w-1/4">
                                <label for="tipo_id" class="block text-sm font-medium text-gray-700">Tipo</label>
                                <select name="tipo_id" id="tipo_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Todos</option>
                                    @foreach ($tipos as $tipo)
                                        <option value="{{ $tipo->id }}" {{ request('tipo_id') == $tipo->id ? 'selected' : '' }}>{{ $tipo->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="w-1/4">
                                <label for="orgao_id" class="block text-sm font-medium text-gray-700">Órgão</label>
                                <select name="orgao_id" id="orgao_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Todos</option>
                                    @foreach ($orgaos as $orgao)
                                        <option value="{{ $orgao->id }}" {{ request('orgao_id') == $orgao->id ? 'selected' : '' }}>{{ $orgao->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="w-full flex justify-end mt-4">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Filtrar
                                </button>
                                <a href="{{ route('materias.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2">
                                    Limpar
                                </a>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">Número</th>
                                    <th class="py-2 px-4 border-b text-left">Título</th>
                                    <th class="py-2 px-4 border-b text-left">Tipo</th>
                                    <th class="py-2 px-4 border-b text-left">Órgão</th>
                                    <th class="py-2 px-4 border-b text-left">Data</th>
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
                                        <td class="py-2 px-4 border-b">{{ $materia->data->format('d/m/Y') }}</td>
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
                                            <div class="flex space-x-2">
                                                <a href="{{ route('materias.show', $materia) }}" class="text-blue-600 hover:text-blue-900">Ver</a>
                                                <a href="{{ route('materias.edit', $materia) }}" class="text-yellow-600 hover:text-yellow-900">Editar</a>
                                                @if ($materia->status === 'pendente')
                                                    <form method="POST" action="{{ route('materias.aprovar', $materia) }}" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-green-600 hover:text-green-900">Aprovar</button>
                                                    </form>
                                                @endif
                                                <form method="POST" action="{{ route('materias.destroy', $materia) }}" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir esta matéria?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Excluir</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-4 px-4 border-b text-center">Nenhuma matéria encontrada.</td>
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
