<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditTrailMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Só registra se for uma requisição que modifica dados
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $this->logActivity($request, $response);
        }

        return $response;
    }

    /**
     * Registra a atividade no log de auditoria
     */
    private function logActivity(Request $request, $response)
    {
        try {
            $user = Auth::user();
            
            // Extrair ID do recurso da URL se possível
            $resourceType = $this->extractResourceType($request);
            $resourceId = $this->extractResourceId($request);
            
            AuditLog::create([
                'user_id' => $user ? $user->id : null,
                'action' => $this->getActionName($request),
                'resource_type' => $resourceType,
                'resource_id' => $resourceId,
                'old_values' => $this->getOldValues($request),
                'new_values' => $this->getNewValues($request),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'http_method' => $request->method(),
                'status_code' => $response->getStatusCode(),
                'session_id' => $request->session()->getId(),
                'metadata' => [
                    'referer' => $request->header('referer'),
                    'route_name' => $request->route()?->getName(),
                    'parameters' => $request->route()?->parameters(),
                ]
            ]);
        } catch (\Exception $e) {
            // Log de auditoria não deve quebrar a aplicação
            \Log::error('Erro no middleware de auditoria: ' . $e->getMessage());
        }
    }

    /**
     * Extrai o tipo de recurso da URL
     */
    private function extractResourceType(Request $request): ?string
    {
        $path = $request->path();
        
        $patterns = [
            'admin/edicoes' => 'Edicao',
            'admin/materias' => 'Materia',
            'admin/tipos' => 'Tipo',
            'admin/orgaos' => 'Orgao',
            'admin/assinaturas' => 'Assinatura',
            'portal/subscriptions' => 'NotificationSubscription',
        ];

        foreach ($patterns as $pattern => $type) {
            if (str_contains($path, $pattern)) {
                return $type;
            }
        }

        return null;
    }

    /**
     * Extrai o ID do recurso da URL
     */
    private function extractResourceId(Request $request): ?int
    {
        $routeParameters = $request->route()?->parameters() ?? [];
        
        // Tenta encontrar um parâmetro que pareça ser um ID
        foreach ($routeParameters as $key => $value) {
            if (is_numeric($value) || (is_object($value) && method_exists($value, 'getKey'))) {
                return is_object($value) ? $value->getKey() : (int) $value;
            }
        }

        return null;
    }

    /**
     * Determina o nome da ação baseado no método HTTP e rota
     */
    private function getActionName(Request $request): string
    {
        $method = $request->method();
        $routeName = $request->route()?->getName() ?? '';

        if (str_contains($routeName, 'store') || $method === 'POST') {
            return 'created';
        }

        if (str_contains($routeName, 'update') || in_array($method, ['PUT', 'PATCH'])) {
            return 'updated';
        }

        if (str_contains($routeName, 'destroy') || $method === 'DELETE') {
            return 'deleted';
        }

        if (str_contains($routeName, 'publicar')) {
            return 'published';
        }

        if (str_contains($routeName, 'assinar')) {
            return 'signed';
        }

        if (str_contains($routeName, 'aprovar')) {
            return 'approved';
        }

        return 'modified';
    }

    /**
     * Captura valores antigos (antes da modificação)
     */
    private function getOldValues(Request $request): ?array
    {
        // Para updates, tentamos capturar o estado anterior
        if (in_array($request->method(), ['PUT', 'PATCH']) && $request->route()) {
            $parameters = $request->route()->parameters();
            
            foreach ($parameters as $parameter) {
                if (is_object($parameter) && method_exists($parameter, 'getOriginal')) {
                    return $parameter->getOriginal();
                }
            }
        }

        return null;
    }

    /**
     * Captura valores novos (após a modificação)
     */
    private function getNewValues(Request $request): ?array
    {
        $data = $request->all();
        
        // Remove campos sensíveis
        unset($data['password'], $data['password_confirmation'], $data['_token'], $data['_method']);
        
        return $data ?: null;
    }
}
