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
                    <div class="flex items-center mb-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="text-xl font-bold text-gray-800">Painel de Relatórios</h3>
                    </div>

                    <p class="text-gray-600 mb-8">Selecione um dos relatórios abaixo para visualizar estatísticas detalhadas sobre o uso do sistema.</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <a href="{{ route('relatorios.downloads') }}" class="block group">
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-md transition-all duration-300 hover:shadow-lg border border-blue-200 overflow-hidden h-full">
                                <div class="p-6">
                                    <div class="flex justify-between items-start mb-4">
                                        <h4 class="text-lg font-semibold text-blue-800 group-hover:text-blue-900 transition-colors duration-200">Relatório de Downloads</h4>
                                        <span class="inline-flex items-center justify-center p-2 bg-blue-500 text-white rounded-full shadow-sm group-hover:bg-blue-600 transition-colors duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </span>
                                    </div>
                                    <p class="text-blue-700 mb-6">Estatísticas de downloads por edição, período e usuários</p>
                                    <div class="flex justify-between items-end">
                                        <div class="text-3xl font-bold text-blue-900">{{ App\Models\Download::count() }}</div>
                                        <div class="text-blue-600 text-sm">Total de downloads</div>
                                    </div>
                                </div>
                                <div class="px-6 py-3 bg-blue-600 text-white flex justify-between items-center group-hover:bg-blue-700 transition-colors duration-200">
                                    <span class="font-medium">Ver relatório completo</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('relatorios.visualizacoes') }}" class="block group">
                            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-md transition-all duration-300 hover:shadow-lg border border-green-200 overflow-hidden h-full">
                                <div class="p-6">
                                    <div class="flex justify-between items-start mb-4">
                                        <h4 class="text-lg font-semibold text-green-800 group-hover:text-green-900 transition-colors duration-200">Relatório de Visualizações</h4>
                                        <span class="inline-flex items-center justify-center p-2 bg-green-500 text-white rounded-full shadow-sm group-hover:bg-green-600 transition-colors duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </span>
                                    </div>
                                    <p class="text-green-700 mb-6">Acompanhe as visualizações de edições e matérias</p>
                                    <div class="flex justify-between items-end">
                                        <div class="text-3xl font-bold text-green-900">{{ App\Models\Visualizacao::count() }}</div>
                                        <div class="text-green-600 text-sm">Total de visualizações</div>
                                    </div>
                                </div>
                                <div class="px-6 py-3 bg-green-600 text-white flex justify-between items-center group-hover:bg-green-700 transition-colors duration-200">
                                    <span class="font-medium">Ver relatório completo</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('relatorios.publicacoes') }}" class="block group">
                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-md transition-all duration-300 hover:shadow-lg border border-purple-200 overflow-hidden h-full">
                                <div class="p-6">
                                    <div class="flex justify-between items-start mb-4">
                                        <h4 class="text-lg font-semibold text-purple-800 group-hover:text-purple-900 transition-colors duration-200">Relatório de Publicações</h4>
                                        <span class="inline-flex items-center justify-center p-2 bg-purple-500 text-white rounded-full shadow-sm group-hover:bg-purple-600 transition-colors duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </span>
                                    </div>
                                    <p class="text-purple-700 mb-6">Análise detalhada das publicações por período</p>
                                    <div class="flex justify-between items-end">
                                        <div class="text-3xl font-bold text-purple-900">{{ App\Models\Edicao::where('publicado', true)->count() }}</div>
                                        <div class="text-purple-600 text-sm">Total de publicações</div>
                                    </div>
                                </div>
                                <div class="px-6 py-3 bg-purple-600 text-white flex justify-between items-center group-hover:bg-purple-700 transition-colors duration-200">
                                    <span class="font-medium">Ver relatório completo</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Resumo Rápido -->
                    <div class="mt-12 bg-gray-50 rounded-lg p-6 shadow-sm border border-gray-200">
                        <h4 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                            Resumo Geral
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-white p-4 rounded-md shadow-sm border border-gray-100">
                                <p class="text-gray-500 text-sm mb-1">Edições Publicadas (Mês Atual)</p>
                                <p class="text-xl font-bold">{{ App\Models\Edicao::where('publicado', true)->whereMonth('created_at', now()->month)->count() }}</p>
                            </div>
                            <div class="bg-white p-4 rounded-md shadow-sm border border-gray-100">
                                <p class="text-gray-500 text-sm mb-1">Downloads (Mês Atual)</p>
                                <p class="text-xl font-bold">{{ App\Models\Download::whereMonth('created_at', now()->month)->count() }}</p>
                            </div>
                            <div class="bg-white p-4 rounded-md shadow-sm border border-gray-100">
                                <p class="text-gray-500 text-sm mb-1">Visualizações (Mês Atual)</p>
                                <p class="text-xl font-bold">{{ App\Models\Visualizacao::whereMonth('created_at', now()->month)->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
