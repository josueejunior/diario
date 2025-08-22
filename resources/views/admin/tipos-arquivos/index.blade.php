@extends('layouts.adminlte')

@section('title', 'Tipos de Arquivos')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1><i class="fas fa-file-archive mr-2"></i>Tipos de Arquivos</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Tipos de Arquivos</li>
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
                                <i class="fas fa-folder-open mr-1"></i>
                                Gerenciar Tipos de Arquivos
                            </h3>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalNovoTipo">
                                <i class="fas fa-plus"></i> Novo Tipo
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
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
                                <label>Categoria:</label>
                                <select class="form-control" id="filtroCategoria">
                                    <option value="">Todas</option>
                                    <option value="documento">Documento</option>
                                    <option value="imagem">Imagem</option>
                                    <option value="video">Vídeo</option>
                                    <option value="audio">Áudio</option>
                                    <option value="planilha">Planilha</option>
                                    <option value="apresentacao">Apresentação</option>
                                    <option value="comprimido">Comprimido</option>
                                    <option value="outro">Outro</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Buscar:</label>
                                <input type="text" class="form-control" id="buscaTipo" placeholder="Digite o nome ou extensão...">
                            </div>
                        </div>
                    </div>

                    <!-- Tabela -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tabelaTipos">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="5%">Ícone</th>
                                    <th width="20%">Nome</th>
                                    <th width="15%">Extensão</th>
                                    <th width="15%">Categoria</th>
                                    <th width="15%">Tamanho Máx.</th>
                                    <th width="10%">Status</th>
                                    <th width="20%">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tipos as $tipo)
                                    <tr>
                                        <td class="text-center">
                                            <i class="{{ $tipo->icone ?? 'fas fa-file' }} fa-2x text-primary"></i>
                                        </td>
                                        <td>
                                            <strong>{{ $tipo->nome }}</strong>
                                            @if($tipo->descricao)
                                                <br><small class="text-muted">{{ Str::limit($tipo->descricao, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary">{{ strtoupper($tipo->extensao) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ ucfirst($tipo->categoria) }}</span>
                                        </td>
                                        <td>
                                            @if($tipo->tamanho_maximo)
                                                {{ $tipo->tamanho_maximo_humanizado }}
                                            @else
                                                <span class="text-muted">Sem limite</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($tipo->ativo)
                                                <span class="badge badge-success">Ativo</span>
                                            @else
                                                <span class="badge badge-danger">Inativo</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-info" onclick="visualizarTipo({{ $tipo->id }})" title="Visualizar">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-warning" onclick="editarTipo({{ $tipo->id }})" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger" onclick="excluirTipo({{ $tipo->id }})" title="Excluir">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <br>Nenhum tipo de arquivo cadastrado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    @if($tipos->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $tipos->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Novo Tipo -->
    <div class="modal fade" id="modalNovoTipo" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h4 class="modal-title">
                        <i class="fas fa-plus"></i> Novo Tipo de Arquivo
                    </h4>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="formNovoTipo">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nome">Nome: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nome" id="nome" required placeholder="Ex: Documento PDF">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="extensao">Extensão: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="extensao" id="extensao" required placeholder="Ex: pdf">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="categoria">Categoria: <span class="text-danger">*</span></label>
                                    <select class="form-control" name="categoria" id="categoria" required>
                                        <option value="">Selecione a categoria</option>
                                        <option value="documento">Documento</option>
                                        <option value="imagem">Imagem</option>
                                        <option value="video">Vídeo</option>
                                        <option value="audio">Áudio</option>
                                        <option value="planilha">Planilha</option>
                                        <option value="apresentacao">Apresentação</option>
                                        <option value="comprimido">Comprimido</option>
                                        <option value="outro">Outro</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="icone">Ícone (FontAwesome):</label>
                                    <input type="text" class="form-control" name="icone" id="icone" placeholder="Ex: fas fa-file-pdf">
                                    <small class="text-muted">Use classes do FontAwesome (opcional)</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="descricao">Descrição:</label>
                            <textarea class="form-control" name="descricao" id="descricao" rows="3" placeholder="Descrição opcional do tipo de arquivo..."></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tamanho_maximo">Tamanho Máximo (MB):</label>
                                    <input type="number" class="form-control" name="tamanho_maximo" id="tamanho_maximo" min="0" step="0.1" placeholder="Ex: 10">
                                    <small class="text-muted">Deixe em branco para sem limite</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mime_types">MIME Types:</label>
                                    <input type="text" class="form-control" name="mime_types" id="mime_types" placeholder="Ex: application/pdf">
                                    <small class="text-muted">Separar múltiplos por vírgula</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" name="ativo" id="ativo" value="1" checked>
                                        <label class="custom-control-label" for="ativo">Ativo</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" name="permite_upload_publico" id="permite_upload_publico" value="1">
                                        <label class="custom-control-label" for="permite_upload_publico">Permite upload público</label>
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
                            <i class="fas fa-save"></i> Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Visualizar Tipo -->
    <div class="modal fade" id="modalVisualizarTipo" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">
                        <i class="fas fa-eye"></i> Detalhes do Tipo de Arquivo
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
    $('#filtroStatus, #filtroCategoria').change(function() {
        filtrarTabela();
    });

    $('#buscaTipo').on('keyup', function() {
        filtrarTabela();
    });

    // Form novo tipo
    $('#formNovoTipo').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '{{ route("admin.tipos-arquivos.store") }}',
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
                let message = 'Erro ao salvar tipo de arquivo.';
                
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

    // Preview do ícone
    $('#icone').on('input', function() {
        const icone = $(this).val();
        if(icone) {
            $(this).after(`<div class="mt-2 preview-icone"><i class="${icone} fa-2x text-primary"></i></div>`);
            $('.preview-icone').not(':last').remove();
        }
    });
});

function filtrarTabela() {
    const status = $('#filtroStatus').val();
    const categoria = $('#filtroCategoria').val().toLowerCase();
    const busca = $('#buscaTipo').val().toLowerCase();

    $('#tabelaTipos tbody tr').each(function() {
        const row = $(this);
        const nome = row.find('td:eq(1)').text().toLowerCase();
        const extensao = row.find('td:eq(2)').text().toLowerCase();
        const categoriaText = row.find('td:eq(3)').text().toLowerCase();
        const statusAtivo = row.find('td:eq(5) .badge-success').length > 0;

        let showRow = true;

        // Filtro por status
        if(status) {
            const statusValue = (status === '1') ? true : false;
            if(statusAtivo !== statusValue) {
                showRow = false;
            }
        }

        // Filtro por categoria
        if(categoria && !categoriaText.includes(categoria)) {
            showRow = false;
        }

        // Filtro por busca
        if(busca && !nome.includes(busca) && !extensao.includes(busca)) {
            showRow = false;
        }

        row.toggle(showRow);
    });
}

function visualizarTipo(id) {
    $.ajax({
        url: `/admin/tipos-arquivos/${id}`,
        method: 'GET',
        success: function(response) {
            $('#conteudoVisualizacao').html(response);
            $('#modalVisualizarTipo').modal('show');
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'Erro ao carregar tipo de arquivo.'
            });
        }
    });
}

function editarTipo(id) {
    window.location.href = `/admin/tipos-arquivos/${id}/edit`;
}

function excluirTipo(id) {
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
                url: `/admin/tipos-arquivos/${id}`,
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
                        text: 'Erro ao excluir tipo de arquivo.'
                    });
                }
            });
        }
    });
}
</script>
@stop
