<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assinatura;
use App\Models\Edicao;
use Illuminate\Http\Request;
use App\Services\AssinaturaService;

class AssinaturaController extends Controller
{
    protected $assinaturaService;

    /**
     * Construtor que inicializa o serviço de assinatura.
     *
     * @param AssinaturaService $assinaturaService
     */
    public function __construct(AssinaturaService $assinaturaService)
    {
        $this->assinaturaService = $assinaturaService;
    }
    
    /**
     * Exibe uma lista de todas as assinaturas.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Assinatura::with('edicao');
        
        // Filtros
        if ($request->has('data_inicio') && $request->data_inicio) {
            $query->whereDate('carimbo_tempo', '>=', $request->data_inicio);
        }
        
        if ($request->has('data_fim') && $request->data_fim) {
            $query->whereDate('carimbo_tempo', '<=', $request->data_fim);
        }
        
        if ($request->has('signatario') && $request->signatario) {
            $query->where('signatario', 'like', '%' . $request->signatario . '%');
        }
        
        if ($request->has('edicao_id') && $request->edicao_id) {
            $query->where('edicao_id', $request->edicao_id);
        }
        
        // Ordenação
        $assinaturas = $query->orderBy('carimbo_tempo', 'desc')->paginate(15);
        $edicoes = Edicao::orderBy('data', 'desc')->get();
        
        return view('admin.assinaturas.index', compact('assinaturas', 'edicoes'));
    }
    
    /**
     * Exibe os detalhes de uma assinatura específica.
     *
     * @param  \App\Models\Assinatura  $assinatura
     * @return \Illuminate\View\View
     */
    public function show(Assinatura $assinatura)
    {
        $assinatura->load('edicao');
        $validacao = $this->assinaturaService->verificar($assinatura);
        
        return view('admin.assinaturas.show', compact('assinatura', 'validacao'));
    }
}
