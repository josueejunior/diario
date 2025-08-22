@extends('layouts.adminlte')

@section('title', 'Editar Matéria')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1><i class="fas fa-edit mr-2"></i>Editar Matéria</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.materias.index') }}">Matérias</a></li>
                <li class="breadcrumb-item active">Editar</li>
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
                        <i class="fas fa-edit mr-1"></i>
                        Editar Matéria
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.materias.index') }}" class="btn btn-secondary btn-sm">
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

                    <form action="{{ route('admin.materias.update', $materia) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipo_id">Tipo <span class="text-danger">*</span></label>
                                    <select name="tipo_id" id="tipo_id" class="form-control @error('tipo_id') is-invalid @enderror" required>
                                        <option value="">Selecione um tipo</option>
                                        @foreach ($tipos as $tipo)
                                            <option value="{{ $tipo->id }}" {{ old('tipo_id', $materia->tipo_id) == $tipo->id ? 'selected' : '' }}>
                                                {{ $tipo->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tipo_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="orgao_id">Órgão <span class="text-danger">*</span></label>
                                    <select name="orgao_id" id="orgao_id" class="form-control @error('orgao_id') is-invalid @enderror" required>
                                        <option value="">Selecione um órgão</option>
                                        @foreach ($orgaos as $orgao)
                                            <option value="{{ $orgao->id }}" {{ old('orgao_id', $materia->orgao_id) == $orgao->id ? 'selected' : '' }}>
                                                {{ $orgao->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('orgao_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="numero">Número <span class="text-danger">*</span></label>
                                    <input type="text" name="numero" id="numero" value="{{ old('numero', $materia->numero) }}" 
                                           class="form-control @error('numero') is-invalid @enderror" required>
                                    @error('numero')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="data">Data <span class="text-danger">*</span></label>
                                    <input type="date" name="data" id="data" value="{{ old('data', $materia->data ? $materia->data->format('Y-m-d') : '') }}" 
                                           class="form-control @error('data') is-invalid @enderror" required>
                                    @error('data')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="titulo">Título <span class="text-danger">*</span></label>
                            <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $materia->titulo) }}" 
                                   class="form-control @error('titulo') is-invalid @enderror" required>
                            @error('titulo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="texto">Texto <span class="text-danger">*</span></label>
                            <textarea name="texto" id="texto" rows="10" 
                                      class="form-control @error('texto') is-invalid @enderror" required>{{ old('texto', $materia->texto) }}</textarea>
                            @error('texto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="arquivo">Arquivo (Opcional)</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="arquivo" id="arquivo" 
                                           class="custom-file-input @error('arquivo') is-invalid @enderror" 
                                           accept=".pdf,.doc,.docx,.odt">
                                    <label class="custom-file-label" for="arquivo">Escolher arquivo...</label>
                                </div>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                Formatos aceitos: PDF, DOC, DOCX, ODT. Tamanho máximo: 10MB
                            </small>
                            @error('arquivo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            
                            @if ($materia->arquivo)
                                <div class="mt-2">
                                    <div class="alert alert-info">
                                        <i class="fas fa-file mr-1"></i>
                                        Arquivo atual: 
                                        <a href="{{ Storage::url($materia->arquivo) }}" target="_blank" class="alert-link">
                                            {{ basename($materia->arquivo) }}
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>
                                Atualizar Matéria
                            </button>
                            <a href="{{ route('admin.materias.index') }}" class="btn btn-secondary ml-2">
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
