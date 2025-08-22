<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UsuariosRootController extends Controller
{
    /**
     * Exibe lista de usuários root
     */
    public function index()
    {
        $usuarios = User::where('is_root', true)->orderBy('name')->paginate(15);
        
        // Estatísticas para a view
        $estatisticas = [
            'total_users' => User::where('is_root', true)->count(),
            'users_ativos' => User::where('is_root', true)->where('ativo', true)->count(),
            'users_criados_mes' => User::where('is_root', true)
                ->whereMonth('created_at', now()->month)
                ->count(),
            'ultimo_acesso' => User::where('is_root', true)
                ->orderBy('updated_at', 'desc')
                ->first()?->updated_at?->diffForHumans() ?? 'Nunca'
        ];

        // Todos os usuários para o select de promoção
        $todosUsuarios = User::where('is_root', false)->orderBy('name')->get();

        return view('admin.usuarios-root.index', compact('usuarios', 'estatisticas', 'todosUsuarios'));
    }

    /**
     * Promove um usuário existente para root
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'observacoes' => 'nullable|string|max:500'
        ]);

        $user = User::findOrFail($request->user_id);
        
        if ($user->is_root) {
            return redirect()->back()->with('error', 'Usuário já possui privilégios root.');
        }

        $user->update([
            'is_root' => true,
            'observacoes' => $request->observacoes
        ]);

        // Log da ação
        SystemLog::create([
            'user_id' => Auth::id(),
            'action' => 'promote_user_to_root',
            'module' => 'usuarios-root',
            'resource_type' => 'User',
            'resource_id' => $user->id,
            'description' => "Usuário {$user->name} promovido para Root",
            'new_values' => json_encode([
                'promoted_user_id' => $user->id,
                'promoted_user_name' => $user->name,
                'observacoes' => $request->observacoes
            ]),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'created_at' => now()
        ]);

        return redirect()->back()->with('success', "Usuário {$user->name} promovido para Root com sucesso.");
    }

    /**
     * Remove privilégios root de um usuário
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Não permite remover o próprio usuário root
        if ($user->id == Auth::id()) {
            return redirect()->back()->with('error', 'Você não pode remover seus próprios privilégios root.');
        }

        // Verifica se é o último usuário root
        $totalRoot = User::where('is_root', true)->count();
        if ($totalRoot <= 1) {
            return redirect()->back()->with('error', 'Não é possível remover o último usuário root do sistema.');
        }

        $userName = $user->name;
        $user->update([
            'is_root' => false,
            'observacoes' => null
        ]);

        // Log da ação
        SystemLog::create([
            'user_id' => Auth::id(),
            'action' => 'remove_root_privileges',
            'module' => 'usuarios-root',
            'resource_type' => 'User',
            'resource_id' => $user->id,
            'description' => "Privilégios root removidos do usuário {$userName}",
            'new_values' => json_encode([
                'demoted_user_id' => $user->id,
                'demoted_user_name' => $userName
            ]),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'created_at' => now()
        ]);

        return redirect()->back()->with('success', "Privilégios root removidos do usuário {$userName}.");
    }

    /**
     * Criar novo usuário diretamente como root
     */
    public function createNew(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'cpf' => 'nullable|string|max:14',
            'cargo' => 'nullable|string|max:255',
            'observacoes' => 'nullable|string|max:500'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'cpf' => $request->cpf,
            'cargo' => $request->cargo,
            'is_root' => true,
            'ativo' => true,
            'perfil' => 'administrador',
            'created_by' => Auth::id(),
            'observacoes' => $request->observacoes
        ]);

        // Log da ação
        SystemLog::create([
            'user_id' => Auth::id(),
            'action' => 'create_root_user',
            'module' => 'usuarios-root',
            'resource_type' => 'User',
            'resource_id' => $user->id,
            'description' => "Novo usuário root criado: {$user->name}",
            'new_values' => json_encode([
                'new_user_id' => $user->id,
                'new_user_name' => $user->name,
                'new_user_email' => $user->email
            ]),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'created_at' => now()
        ]);

        return redirect()->back()->with('success', "Usuário root {$user->name} criado com sucesso.");
    }

    /**
     * Atividade recente dos usuários root
     */
    public function atividade()
    {
        $atividades = SystemLog::with('user')
            ->whereHas('user', function($query) {
                $query->where('is_root', true);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.usuarios-root.atividade', compact('atividades'));
    }
}
