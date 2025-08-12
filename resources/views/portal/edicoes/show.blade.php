@extends('layouts.portal')

@section('title', 'Edição Nº ' . $edicao->numero . ' - Diário Oficial')

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
                <a href="{{ route('portal.edicoes.index') }}" class="hover:text-blue-600">Edições</a>
                <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                    <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/>
                </svg>
            </li>
            <li>
                <span class="text-gray-700">Edição Nº {{ $edicao->numero }}</span>
            </li>
        </ol>
    </nav>
    
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex flex-wrap justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Edição Nº {{ $edicao->numero }}</h1>
                <p class="text-gray-600">
                    <span class="font-medium">Data de Publicação:</span> {{ $edicao->data_publicacao->format('d/m/Y') }}
                </p>
            </div>
            
            <div class="flex space-x-3 mt-4 md:mt-0">
                <a href="{{ route('portal.edicoes.materias', $edicao) }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    Ver Matérias
                </a>
                
                <a href="{{ route('portal.edicoes.pdf', $edicao) }}"
                   class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                    Download PDF
                </a>
            </div>
        </div>
        
        @if($edicao->descricao)
            <div class="bg-gray-50 p-4 rounded-md mb-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-2">Descrição</h2>
                <p class="text-gray-600">{{ $edicao->descricao }}</p>
            </div>
        @endif
        
        <div>
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Informações da Edição</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-blue-50 p-4 rounded-md">
                    <h3 class="font-medium text-blue-800 mb-2">Código de Autenticidade</h3>
                    <p class="font-mono text-sm bg-white p-2 border border-blue-100 rounded">{{ $edicao->hash }}</p>
                </div>
                
                <div class="bg-green-50 p-4 rounded-md">
                    <h3 class="font-medium text-green-800 mb-2">Matérias</h3>
                    <p class="text-green-700">
                        {{ $edicao->materias->count() }} matéria(s) publicada(s)
                    </p>
                    <a href="{{ route('portal.edicoes.materias', $edicao) }}" 
                       class="inline-block mt-2 text-sm text-green-700 hover:underline">
                        Visualizar todas as matérias →
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Verificação de Autenticidade</h2>
        <p class="text-gray-600 mb-4">
            Para verificar a autenticidade deste documento, utilize a ferramenta de verificação com o código: 
            <span class="font-mono bg-gray-100 p-1">{{ $edicao->hash }}</span>
        </p>
        <a href="{{ route('portal.verificar') }}" 
           class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors inline-block">
            Ir para Verificação
        </a>
    </div>
</div>
@endsection
