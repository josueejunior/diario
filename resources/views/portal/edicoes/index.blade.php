@extends('layouts.portal')

@section('title', 'Diário Oficial - Edições Recentes')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Diário Oficial</h1>
    
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Edições Recentes</h2>
        
        @if($edicoes->isEmpty())
            <div class="bg-gray-100 p-4 rounded-md text-center">
                <p class="text-gray-600">Nenhuma edição publicada ainda.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($edicoes as $edicao)
                    <div class="border border-gray-200 rounded-lg hover:shadow-lg transition-shadow duration-300 bg-white">
                        <div class="p-5">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">
                                Edição Nº {{ $edicao->numero }}
                            </h3>
                            <p class="text-gray-600 mb-3">
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mr-2">
                                    {{ $edicao->data_publicacao->format('d/m/Y') }}
                                </span>
                            </p>
                            
                            @if($edicao->descricao)
                                <p class="text-gray-600 mb-4 text-sm">
                                    {{ \Illuminate\Support\Str::limit($edicao->descricao, 100) }}
                                </p>
                            @endif
                            
                            <div class="flex space-x-3 mt-4">
                                <a href="{{ route('portal.edicoes.show', $edicao) }}" 
                                   class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm">
                                    Visualizar
                                </a>
                                
                                <a href="{{ route('portal.edicoes.pdf', $edicao) }}"
                                   class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors text-sm">
                                    Download PDF
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-6">
                {{ $edicoes->links() }}
            </div>
        @endif
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Verificação de Autenticidade</h2>
        <p class="text-gray-600 mb-4">
            Para verificar a autenticidade de um documento, utilize a ferramenta de verificação.
        </p>
        <a href="{{ route('portal.verificar') }}" 
           class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors inline-block">
            Verificar Documento
        </a>
    </div>
</div>
@endsection
