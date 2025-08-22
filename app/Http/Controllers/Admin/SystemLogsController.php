<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SystemLogsController extends Controller
{
    /**
     * Display the system logs dashboard.
     */
    public function index(Request $request)
    {
        // Estatísticas gerais
        $stats = [
            'total_logs' => AuditLog::count(),
            'logs_hoje' => AuditLog::whereDate('created_at', today())->count(),
            'usuarios_ativos_hoje' => AuditLog::whereDate('created_at', today())->distinct('user_id')->count('user_id'),
            'acoes_mais_comum' => AuditLog::select('action')
                ->groupBy('action')
                ->orderByRaw('COUNT(*) DESC')
                ->first()?->action ?? 'N/A'
        ];

        // Filtros
        $query = AuditLog::with(['user'])->latest();

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('module')) {
            $query->where('module', $request->module);
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

        // Paginação
        $logs = $query->paginate(50);

        // Dados para filtros
        $users = User::select('id', 'name')->orderBy('name')->get();
        $actions = AuditLog::distinct()->pluck('action')->sort();
        $modules = AuditLog::distinct()->pluck('module')->sort();

        // Gráfico de atividade (últimos 7 dias)
        $activityChart = AuditLog::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total')
        )
        ->where('created_at', '>=', Carbon::now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // Top usuários mais ativos (últimos 30 dias)
        $topUsers = AuditLog::select('user_id', DB::raw('COUNT(*) as total'))
            ->with('user:id,name')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Top IPs mais ativos
        $topIps = AuditLog::select('ip_address', DB::raw('COUNT(*) as total'))
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('ip_address')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return view('admin.logs.sistema', compact(
            'logs', 'stats', 'users', 'actions', 'modules', 
            'activityChart', 'topUsers', 'topIps'
        ));
    }

    /**
     * Show detailed log entry.
     */
    public function show($id)
    {
        $log = AuditLog::with('user')->findOrFail($id);
        return view('admin.logs.show', compact('log'));
    }

    /**
     * Export logs to CSV.
     */
    public function export(Request $request)
    {
        $query = AuditLog::with('user');

        // Aplicar mesmos filtros da listagem
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        $filename = 'system_logs_' . now()->format('Y_m_d_H_i_s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // Cabeçalho CSV
            fputcsv($file, [
                'ID', 'Usuário', 'Ação', 'Módulo', 'Descrição', 'IP', 
                'Data/Hora', 'User Agent', 'URL', 'Método'
            ]);

            // Dados
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user ? $log->user->name : 'Sistema',
                    $log->action,
                    $log->module,
                    $log->description,
                    $log->ip_address,
                    $log->created_at->format('d/m/Y H:i:s'),
                    $log->user_agent,
                    $log->url,
                    $log->method
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Clear old logs.
     */
    public function clear(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:30|max:3650'
        ]);

        $cutoffDate = Carbon::now()->subDays($request->days);
        $deletedCount = AuditLog::where('created_at', '<', $cutoffDate)->delete();

        return back()->with('success', "Foram removidos {$deletedCount} registros de log anteriores a " . $cutoffDate->format('d/m/Y') . ".");
    }

    /**
     * View user activity logs.
     */
    public function userActivity($userId)
    {
        $user = User::findOrFail($userId);
        
        $logs = AuditLog::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        $stats = [
            'total_actions' => AuditLog::where('user_id', $userId)->count(),
            'last_login' => AuditLog::where('user_id', $userId)
                ->where('action', 'login')
                ->latest()
                ->first()?->created_at,
            'actions_today' => AuditLog::where('user_id', $userId)
                ->whereDate('created_at', today())
                ->count(),
            'most_used_ip' => AuditLog::where('user_id', $userId)
                ->select('ip_address')
                ->groupBy('ip_address')
                ->orderByRaw('COUNT(*) DESC')
                ->first()?->ip_address
        ];

        return view('admin.logs.user-activity', compact('user', 'logs', 'stats'));
    }
}
