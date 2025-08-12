<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Relatórios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-6">Painel de Relatórios</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <a href="{{ route('relatorios.downloads') }}" class="block p-6 bg-blue-50 hover:bg-blue-100 rounded-lg shadow-md transition">
                            <h4 class="text-lg font-semibold text-blue-700">Relatório de Downloads</h4>
                            <p class="text-sm text-blue-600 mt-1">Estatísticas de downloads por edição, período e usuários</p>
                            <div class="mt-4 text-center">
                                <svg class="w-10 h-10 mx-auto text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('relatorios.visualizacoes') }}" class="block p-6 bg-green-50 hover:bg-green-100 rounded-lg shadow-md transition">
                            <h4 class="text-lg font-semibold text-green-700">Relatório de Visualizações</h4>
                            <p class="text-sm text-green-600 mt-1">Acompanhe as visualizações de edições e matérias</p>
                            <div class="mt-4 text-center">
                                <svg class="w-10 h-10 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('relatorios.publicacoes') }}" class="block p-6 bg-purple-50 hover:bg-purple-100 rounded-lg shadow-md transition">
                            <h4 class="text-lg font-semibold text-purple-700">Relatório de Publicações</h4>
                            <p class="text-sm text-purple-600 mt-1">Análise detalhada das publicações por período</p>
                            <div class="mt-4 text-center">
                                <svg class="w-10 h-10 mx-auto text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
