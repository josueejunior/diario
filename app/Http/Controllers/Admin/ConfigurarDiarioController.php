<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CertificadoNuvem;
use App\Models\ConfiguracaoSistema;
use Illuminate\Http\Request;

class ConfigurarDiarioController extends Controller
{
    public function index()
    {
        // Buscar certificados ativos
        $certificados = CertificadoNuvem::where('status', 'ativo')
            ->where('data_vencimento', '>', now())
            ->orderBy('nome')
            ->get();

        // Buscar configurações do diário
        $configuracoes = ConfiguracaoSistema::whereIn('chave', [
            'cabecalho',
            'rodape',
            'logo',
            'margem_superior',
            'margem_inferior',
            'margem_esquerda',
            'margem_direita',
            'fonte',
            'tamanho_fonte',
            'tipo_assinatura',
            'certificado_id',
            'posicao_assinatura'
        ])->pluck('valor', 'chave');

        return view('admin.configurar-diario.index', compact('certificados', 'configuracoes'));
    }

    public function salvarTemplate(Request $request)
    {
        $request->validate([
            'cabecalho' => 'nullable|string|max:1000',
            'rodape' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'margem_superior' => 'nullable|integer|min:0|max:100',
            'margem_inferior' => 'nullable|integer|min:0|max:100',
            'margem_esquerda' => 'nullable|integer|min:0|max:100',
            'margem_direita' => 'nullable|integer|min:0|max:100',
            'fonte' => 'nullable|string|in:Arial,Times New Roman,Helvetica,Courier New',
            'tamanho_fonte' => 'nullable|integer|in:10,11,12,14'
        ]);

        $configuracoes = $request->except(['_token', 'logo']);

        // Upload da logo se fornecida
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $configuracoes['logo'] = $logoPath;
        }

        // Salvar cada configuração
        foreach ($configuracoes as $chave => $valor) {
            ConfiguracaoSistema::updateOrCreate(
                ['chave' => $chave],
                ['valor' => $valor, 'grupo' => 'diario_template']
            );
        }

        return response()->json(['success' => true, 'message' => 'Template salvo com sucesso']);
    }

    public function configurarAssinatura(Request $request)
    {
        $request->validate([
            'tipo_assinatura' => 'required|in:automatica,manual',
            'certificado_id' => 'nullable|exists:certificados_nuvem,id',
            'posicao_assinatura' => 'required|in:final,rodape,primeira_pagina'
        ]);

        $configuracoes = [
            'tipo_assinatura' => $request->tipo_assinatura,
            'certificado_id' => $request->certificado_id,
            'posicao_assinatura' => $request->posicao_assinatura
        ];

        // Salvar configurações de assinatura
        foreach ($configuracoes as $chave => $valor) {
            ConfiguracaoSistema::updateOrCreate(
                ['chave' => $chave],
                ['valor' => $valor, 'grupo' => 'diario_assinatura']
            );
        }

        return response()->json(['success' => true, 'message' => 'Assinatura configurada com sucesso']);
    }
}
