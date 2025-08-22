@extends('layouts.adminlte')

@section('title', 'Usuários [Root]')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1><i class="fas fa-user-shield mr-2"></i>Usuários [Root]</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Usuários [Root]</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="card-title">
                                <i class="fas fa-users-cog mr-1"></i>
                                Gerenciar Usuários Root
                            </h3>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" class="btn btn-warning mr-2" data-toggle="modal" data-target="#modalPromoverUsuario">
                                <i class="fas fa-user-shield"></i> Promover Usuário
                            </button>
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalNovoUsuario">
                                <i class="fas fa-user-plus"></i> Novo Usuário Root
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Alertas de Segurança -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong>ATENÇÃO:</strong> Usuários Root possuem acesso total ao sistema. 
                                Conceda este privilégio apenas para usuários de extrema confiança.
                            </div>
                        </div>
                    </div>

                    <!-- Estatísticas -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3>{{ $estatisticas['total_users'] }}</h3>
                                    <p>Total de Usuários Root</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $estatisticas['users_ativos'] }}</h3>
                                    <p>Usuários Ativos</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-user-check"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $estatisticas['users_criados_mes'] }}</h3>
                                    <p>Criados Este Mês</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h6 style="font-size: 14px;">{{ $estatisticas['ultimo_acesso'] }}</h6>
                                    <p>Último Acesso</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status:</label>
                                <select class="form-control" id="filtroStatus">
                                    <option value="">Todos</option>
                                    <option value="1">Ativo</option>
                                    <option value="0">Inativo</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Situação:</label>
                                <select class="form-control" id="filtroSituacao">
                                    <option value="">Todas</option>
                                    <option value="online">Online</option>
                                    <option value="offline">Offline</option>
                                    <option value="verificado">E-mail verificado</option>
                                    <option value="nao_verificado">E-mail não verificado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Buscar:</label>
                                <input type="text" class="form-control" id="buscaUsuario" placeholder="Digite nome ou e-mail...">
                            </div>
                        </div>
                    </div>

                    <!-- Tabela -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tabelaUsuarios">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="5%">Avatar</th>
                                    <th width="20%">Nome</th>
                                    <th width="20%">E-mail</th>
                                    <th width="15%">Último Login</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Situação</th>
                                    <th width="20%">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($usuarios as $usuario)
                                    <tr>
                                        <td class="text-center">
                                            @if($usuario->avatar)
                                                <img src="{{ asset('storage/' . $usuario->avatar) }}" alt="Avatar" class="img-circle" style="width: 40px; height: 40px;">
                                            @else
                                                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <span class="text-white font-weight-bold">{{ strtoupper(substr($usuario->name, 0, 2)) }}</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $usuario->name }}</strong>
                                            @if($usuario->id === auth()->id())
                                                <span class="badge badge-info ml-1">Você</span>
                                            @endif
                                            @if($usuario->esta_online)
                                                <i class="fas fa-circle text-success ml-1" title="Online"></i>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $usuario->email }}
                                            @if($usuario->hasVerifiedEmail())
                                                <i class="fas fa-check-circle text-success ml-1" title="E-mail verificado"></i>
                                            @else
                                                <i class="fas fa-exclamation-circle text-warning ml-1" title="E-mail não verificado"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if($usuario->ultimo_login)
                                                {{ $usuario->ultimo_login->format('d/m/Y H:i') }}
                                                <br><small class="text-muted">{{ $usuario->ultimo_login->diffForHumans() }}</small>
                                            @else
                                                <span class="text-muted">Nunca</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($usuario->ativo)
                                                <span class="badge badge-success">Ativo</span>
                                            @else
                                                <span class="badge badge-danger">Inativo</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($usuario->esta_online)
                                                <span class="badge badge-success">Online</span>
                                            @else
                                                <span class="badge badge-secondary">Offline</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-info" onclick="visualizarUsuario({{ $usuario->id }})" title="Visualizar">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-warning" onclick="editarUsuario({{ $usuario->id }})" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                @if($usuario->id !== auth()->id())
                                                    <button type="button" class="btn btn-secondary" onclick="impersonarUsuario({{ $usuario->id }})" title="Entrar como usuário">
                                                        <i class="fas fa-user-secret"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger" onclick="excluirUsuario({{ $usuario->id }})" title="Excluir">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">
                                            <i class="fas fa-users fa-3x mb-3"></i>
                                            <br>Nenhum usuário root cadastrado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    @if($usuarios->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $usuarios->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Novo Usuário -->
    <div class="modal fade" id="modalNovoUsuario" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h4 class="modal-title">
                        <i class="fas fa-user-plus"></i> Novo Usuário Root
                    </h4>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="formNovoUsuario">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-shield-alt mr-2"></i>
                            <strong>Importante:</strong> Este usuário terá acesso completo ao sistema administrativo.
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nome Completo: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" id="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">E-mail: <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email" id="email" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Senha: <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="password" id="password" required minlength="8">
                                    <small class="text-muted">Mínimo 8 caracteres</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Confirmar Senha: <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="telefone">Telefone:</label>
                                    <input type="text" class="form-control" name="telefone" id="telefone" placeholder="(00) 00000-0000">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cargo">Cargo/Função:</label>
                                    <input type="text" class="form-control" name="cargo" id="cargo" placeholder="Ex: Administrador do Sistema">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="observacoes">Observações:</label>
                            <textarea class="form-control" name="observacoes" id="observacoes" rows="3" placeholder="Informações adicionais sobre o usuário..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" name="ativo" id="ativo" value="1" checked>
                                        <label class="custom-control-label" for="ativo">Usuário ativo</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" name="email_verified" id="email_verified" value="1">
                                        <label class="custom-control-label" for="email_verified">E-mail verificado</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Criar Usuário
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Visualizar Usuário -->
    <div class="modal fade" id="modalVisualizarUsuario" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">
                        <i class="fas fa-eye"></i> Detalhes do Usuário
                    </h4>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="conteudoVisualizacao">
                    <!-- Conteúdo carregado via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Promover Usuário Existente -->
    <div class="modal fade" id="modalPromoverUsuario" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h4 class="modal-title">
                        <i class="fas fa-user-shield"></i> Promover Usuário para Root
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.usuarios-root.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Atenção:</strong> Ao promover um usuário para Root, ele terá acesso total ao sistema.
                        </div>

                        <div class="form-group">
                            <label for="user_id">Usuário:</label>
                            <select class="form-control" name="user_id" id="user_id" required>
                                <option value="">Selecione um usuário...</option>
                                @foreach($todosUsuarios as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="observacoes_promocao">Observações:</label>
                            <textarea class="form-control" name="observacoes" id="observacoes_promocao" rows="3" 
                                placeholder="Motivo da promoção, período de acesso, etc..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-user-shield"></i> Promover Usuário
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Criar Novo Usuário Root -->
    <div class="modal fade" id="modalNovoUsuario" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h4 class="modal-title">
                        <i class="fas fa-user-plus"></i> Criar Novo Usuário Root
                    </h4>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.usuarios-root.create-new') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Novo Usuário:</strong> Este usuário será criado diretamente com privilégios Root.
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nome Completo: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" id="name" required 
                                        placeholder="Digite o nome completo">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">E-mail: <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email" id="email" required 
                                        placeholder="Digite o e-mail">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Senha: <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="password" id="password" required 
                                        minlength="8" placeholder="Mínimo 8 caracteres">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Confirmar Senha: <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="password_confirmation" 
                                        id="password_confirmation" required placeholder="Confirme a senha">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cpf">CPF:</label>
                                    <input type="text" class="form-control" name="cpf" id="cpf" 
                                        placeholder="000.000.000-00">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cargo">Cargo:</label>
                                    <input type="text" class="form-control" name="cargo" id="cargo" 
                                        placeholder="Ex: Administrador do Sistema">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="observacoes_novo">Observações:</label>
                            <textarea class="form-control" name="observacoes" id="observacoes_novo" rows="3" 
                                placeholder="Informações adicionais sobre o usuário..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-user-plus"></i> Criar Usuário Root
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Filtros
    $('#filtroStatus, #filtroSituacao').change(function() {
        filtrarTabela();
    });

    $('#buscaUsuario').on('keyup', function() {
        filtrarTabela();
    });

    // Máscara de telefone
    $('#telefone').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        value = value.replace(/(\d{2})(\d)/, '($1) $2');
        value = value.replace(/(\d{5})(\d)/, '$1-$2');
        $(this).val(value);
    });

    // Validação de senhas
    $('#password_confirmation').on('keyup', function() {
        const senha = $('#password').val();
        const confirmacao = $(this).val();
        
        if(senha !== confirmacao) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid').addClass('is-valid');
        }
    });

    // Form novo usuário
    $('#formNovoUsuario').on('submit', function(e) {
        e.preventDefault();
        
        const senha = $('#password').val();
        const confirmacao = $('#password_confirmation').val();
        
        if(senha !== confirmacao) {
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'As senhas não coincidem.'
            });
            return;
        }
        
        $.ajax({
            url: '{{ route("admin.usuarios-root.store") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if(response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: response.message,
                        timer: 3000
                    }).then(() => {
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                let message = 'Erro ao criar usuário.';
                
                if(errors) {
                    message = Object.values(errors).flat().join('\n');
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: message
                });
            }
        });
    });
});

function filtrarTabela() {
    const status = $('#filtroStatus').val();
    const situacao = $('#filtroSituacao').val().toLowerCase();
    const busca = $('#buscaUsuario').val().toLowerCase();

    $('#tabelaUsuarios tbody tr').each(function() {
        const row = $(this);
        const nome = row.find('td:eq(1)').text().toLowerCase();
        const email = row.find('td:eq(2)').text().toLowerCase();
        const statusAtivo = row.find('td:eq(4) .badge-success').length > 0;
        const online = row.find('td:eq(5) .badge-success').length > 0;
        const emailVerificado = row.find('td:eq(2) .fa-check-circle').length > 0;

        let showRow = true;

        // Filtro por status
        if(status) {
            const statusValue = (status === '1') ? true : false;
            if(statusAtivo !== statusValue) {
                showRow = false;
            }
        }

        // Filtro por situação
        if(situacao) {
            if(situacao === 'online' && !online) showRow = false;
            if(situacao === 'offline' && online) showRow = false;
            if(situacao === 'verificado' && !emailVerificado) showRow = false;
            if(situacao === 'nao_verificado' && emailVerificado) showRow = false;
        }

        // Filtro por busca
        if(busca && !nome.includes(busca) && !email.includes(busca)) {
            showRow = false;
        }

        row.toggle(showRow);
    });
}

function visualizarUsuario(id) {
    $.ajax({
        url: `/admin/usuarios-root/${id}`,
        method: 'GET',
        success: function(response) {
            $('#conteudoVisualizacao').html(response);
            $('#modalVisualizarUsuario').modal('show');
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'Erro ao carregar usuário.'
            });
        }
    });
}

function editarUsuario(id) {
    window.location.href = `/admin/usuarios-root/${id}/edit`;
}

function impersonarUsuario(id) {
    Swal.fire({
        title: 'Entrar como usuário?',
        text: 'Você será logado como este usuário. Deseja continuar?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, entrar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `/admin/usuarios-root/${id}/impersonate`;
        }
    });
}

function excluirUsuario(id) {
    Swal.fire({
        title: 'Tem certeza?',
        text: 'Esta ação não pode ser desfeita e o usuário perderá acesso ao sistema!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/usuarios-root/${id}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if(response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Excluído!',
                            text: response.message,
                            timer: 3000
                        }).then(() => {
                            location.reload();
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: 'Erro ao excluir usuário.'
                    });
                }
            });
        }
    });
}
</script>
@stop
