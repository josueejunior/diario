<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LogsController extends Controller
{
    /**
     * Exibe logs do sistema com auditoria completa
     */
    public function sistema(Request $request)
    {
        // Logs de arquivo Laravel
        $logPath = storage_path('logs/laravel.log');
        $laravelLogs = [];
        
        if (File::exists($logPath)) {
            $content = File::get($logPath);
            $lines = array_reverse(explode("\n", $content));
            $laravelLogs = array_slice($lines, 0, 100); // Últimas 100 linhas
        }

        // Logs de auditoria do banco de dados
        $query = SystemLog::with('user')->orderBy('created_at', 'desc');

        // Filtros
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

        $systemLogs = $query->paginate(50);

        // Dados para filtros
        $users = User::orderBy('name')->get();
        $actions = SystemLog::distinct()->pluck('action');
        $modules = SystemLog::distinct()->pluck('module');

        // Estatísticas
        $stats = [
            'total_logs' => SystemLog::count(),
            'logs_hoje' => SystemLog::whereDate('created_at', today())->count(),
            'errors_hoje' => SystemLog::where('action', 'error')->whereDate('created_at', today())->count(),
            'usuarios_ativos' => SystemLog::distinct('user_id')->whereDate('created_at', today())->count('user_id'),
        ];

        return view('admin.logs.sistema', compact(
            'laravelLogs', 
            'systemLogs', 
            'users', 
            'actions', 
            'modules', 
            'stats'
        ));
    }

    /**
     * Criar um novo log de sistema
     */
    public static function createLog($action, $description, $module = null, $data = null)
    {
        return SystemLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'new_values' => $data ? json_encode($data) : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'created_at' => now()
        ]);
    }

    public function download()
    {
        $logPath = storage_path('logs/laravel.log');
        
        if (File::exists($logPath)) {
            // Log da ação
            self::createLog('download_logs', 'Download de logs do sistema', 'logs');
            
            return response()->download($logPath, 'sistema-logs-' . date('Y-m-d-H-i-s') . '.log');
        }
        
        return redirect()->back()->with('error', 'Arquivo de log não encontrado');
    }

    public function clear()
    {
        $logPath = storage_path('logs/laravel.log');
        
        if (File::exists($logPath)) {
            // Backup antes de limpar
            $backupPath = storage_path('logs/backup-' . date('Y-m-d-H-i-s') . '.log');
            File::copy($logPath, $backupPath);
            
            File::put($logPath, '');
            
            // Log da ação
            self::createLog('clear_logs', 'Logs do sistema foram limpos', 'logs', [
                'backup_path' => $backupPath
            ]);
            
            return redirect()->back()->with('success', 'Logs limpos com sucesso. Backup salvo em: ' . basename($backupPath));
        }
        
        return redirect()->back()->with('error', 'Arquivo de log não encontrado');
    }

    /**
     * Exportar logs filtrados
     */
    public function export(Request $request)
    {
        $query = SystemLog::with('user')->orderBy('created_at', 'desc');

        // Aplicar mesmos filtros
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

        $logs = $query->get();

        $csv = "Data,Usuário,Ação,Módulo,Descrição,IP,User Agent\n";
        
        foreach ($logs as $log) {
            $csv .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s\n",
                $log->created_at->format('Y-m-d H:i:s'),
                $log->user ? $log->user->name : 'Sistema',
                $log->action,
                $log->module ?? 'N/A',
                str_replace(['"', ','], ['""', ';'], $log->description),
                $log->ip_address,
                str_replace(['"', ','], ['""', ';'], $log->user_agent)
            );
        }

        // Log da ação
        self::createLog('export_logs', 'Exportação de logs do sistema', 'logs', [
            'total_records' => $logs->count(),
            'filters' => $request->only(['user_id', 'action', 'level', 'date_from', 'date_to'])
        ]);

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="system-logs-' . date('Y-m-d-H-i-s') . '.csv"');
    }

    /**
     * Limpeza automática de logs antigos
     */
    public function cleanup(Request $request)
    {
        $days = $request->input('days', 30);
        
        $deleted = SystemLog::where('created_at', '<', Carbon::now()->subDays($days))->delete();
        
        // Log da ação
        self::createLog('cleanup_logs', "Limpeza automática de logs: {$deleted} registros removidos", 'logs', [
            'days' => $days,
            'deleted_count' => $deleted
        ]);

        return response()->json([
            'success' => true,
            'message' => "Limpeza concluída: {$deleted} logs removidos",
            'deleted_count' => $deleted
        ]);
    }
}
