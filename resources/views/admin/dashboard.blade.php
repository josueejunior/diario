<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Card de Matérias -->
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold mb-2">Matérias</h3>
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-3xl font-bold">{{ App\Models\Materia::count() }}</p>
                                    <p class="text-sm text-gray-500">Total</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-semibold text-green-600">{{ App\Models\Materia::where('status', 'aprovado')->count() }}</p>
                                    <p class="text-sm text-gray-500">Aprovadas</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('materias.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                    Ver todas →
                                </a>
                            </div>
                        </div>

                        <!-- Card de Edições -->
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold mb-2">Edições</h3>
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-3xl font-bold">{{ App\Models\Edicao::count() }}</p>
                                    <p class="text-sm text-gray-500">Total</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-semibold text-blue-600">{{ App\Models\Edicao::whereDate('data', today())->count() }}</p>
                                    <p class="text-sm text-gray-500">Hoje</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('edicoes.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                    Ver todas →
                                </a>
                            </div>
                        </div>

                        <!-- Card de Downloads -->
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold mb-2">Downloads</h3>
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-3xl font-bold">{{ App\Models\Download::count() }}</p>
                                    <p class="text-sm text-gray-500">Total</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-semibold text-purple-600">{{ App\Models\Download::whereDate('created_at', today())->count() }}</p>
                                    <p class="text-sm text-gray-500">Hoje</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('relatorios.downloads') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                    Ver relatório →
                                </a>
                            </div>
                        </div>

                        <!-- Card de Visualizações -->
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold mb-2">Visualizações</h3>
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-3xl font-bold">{{ App\Models\Visualizacao::count() }}</p>
                                    <p class="text-sm text-gray-500">Total</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-semibold text-yellow-600">{{ App\Models\Visualizacao::whereDate('created_at', today())->count() }}</p>
                                    <p class="text-sm text-gray-500">Hoje</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('relatorios.visualizacoes') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                    Ver relatório →
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Ações Rápidas -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-4">Ações Rápidas</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <a href="{{ route('materias.create') }}" class="flex items-center p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100">
                                <span class="text-indigo-700">Nova Matéria</span>
                            </a>
                            <a href="{{ route('edicoes.create') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100">
                                <span class="text-green-700">Nova Edição</span>
                            </a>
                            <a href="{{ route('tipos.index') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100">
                                <span class="text-purple-700">Gerenciar Tipos</span>
                            </a>
                            <a href="{{ route('orgaos.index') }}" class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100">
                                <span class="text-yellow-700">Gerenciar Órgãos</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</x-admin-layout>
