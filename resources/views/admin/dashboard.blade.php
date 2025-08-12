<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">Visão Geral do Sistema</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Card de Matérias -->
                        <div class="bg-gradient-to-br from-white to-blue-50 p-6 rounded-xl shadow-md border border-blue-100 transition-all duration-300 hover:shadow-lg">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-lg font-semibold text-gray-700">Matérias</h3>
                                <div class="p-2 bg-blue-100 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="text-3xl font-bold text-gray-800">{{ App\Models\Materia::count() }}</p>
                                    <p class="text-sm text-gray-500">Total de matérias</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-semibold text-green-600">{{ App\Models\Materia::where('status', 'aprovado')->count() }}</p>
                                    <p class="text-sm text-gray-500">Aprovadas</p>
                                </div>
                            </div>
                            <div class="mt-4 pt-4 border-t">
                                <a href="{{ route('materias.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors duration-200">
                                    Ver todas
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <!-- Card de Edições -->
                        <div class="bg-gradient-to-br from-white to-indigo-50 p-6 rounded-xl shadow-md border border-indigo-100 transition-all duration-300 hover:shadow-lg">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-lg font-semibold text-gray-700">Edições</h3>
                                <div class="p-2 bg-indigo-100 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="text-3xl font-bold text-gray-800">{{ App\Models\Edicao::count() }}</p>
                                    <p class="text-sm text-gray-500">Total de edições</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-semibold text-blue-600">{{ App\Models\Edicao::whereDate('data', today())->count() }}</p>
                                    <p class="text-sm text-gray-500">Hoje</p>
                                </div>
                            </div>
                            <div class="mt-4 pt-4 border-t">
                                <a href="{{ route('edicoes.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 text-sm font-medium transition-colors duration-200">
                                    Ver todas
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <!-- Card de Downloads -->
                        <div class="bg-gradient-to-br from-white to-purple-50 p-6 rounded-xl shadow-md border border-purple-100 transition-all duration-300 hover:shadow-lg">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-lg font-semibold text-gray-700">Downloads</h3>
                                <div class="p-2 bg-purple-100 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="text-3xl font-bold text-gray-800">{{ App\Models\Download::count() }}</p>
                                    <p class="text-sm text-gray-500">Total de downloads</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-semibold text-purple-600">{{ App\Models\Download::whereDate('created_at', today())->count() }}</p>
                                    <p class="text-sm text-gray-500">Hoje</p>
                                </div>
                            </div>
                            <div class="mt-4 pt-4 border-t">
                                <a href="{{ route('relatorios.downloads') }}" class="inline-flex items-center text-purple-600 hover:text-purple-800 text-sm font-medium transition-colors duration-200">
                                    Ver relatório
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <!-- Card de Visualizações -->
                        <div class="bg-gradient-to-br from-white to-amber-50 p-6 rounded-xl shadow-md border border-amber-100 transition-all duration-300 hover:shadow-lg">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-lg font-semibold text-gray-700">Visualizações</h3>
                                <div class="p-2 bg-amber-100 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="text-3xl font-bold text-gray-800">{{ App\Models\Visualizacao::count() }}</p>
                                    <p class="text-sm text-gray-500">Total de visualizações</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-semibold text-amber-600">{{ App\Models\Visualizacao::whereDate('created_at', today())->count() }}</p>
                                    <p class="text-sm text-gray-500">Hoje</p>
                                </div>
                            </div>
                            <div class="mt-4 pt-4 border-t">
                                <a href="{{ route('relatorios.visualizacoes') }}" class="inline-flex items-center text-amber-600 hover:text-amber-800 text-sm font-medium transition-colors duration-200">
                                    Ver relatório
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ações Rápidas -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">Ações Rápidas</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <a href="{{ route('materias.create') }}" class="flex items-center p-4 rounded-lg transition-all duration-200 bg-gradient-to-r from-indigo-50 to-indigo-100 hover:from-indigo-100 hover:to-indigo-200 border border-indigo-200 shadow-sm hover:shadow">
                            <div class="p-3 mr-4 rounded-full bg-indigo-500 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-indigo-700 font-medium">Nova Matéria</span>
                                <p class="text-indigo-500 text-sm">Criar publicação</p>
                            </div>
                        </a>
                        <a href="{{ route('edicoes.create') }}" class="flex items-center p-4 rounded-lg transition-all duration-200 bg-gradient-to-r from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 border border-green-200 shadow-sm hover:shadow">
                            <div class="p-3 mr-4 rounded-full bg-green-500 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-green-700 font-medium">Nova Edição</span>
                                <p class="text-green-500 text-sm">Criar diário</p>
                            </div>
                        </a>
                        <a href="{{ route('tipos.index') }}" class="flex items-center p-4 rounded-lg transition-all duration-200 bg-gradient-to-r from-purple-50 to-purple-100 hover:from-purple-100 hover:to-purple-200 border border-purple-200 shadow-sm hover:shadow">
                            <div class="p-3 mr-4 rounded-full bg-purple-500 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-purple-700 font-medium">Gerenciar Tipos</span>
                                <p class="text-purple-500 text-sm">Categorias de matérias</p>
                            </div>
                        </a>
                        <a href="{{ route('orgaos.index') }}" class="flex items-center p-4 rounded-lg transition-all duration-200 bg-gradient-to-r from-amber-50 to-amber-100 hover:from-amber-100 hover:to-amber-200 border border-amber-200 shadow-sm hover:shadow">
                            <div class="p-3 mr-4 rounded-full bg-amber-500 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-amber-700 font-medium">Gerenciar Órgãos</span>
                                <p class="text-amber-500 text-sm">Entidades publicadoras</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
