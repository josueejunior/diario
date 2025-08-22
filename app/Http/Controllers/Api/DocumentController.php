<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Edicao;
use App\Models\Materia;
use App\Models\Tipo;
use App\Models\Orgao;
use App\Models\Assinatura;
use App\Models\Download;
use App\Models\Visualizacao;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DocumentController extends Controller
{
    /**
     * Lista documentos com paginação e filtros
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = min($request->get('per_page', 15), 100); // Máximo 100 por página
        
        $edicoes = Edicao::with(['materias.tipo', 'materias.orgao', 'assinatura'])
            ->where('publicado', true)
            ->when($request->get('ano'), function ($query, $ano) {
                $query->whereYear('data', $ano);
            })
            ->when($request->get('mes'), function ($query, $mes) {
                $query->whereMonth('data', $mes);
            })
            ->when($request->get('tipo'), function ($query, $tipo) {
                $query->where('tipo', $tipo);
            })
            ->orderBy('data', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $edicoes->items(),
            'pagination' => [
                'current_page' => $edicoes->currentPage(),
                'last_page' => $edicoes->lastPage(),
                'per_page' => $edicoes->perPage(),
                'total' => $edicoes->total(),
                'from' => $edicoes->firstItem(),
                'to' => $edicoes->lastItem(),
            ]
        ]);
    }

    /**
     * Lista matérias com filtros avançados
     */
    public function materias(Request $request): JsonResponse
    {
        $perPage = min($request->get('per_page', 15), 100);
        
        $materias = Materia::with(['tipo', 'orgao', 'edicoes'])
            ->where('status', 'aprovado')
            ->when($request->get('tipo_id'), function ($query, $tipoId) {
                $query->where('tipo_id', $tipoId);
            })
            ->when($request->get('orgao_id'), function ($query, $orgaoId) {
                $query->where('orgao_id', $orgaoId);
            })
            ->when($request->get('data_inicio'), function ($query, $dataInicio) {
                $query->whereDate('data', '>=', $dataInicio);
            })
            ->when($request->get('data_fim'), function ($query, $dataFim) {
                $query->whereDate('data', '<=', $dataFim);
            })
            ->when($request->get('search'), function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('titulo', 'LIKE', "%{$search}%")
                      ->orWhere('texto', 'LIKE', "%{$search}%")
                      ->orWhere('numero', 'LIKE', "%{$search}%");
                });
            })
            ->orderBy('data', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $materias->items(),
            'pagination' => [
                'current_page' => $materias->currentPage(),
                'last_page' => $materias->lastPage(),
                'per_page' => $materias->perPage(),
                'total' => $materias->total(),
                'from' => $materias->firstItem(),
                'to' => $materias->lastItem(),
            ]
        ]);
    }

    /**
     * Exibe uma matéria específica
     */
    public function show(Request $request, Materia $materia): JsonResponse
    {
        if ($materia->status !== 'aprovado') {
            return response()->json([
                'success' => false,
                'message' => 'Matéria não disponível para visualização pública'
            ], 404);
        }

        $materia->load(['tipo', 'orgao', 'edicoes.assinatura']);

        // Registrar visualização se não for bot
        if (!$this->isBot($request->userAgent())) {
            $this->registrarVisualizacao($materia, $request);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $materia->id,
                'titulo' => $materia->titulo,
                'numero' => $materia->numero,
                'texto' => $materia->texto,
                'data' => $materia->data,
                'status' => $materia->status,
                'tipo' => $materia->tipo,
                'orgao' => $materia->orgao,
                'edicoes' => $materia->edicoes,
                'created_at' => $materia->created_at,
                'updated_at' => $materia->updated_at,
            ]
        ]);
    }

    /**
     * Lista tipos de documentos
     */
    public function tipos(): JsonResponse
    {
        $tipos = Tipo::withCount('materias')
            ->where('ativo', true)
            ->orderBy('nome')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $tipos
        ]);
    }

    /**
     * Lista órgãos
     */
    public function orgaos(): JsonResponse
    {
        $orgaos = Orgao::withCount('materias')
            ->where('ativo', true)
            ->orderBy('nome')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $orgaos
        ]);
    }

    /**
     * Verifica autenticidade de documento via hash
     */
    public function verificarHash(Request $request, $hash): JsonResponse
    {
        // Buscar por assinatura com o hash
        $assinatura = Assinatura::where('hash', $hash)->first();
        
        if (!$assinatura) {
            return response()->json([
                'success' => false,
                'message' => 'Hash não encontrado no sistema',
                'authentic' => false
            ], 404);
        }

        // Verificar se a assinatura está vinculada a uma edição ou matéria
        $documento = null;
        $tipo = null;
        
        if ($assinatura->edicao_id) {
            $documento = Edicao::with(['materias', 'assinatura'])->find($assinatura->edicao_id);
            $tipo = 'edicao';
        } elseif ($assinatura->materia_id) {
            $documento = Materia::with(['tipo', 'orgao'])->find($assinatura->materia_id);
            $tipo = 'materia';
        }

        if (!$documento) {
            return response()->json([
                'success' => false,
                'message' => 'Documento associado não encontrado',
                'authentic' => false
            ], 404);
        }

        return response()->json([
            'success' => true,
            'authentic' => true,
            'message' => 'Documento autêntico verificado com sucesso',
            'data' => [
                'tipo' => $tipo,
                'documento' => $documento,
                'assinatura' => [
                    'signatario' => $assinatura->signatario,
                    'cpf_signatario' => $assinatura->cpf_signatario,
                    'cargo_signatario' => $assinatura->cargo_signatario,
                    'ac' => $assinatura->ac,
                    'algoritmo' => $assinatura->algoritmo,
                    'carimbo_tempo' => $assinatura->carimbo_tempo,
                    'hash' => $assinatura->hash
                ]
            ]
        ]);
    }

    /**
     * Estatísticas públicas do sistema
     */
    public function estatisticas(): JsonResponse
    {
        $stats = [
            'total_edicoes' => Edicao::where('publicado', true)->count(),
            'total_materias' => Materia::where('status', 'aprovado')->count(),
            'total_tipos' => Tipo::where('ativo', true)->count(),
            'total_orgaos' => Orgao::where('ativo', true)->count(),
            'ultima_edicao' => Edicao::where('publicado', true)
                ->orderBy('data', 'desc')
                ->first(['numero', 'data', 'created_at']),
            'materias_por_tipo' => Tipo::withCount(['materias' => function ($query) {
                $query->where('status', 'aprovado');
            }])
            ->where('ativo', true)
            ->orderBy('materias_count', 'desc')
            ->limit(10)
            ->get(['nome', 'materias_count']),
            'edicoes_recentes' => Edicao::where('publicado', true)
                ->withCount('materias')
                ->orderBy('data', 'desc')
                ->limit(5)
                ->get(['numero', 'data', 'tipo', 'materias_count']),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'generated_at' => now()->toISOString()
        ]);
    }

    /**
     * Verifica se o user agent é de um bot
     */
    private function isBot($userAgent): bool
    {
        $bots = [
            'bot', 'crawler', 'spider', 'scraper', 'wget', 'curl',
            'googlebot', 'bingbot', 'yahoo', 'slurp', 'facebook',
            'twitter', 'linkedin', 'whatsapp', 'telegram'
        ];

        $userAgent = strtolower($userAgent);
        
        foreach ($bots as $bot) {
            if (strpos($userAgent, $bot) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Registra visualização para estatísticas
     */
    private function registrarVisualizacao(Materia $materia, Request $request): void
    {
        // Evitar registros duplicados da mesma IP na última hora
        $existing = Visualizacao::where('materia_id', $materia->id)
            ->where('ip', $request->ip())
            ->where('created_at', '>=', now()->subHour())
            ->exists();

        if (!$existing) {
            Visualizacao::create([
                'materia_id' => $materia->id,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'origem' => 'api'
            ]);
        }
    }
}
