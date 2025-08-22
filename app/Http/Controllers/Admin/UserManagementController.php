<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    /**
     * Display users management dashboard.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filtros
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('cpf', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('perfil')) {
            $query->where('perfil', $request->perfil);
        }

        if ($request->filled('ativo')) {
            $query->where('ativo', $request->ativo);
        }

        // Ordenação
        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $users = $query->paginate(15);

        // Estatísticas
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('ativo', true)->count(),
            'admins' => User::where('perfil', 'administrador')->count(),
            'online_users' => User::where('updated_at', '>=', now()->subMinutes(15))->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Show form to create new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store new user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'perfil' => 'required|in:administrador,editor,visualizador,usuario',
            'cpf' => 'nullable|string|size:11|unique:users,cpf',
            'cargo' => 'nullable|string|max:255',
            'observacoes' => 'nullable|string|max:1000',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'perfil' => $request->perfil,
            'ativo' => true,
            'cpf' => $request->cpf,
            'cargo' => $request->cargo,
            'observacoes' => $request->observacoes,
            'created_by' => auth()->id(),
        ];

        $user = User::create($userData);

        // Log da ação
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'create',
            'module' => 'users',
            'resource_type' => 'User',
            'resource_id' => $user->id,
            'description' => "Usuário '{$user->name}' criado",
            'new_values' => json_encode([
                'name' => $user->name,
                'email' => $user->email,
                'perfil' => $user->perfil
            ]),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->url(),
            'method' => request()->method(),
        ]);

        return redirect()->route('admin.users.show', $user)
                        ->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Show user details.
     */
    public function show(User $user)
    {
        // Carregar logs recentes do usuário
        $recentLogs = AuditLog::where('user_id', $user->id)
            ->latest()
            ->limit(10)
            ->get();

        // Estatísticas do usuário
        $userStats = [
            'total_logins' => AuditLog::where('user_id', $user->id)
                ->where('action', 'login')
                ->count(),
            'last_login' => AuditLog::where('user_id', $user->id)
                ->where('action', 'login')
                ->latest()
                ->first()?->created_at,
            'total_actions' => AuditLog::where('user_id', $user->id)->count(),
            'most_used_ip' => AuditLog::where('user_id', $user->id)
                ->select('ip_address')
                ->groupBy('ip_address')
                ->orderByRaw('COUNT(*) DESC')
                ->first()?->ip_address,
        ];

        return view('admin.users.show', compact('user', 'recentLogs', 'userStats'));
    }

    /**
     * Show form to edit user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,editor,visualizador,super_admin',
            'status' => 'required|in:ativo,inativo,suspenso',
            'cpf' => ['nullable', 'string', 'size:11', Rule::unique('users')->ignore($user->id)],
            'cargo' => 'nullable|string|max:255',
            'telefone' => 'nullable|string|max:20',
            'departamento' => 'nullable|string|max:255',
            'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'observacoes' => 'nullable|string|max:1000',
            'permissions' => 'nullable|array',
        ]);

        $oldValues = $user->toArray();

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'status' => $request->status,
            'cpf' => $request->cpf,
            'cargo' => $request->cargo,
            'telefone' => $request->telefone,
            'departamento' => $request->departamento,
            'observacoes' => $request->observacoes,
            'permissions' => $request->permissions ? json_encode($request->permissions) : null,
        ];

        // Atualizar senha se fornecida
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        // Upload de nova foto do perfil
        if ($request->hasFile('foto_perfil')) {
            // Deletar foto antiga
            if ($user->foto_perfil && Storage::disk('public')->exists($user->foto_perfil)) {
                Storage::disk('public')->delete($user->foto_perfil);
            }
            $userData['foto_perfil'] = $request->file('foto_perfil')->store('profile_photos', 'public');
        }

        $user->update($userData);

        // Log da ação
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'module' => 'users',
            'resource_type' => 'User',
            'resource_id' => $user->id,
            'description' => "Usuário '{$user->name}' atualizado",
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($user->toArray()),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->url(),
            'method' => request()->method(),
        ]);

        return redirect()->route('admin.users.show', $user)
                        ->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Delete user.
     */
    public function destroy(User $user)
    {
        // Não permitir exclusão do próprio usuário
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Você não pode excluir sua própria conta.']);
        }

        $oldValues = $user->toArray();

        // Deletar foto do perfil se existir
        if ($user->foto_perfil && Storage::disk('public')->exists($user->foto_perfil)) {
            Storage::disk('public')->delete($user->foto_perfil);
        }

        $userName = $user->name;
        $user->delete();

        // Log da ação
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'module' => 'users',
            'resource_type' => 'User',
            'resource_id' => $user->id,
            'description' => "Usuário '{$userName}' excluído",
            'old_values' => json_encode($oldValues),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->url(),
            'method' => request()->method(),
        ]);

        return redirect()->route('admin.users.index')
                        ->with('success', 'Usuário excluído com sucesso!');
    }

    /**
     * Block/Unblock user.
     */
    public function toggleBlock(User $user)
    {
        $newStatus = $user->status === 'suspenso' ? 'ativo' : 'suspenso';
        $action = $newStatus === 'suspenso' ? 'bloqueado' : 'desbloqueado';
        
        $user->update(['status' => $newStatus]);

        // Log da ação
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'status_change',
            'module' => 'users',
            'resource_type' => 'User',
            'resource_id' => $user->id,
            'description' => "Usuário '{$user->name}' {$action}",
            'new_values' => json_encode(['status' => $newStatus]),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->url(),
            'method' => request()->method(),
        ]);

        return back()->with('success', "Usuário {$action} com sucesso!");
    }

    /**
     * Reset user password.
     */
    public function resetPassword(User $user)
    {
        // Gerar nova senha temporária
        $newPassword = 'DiarioOficial' . rand(1000, 9999);
        $user->update(['password' => Hash::make($newPassword)]);

        // Log da ação
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'password_reset',
            'module' => 'users',
            'resource_type' => 'User',
            'resource_id' => $user->id,
            'description' => "Senha do usuário '{$user->name}' redefinida",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->url(),
            'method' => request()->method(),
        ]);

        return back()->with('success', "Senha redefinida! Nova senha: {$newPassword}");
    }
}
