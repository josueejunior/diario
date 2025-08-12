@extends('layouts.portal')

@section('title', 'Matérias da Edição Nº ' . $edicao->numero . ' - Diário Oficial')

@section('content')
<div class="container mx-auto px-4 py-8">
    <nav class="text-gray-500 mb-6" aria-label="Breadcrumb">
        <ol class="list-none p-0 inline-flex">
            <li class="flex items-center">
                <a href="{{ route('home') }}" class="hover:text-blue-600">Início</a>
                <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                    <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/>
                </svg>
            </li>
            <li class="flex items-center">
                <a href="{{ route('portal.edicoes.index') }}" class="hover:text-blue-600">Edições</a>
                <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                    <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/>
                </svg>
            </li>
            <li class="flex items-center">
                <a href="{{ route('portal.edicoes.show', $edicao) }}" class="hover:text-blue-600">
                    Edição Nº {{ $edicao->numero }}
                </a>
                <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                    <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/>
                </svg>
            </li>
            <li>
                <span class="text-gray-700">Matérias</span>
            </li>
        </ol>
    </nav>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-wrap justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    Matérias da Edição Nº {{ $edicao->numero }}
                </h1>
                <p class="text-gray-600">
                    <span class="font-medium">Data de Publicação:</span> {{ $edicao->data_publicacao ? $edicao->data_publicacao->format('d/m/Y') : $edicao->data->format('d/m/Y') }}
                </p>
            </div>
            
            <div class="mt-4 md:mt-0">
                <a href="{{ route('portal.edicoes.pdf', $edicao) }}"
                   class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                    Download PDF
                </a>
            </div>
        </div>
        
        @if($materias->isEmpty())
            <div class="bg-gray-100 p-4 rounded-md text-center">
                <p class="text-gray-600">Nenhuma matéria encontrada nesta edição.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-3 px-4 text-left text-gray-700 font-medium text-sm">Título</th>
                            <th class="py-3 px-4 text-left text-gray-700 font-medium text-sm">Tipo</th>
                            <th class="py-3 px-4 text-left text-gray-700 font-medium text-sm">Órgão</th>
                            <th class="py-3 px-4 text-left text-gray-700 font-medium text-sm">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($materias as $materia)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4 text-gray-800">{{ $materia->titulo }}</td>
                                <td class="py-3 px-4 text-gray-600">{{ $materia->tipo->nome ?? 'N/A' }}</td>
                                <td class="py-3 px-4 text-gray-600">{{ $materia->orgao->nome ?? 'N/A' }}</td>
                                <td class="py-3 px-4">
                                    <a href="{{ route('portal.materias.show', $materia) }}" 
                                       class="text-blue-600 hover:text-blue-800">
                                        Ver Detalhes
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6">
                {{ $materias->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
