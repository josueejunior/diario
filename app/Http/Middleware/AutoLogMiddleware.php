<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SystemLog;

class AutoLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only log for authenticated users and successful requests
        if (Auth::check() && $response->getStatusCode() < 400) {
            $this->logAction($request, $response);
        }

        return $response;
    }

    /**
     * Log important actions automatically
     */
    private function logAction(Request $request, $response)
    {
        $route = $request->route();
        $routeName = $route?->getName();
        $method = $request->method();

        // Skip if not an admin route
        if (!$routeName || !str_starts_with($routeName, 'admin.')) {
            return;
        }

        // Skip GET requests for index/show pages to avoid spam
        if ($method === 'GET' && (
            str_ends_with($routeName, '.index') || 
            str_ends_with($routeName, '.show') ||
            str_ends_with($routeName, '.edit') ||
            str_ends_with($routeName, '.create')
        )) {
            return;
        }

        // Skip logs and search routes
        $skipRoutes = [
            'admin.logs.*',
            'admin.search',
            'admin.dashboard'
        ];

        foreach ($skipRoutes as $skip) {
            if (str($routeName)->is($skip)) {
                return;
            }
        }

        // Map actions
        $actionMapping = $this->getActionMapping($routeName, $method);
        
        if (!$actionMapping) {
            return;
        }

        try {
            SystemLog::create([
                'user_id' => Auth::id(),
                'action' => $actionMapping['action'],
                'description' => $actionMapping['description'],
                'level' => $actionMapping['level'],
                'data' => json_encode([
                    'route' => $routeName,
                    'method' => $method,
                    'url' => $request->url(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'parameters' => $this->filterSensitiveData($request->all())
                ]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
        } catch (\Exception $e) {
            // Fail silently to avoid breaking the application
            \Log::error('Failed to create system log: ' . $e->getMessage());
        }
    }

    /**
     * Get action mapping for route
     */
    private function getActionMapping(string $routeName, string $method): ?array
    {
        // Extract resource and action from route name
        $parts = explode('.', $routeName);
        
        if (count($parts) < 2) {
            return null;
        }

        $resource = $parts[1]; // admin.users.store -> users
        $action = end($parts); // admin.users.store -> store

        // Resource name mapping
        $resourceNames = [
            'users' => 'usuário',
            'materias' => 'matéria',
            'edicoes' => 'edição',
            'tipos' => 'tipo de matéria',
            'orgaos' => 'órgão',
            'legislacao' => 'legislação',
            'certificados-nuvem' => 'certificado',
            'configuracoes' => 'configurações',
            'logs' => 'logs'
        ];

        $resourceName = $resourceNames[$resource] ?? $resource;

        // Action mapping
        $actionMappings = [
            'store' => [
                'action' => 'create',
                'description' => "Criou {$resourceName}",
                'level' => 'info'
            ],
            'update' => [
                'action' => 'update',
                'description' => "Atualizou {$resourceName}",
                'level' => 'info'
            ],
            'destroy' => [
                'action' => 'delete',
                'description' => "Excluiu {$resourceName}",
                'level' => 'warning'
            ],
            'publicar' => [
                'action' => 'publish',
                'description' => "Publicou {$resourceName}",
                'level' => 'info'
            ],
            'aprovar' => [
                'action' => 'approve',
                'description' => "Aprovou {$resourceName}",
                'level' => 'info'
            ],
            'assinar' => [
                'action' => 'sign',
                'description' => "Assinou {$resourceName}",
                'level' => 'info'
            ],
            'toggle-status' => [
                'action' => 'status_change',
                'description' => "Alterou status de {$resourceName}",
                'level' => 'info'
            ],
            'reset-password' => [
                'action' => 'password_reset',
                'description' => "Resetou senha de {$resourceName}",
                'level' => 'warning'
            ]
        ];

        return $actionMappings[$action] ?? null;
    }

    /**
     * Filter sensitive data from request
     */
    private function filterSensitiveData(array $data): array
    {
        $sensitiveFields = [
            'password',
            'password_confirmation',
            'token',
            'api_key',
            'secret',
            '_token',
            '_method'
        ];

        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = '[FILTERED]';
            }
        }

        // Limit data size to prevent bloat
        return array_slice($data, 0, 20);
    }
}
