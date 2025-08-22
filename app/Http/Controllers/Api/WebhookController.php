<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NotificationSubscription;
use App\Models\Materia;
use App\Models\Edicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class WebhookController extends Controller
{
    /**
     * Registra um webhook
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url|max:255',
            'events' => 'required|array',
            'events.*' => 'in:materia.created,materia.updated,edicao.published,edicao.signed',
            'filters' => 'nullable|array',
            'filters.tipos' => 'nullable|array',
            'filters.orgaos' => 'nullable|array',
            'filters.keywords' => 'nullable|string',
            'secret' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $webhook = NotificationSubscription::create([
            'email' => $request->email ?? 'webhook@system.local',
            'webhook_url' => $request->url,
            'webhook_events' => json_encode($request->events),
            'webhook_filters' => json_encode($request->filters ?? []),
            'webhook_secret' => $request->secret,
            'notify_new_materials' => in_array('materia.created', $request->events),
            'notify_new_editions' => in_array('edicao.published', $request->events),
            'is_webhook' => true,
            'is_verified' => true,
            'is_active' => true
        ]);

        return response()->json([
            'id' => $webhook->id,
            'url' => $webhook->webhook_url,
            'events' => json_decode($webhook->webhook_events),
            'created_at' => $webhook->created_at
        ], 201);
    }

    /**
     * Lista webhooks do usuário
     */
    public function index(Request $request)
    {
        $webhooks = NotificationSubscription::where('is_webhook', true)
            ->when($request->email, function ($query, $email) {
                return $query->where('email', $email);
            })
            ->get();

        return response()->json($webhooks->map(function ($webhook) {
            return [
                'id' => $webhook->id,
                'url' => $webhook->webhook_url,
                'events' => json_decode($webhook->webhook_events ?? '[]'),
                'filters' => json_decode($webhook->webhook_filters ?? '{}'),
                'status' => $webhook->is_active ? 'active' : 'inactive',
                'created_at' => $webhook->created_at,
                'last_triggered' => $webhook->webhook_last_triggered
            ];
        }));
    }

    /**
     * Remove um webhook
     */
    public function destroy($id)
    {
        $webhook = NotificationSubscription::where('is_webhook', true)->findOrFail($id);
        $webhook->delete();

        return response()->json(['message' => 'Webhook removido com sucesso']);
    }

    /**
     * Dispara webhook para nova matéria
     */
    public static function triggerMateriaCreated(Materia $materia)
    {
        $webhooks = NotificationSubscription::where('is_webhook', true)
            ->where('is_active', true)
            ->whereJsonContains('webhook_events', 'materia.created')
            ->get();

        foreach ($webhooks as $webhook) {
            if (self::matchesFilters($webhook, $materia)) {
                self::sendWebhook($webhook, 'materia.created', [
                    'id' => $materia->id,
                    'numero' => $materia->numero,
                    'titulo' => $materia->titulo,
                    'tipo' => [
                        'id' => $materia->tipo->id,
                        'nome' => $materia->tipo->nome
                    ],
                    'orgao' => [
                        'id' => $materia->orgao->id,
                        'nome' => $materia->orgao->nome,
                        'sigla' => $materia->orgao->sigla
                    ],
                    'data' => $materia->data->toISOString(),
                    'status' => $materia->status,
                    'url' => route('portal.atos.show', $materia),
                    'api_url' => route('portal.atos.json', $materia),
                    'created_at' => $materia->created_at->toISOString()
                ]);
            }
        }
    }

    /**
     * Dispara webhook para edição publicada
     */
    public static function triggerEdicaoPublished(Edicao $edicao)
    {
        $webhooks = NotificationSubscription::where('is_webhook', true)
            ->where('is_active', true)
            ->whereJsonContains('webhook_events', 'edicao.published')
            ->get();

        foreach ($webhooks as $webhook) {
            self::sendWebhook($webhook, 'edicao.published', [
                'id' => $edicao->id,
                'numero' => $edicao->numero,
                'data' => $edicao->data->toISOString(),
                'tipo' => $edicao->tipo,
                'materias_count' => $edicao->materias->count(),
                'materias' => $edicao->materias->map(function ($materia) {
                    return [
                        'id' => $materia->id,
                        'numero' => $materia->numero,
                        'titulo' => $materia->titulo,
                        'tipo' => $materia->tipo->nome,
                        'orgao' => $materia->orgao->sigla,
                        'url' => route('portal.atos.show', $materia)
                    ];
                }),
                'url' => route('portal.edicoes.show', $edicao),
                'pdf_url' => route('portal.edicoes.pdf', $edicao),
                'published_at' => $edicao->updated_at->toISOString()
            ]);
        }
    }

    /**
     * Dispara webhook para edição assinada
     */
    public static function triggerEdicaoSigned(Edicao $edicao)
    {
        $webhooks = NotificationSubscription::where('is_webhook', true)
            ->where('is_active', true)
            ->whereJsonContains('webhook_events', 'edicao.signed')
            ->get();

        foreach ($webhooks as $webhook) {
            self::sendWebhook($webhook, 'edicao.signed', [
                'id' => $edicao->id,
                'numero' => $edicao->numero,
                'data' => $edicao->data->toISOString(),
                'assinatura' => [
                    'signatario' => $edicao->assinatura->signatario,
                    'ac' => $edicao->assinatura->ac,
                    'algoritmo' => $edicao->assinatura->algoritmo,
                    'hash' => $edicao->assinatura->hash,
                    'carimbo_tempo' => $edicao->assinatura->carimbo_tempo->toISOString(),
                    'pades_ltv' => $edicao->assinatura->is_ltv_enabled ?? false,
                    'validation_url' => route('portal.verificar', ['hash' => $edicao->assinatura->hash])
                ],
                'url' => route('portal.edicoes.show', $edicao),
                'signed_at' => $edicao->assinatura->carimbo_tempo->toISOString()
            ]);
        }
    }

    /**
     * Verifica se o webhook corresponde aos filtros
     */
    private static function matchesFilters(NotificationSubscription $webhook, Materia $materia): bool
    {
        $filters = json_decode($webhook->webhook_filters ?? '{}', true);

        // Filtro por tipos
        if (!empty($filters['tipos'])) {
            if (!in_array($materia->tipo_id, $filters['tipos'])) {
                return false;
            }
        }

        // Filtro por órgãos
        if (!empty($filters['orgaos'])) {
            if (!in_array($materia->orgao_id, $filters['orgaos'])) {
                return false;
            }
        }

        // Filtro por palavras-chave
        if (!empty($filters['keywords'])) {
            $keywords = explode(',', strtolower($filters['keywords']));
            $found = false;
            
            foreach ($keywords as $keyword) {
                $keyword = trim($keyword);
                if (stripos($materia->titulo, $keyword) !== false || 
                    stripos($materia->texto, $keyword) !== false) {
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                return false;
            }
        }

        return true;
    }

    /**
     * Envia webhook HTTP
     */
    private static function sendWebhook(NotificationSubscription $webhook, string $event, array $data)
    {
        try {
            $payload = [
                'event' => $event,
                'data' => $data,
                'timestamp' => now()->toISOString(),
                'webhook_id' => $webhook->id
            ];

            $headers = [
                'Content-Type' => 'application/json',
                'User-Agent' => 'DiarioOficial-Webhook/1.0'
            ];

            // Adicionar assinatura HMAC se secret estiver definido
            if ($webhook->webhook_secret) {
                $signature = hash_hmac('sha256', json_encode($payload), $webhook->webhook_secret);
                $headers['X-Webhook-Signature'] = 'sha256=' . $signature;
            }

            $response = Http::timeout(30)
                ->withHeaders($headers)
                ->post($webhook->webhook_url, $payload);

            // Atualizar último disparo
            $webhook->update([
                'webhook_last_triggered' => now(),
                'webhook_last_status' => $response->status()
            ]);

            if (!$response->successful()) {
                Log::warning('Webhook failed', [
                    'webhook_id' => $webhook->id,
                    'url' => $webhook->webhook_url,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Webhook error', [
                'webhook_id' => $webhook->id,
                'url' => $webhook->webhook_url,
                'error' => $e->getMessage()
            ]);

            // Desativar webhook após muitas falhas consecutivas
            $webhook->increment('webhook_failures');
            
            if ($webhook->webhook_failures >= 10) {
                $webhook->update(['is_active' => false]);
                Log::warning('Webhook deactivated due to failures', [
                    'webhook_id' => $webhook->id,
                    'failures' => $webhook->webhook_failures
                ]);
            }
        }
    }

    /**
     * Endpoint para teste de webhook
     */
    public function test($id)
    {
        $webhook = NotificationSubscription::where('is_webhook', true)->findOrFail($id);

        self::sendWebhook($webhook, 'test', [
            'message' => 'Este é um teste do webhook',
            'timestamp' => now()->toISOString()
        ]);

        return response()->json(['message' => 'Teste enviado com sucesso']);
    }

    /**
     * Portal CKAN-style para dados abertos
     */
    public function catalog()
    {
        return response()->json([
            'datasets' => [
                [
                    'id' => 'materias',
                    'title' => 'Matérias do Diário Oficial',
                    'description' => 'Conjunto completo de todas as matérias publicadas no Diário Oficial',
                    'url' => route('api.documents.index'),
                    'format' => ['JSON', 'XML'],
                    'license' => 'Domínio Público',
                    'updated' => Materia::max('updated_at')
                ],
                [
                    'id' => 'edicoes',
                    'title' => 'Edições do Diário Oficial',
                    'description' => 'Conjunto completo de todas as edições publicadas',
                    'url' => route('api.edicoes.index'),
                    'format' => ['JSON', 'PDF'],
                    'license' => 'Domínio Público',
                    'updated' => Edicao::max('updated_at')
                ],
                [
                    'id' => 'tipos',
                    'title' => 'Tipos de Atos',
                    'description' => 'Taxonomia dos tipos de atos administrativos',
                    'url' => route('api.tipos.index'),
                    'format' => ['JSON'],
                    'license' => 'Domínio Público',
                    'updated' => \App\Models\Tipo::max('updated_at')
                ],
                [
                    'id' => 'orgaos',
                    'title' => 'Órgãos Municipais',
                    'description' => 'Lista de órgãos da administração municipal',
                    'url' => route('api.orgaos.index'),
                    'format' => ['JSON'],
                    'license' => 'Domínio Público',
                    'updated' => \App\Models\Orgao::max('updated_at')
                ]
            ],
            'api_info' => [
                'version' => '1.0',
                'documentation' => url('/api/docs'),
                'rate_limit' => '1000 requests/hour',
                'authentication' => 'optional',
                'formats' => ['JSON', 'XML', 'CSV']
            ]
        ]);
    }
}
