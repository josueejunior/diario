@extends('layouts.portal')

@section('title', 'Verificar Autenticidade - Diário Oficial')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Verificar Autenticidade</h1>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">
            Verificação de Autenticidade de Documentos
        </h2>
        
        <p class="text-gray-600 mb-6">
            Use o código de autenticidade (hash) presente no documento para verificar sua autenticidade.
            Digite o código no campo abaixo e clique em "Verificar".
        </p>
        
        <form action="{{ route('portal.verificar.hash') }}" method="POST" class="mb-8">
            @csrf
            <div class="mb-4">
                <label for="hash" class="block text-gray-700 text-sm font-medium mb-2">
                    Código de Autenticidade (Hash)
                </label>
                <input type="text" name="hash" id="hash" 
                       class="w-full border border-gray-300 rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Digite o código de autenticidade"
                       required>
            </div>
            
            <button type="submit" class="w-full md:w-auto px-6 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition-colors">
                Verificar Autenticidade
            </button>
        </form>
        
        <div class="bg-gray-50 p-4 rounded-md">
            <h3 class="text-lg font-medium text-gray-700 mb-3">Como funciona?</h3>
            <div class="text-gray-600 space-y-2">
                <p>
                    Cada documento publicado no Diário Oficial recebe um código único de autenticidade (hash).
                </p>
                <p>
                    Este código é gerado usando algoritmos criptográficos avançados que garantem a integridade 
                    e autenticidade do documento.
                </p>
                <p>
                    Ao verificar o código, nosso sistema confirma se o documento é oficial e não foi alterado 
                    desde sua publicação.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
