@extends('layouts.adminlte')

@section('title', 'Gerenciamento de Usuários')
@section('page-title', 'Gerenciamento de Usuários')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Usuários</li>
@endsection

@section('content')
<!-- Estatísticas -->
<div class="row mb-3">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total_users'] ?? 0 }}</h3>
                <p>Total de Usuários</p>
            </div>
            <div class="icon">
                <i class="ion ion-person"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['active_users'] ?? 0 }}</h3>
                <p>Usuários Ativos</p>
            </div>
            <div class="icon">
                <i class="ion ion-checkmark"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['admins'] ?? 0 }}</h3>
                <p>Administradores</p>
            </div>
            <div class="icon">
                <i class="ion ion-key"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['online_users'] ?? 0 }}</h3>
                <p>Online Agora</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-users mr-2"></i>
            Lista de Usuários
        </h3>
        <div class="card-tools">
            <a href="{{ route('admin.users.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Novo Usuário
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Filtros -->
        <div class="row mb-3">
            <div class="col-12">
                <form action="{{ route('admin.users.index') }}" method="GET" class="form-inline">
                    <div class="form-group mr-3">
                        <label for="search" class="sr-only">Buscar</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               value="{{ request('search') }}" placeholder="Nome ou email...">
                    </div>
                    <div class="form-group mr-3">
                        <select name="status" id="status" class="form-control">
                            <option value="">Todos os status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Ativos</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inativos</option>
                        </select>
                    </div>
                    <div class="form-group mr-3">
                        <select name="role" id="role" class="form-control">
                            <option value="">Todos os perfis</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                            <option value="editor" {{ request('role') == 'editor' ? 'selected' : '' }}>Editor</option>
                            <option value="viewer" {{ request('role') == 'viewer' ? 'selected' : '' }}>Visualizador</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Limpar
                    </a>
                </form>
            </div>
        </div>

        <!-- Tabela de usuários -->
        @if($users->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Perfil</th>
                        <th>Departamento</th>
                        <th>Status</th>
                        <th>Último Acesso</th>
                        <th>Criado em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar mr-2">
                                    @if($user->avatar)
                                        <img src="{{ $user->avatar }}" class="img-circle" style="width: 32px; height: 32px;" alt="Avatar">
                                    @else
                                        <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 14px;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <strong>{{ $user->name }}</strong>
                                    @if($user->cargo)
                                        <br><small class="text-muted">{{ $user->cargo }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @php
                                $roleClass = [
                                    'admin' => 'badge-danger',
                                    'editor' => 'badge-warning', 
                                    'viewer' => 'badge-info'
                                ][$user->role] ?? 'badge-secondary';
                                
                                $roleLabel = [
                                    'admin' => 'Administrador',
                                    'editor' => 'Editor',
                                    'viewer' => 'Visualizador'
                                ][$user->role] ?? ucfirst($user->role);
                            @endphp
                            <span class="badge {{ $roleClass }}">{{ $roleLabel }}</span>
                        </td>
                        <td>{{ $user->departamento ?? '-' }}</td>
                        <td>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input status-toggle" 
                                       id="status_{{ $user->id }}" 
                                       data-user-id="{{ $user->id }}"
                                       {{ $user->status === 'ativo' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="status_{{ $user->id }}"></label>
                            </div>
                        </td>
                        <td>
                            @if($user->last_login_at)
                                <span title="{{ $user->last_login_at->format('d/m/Y H:i:s') }}">
                                    {{ $user->last_login_at->diffForHumans() }}
                                </span>
                            @else
                                <span class="text-muted">Nunca</span>
                            @endif
                        </td>
                        <td>
                            <span title="{{ $user->created_at->format('d/m/Y H:i:s') }}">
                                {{ $user->created_at->format('d/m/Y') }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-info" title="Visualizar">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-warning" 
                                        onclick="showResetPasswordModal({{ $user->id }}, '{{ $user->name }}')" title="Resetar Senha">
                                    <i class="fas fa-key"></i>
                                </button>
                                @if($user->id !== auth()->id())
                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                        onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')" title="Remover">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <div class="d-flex justify-content-center">
            {{ $users->appends(request()->query())->links() }}
        </div>
        @else
        <div class="text-center p-4">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <p class="text-muted">Nenhum usuário encontrado.</p>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Criar Primeiro Usuário
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Modal para resetar senha -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Resetar Senha</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="resetPasswordForm">
                <div class="modal-body">
                    <p>Resetar senha do usuário: <strong id="resetUserName"></strong></p>
                    <div class="form-group">
                        <label for="new_password">Nova Senha</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password_confirmation">Confirmar Nova Senha</label>
                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Resetar Senha</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de confirmação para exclusão -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirmar Exclusão</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja remover o usuário: <strong id="deleteUserName"></strong>?</p>
                <p class="text-danger"><strong>Esta ação não pode ser desfeita.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Sim, Remover</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let currentUserId = null;

// Toggle status do usuário
$('.status-toggle').change(function() {
    const userId = $(this).data('user-id');
    const isChecked = $(this).is(':checked');
    
    $.post(`/admin/users/${userId}/toggle-status`, {
        _token: '{{ csrf_token() }}'
    })
    .done(function(response) {
        if (response.success) {
            toastr.success(response.message);
        } else {
            toastr.error('Erro ao alterar status');
            // Reverter o switch
            $(`#status_${userId}`).prop('checked', !isChecked);
        }
    })
    .fail(function() {
        toastr.error('Erro ao alterar status');
        // Reverter o switch
        $(`#status_${userId}`).prop('checked', !isChecked);
    });
});

// Modal para resetar senha
function showResetPasswordModal(userId, userName) {
    currentUserId = userId;
    $('#resetUserName').text(userName);
    $('#resetPasswordForm')[0].reset();
    $('#resetPasswordModal').modal('show');
}

// Submit do formulário de reset de senha
$('#resetPasswordForm').submit(function(e) {
    e.preventDefault();
    
    const password = $('#new_password').val();
    const passwordConfirmation = $('#new_password_confirmation').val();
    
    if (password !== passwordConfirmation) {
        toastr.error('As senhas não coincidem');
        return;
    }
    
    $.post(`/admin/users/${currentUserId}/reset-password`, {
        _token: '{{ csrf_token() }}',
        new_password: password,
        new_password_confirmation: passwordConfirmation
    })
    .done(function(response) {
        if (response.success) {
            $('#resetPasswordModal').modal('hide');
            toastr.success(response.message);
        } else {
            toastr.error('Erro ao resetar senha');
        }
    })
    .fail(function(xhr) {
        if (xhr.responseJSON && xhr.responseJSON.errors) {
            Object.values(xhr.responseJSON.errors).forEach(function(errors) {
                errors.forEach(function(error) {
                    toastr.error(error);
                });
            });
        } else {
            toastr.error('Erro ao resetar senha');
        }
    });
});

// Confirmar exclusão
function confirmDelete(userId, userName) {
    currentUserId = userId;
    $('#deleteUserName').text(userName);
    $('#deleteModal').modal('show');
}

$('#confirmDeleteBtn').click(function() {
    $.ajax({
        url: `/admin/users/${currentUserId}`,
        type: 'DELETE',
        data: {
            _token: '{{ csrf_token() }}'
        }
    })
    .done(function(response) {
        if (response.success) {
            $('#deleteModal').modal('hide');
            toastr.success(response.message);
            location.reload();
        } else {
            toastr.error('Erro ao remover usuário');
        }
    })
    .fail(function() {
        toastr.error('Erro ao remover usuário');
    });
});
</script>
@endsection
