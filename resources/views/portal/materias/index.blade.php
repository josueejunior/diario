@extends('layouts.portal')

@section('title', 'Matérias Publicadas - Diário Oficial')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Matérias Publicadas</h1>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        @if($materias->isEmpty())
            <div class="bg-gray-100 p-4 rounded-md text-center">
                <p class="text-gray-600">Nenhuma matéria encontrada.</p>
            </div>
        @else
            <div class="mb-6">
                <form action="{{ route('portal.materias.index') }}" method="GET" class="flex flex-wrap gap-4">
                    <div class="flex-grow">
                        <input type="text" name="search" placeholder="Buscar matérias..." 
                               value="{{ request('search') }}"
                               class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Buscar
                    </button>
                </form>
            </div>

            <div class="space-y-6">
                @foreach($materias as $materia)
                    <div class="border border-gray-200 rounded-lg p-5 hover:shadow-md transition-shadow">
                        <div class="flex flex-wrap justify-between items-start mb-3">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800 hover:text-blue-600">
                                    <a href="{{ route('portal.materias.show', $materia) }}">
                                        {{ $materia->titulo }}
                                    </a>
                                </h2>
                                <p class="text-gray-500 text-sm mt-1">
                                    <span class="font-medium">Edição:</span> 
                                    @if($materia->edicao)
                                        <a href="{{ route('portal.edicoes.show', $materia->edicao) }}" class="text-blue-600 hover:underline">
                                            Nº {{ $materia->edicao->numero }}
                                        </a>
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                            <div class="mt-2 md:mt-0">
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                    {{ $materia->tipo->nome ?? 'Sem tipo' }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap justify-between text-sm text-gray-600 mt-4">
                            <div>
                                <span class="font-medium">Órgão:</span> {{ $materia->orgao->nome ?? 'N/A' }}
                            </div>
                            <div>
                                <span class="font-medium">Data:</span> {{ $materia->data->format('d/m/Y') }}
                            </div>
                            <div>
                                <a href="{{ route('portal.materias.show', $materia) }}" class="text-blue-600 hover:underline">
                                    Ler matéria →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-6">
                {{ $materias->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
