@extends('layouts.adminlte')

@section('title', 'Configurações Gerais')
@section('page-title', 'Configurações Gerais')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item">Configurações</li>
<li class="breadcrumb-item active">Geral</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-cog mr-2"></i>
                    Configurações Gerais do Sistema
                </h3>
            </div>
            
            <form action="{{ route('admin.configuracoes.geral.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nome_sistema">Nome do Sistema</label>
                                <input type="text" class="form-control @error('nome_sistema') is-invalid @enderror" 
                                       id="nome_sistema" name="nome_sistema" 
                                       value="{{ old('nome_sistema', $configuracoes['nome_sistema'] ?? 'Diário Oficial') }}" 
                                       required>
                                @error('nome_sistema')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nome_entidade">Nome da Entidade</label>
                                <input type="text" class="form-control @error('nome_entidade') is-invalid @enderror" 
                                       id="nome_entidade" name="nome_entidade" 
                                       value="{{ old('nome_entidade', $configuracoes['nome_entidade'] ?? '') }}" 
                                       required>
                                @error('nome_entidade')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="endereco">Endereço da Entidade</label>
                        <textarea class="form-control @error('endereco') is-invalid @enderror" 
                                  id="endereco" name="endereco" rows="3" required>{{ old('endereco', $configuracoes['endereco'] ?? '') }}</textarea>
                        @error('endereco')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="telefone">Telefone</label>
                                <input type="text" class="form-control @error('telefone') is-invalid @enderror" 
                                       id="telefone" name="telefone" 
                                       value="{{ old('telefone', $configuracoes['telefone'] ?? '') }}">
                                @error('telefone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="email_contato">E-mail de Contato</label>
                                <input type="email" class="form-control @error('email_contato') is-invalid @enderror" 
                                       id="email_contato" name="email_contato" 
                                       value="{{ old('email_contato', $configuracoes['email_contato'] ?? '') }}" 
                                       required>
                                @error('email_contato')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="site_url">Site da Entidade</label>
                                <input type="url" class="form-control @error('site_url') is-invalid @enderror" 
                                       id="site_url" name="site_url" 
                                       value="{{ old('site_url', $configuracoes['site_url'] ?? '') }}">
                                @error('site_url')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="logo">Logo do Sistema</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('logo') is-invalid @enderror" 
                                       id="logo" name="logo" accept="image/*">
                                <label class="custom-file-label" for="logo">Escolher arquivo...</label>
                            </div>
                        </div>
                        @if(isset($configuracoes['logo_path']) && $configuracoes['logo_path'])
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $configuracoes['logo_path']) }}" 
                                     alt="Logo atual" class="img-thumbnail" style="max-height: 100px;">
                                <small class="text-muted d-block">Logo atual</small>
                            </div>
                        @endif
                        @error('logo')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">Formatos aceitos: JPEG, PNG, JPG, GIF. Tamanho máximo: 2MB.</small>
                    </div>

                    <hr>

                    <h5>Configurações Avançadas</h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="timezone">Fuso Horário</label>
                                <select class="form-control @error('timezone') is-invalid @enderror" 
                                        id="timezone" name="timezone">
                                    <option value="America/Sao_Paulo" {{ old('timezone', $configuracoes['timezone'] ?? 'America/Sao_Paulo') == 'America/Sao_Paulo' ? 'selected' : '' }}>
                                        Brasília (UTC-3)
                                    </option>
                                    <option value="America/Manaus" {{ old('timezone', $configuracoes['timezone'] ?? '') == 'America/Manaus' ? 'selected' : '' }}>
                                        Manaus (UTC-4)
                                    </option>
                                    <option value="America/Rio_Branco" {{ old('timezone', $configuracoes['timezone'] ?? '') == 'America/Rio_Branco' ? 'selected' : '' }}>
                                        Rio Branco (UTC-5)
                                    </option>
                                </select>
                                @error('timezone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="idioma">Idioma Padrão</label>
                                <select class="form-control @error('idioma') is-invalid @enderror" 
                                        id="idioma" name="idioma">
                                    <option value="pt_BR" {{ old('idioma', $configuracoes['idioma'] ?? 'pt_BR') == 'pt_BR' ? 'selected' : '' }}>
                                        Português (Brasil)
                                    </option>
                                    <option value="en" {{ old('idioma', $configuracoes['idioma'] ?? '') == 'en' ? 'selected' : '' }}>
                                        English
                                    </option>
                                </select>
                                @error('idioma')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="manutencao" name="manutencao" value="1"
                                           {{ old('manutencao', $configuracoes['manutencao'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="manutencao">
                                        Modo Manutenção
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    Quando ativo, o site ficará indisponível para visitantes.
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="debug_mode" name="debug_mode" value="1"
                                           {{ old('debug_mode', $configuracoes['debug_mode'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="debug_mode">
                                        Modo Debug
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    Exibe informações detalhadas de erro (apenas para desenvolvimento).
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i>
                        Salvar Configurações
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Voltar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Custom file input
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
});
</script>
@endpush
