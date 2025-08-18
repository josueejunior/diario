@extends('layouts.portal-standalone')

@section('title', 'Matérias - Diário Oficial')
@section('description', 'Navegue por todas as matérias publicadas no Diário Oficial da Prefeitura Municipal')

@section('content')
<div class="container mx-auto px-6 py-6">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-4">
            <i class="fas fa-folder text-2xl text-[#17639D] dark:text-blue-400"></i>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Todas as Matérias</h1>
        </div>
        <p class="text-gray-600 dark:text-gray-300">Navegue por todas as matérias publicadas no Diário Oficial</p>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white dark:bg-slate-700 rounded-lg shadow-lg p-6 mb-6 wl_shadow_3">
        <form method="GET" action="{{ route('portal.materias.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search Input -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Buscar matérias
                </label>
                <div class="relative">
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Digite palavras-chave..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:text-white dark:placeholder-gray-400">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>

            <!-- Type Filter -->
            <div>
                <label for="tipo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Tipo de documento
                </label>
                <select name="tipo" 
                        id="tipo"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:text-white">
                    <option value="">Todos os tipos</option>
                    @foreach($tipos as $tipo)
                    <option value="{{ $tipo->slug }}" {{ request('tipo') == $tipo->slug ? 'selected' : '' }}>
                        {{ $tipo->nome }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Orgao Filter -->
            <div>
                <label for="orgao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Órgão
                </label>
                <select name="orgao" 
                        id="orgao"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:text-white">
                    <option value="">Todos os órgãos</option>
                    @foreach($orgaos as $orgao)
                    <option value="{{ $orgao->id }}" {{ request('orgao') == $orgao->id ? 'selected' : '' }}>
                        {{ $orgao->nome }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Submit Button -->
            <div class="flex items-end">
                <button type="submit" 
                        class="w-full bg-[#17639D] hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-filter"></i>
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Results Count -->
    <div class="mb-6">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Encontradas {{ $materias->total() }} matérias
            @if(request('search'))
                para "<strong>{{ request('search') }}</strong>"
            @endif
        </p>
    </div>

    <!-- Materials List -->
    <div class="space-y-4">
        @forelse($materias as $materia)
        <div class="bg-white dark:bg-slate-700 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow wl_shadow_3">
            <div class="p-6">
                <div class="flex items-start justify-between gap-4">
                    <!-- Content -->
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <!-- Type Icon -->
                            @php
                                $typeColors = [
                                    'portaria' => 'text-amber-500',
                                    'decreto' => 'text-blue-500',
                                    'lei' => 'text-green-500',
                                    'resolucao' => 'text-purple-500',
                                    'edital' => 'text-red-500'
                                ];
                                $typeColor = $typeColors[$materia->tipo->slug ?? 'portaria'] ?? 'text-gray-500';
                            @endphp
                            <i class="fas fa-file-alt {{ $typeColor }} text-lg"></i>
                            
                            <!-- Type Badge -->
                            <span class="px-2 py-1 text-xs font-semibold bg-gray-100 dark:bg-slate-600 text-gray-700 dark:text-gray-300 rounded-full uppercase">
                                {{ $materia->tipo->nome ?? 'Documento' }}
                            </span>

                            <!-- Date -->
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $materia->data->format('d/m/Y') }}
                            </span>
                        </div>

                        <!-- Title -->
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2 hover:text-[#17639D] dark:hover:text-blue-400 transition-colors">
                            <a href="{{ route('portal.materias.show', $materia) }}">
                                {{ $materia->titulo }}
                            </a>
                        </h3>

                        <!-- Summary -->
                        @if($materia->resumo)
                        <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed mb-3">
                            {{ Str::limit($materia->resumo, 200) }}
                        </p>
                        @endif

                        <!-- Metadata -->
                        <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                            @if($materia->orgao)
                            <span class="flex items-center gap-1">
                                <i class="fas fa-building"></i>
                                {{ $materia->orgao->nome }}
                            </span>
                            @endif
                            
                            <span class="flex items-center gap-1">
                                <i class="fas fa-eye"></i>
                                {{ $materia->visualizacoes_count ?? 0 }} visualizações
                            </span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('portal.materias.show', $materia) }}" 
                           class="bg-[#17639D] hover:bg-blue-600 dark:bg-slate-600 dark:hover:bg-slate-500 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                            <i class="fas fa-eye"></i>
                            Ver detalhes
                        </a>
                        
                        @if($materia->edicoes->first())
                        <a href="{{ route('portal.edicoes.show', $materia->edicoes->first()) }}" 
                           class="bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-700 dark:hover:bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                            <i class="fas fa-newspaper"></i>
                            Ver edição
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="bg-white dark:bg-slate-700 rounded-lg shadow-lg p-8 wl_shadow_3">
                <i class="fas fa-search text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-500 dark:text-gray-400 mb-2">
                    Nenhuma matéria encontrada
                </h3>
                <p class="text-gray-400 dark:text-gray-500">
                    @if(request('search') || request('tipo'))
                        Tente ajustar os filtros de busca para encontrar mais resultados.
                    @else
                        Não há matérias publicadas no momento.
                    @endif
                </p>
                
                @if(request('search') || request('tipo'))
                <a href="{{ route('portal.materias.index') }}" 
                   class="inline-flex items-center gap-2 mt-4 text-[#17639D] dark:text-blue-400 hover:underline">
                    <i class="fas fa-times"></i>
                    Limpar filtros
                </a>
                @endif
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($materias->hasPages())
    <div class="mt-8">
        <div class="bg-white dark:bg-slate-700 rounded-lg shadow-lg p-4 wl_shadow_3">
            {{ $materias->withQueryString()->links() }}
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form on select change for better UX
    document.getElementById('tipo').addEventListener('change', function() {
        this.closest('form').submit();
    });
});
</script>
@endpush