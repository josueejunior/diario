@extends('layouts.portal')

@section('title', $titulo . ' - Diário Oficial')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header da seção -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg p-6">
            <div class="flex items-center">
                <div class="bg-white/20 p-3 rounded-lg mr-4">
                    <i class="fas fa-file-alt text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">{{ $titulo }}</h1>
                    <p class="text-blue-100 mt-1">
                        {{ $materias->total() }} documento{{ $materias->total() !== 1 ? 's' : '' }} encontrado{{ $materias->total() !== 1 ? 's' : '' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    @if($materias->count() > 0)
        <!-- Lista de documentos -->
        <div class="space-y-4">
            @foreach($materias as $materia)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex-1">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="bg-blue-100 text-blue-600 p-2 rounded-lg">
                                        <i class="fas fa-file-alt text-lg"></i>
                                    </div>
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                        {{ $materia->titulo }}
                                    </h3>
                                    
                                    <div class="flex flex-wrap items-center text-sm text-gray-600 space-x-4 mb-3">
                                        @if($materia->tipo)
                                            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded">
                                                {{ $materia->tipo->nome }}
                                            </span>
                                        @endif
                                        
                                        @if($materia->orgao)
                                            <span class="flex items-center">
                                                <i class="fas fa-building mr-1"></i>
                                                {{ $materia->orgao->nome }}
                                            </span>
                                        @endif
                                        
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ $materia->edicao->data->format('d/m/Y') }}
                                        </span>
                                        
                                        <span class="flex items-center">
                                            <i class="fas fa-newspaper mr-1"></i>
                                            Edição {{ $materia->edicao->numero }}
                                        </span>
                                    </div>
                                    
                                    @if($materia->resumo)
                                        <p class="text-gray-700 text-sm leading-relaxed">
                                            {{ Str::limit($materia->resumo, 200) }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-3 mt-4 lg:mt-0">
                            <a href="{{ route('portal.materias.show', $materia) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                <i class="fas fa-eye mr-1"></i>
                                Ver Detalhes
                            </a>
                            
                            <a href="{{ route('portal.edicoes.show', $materia->edicao) }}" 
                               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                <i class="fas fa-newspaper mr-1"></i>
                                Ver Edição
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginação -->
        <div class="mt-8">
            {{ $materias->links() }}
        </div>
    @else
        <!-- Estado vazio -->
        <div class="text-center py-16">
            <div class="bg-gray-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-file-alt text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum documento encontrado</h3>
            <p class="text-gray-600 mb-6">
                Não há {{ strtolower($titulo) }} publicado{{ $titulo !== 'Leis' ? 's' : '' }} no momento.
            </p>
            <a href="{{ route('home') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                <i class="fas fa-home mr-2"></i>
                Voltar ao Início
            </a>
        </div>
    @endif
</div>
@endsection
