<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AuditController extends Controller
{
    /**
     * Lista de logs de auditoria
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('user')
            ->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('resource_type')) {
            $query->where('resource_type', $request->resource_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('ip_address')) {
            $query->where('ip_address', 'like', '%' . $request->ip_address . '%');
        }

        $logs = $query->paginate(50);

        // Dados para filtros
        $users = \App\Models\User::select('id', 'name')->get();
        $actions = AuditLog::distinct()->pluck('action')->sort();
        $resourceTypes = AuditLog::distinct()->whereNotNull('resource_type')->pluck('resource_type')->sort();

        return view('admin.audit.index', compact('logs', 'users', 'actions', 'resourceTypes'));
    }

    /**
     * Detalhes de um log específico
     */
    public function show(AuditLog $auditLog)
    {
        $auditLog->load('user');
        return view('admin.audit.show', compact('auditLog'));
    }

    /**
     * Exportar logs em CSV
     */
    public function export(Request $request)
    {
        $query = AuditLog::with('user')
            ->orderBy('created_at', 'desc');

        // Aplicar os mesmos filtros da listagem
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('resource_type')) {
            $query->where('resource_type', $request->resource_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('ip_address')) {
            $query->where('ip_address', 'like', '%' . $request->ip_address . '%');
        }

        $logs = $query->get();

        $filename = 'audit_log_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // Cabeçalho do CSV
            fputcsv($file, [
                'ID',
                'Data/Hora',
                'Usuário',
                'Ação',
                'Tipo de Recurso',
                'ID do Recurso',
                'IP',
                'User Agent',
                'URL',
                'Método HTTP',
                'Status',
                'Valores Antigos',
                'Valores Novos'
            ]);

            // Dados
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->created_at->format('d/m/Y H:i:s'),
                    $log->user ? $log->user->name : 'Sistema',
                    $log->action,
                    $log->resource_type,
                    $log->resource_id,
                    $log->ip_address,
                    $log->user_agent,
                    $log->url,
                    $log->http_method,
                    $log->status_code,
                    $log->old_values ? json_encode($log->old_values, JSON_UNESCAPED_UNICODE) : '',
                    $log->new_values ? json_encode($log->new_values, JSON_UNESCAPED_UNICODE) : ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Dashboard de estatísticas de auditoria
     */
    public function dashboard()
    {
        $stats = [
            'total_actions' => AuditLog::count(),
            'unique_users' => AuditLog::distinct('user_id')->count('user_id'),
            'actions_today' => AuditLog::whereDate('created_at', today())->count(),
            'actions_this_week' => AuditLog::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
        ];

        // Ações mais comuns
        $topActions = AuditLog::selectRaw('action, COUNT(*) as count')
            ->groupBy('action')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        // Usuários mais ativos
        $topUsers = AuditLog::selectRaw('user_id, COUNT(*) as count')
            ->with('user:id,name')
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        // Atividade por dia (últimos 30 dias)
        $dailyActivity = AuditLog::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // IPs mais ativos
        $topIPs = AuditLog::selectRaw('ip_address, COUNT(*) as count')
            ->groupBy('ip_address')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.audit.dashboard', compact(
            'stats', 
            'topActions', 
            'topUsers', 
            'dailyActivity', 
            'topIPs'
        ));
    }

    /**
     * Limpar logs antigos
     */
    public function cleanup(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:30|max:3650'
        ]);

        $cutoffDate = now()->subDays($request->days);
        $deletedCount = AuditLog::where('created_at', '<', $cutoffDate)->delete();

        return back()->with('success', "Foram removidos {$deletedCount} registros de auditoria anteriores a " . $cutoffDate->format('d/m/Y') . ".");
    }
}
