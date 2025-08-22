@extends('layouts.adminlte')

@section('title', 'Diagramação')
@section('page-title', 'Diagramação')

@section('content')
<div class="container-fluid">
    <!-- Estatísticas -->
    <div class="row mb-4">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $stats['total_edicoes'] }}</h3>
                    <p>Total de Edições</p>
                </div>
                <div class="icon">
                    <i class="fas fa-newspaper"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $stats['edicoes_rascunho'] }}</h3>
                    <p>Rascunhos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-edit"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $stats['edicoes_prontas'] }}</h3>
                    <p>Prontas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $stats['edicoes_publicadas'] }}</h3>
                    <p>Publicadas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-globe"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-2"></i>
                        Diagramação de Edições
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalNovaEdicao">
                            <i class="fas fa-plus"></i> Nova Edição
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="filtro-data">Data da Edição</label>
                            <input type="date" class="form-control" id="filtro-data">
                        </div>
                        <div class="col-md-3">
                            <label for="filtro-status">Status</label>
                            <select class="form-control" id="filtro-status">
                                <option value="">Todos</option>
                                <option value="rascunho">Rascunho</option>
                                <option value="diagramando">Diagramando</option>
                                <option value="pronto">Pronto</option>
                                <option value="publicado">Publicado</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>&nbsp;</label><br>
                            <button class="btn btn-info" onclick="filtrarEdicoes()">
                                <i class="fas fa-search"></i> Filtrar
                            </button>
                            <button class="btn btn-secondary" onclick="limparFiltros()">
                                <i class="fas fa-times"></i> Limpar
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Número</th>
                                    <th>Matérias</th>
                                    <th>Status</th>
                                    <th>Última Atualização</th>
                                    <th width="200">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($edicoes as $edicao)
                                    <tr>
                                        <td>{{ $edicao->data ? $edicao->data->format('d/m/Y') : 'N/A' }}</td>
                                        <td>{{ $edicao->numero ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ $edicao->materias->count() }} matérias</span>
                                        </td>
                                        <td>
                                            @if($edicao->publicado)
                                                <span class="badge badge-success">Publicado</span>
                                            @elseif($edicao->descricao)
                                                <span class="badge badge-info">Pronto</span>
                                            @else
                                                <span class="badge badge-warning">Rascunho</span>
                                            @endif
                                        </td>
                                        <td>{{ $edicao->updated_at->diffForHumans() }}</td>
                                        <td>
                                            @if(!$edicao->publicado)
                                                <a href="{{ route('admin.diagramacao.edit', $edicao->id) }}" class="btn btn-sm btn-primary" title="Editar Diagramação">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('admin.diagramacao.show', $edicao->id) }}" class="btn btn-sm btn-success" title="Visualizar">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($edicao->caminho_arquivo)
                                                <a href="{{ asset($edicao->caminho_arquivo) }}" target="_blank" class="btn btn-sm btn-info" title="Gerar PDF">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                            @endif
                                            @if(!$edicao->publicado)
                                                <button class="btn btn-sm btn-danger" onclick="excluirEdicao({{ $edicao->id }})" title="Excluir">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="alert alert-info mb-0">
                                                <i class="fas fa-info-circle"></i>
                                                Nenhuma edição encontrada.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($edicoes->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $edicoes->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nova Edição -->
<div class="modal fade" id="modalNovaEdicao">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nova Edição para Diagramação</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="formNovaEdicao">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Data da Edição</label>
                                <input type="date" class="form-control" name="data_edicao" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Número da Edição</label>
                                <input type="text" class="form-control" name="numero" placeholder="Ex: 001/2025" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Matérias Aprovadas</label>
                        <select class="form-control select2" name="materias[]" multiple required>
                            @foreach($materiasSemEdicao as $materia)
                                <option value="{{ $materia->id }}">
                                    {{ $materia->numero ?? 'S/N' }} - {{ $materia->titulo }}
                                    ({{ $materia->tipo->nome ?? 'N/A' }} | {{ $materia->orgao->nome ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                        @if($materiasSemEdicao->count() == 0)
                            <small class="text-muted">Não há matérias disponíveis para nova edição.</small>
                        @endif
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" form="formNovaEdicao" class="btn btn-primary">Criar Edição</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: 'Selecione as matérias',
        allowClear: true
    });

    // Submit do formulário de nova edição
    $('#formNovaEdicao').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '{{ route("admin.diagramacao.gerar") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#modalNovaEdicao').modal('hide');
                    
                    // Redirecionar para a edição criada se fornecida
                    if (response.redirect) {
                        setTimeout(function() {
                            window.location.href = response.redirect;
                        }, 1000);
                    } else {
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    }
                } else {
                    toastr.error(response.message || 'Erro ao criar edição');
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON?.errors || {};
                var message = xhr.responseJSON?.message || 'Erro ao criar edição';
                
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

function filtrarEdicoes() {
    var data = $('#filtro-data').val();
    var status = $('#filtro-status').val();
    
    var params = new URLSearchParams();
    if (data) params.append('data', data);
    if (status) params.append('status', status);
    
    var url = '{{ route("admin.diagramacao.index") }}';
    if (params.toString()) {
        url += '?' + params.toString();
    }
    
    window.location.href = url;
}

function limparFiltros() {
    $('#filtro-data').val('');
    $('#filtro-status').val('');
    window.location.href = '{{ route("admin.diagramacao.index") }}';
}

function excluirEdicao(edicaoId) {
    if (confirm('Tem certeza que deseja excluir esta edição? Esta ação não pode ser desfeita.')) {
        $.ajax({
            url: '/admin/edicoes/' + edicaoId,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('Edição excluída com sucesso');
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                } else {
                    toastr.error(response.message || 'Erro ao excluir edição');
                }
            },
            error: function(xhr) {
                toastr.error('Erro ao excluir edição');
            }
        });
    }
}
</script>
@endpush
