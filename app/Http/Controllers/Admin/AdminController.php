<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Materia;
use App\Models\Edicao;
use App\Models\Visualizacao;
use App\Models\Download;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard()
    {
        // Estatísticas básicas
        $stats = [
            'total_materias' => Materia::count(),
            'total_edicoes' => Edicao::count(),
            'visualizacoes_hoje' => Visualizacao::whereDate('created_at', today())->count(),
            'downloads_hoje' => Download::whereDate('created_at', today())->count(),
            'usuarios_ativos' => User::where('updated_at', '>=', now()->subDays(30))->count(),
        ];

        // Gráfico de visualizações por mês (últimos 12 meses)
        $visualizacoesPorMes = Visualizacao::select(
            DB::raw('YEAR(created_at) as ano'),
            DB::raw('MONTH(created_at) as mes'),
            DB::raw('COUNT(*) as total')
        )
        ->where('created_at', '>=', now()->subMonths(12))
        ->groupBy('ano', 'mes')
        ->orderBy('ano', 'asc')
        ->orderBy('mes', 'asc')
        ->get()
        ->map(function ($item) {
            return [
                'periodo' => Carbon::createFromDate($item->ano, $item->mes, 1)->format('M/Y'),
                'total' => $item->total
            ];
        });

        // Downloads por mês (últimos 12 meses)
        $downloadsPorMes = Download::select(
            DB::raw('YEAR(created_at) as ano'),
            DB::raw('MONTH(created_at) as mes'),
            DB::raw('COUNT(*) as total')
        )
        ->where('created_at', '>=', now()->subMonths(12))
        ->groupBy('ano', 'mes')
        ->orderBy('ano', 'asc')
        ->orderBy('mes', 'asc')
        ->get()
        ->map(function ($item) {
            return [
                'periodo' => Carbon::createFromDate($item->ano, $item->mes, 1)->format('M/Y'),
                'total' => $item->total
            ];
        });

        // Matérias mais visualizadas (últimos 30 dias)
        $materiasMaisVisualizadas = Materia::select('materias.*')
            ->join('visualizacoes', 'materias.id', '=', 'visualizacoes.materia_id')
            ->where('visualizacoes.created_at', '>=', now()->subDays(30))
            ->selectRaw('materias.*, COUNT(visualizacoes.id) as total_visualizacoes')
            ->groupBy('materias.id')
            ->orderByDesc('total_visualizacoes')
            ->limit(10)
            ->get();

        // Atividade recente
        $atividadeRecente = AuditLog::with('user')
            ->latest()
            ->limit(10)
            ->get();

        // Edições recentes
        $edicoesRecentes = Edicao::latest()
            ->limit(5)
            ->get();

        // Usuários mais ativos
        $usuariosMaisAtivos = User::select('users.*')
            ->join('audit_logs', 'users.id', '=', 'audit_logs.user_id')
            ->where('audit_logs.created_at', '>=', now()->subDays(30))
            ->selectRaw('users.*, COUNT(audit_logs.id) as total_acoes')
            ->groupBy('users.id')
            ->orderByDesc('total_acoes')
            ->limit(5)
            ->get();

        return view('admin.dashboard-adminlte', compact(
            'stats',
            'visualizacoesPorMes',
            'downloadsPorMes',
            'materiasMaisVisualizadas',
            'atividadeRecente',
            'edicoesRecentes',
            'usuariosMaisAtivos'
        ));
    }

    /**
     * Display system information.
     */
    public function systemInfo()
    {
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'database_version' => DB::select('select version() as version')[0]->version ?? 'Unknown',
            'disk_free_space' => disk_free_space('/'),
            'disk_total_space' => disk_total_space('/'),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
        ];

        return view('admin.system.info', compact('systemInfo'));
    }

    /**
     * Perform global search.
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return back()->with('error', 'Por favor, insira um termo de busca.');
        }

        // Buscar em matérias
        $materias = Materia::where('titulo', 'like', "%{$query}%")
            ->orWhere('conteudo', 'like', "%{$query}%")
            ->limit(10)
            ->get();

        // Buscar em edições
        $edicoes = Edicao::where('numero', 'like', "%{$query}%")
            ->orWhere('observacoes', 'like', "%{$query}%")
            ->limit(10)
            ->get();

        // Buscar em usuários
        $usuarios = User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->limit(10)
            ->get();

        return view('admin.search.results', compact(
            'query',
            'materias',
            'edicoes',
            'usuarios'
        ));
    }

    /**
     * Display profile edit form.
     */
    public function profileEdit()
    {
        $user = auth()->user();
        return view('admin.profile.edit', compact('user'));
    }

    /**
     * Update user profile.
     */
    public function profileUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = auth()->user();
        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        
        $user->save();

        return back()->with('success', 'Perfil atualizado com sucesso!');
    }
}
