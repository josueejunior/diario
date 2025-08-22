@extends('layouts.adminlte')

@section('title', 'Editar Edição')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1><i class="fas fa-edit mr-2"></i>Editar Edição</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.edicoes.index') }}">Edições</a></li>
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
                        Editar Edição #{{ $edicao->numero }}
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

                    <form action="{{ route('admin.edicoes.update', $edicao) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="numero">Número: <span class="text-danger">*</span></label>
                                    <input type="text" name="numero" id="numero" class="form-control" value="{{ old('numero', $edicao->numero) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="data">Data: <span class="text-danger">*</span></label>
                                    <input type="date" name="data" id="data" class="form-control" value="{{ old('data', $edicao->data->format('Y-m-d')) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipo">Tipo: <span class="text-danger">*</span></label>
                                    <select name="tipo" id="tipo" class="form-control" required>
                                        <option value="">Selecione o tipo</option>
                                        <option value="normal" {{ old('tipo', $edicao->tipo) == 'normal' ? 'selected' : '' }}>Normal</option>
                                        <option value="extra" {{ old('tipo', $edicao->tipo) == 'extra' ? 'selected' : '' }}>Extra</option>
                                        <option value="especial" {{ old('tipo', $edicao->tipo) == 'especial' ? 'selected' : '' }}>Especial</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" name="publicado" id="publicado" value="1" {{ old('publicado', $edicao->publicado) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="publicado">Publicado</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="descricao">Descrição:</label>
                            <textarea name="descricao" id="descricao" class="form-control" rows="3" placeholder="Descrição da edição...">{{ old('descricao', $edicao->descricao) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="arquivo_pdf">Arquivo PDF:</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="arquivo_pdf" id="arquivo_pdf" class="custom-file-input" accept=".pdf">
                                    <label class="custom-file-label" for="arquivo_pdf">Escolher arquivo PDF...</label>
                                </div>
                            </div>
                            <small class="text-muted">Tamanho máximo: 100MB. Deixe em branco para manter o arquivo atual.</small>
                            
                            @if ($edicao->caminho_arquivo)
                                <div class="mt-2 p-3 bg-light rounded">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-file-pdf text-danger mr-2"></i>
                                        <span>Arquivo atual: </span>
                                        <a href="{{ Storage::url($edicao->caminho_arquivo) }}" target="_blank" class="btn btn-link btn-sm p-0 ml-1">
                                            Ver PDF <i class="fas fa-external-link-alt ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Atualizar Edição
                                    </button>
                                    <a href="{{ route('admin.edicoes.index') }}" class="btn btn-secondary ml-2">
                                        <i class="fas fa-times"></i> Cancelar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Custom file input label
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
    });

    // Form validation
    $('form').on('submit', function(e) {
        const numero = $('#numero').val().trim();
        const data = $('#data').val();
        const tipo = $('#tipo').val();

        if (!numero || !data || !tipo) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Campos obrigatórios',
                text: 'Por favor, preencha todos os campos obrigatórios.'
            });
            return false;
        }
    });
});
</script>
@stop
