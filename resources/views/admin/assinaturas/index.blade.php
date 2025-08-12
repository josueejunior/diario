<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assinaturas Digitais') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Histórico de Assinaturas</h3>
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
                                    <th class="py-2 px-4 border-b text-left">Edição</th>
                                    <th class="py-2 px-4 border-b text-left">Signatário</th>
                                    <th class="py-2 px-4 border-b text-left">Data</th>
                                    <th class="py-2 px-4 border-b text-left">Algoritmo</th>
                                    <th class="py-2 px-4 border-b text-left">Hash</th>
                                    <th class="py-2 px-4 border-b text-left">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($assinaturas as $assinatura)
                                    <tr>
                                        <td class="py-2 px-4 border-b">
                                            <a href="{{ route('edicoes.show', $assinatura->edicao) }}" class="text-blue-600 hover:text-blue-900">
                                                {{ $assinatura->edicao->numero }}
                                            </a>
                                        </td>
                                        <td class="py-2 px-4 border-b">{{ $assinatura->signatario }}</td>
                                        <td class="py-2 px-4 border-b">{{ $assinatura->created_at->format('d/m/Y H:i:s') }}</td>
                                        <td class="py-2 px-4 border-b">{{ $assinatura->algoritmo }}</td>
                                        <td class="py-2 px-4 border-b">
                                            <span class="text-xs">{{ Str::limit($assinatura->hash, 20) }}</span>
                                        </td>
                                        <td class="py-2 px-4 border-b">
                                            <a href="{{ route('assinaturas.show', $assinatura) }}" class="text-blue-600 hover:text-blue-900">Detalhes</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-4 px-4 border-b text-center">Nenhuma assinatura encontrada.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $assinaturas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
