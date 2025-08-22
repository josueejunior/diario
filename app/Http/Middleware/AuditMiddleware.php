<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SystemLog;

class AuditMiddleware
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

        // Only log for authenticated users in admin routes
        if (Auth::check() && $request->is('admin/*')) {
            $this->logRequest($request, $response);
        }

        return $response;
    }

    /**
     * Log the request details
     */
    private function logRequest(Request $request, $response)
    {
        // Skip logging for certain routes
        $skipRoutes = [
            'admin.logs.*',
            'admin.search',
            'admin.dashboard'
        ];

        $routeName = $request->route()?->getName();
        
        if ($routeName && collect($skipRoutes)->contains(fn($skip) => str($routeName)->is($skip))) {
            return;
        }

        // Determine action type
        $method = $request->method();
        $action = $this->determineAction($request, $method);
        
        if (!$action) {
            return;
        }

        // Get description
        $description = $this->getActionDescription($request, $action);

        // Determine level
        $level = $this->determineLevel($method, $response->getStatusCode());

        // Collect relevant data
        $data = [
            'method' => $method,
            'url' => $request->fullUrl(),
            'route' => $routeName,
            'status_code' => $response->getStatusCode(),
            'parameters' => $this->filterSensitiveData($request->all())
        ];

        // Only log if we have meaningful data
        if ($description !== 'Acesso a página') {
            SystemLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'module' => $this->getModuleFromRoute($routeName),
                'resource_type' => $this->getResourceTypeFromRoute($routeName),
                'resource_id' => $this->getResourceIdFromRequest($request),
                'description' => $description,
                'old_values' => null,
                'new_values' => json_encode($data),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $method,
                'response_code' => $response->getStatusCode(),
                'created_at' => now()
            ]);
        }
    }

    /**
     * Determine the action type based on request
     */
    private function determineAction(Request $request, string $method): ?string
    {
        $route = $request->route();
        $routeName = $route?->getName();
        
        if (!$routeName) {
            return null;
        }

        // Map route patterns to actions
        $actionMappings = [
            '*.store' => 'create',
            '*.update' => 'update',
            '*.destroy' => 'delete',
            '*.publicar' => 'publish',
            '*.aprovar' => 'approve',
            '*.revisar' => 'review',
            '*.assinar' => 'sign',
            '*.export' => 'export',
            '*.download' => 'download',
            '*.upload' => 'upload',
            '*.clear' => 'clear',
            '*.backup' => 'backup',
            '*.restore' => 'restore'
        ];

        foreach ($actionMappings as $pattern => $action) {
            if (str($routeName)->is($pattern)) {
                return $action;
            }
        }

        // Default actions based on HTTP method
        return match($method) {
            'POST' => 'create',
            'PUT', 'PATCH' => 'update',
            'DELETE' => 'delete',
            default => null
        };
    }

    /**
     * Get human-readable description
     */
    private function getActionDescription(Request $request, string $action): string
    {
        $route = $request->route();
        $routeName = $route?->getName();
        
        // Extract resource from route name
        $resource = $this->extractResourceFromRoute($routeName);
        
        $descriptions = [
            'create' => "Criou {$resource}",
            'update' => "Atualizou {$resource}",
            'delete' => "Excluiu {$resource}",
            'publish' => "Publicou {$resource}",
            'approve' => "Aprovou {$resource}",
            'review' => "Revisou {$resource}",
            'sign' => "Assinou {$resource}",
            'export' => "Exportou dados de {$resource}",
            'download' => "Baixou {$resource}",
            'upload' => "Enviou arquivo para {$resource}",
            'clear' => "Limpou {$resource}",
            'backup' => "Fez backup de {$resource}",
            'restore' => "Restaurou {$resource}"
        ];

        return $descriptions[$action] ?? "Ação em {$resource}";
    }

    /**
     * Extract resource name from route
     */
    private function extractResourceFromRoute(?string $routeName): string
    {
        if (!$routeName) {
            return 'recurso';
        }

        // Remove admin prefix and action suffix
        $parts = explode('.', str_replace('admin.', '', $routeName));
        
        if (count($parts) >= 2) {
            $resource = $parts[0];
            
            // Convert to human-readable
            $resourceNames = [
                'materias' => 'matéria',
                'edicoes' => 'edição',
                'tipos' => 'tipo de matéria',
                'orgaos' => 'órgão',
                'legislacao' => 'legislação',
                'usuarios' => 'usuário',
                'logs' => 'logs',
                'certificados-nuvem' => 'certificado',
                'configuracoes' => 'configurações'
            ];

            return $resourceNames[$resource] ?? $resource;
        }

        return 'recurso';
    }

    /**
     * Determine log level based on method and response
     */
    private function determineLevel(string $method, int $statusCode): string
    {
        if ($statusCode >= 400) {
            return 'error';
        }

        return match($method) {
            'DELETE' => 'warning',
            'POST', 'PUT', 'PATCH' => 'info',
            default => 'debug'
        };
    }

    /**
     * Filter sensitive data from request parameters
     */
    private function filterSensitiveData(array $data): array
    {
        $sensitiveFields = [
            'password',
            'password_confirmation',
            'token',
            'api_key',
            'secret',
            '_token'
        ];

        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = '[FILTERED]';
            }
        }

        // Limit data size
        return array_slice($data, 0, 20);
    }

    /**
     * Get module name from route
     */
    private function getModuleFromRoute(?string $routeName): ?string
    {
        if (!$routeName) {
            return null;
        }

        // Remove admin prefix and get the first part
        $parts = explode('.', str_replace('admin.', '', $routeName));
        
        if (count($parts) >= 1) {
            return $parts[0];
        }

        return null;
    }

    /**
     * Get resource type from route
     */
    private function getResourceTypeFromRoute(?string $routeName): ?string
    {
        if (!$routeName) {
            return null;
        }

        // Map route names to model names
        $resourceMappings = [
            'materias' => 'Materia',
            'edicoes' => 'Edicao',
            'tipos' => 'Tipo',
            'orgaos' => 'Orgao',
            'legislacao' => 'Legislacao',
            'usuarios' => 'User',
            'usuarios-root' => 'User',
            'certificados-nuvem' => 'CertificadoNuvem',
            'configuracoes' => 'ConfiguracaoSistema'
        ];

        $module = $this->getModuleFromRoute($routeName);
        
        return $resourceMappings[$module] ?? null;
    }

    /**
     * Get resource ID from request
     */
    private function getResourceIdFromRequest(Request $request): ?int
    {
        $route = $request->route();
        
        if (!$route) {
            return null;
        }

        // Try to get ID from route parameters
        $parameters = $route->parameters();
        
        // Look for common parameter names
        $possibleIdParams = ['id', 'materia', 'edicao', 'tipo', 'orgao', 'user', 'usuario'];
        
        foreach ($possibleIdParams as $param) {
            if (isset($parameters[$param])) {
                $value = $parameters[$param];
                
                // If it's a model instance, get the ID
                if (is_object($value) && method_exists($value, 'getKey')) {
                    return $value->getKey();
                }
                
                // If it's already an ID
                if (is_numeric($value)) {
                    return (int) $value;
                }
            }
        }

        return null;
    }
}
