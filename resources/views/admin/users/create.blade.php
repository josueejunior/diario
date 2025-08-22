@extends('layouts.adminlte')

@section('title', 'Criar Usuário')
@section('page-title', 'Criar Novo Usuário')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Usuários</a></li>
<li class="breadcrumb-item active">Criar</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-plus mr-2"></i>
                    Dados do Novo Usuário
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <!-- Informações Básicas -->
                        <div class="col-md-6">
                            <h5 class="text-muted mb-3">
                                <i class="fas fa-user"></i> Informações Básicas
                            </h5>
                            
                            <div class="form-group">
                                <label for="name">Nome Completo <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="telefone">Telefone</label>
                                <input type="text" class="form-control @error('telefone') is-invalid @enderror" 
                                       id="telefone" name="telefone" value="{{ old('telefone') }}" 
                                       placeholder="(99) 99999-9999">
                                @error('telefone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Informações Profissionais -->
                        <div class="col-md-6">
                            <h5 class="text-muted mb-3">
                                <i class="fas fa-briefcase"></i> Informações Profissionais
                            </h5>
                            
                            <div class="form-group">
                                <label for="cargo">Cargo</label>
                                <input type="text" class="form-control @error('cargo') is-invalid @enderror" 
                                       id="cargo" name="cargo" value="{{ old('cargo') }}" 
                                       placeholder="Ex: Secretário, Diretor, Assessor">
                                @error('cargo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="departamento">Departamento/Secretaria</label>
                                <input type="text" class="form-control @error('departamento') is-invalid @enderror" 
                                       id="departamento" name="departamento" value="{{ old('departamento') }}" 
                                       placeholder="Ex: Gabinete, Educação, Saúde">
                                @error('departamento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <!-- Configurações de Acesso -->
                        <div class="col-md-6">
                            <h5 class="text-muted mb-3">
                                <i class="fas fa-key"></i> Configurações de Acesso
                            </h5>
                            
                            <div class="form-group">
                                <label for="role">Perfil de Acesso <span class="text-danger">*</span></label>
                                <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" required>
                                    <option value="">Selecione um perfil</option>
                                    <option value="viewer" {{ old('role') == 'viewer' ? 'selected' : '' }}>
                                        Visualizador - Apenas visualização
                                    </option>
                                    <option value="editor" {{ old('role') == 'editor' ? 'selected' : '' }}>
                                        Editor - Pode criar e editar conteúdo
                                    </option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                        Administrador - Acesso completo
                                    </option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    O perfil determina quais funcionalidades o usuário pode acessar.
                                </small>
                            </div>

                            <div class="form-group">
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="ativo" {{ old('status') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                                    <option value="inativo" {{ old('status') == 'inativo' ? 'selected' : '' }}>Inativo</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Senha -->
                        <div class="col-md-6">
                            <h5 class="text-muted mb-3">
                                <i class="fas fa-lock"></i> Definir Senha
                            </h5>
                            
                            <div class="form-group">
                                <label for="password">Senha <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password')">
                                            <i class="fas fa-eye" id="password-icon"></i>
                                        </button>
                                    </div>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Mínimo 8 caracteres, incluindo letras e números.
                                </small>
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Confirmar Senha <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation')">
                                            <i class="fas fa-eye" id="password_confirmation-icon"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="button" class="btn btn-outline-info btn-sm" onclick="generatePassword()">
                                    <i class="fas fa-random"></i> Gerar Senha Aleatória
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Resumo das Permissões -->
                    <div class="alert alert-info" id="permissionsInfo" style="display: none;">
                        <h6><i class="fas fa-info-circle"></i> Permissões do Perfil:</h6>
                        <div id="permissionsContent"></div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Criar Usuário
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Toggle password visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Generate random password
function generatePassword() {
    const length = 12;
    const charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
    let password = '';
    
    for (let i = 0; i < length; i++) {
        password += charset.charAt(Math.floor(Math.random() * charset.length));
    }
    
    document.getElementById('password').value = password;
    document.getElementById('password_confirmation').value = password;
    
    toastr.info('Senha gerada automaticamente. Certifique-se de compartilhá-la com o usuário.');
}

// Show permissions based on role
$('#role').change(function() {
    const role = $(this).val();
    const permissionsInfo = $('#permissionsInfo');
    const permissionsContent = $('#permissionsContent');
    
    if (role) {
        const permissions = {
            'viewer': [
                'Visualizar matérias e edições',
                'Acessar relatórios básicos',
                'Visualizar configurações (somente leitura)'
            ],
            'editor': [
                'Todas as permissões do Visualizador',
                'Criar e editar matérias',
                'Criar e gerenciar edições',
                'Acessar ferramentas de diagramação',
                'Gerenciar tipos e órgãos'
            ],
            'admin': [
                'Todas as permissões do Editor',
                'Gerenciar usuários',
                'Configurar sistema',
                'Acessar logs e auditoria',
                'Gerenciar certificados digitais',
                'Configurações avançadas'
            ]
        };
        
        permissionsContent.html('<ul class="mb-0">' + 
            permissions[role].map(p => `<li>${p}</li>`).join('') + 
        '</ul>');
        permissionsInfo.show();
    } else {
        permissionsInfo.hide();
    }
});

// Format phone number
$('#telefone').mask('(00) 00000-0000', {
    placeholder: '(99) 99999-9999',
    translation: {
        '0': {pattern: /[0-9]/}
    }
});

// Form validation
$('form').submit(function(e) {
    const password = $('#password').val();
    const passwordConfirmation = $('#password_confirmation').val();
    
    if (password !== passwordConfirmation) {
        e.preventDefault();
        toastr.error('As senhas não coincidem');
        return false;
    }
    
    if (password.length < 8) {
        e.preventDefault();
        toastr.error('A senha deve ter pelo menos 8 caracteres');
        return false;
    }
});
</script>
@endsection
