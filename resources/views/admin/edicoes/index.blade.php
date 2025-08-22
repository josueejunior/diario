@extends('layouts.adminlte')

@section('title', 'Edições')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1><i class="fas fa-newspaper mr-2"></i>Edições</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Edições</li>
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
                                <i class="fas fa-list mr-1"></i>
                                Lista de Edições
                            </h3>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="{{ route('admin.edicoes.create') }}" class="btn btn-success">
                                <i class="fas fa-plus"></i> Nova Edição
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <i class="fas fa-check mr-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Filtros -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status:</label>
                                <select class="form-control" id="filtroStatus">
                                    <option value="">Todos</option>
                                    <option value="publicado">Publicado</option>
                                    <option value="rascunho">Rascunho</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tipo:</label>
                                <select class="form-control" id="filtroTipo">
                                    <option value="">Todos</option>
                                    <option value="normal">Normal</option>
                                    <option value="extra">Extra</option>
                                    <option value="especial">Especial</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Período:</label>
                                <input type="date" class="form-control" id="filtroData">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Buscar:</label>
                                <input type="text" class="form-control" id="buscaEdicao" placeholder="Digite o número...">
                            </div>
                        </div>
                    </div>

                    <!-- Tabela -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tabelaEdicoes">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="15%">Número</th>
                                    <th width="15%">Data</th>
                                    <th width="15%">Tipo</th>
                                    <th width="15%">Status</th>
                                    <th width="15%">Matérias</th>
                                    <th width="25%">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($edicoes as $edicao)
                                    <tr>
                                        <td><strong>{{ $edicao->numero }}</strong></td>
                                        <td>{{ $edicao->data->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ ucfirst($edicao->tipo) }}</span>
                                        </td>
                                        <td>
                                            @if ($edicao->publicado)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check mr-1"></i>Publicado
                                                </span>
                                            @else
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-edit mr-1"></i>Rascunho
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary">{{ $edicao->materias_count ?? 0 }} matérias</span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.edicoes.show', $edicao) }}" class="btn btn-info" title="Visualizar">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.edicoes.edit', $edicao) }}" class="btn btn-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if (!$edicao->publicado)
                                                    <button type="button" class="btn btn-success" onclick="publicarEdicao({{ $edicao->id }})" title="Publicar">
                                                        <i class="fas fa-share"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-primary" onclick="gerarPDF({{ $edicao->id }})" title="Gerar PDF">
                                                    <i class="fas fa-file-pdf"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger" onclick="excluirEdicao({{ $edicao->id }})" title="Excluir">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <br>Nenhuma edição encontrada.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    @if($edicoes->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $edicoes->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Filtros
    $('#filtroStatus, #filtroTipo').change(function() {
        filtrarTabela();
    });

    $('#filtroData, #buscaEdicao').on('input change', function() {
        filtrarTabela();
    });
});

function filtrarTabela() {
    const status = $('#filtroStatus').val().toLowerCase();
    const tipo = $('#filtroTipo').val().toLowerCase();
    const data = $('#filtroData').val();
    const busca = $('#buscaEdicao').val().toLowerCase();

    $('#tabelaEdicoes tbody tr').each(function() {
        const row = $(this);
        const numero = row.find('td:eq(0)').text().toLowerCase();
        const dataEdicao = row.find('td:eq(1)').text();
        const tipoEdicao = row.find('td:eq(2)').text().toLowerCase();
        const statusEdicao = row.find('td:eq(3)').text().toLowerCase();

        let showRow = true;

        // Filtro por status
        if(status) {
            if(status === 'publicado' && !statusEdicao.includes('publicado')) showRow = false;
            if(status === 'rascunho' && !statusEdicao.includes('rascunho')) showRow = false;
        }

        // Filtro por tipo
        if(tipo && !tipoEdicao.includes(tipo)) {
            showRow = false;
        }

        // Filtro por busca
        if(busca && !numero.includes(busca)) {
            showRow = false;
        }

        row.toggle(showRow);
    });
}

function publicarEdicao(id) {
    Swal.fire({
        title: 'Publicar Edição?',
        text: 'Esta ação tornará a edição pública e visível no site.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim, publicar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/edicoes/${id}/publicar`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if(response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Publicado!',
                            text: response.message,
                            timer: 3000
                        }).then(() => {
                            location.reload();
                        });
                    }
                },
                error: function(xhr) {
                    let message = 'Erro ao publicar edição.';
                    if(xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: message
                    });
                }
            });
        }
    });
}

function gerarPDF(id) {
    // Abrir PDF diretamente
    window.open(`/admin/edicoes/${id}/pdf`, '_blank');
}

function excluirEdicao(id) {
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
                url: `/admin/edicoes/${id}`,
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
                error: function(xhr) {
                    let message = 'Erro ao excluir edição.';
                    if(xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: message
                    });
                }
            });
        }
    });
}
</script>
@stop
