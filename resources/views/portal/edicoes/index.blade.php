@extends('layouts.portal-standalone')

@section('title', 'Edições - Diário Oficial')
@section('description', 'Navegue por todas as edições publicadas do Diário Oficial da Prefeitura Municipal')

@section('content')
<div class="container mx-auto px-6 py-6">
    
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-4">
            <i class="fas fa-newspaper text-2xl text-[#17639D] dark:text-blue-400"></i>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Edições do Diário Oficial</h1>
        </div>
        <p class="text-gray-600 dark:text-gray-300">Navegue por todas as edições publicadas do Diário Oficial</p>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-slate-700 rounded-lg shadow-lg p-6 mb-6 wl_shadow_3">
        <form method="GET" action="{{ route('portal.edicoes.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Year Filter -->
            <div>
                <label for="ano" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Ano
                </label>
                <select name="ano" id="ano" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:text-white">
                    <option value="">Todos os anos</option>
                    @for($year = date('Y'); $year >= 2020; $year--)
                        <option value="{{ $year }}" {{ request('ano') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endfor
                </select>
            </div>

            <!-- Month Filter -->
            <div>
                <label for="mes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Mês
                </label>
                <select name="mes" id="mes" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:text-white">
                    <option value="">Todos os meses</option>
                    @php
                        $meses = [
                            1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
                            5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
                            9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
                        ];
                    @endphp
                    @foreach($meses as $num => $nome)
                        <option value="{{ $num }}" {{ request('mes') == $num ? 'selected' : '' }}>{{ $nome }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Extra Filter -->
            <div>
                <label for="extra" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Tipo
                </label>
                <select name="extra" id="extra" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:text-white">
                    <option value="">Todas</option>
                    <option value="0" {{ request('extra') === '0' ? 'selected' : '' }}>Regulares</option>
                    <option value="1" {{ request('extra') === '1' ? 'selected' : '' }}>Extras</option>
                </select>
            </div>

            <!-- Submit Button -->
            <div class="flex items-end">
                <button type="submit" class="w-full bg-[#17639D] hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-filter"></i>
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    @if(isset($edicaoDestaque))
    <!-- Featured Edition -->
    <div class="bg-gradient-to-r from-[#17639D] to-blue-600 dark:from-slate-700 dark:to-slate-600 rounded-lg shadow-lg overflow-hidden mb-8 wl_shadow_3">
        <div class="p-6 text-white">
            <div class="flex items-center gap-3 mb-4">
                <i class="fas fa-star text-yellow-300"></i>
                <h2 class="text-xl font-bold">Edição Mais Recente</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                <div>
                    <h3 class="text-2xl font-bold mb-2">
                        Edição {{ $edicaoDestaque->numero }}
                        @if($edicaoDestaque->extra)
                            <span class="text-yellow-300">- Extra</span>
                        @endif
                    </h3>
                    <p class="text-blue-100 dark:text-slate-300 mb-4">
                        {{ $edicaoDestaque->data->format('l, d \de F \de Y') }}
                    </p>
                    <div class="flex items-center gap-4 text-sm text-blue-100 dark:text-slate-300 mb-4">
                        <span><i class="fas fa-eye mr-1"></i> {{ $edicaoDestaque->visualizacoes_count ?? 0 }}</span>
                        <span><i class="fas fa-download mr-1"></i> {{ $edicaoDestaque->downloads_count ?? 0 }}</span>
                        <span><i class="fas fa-file-alt mr-1"></i> {{ $edicaoDestaque->materias_count ?? 0 }} matérias</span>
                    </div>
                </div>
                
                <div class="text-right">
                    <div class="flex flex-col gap-3">
                        <a href="{{ route('portal.edicoes.show', $edicaoDestaque) }}" 
                           class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-semibold transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-eye"></i>
                            Visualizar Edição
                        </a>
                        <a href="{{ route('portal.edicoes.materias', $edicaoDestaque) }}" 
                           class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-list"></i>
                            Ver Matérias
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Results Count -->
    <div class="mb-6">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Encontradas {{ $edicoes->total() }} edições
        </p>
    </div>

    <!-- Editions Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($edicoes as $edicao)
        <div class="bg-white dark:bg-slate-700 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow wl_shadow_3">
            
            <!-- Edition Header -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-slate-600 dark:to-slate-500 p-4 border-b border-gray-200 dark:border-slate-600">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">
                            Edição {{ $edicao->numero }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            {{ $edicao->data->format('d/m/Y') }}
                        </p>
                    </div>
                    
                    @if($edicao->extra)
                    <span class="bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-300 px-2 py-1 rounded-full text-xs font-semibold uppercase">
                        Extra
                    </span>
                    @endif
                </div>
            </div>

            <!-- Edition Stats -->
            <div class="p-4">
                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div class="text-center">
                        <div class="text-lg font-bold text-[#17639D] dark:text-blue-400">
                            {{ $edicao->visualizacoes_count ?? 0 }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Visualizações</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-lg font-bold text-emerald-600 dark:text-emerald-400">
                            {{ $edicao->downloads_count ?? 0 }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Downloads</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-lg font-bold text-purple-600 dark:text-purple-400">
                            {{ $edicao->materias_count ?? 0 }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Matérias</div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col gap-2">
                    <a href="{{ route('portal.edicoes.show', $edicao) }}" 
                       class="w-full bg-[#17639D] hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-eye"></i>
                        Visualizar
                    </a>
                    
                    <a href="{{ route('portal.edicoes.materias', $edicao) }}" 
                       class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-list"></i>
                        Ver Matérias
                    </a>
                </div>
            </div>
        </div>
        @empty
        <!-- Empty State -->
        <div class="col-span-full">
            <div class="text-center py-12">
                <div class="bg-white dark:bg-slate-700 rounded-lg shadow-lg p-8 wl_shadow_3">
                    <i class="fas fa-newspaper text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-500 dark:text-gray-400 mb-2">
                        Nenhuma edição encontrada
                    </h3>
                    <p class="text-gray-400 dark:text-gray-500">
                        @if(request()->hasAny(['ano', 'mes', 'extra']))
                            Tente ajustar os filtros para encontrar mais resultados.
                        @else
                            Não há edições publicadas no momento.
                        @endif
                    </p>
                    
                    @if(request()->hasAny(['ano', 'mes', 'extra']))
                    <a href="{{ route('portal.edicoes.index') }}" 
                       class="inline-flex items-center gap-2 mt-4 text-[#17639D] dark:text-blue-400 hover:underline">
                        <i class="fas fa-times"></i>
                        Limpar filtros
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($edicoes->hasPages())
    <div class="mt-8">
        <div class="bg-white dark:bg-slate-700 rounded-lg shadow-lg p-4 wl_shadow_3">
            {{ $edicoes->withQueryString()->links() }}
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form on select changes for better UX
    ['ano', 'mes', 'extra'].forEach(function(id) {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('change', function() {
                this.closest('form').submit();
            });
        }
    });
});
</script>
@endpush
