@extends('layouts.portal')

@section('title', $materia->titulo . ' - Diário Oficial')

@section('content')
<div class="container mx-auto px-4 py-8">
    <nav class="text-gray-500 mb-6" aria-label="Breadcrumb">
        <ol class="list-none p-0 inline-flex">
            <li class="flex items-center">
                <a href="{{ route('portal.home') }}" class="hover:text-blue-600">Início</a>
                <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                    <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/>
                </svg>
            </li>
            <li class="flex items-center">
                <a href="{{ route('portal.materias.index') }}" class="hover:text-blue-600">Matérias</a>
                <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                    <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/>
                </svg>
            </li>
            <li>
                <span class="text-gray-700">{{ \Illuminate\Support\Str::limit($materia->titulo, 40) }}</span>
            </li>
        </ol>
    </nav>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="border-b border-gray-200 pb-4 mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $materia->titulo }}</h1>
            
            <div class="flex flex-wrap gap-2 mt-4">
                @if($materia->tipo)
                    <span class="inline-block bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">
                        {{ $materia->tipo->nome }}
                    </span>
                @endif
                
                @if($materia->orgao)
                    <span class="inline-block bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full">
                        {{ $materia->orgao->nome }}
                    </span>
                @endif
                
                <span class="inline-block bg-gray-100 text-gray-800 text-sm px-3 py-1 rounded-full">
                    {{ $materia->data->format('d/m/Y') }}
                </span>
                
                @if($materia->edicao)
                    <span class="inline-block bg-yellow-100 text-yellow-800 text-sm px-3 py-1 rounded-full">
                        Edição Nº {{ $materia->edicao->numero }}
                    </span>
                @endif
            </div>
        </div>
        
        <div class="prose max-w-none mb-8">
            {!! nl2br(e($materia->texto)) !!}
        </div>
        
        <div class="border-t border-gray-200 pt-4 mt-8">
            <div class="flex flex-wrap justify-between items-center">
                <div>
                    @if($materia->edicao)
                        <a href="{{ route('portal.edicoes.show', $materia->edicao) }}" 
                           class="text-blue-600 hover:underline inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Voltar para Edição Nº {{ $materia->edicao->numero }}
                        </a>
                    @endif
                </div>
                
                <div>
                    @if($materia->edicao)
                        <a href="{{ route('portal.edicoes.pdf', $materia->edicao) }}"
                           class="text-gray-600 hover:text-gray-800 inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Baixar Edição Completa (PDF)
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
