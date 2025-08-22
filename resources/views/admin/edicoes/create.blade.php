@extends('layouts.adminlte')

@section('title', 'Nova Edição')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1><i class="fas fa-plus mr-2"></i>Nova Edição</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.edicoes.index') }}">Edições</a></li>
                <li class="breadcrumb-item active">Nova</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus mr-1"></i>
                        Criar Nova Edição
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.edicoes.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <h5><i class="icon fas fa-ban"></i> Erro!</h5>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.edicoes.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="numero">Número <span class="text-danger">*</span></label>
                                    <input type="text" name="numero" id="numero" value="{{ old('numero') }}" 
                                           class="form-control @error('numero') is-invalid @enderror" required>
                                    @error('numero')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="data">Data <span class="text-danger">*</span></label>
                                    <input type="date" name="data" id="data" value="{{ old('data', date('Y-m-d')) }}" 
                                           class="form-control @error('data') is-invalid @enderror" required>
                                    @error('data')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipo">Tipo <span class="text-danger">*</span></label>
                                    <select name="tipo" id="tipo" class="form-control @error('tipo') is-invalid @enderror" required>
                                        <option value="">Selecione o tipo</option>
                                        <option value="normal" {{ old('tipo') == 'normal' ? 'selected' : '' }}>Normal</option>
                                        <option value="extra" {{ old('tipo') == 'extra' ? 'selected' : '' }}>Extra</option>
                                    </select>
                                    @error('tipo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="publicado">Status</label>
                                    <select name="publicado" id="publicado" class="form-control">
                                        <option value="0" {{ old('publicado', '0') == '0' ? 'selected' : '' }}>Rascunho</option>
                                        <option value="1" {{ old('publicado') == '1' ? 'selected' : '' }}>Publicado</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="descricao">Descrição</label>
                            <textarea name="descricao" id="descricao" rows="3" 
                                      class="form-control @error('descricao') is-invalid @enderror" 
                                      placeholder="Digite uma descrição para esta edição...">{{ old('descricao') }}</textarea>
                            @error('descricao')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="arquivo_pdf">Arquivo PDF <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="arquivo_pdf" id="arquivo_pdf" 
                                           class="custom-file-input @error('arquivo_pdf') is-invalid @enderror" 
                                           accept=".pdf" required>
                                    <label class="custom-file-label" for="arquivo_pdf">Escolher arquivo PDF...</label>
                                </div>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                Tamanho máximo: 100MB. Apenas arquivos PDF são aceitos.
                            </small>
                            @error('arquivo_pdf')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>
                                Salvar Edição
                            </button>
                            <a href="{{ route('admin.edicoes.index') }}" class="btn btn-secondary ml-2">
                                <i class="fas fa-times mr-1"></i>
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@stop

@section('js')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- BS Custom File Input -->
    <script src="{{ asset('vendor/adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

    <script>
        $(function () {
            // Initialize BS Custom File Input
            bsCustomFileInput.init();
        });

        // Show success message if session has success
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        // Show error message if session has error
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: '{{ session('error') }}'
            });
        @endif
    </script>
@stop
