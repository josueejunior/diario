@extends('layouts.adminlte')

@section('title', 'Legislação Aplicada')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1><i class="fas fa-balance-scale mr-2"></i>Legislação Aplicada</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Legislação Aplicada</li>
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
                                <i class="fas fa-gavel mr-1"></i>
                                Gerenciar Legislação
                            </h3>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalNovaLegislacao">
                                <i class="fas fa-plus"></i> Nova Legislação
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filtros -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tipo:</label>
                                <select class="form-control" id="filtroTipo">
                                    <option value="">Todos os tipos</option>
                                    <option value="Lei">Lei</option>
                                    <option value="Decreto">Decreto</option>
                                    <option value="Portaria">Portaria</option>
                                    <option value="Resolução">Resolução</option>
                                    <option value="Instrução Normativa">Instrução Normativa</option>
                                </select>
                            </div>
                        </div>
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
                                <label>Buscar:</label>
                                <input type="text" class="form-control" id="buscaLegislacao" placeholder="Digite o número, título ou descrição...">
                            </div>
                        </div>
                    </div>

                    <!-- Tabela -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tabelaLegislacao">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="10%">Tipo</th>
                                    <th width="15%">Número</th>
                                    <th width="35%">Título</th>
                                    <th width="15%">Data</th>
                                    <th width="10%">Status</th>
                                    <th width="15%">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($legislacao as $lei)
                                    <tr>
                                        <td>
                                            <span class="badge badge-primary">{{ $lei->tipo }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $lei->numero }}/{{ $lei->data_publicacao->format('Y') }}</strong>
                                        </td>
                                        <td>
                                            <strong>{{ $lei->titulo }}</strong>
                                            @if($lei->ementa)
                                                <br><small class="text-muted">{{ Str::limit($lei->ementa, 80) }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $lei->data_publicacao->format('d/m/Y') }}</td>
                                        <td>
                                            @if($lei->status == 'vigente')
                                                <span class="badge badge-success">Vigente</span>
                                            @elseif($lei->status == 'revogada')
                                                <span class="badge badge-danger">Revogada</span>
                                            @else
                                                <span class="badge badge-warning">Suspensa</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-info" onclick="visualizarLegislacao({{ $lei->id }})" title="Visualizar">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-warning" onclick="editarLegislacao({{ $lei->id }})" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger" onclick="excluirLegislacao({{ $lei->id }})" title="Excluir">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <br>Nenhuma legislação cadastrada.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    @if($legislacao->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $legislacao->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nova Legislação -->
    <div class="modal fade" id="modalNovaLegislacao" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h4 class="modal-title">
                        <i class="fas fa-plus"></i> Nova Legislação
                    </h4>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="formNovaLegislacao">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipo">Tipo: <span class="text-danger">*</span></label>
                                    <select class="form-control" name="tipo" id="tipo" required>
                                        <option value="">Selecione o tipo</option>
                                        <option value="Lei">Lei</option>
                                        <option value="Decreto">Decreto</option>
                                        <option value="Portaria">Portaria</option>
                                        <option value="Resolução">Resolução</option>
                                        <option value="Instrução Normativa">Instrução Normativa</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="numero">Número: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="numero" id="numero" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="ano">Ano: <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="ano" id="ano" value="{{ date('Y') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="data">Data: <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="data" id="data" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="orgao">Órgão:</label>
                                    <input type="text" class="form-control" name="orgao" id="orgao">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="titulo">Título: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="titulo" id="titulo" required>
                        </div>
                        <div class="form-group">
                            <label for="ementa">Ementa:</label>
                            <textarea class="form-control" name="ementa" id="ementa" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="conteudo">Conteúdo:</label>
                            <textarea class="form-control" name="conteudo" id="conteudo" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="ativo" id="ativo" value="1" checked>
                                <label class="custom-control-label" for="ativo">Ativo</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Visualizar Legislação -->
    <div class="modal fade" id="modalVisualizarLegislacao" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">
                        <i class="fas fa-eye"></i> Visualizar Legislação
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
@stop

@section('js')
<script>
$(document).ready(function() {
    // Filtros
    $('#filtroTipo, #filtroStatus').change(function() {
        filtrarTabela();
    });

    $('#buscaLegislacao').on('keyup', function() {
        filtrarTabela();
    });

    // Form nova legislação
    $('#formNovaLegislacao').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '{{ route("admin.legislacao.store") }}',
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
                let message = 'Erro ao salvar legislação.';
                
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
    const tipo = $('#filtroTipo').val().toLowerCase();
    const status = $('#filtroStatus').val();
    const busca = $('#buscaLegislacao').val().toLowerCase();

    $('#tabelaLegislacao tbody tr').each(function() {
        const row = $(this);
        const tipoText = row.find('td:eq(0)').text().toLowerCase();
        const numero = row.find('td:eq(1)').text().toLowerCase();
        const titulo = row.find('td:eq(2)').text().toLowerCase();
        const statusAtivo = row.find('td:eq(4) .badge-success').length > 0;

        let showRow = true;

        // Filtro por tipo
        if(tipo && !tipoText.includes(tipo)) {
            showRow = false;
        }

        // Filtro por status
        if(status) {
            const statusValue = (status === '1') ? true : false;
            if(statusAtivo !== statusValue) {
                showRow = false;
            }
        }

        // Filtro por busca
        if(busca && !numero.includes(busca) && !titulo.includes(busca)) {
            showRow = false;
        }

        row.toggle(showRow);
    });
}

function visualizarLegislacao(id) {
    $.ajax({
        url: `/admin/legislacao/${id}`,
        method: 'GET',
        success: function(response) {
            $('#conteudoVisualizacao').html(response);
            $('#modalVisualizarLegislacao').modal('show');
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'Erro ao carregar legislação.'
            });
        }
    });
}

function editarLegislacao(id) {
    // Implementar edição
    window.location.href = `/admin/legislacao/${id}/edit`;
}

function excluirLegislacao(id) {
    Swal.fire({
        title: 'Tem certeza?',
        text: 'Esta ação não pode ser desfeita!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/legislacao/${id}`,
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
                        text: 'Erro ao excluir legislação.'
                    });
                }
            });
        }
    });
}
</script>
@stop
