@extends('layouts.adminlte')

@section('title', 'Editar Diagramação')
@section('page-title', 'Editar Diagramação')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-2"></i>
                        Editar Edição {{ $edicao->numero ?? 'N/A' }} - {{ $edicao->data ? $edicao->data->format('d/m/Y') : 'N/A' }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.diagramacao.show', $edicao->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Visualizar
                        </a>
                        <a href="{{ route('admin.diagramacao.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form id="formEdicao" action="{{ route('admin.diagramacao.salvar') }}" method="POST">
                        @csrf
                        <input type="hidden" name="edicao_id" value="{{ $edicao->id }}">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Informações da Edição</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Número da Edição</label>
                                            <input type="text" class="form-control" value="{{ $edicao->numero }}" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Data da Edição</label>
                                            <input type="date" class="form-control" value="{{ $edicao->data ? $edicao->data->format('Y-m-d') : '' }}" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Observações</label>
                                            <textarea name="observacoes" class="form-control" rows="4" placeholder="Observações sobre esta edição...">{{ $edicao->observacoes }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Status</label>
                                            <p class="form-control-static">
                                                @if($edicao->publicado)
                                                    <span class="badge badge-success">Publicado</span>
                                                @elseif($edicao->descricao)
                                                    <span class="badge badge-info">Pronto</span>
                                                @else
                                                    <span class="badge badge-warning">Rascunho</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Matérias Disponíveis</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <input type="text" id="searchMaterias" class="form-control" placeholder="Buscar matérias...">
                                        </div>
                                        <div style="max-height: 300px; overflow-y: auto;">
                                            @foreach($materiasDisponiveis as $materia)
                                                <div class="materia-item border p-2 mb-2 cursor-pointer" data-id="{{ $materia->id }}">
                                                    <strong>{{ $materia->numero ?? 'S/N' }}</strong> - {{ $materia->titulo }}
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ $materia->tipo->nome ?? 'N/A' }} | {{ $materia->orgao->nome ?? 'N/A' }}
                                                    </small>
                                                    <button type="button" class="btn btn-sm btn-success float-right" onclick="adicionarMateria({{ $materia->id }})">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Matérias da Edição ({{ $edicao->materias->count() }})</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="materiasEdicao">
                                            @foreach($edicao->materias as $materia)
                                                <div class="materia-edicao border p-3 mb-2" data-id="{{ $materia->id }}">
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                            <strong>{{ $materia->numero ?? 'S/N' }}</strong> - {{ $materia->titulo }}
                                                            <br>
                                                            <small class="text-muted">
                                                                {{ $materia->tipo->nome ?? 'N/A' }} | {{ $materia->orgao->nome ?? 'N/A' }}
                                                                | {{ $materia->data ? $materia->data->format('d/m/Y') : $materia->created_at->format('d/m/Y') }}
                                                            </small>
                                                            <input type="hidden" name="materias[]" value="{{ $materia->id }}">
                                                        </div>
                                                        <div class="col-md-2 text-right">
                                                            <button type="button" class="btn btn-sm btn-danger" onclick="removerMateria({{ $materia->id }})">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        @if($edicao->materias->count() == 0)
                                            <div id="noMaterias" class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                Nenhuma matéria associada a esta edição.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Salvar Alterações
                                </button>
                                <a href="{{ route('admin.diagramacao.show', $edicao->id) }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Busca de matérias
    $('#searchMaterias').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('.materia-item').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Submit do formulário
    $('#formEdicao').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    // Recarregar página após 1 segundo
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                } else {
                    toastr.error(response.message || 'Erro ao salvar alterações');
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON?.errors || {};
                var message = xhr.responseJSON?.message || 'Erro ao salvar alterações';
                
                if (Object.keys(errors).length > 0) {
                    $.each(errors, function(field, messages) {
                        toastr.error(messages[0]);
                    });
                } else {
                    toastr.error(message);
                }
            }
        });
    });
});

function adicionarMateria(materiaId) {
    var materiaItem = $('.materia-item[data-id="' + materiaId + '"]');
    var materiaTexto = materiaItem.html();
    
    // Remover da lista de disponíveis
    materiaItem.remove();
    
    // Extrair informações da matéria
    var texto = materiaItem.text().trim();
    var numero = texto.split(' - ')[0];
    var titulo = texto.split(' - ')[1].split('\n')[0];
    
    // Adicionar à lista da edição
    var novoItem = `
        <div class="materia-edicao border p-3 mb-2" data-id="${materiaId}">
            <div class="row">
                <div class="col-md-10">
                    ${materiaTexto.replace('btn-success', 'btn-danger').replace('fa-plus', 'fa-trash').replace('adicionarMateria', 'removerMateria')}
                    <input type="hidden" name="materias[]" value="${materiaId}">
                </div>
                <div class="col-md-2 text-right">
                    <button type="button" class="btn btn-sm btn-danger" onclick="removerMateria(${materiaId})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    $('#materiasEdicao').append(novoItem);
    $('#noMaterias').hide();
    
    toastr.success('Matéria adicionada à edição');
}

function removerMateria(materiaId) {
    if (confirm('Tem certeza que deseja remover esta matéria da edição?')) {
        $('.materia-edicao[data-id="' + materiaId + '"]').remove();
        
        // Verificar se ficou vazio
        if ($('#materiasEdicao .materia-edicao').length == 0) {
            $('#noMaterias').show();
        }
        
        toastr.info('Matéria removida da edição');
    }
}
</script>
@endpush

@push('styles')
<style>
.materia-item:hover {
    background-color: #f8f9fa;
    cursor: pointer;
}

.materia-edicao {
    background-color: #f8f9fa;
}

.cursor-pointer {
    cursor: pointer;
}
</style>
@endpush
