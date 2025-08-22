@extends('layouts.adminlte')

@section('title', 'Matérias')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1><i class="fas fa-file-alt mr-2"></i>Matérias</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Matérias</li>
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
                        <i class="fas fa-list mr-1"></i>
                        Lista de Matérias
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.materias.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Nova Matéria
                        </a>
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
                    <div class="card card-outline card-secondary collapsed-card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-filter mr-1"></i>
                                Filtros de Pesquisa
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body" style="display: none;">
                            <form action="{{ route('admin.materias.index') }}" method="GET">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="search">Buscar</label>
                                            <input type="text" name="search" id="search" class="form-control" 
                                                   value="{{ request('search') }}" placeholder="Título, número...">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="tipo_id">Tipo</label>
                                            <select name="tipo_id" id="tipo_id" class="form-control">
                                                <option value="">Todos os tipos</option>
                                                @foreach($tipos as $tipo)
                                                    <option value="{{ $tipo->id }}" 
                                                        {{ request('tipo_id') == $tipo->id ? 'selected' : '' }}>
                                                        {{ $tipo->nome }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="orgao_id">Órgão</label>
                                            <select name="orgao_id" id="orgao_id" class="form-control">
                                                <option value="">Todos os órgãos</option>
                                                @foreach($orgaos as $orgao)
                                                    <option value="{{ $orgao->id }}" 
                                                        {{ request('orgao_id') == $orgao->id ? 'selected' : '' }}>
                                                        {{ $orgao->nome }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="">Todos os status</option>
                                                <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                                                <option value="revisao" {{ request('status') == 'revisao' ? 'selected' : '' }}>Em Revisão</option>
                                                <option value="aprovado" {{ request('status') == 'aprovado' ? 'selected' : '' }}>Aprovado</option>
                                                <option value="publicado" {{ request('status') == 'publicado' ? 'selected' : '' }}>Publicado</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="data_inicio">Data Início</label>
                                            <input type="date" name="data_inicio" id="data_inicio" class="form-control" 
                                                   value="{{ request('data_inicio') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <div class="btn-group d-block">
                                                <button type="submit" class="btn btn-primary btn-block">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <a href="{{ route('admin.materias.index') }}" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-times mr-1"></i>
                                            Limpar Filtros
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Tabela -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="8%">Número</th>
                                    <th width="30%">Título</th>
                                    <th width="12%">Tipo</th>
                                    <th width="15%">Órgão</th>
                                    <th width="10%">Data</th>
                                    <th width="10%">Status</th>
                                    <th width="15%">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($materias as $materia)
                                    <tr>
                                        <td><strong>{{ $materia->numero }}</strong></td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 200px;" title="{{ $materia->titulo }}">
                                                {{ $materia->titulo }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">{{ $materia->tipo->nome ?? 'N/A' }}</span>
                                        </td>
                                        <td>{{ $materia->orgao->nome ?? 'N/A' }}</td>
                                        <td>{{ $materia->data ? $materia->data->format('d/m/Y') : 'N/A' }}</td>
                                        <td>
                                            @switch($materia->status)
                                                @case('pendente')
                                                    <span class="badge badge-warning">
                                                        <i class="fas fa-clock mr-1"></i>Pendente
                                                    </span>
                                                    @break
                                                @case('revisao')
                                                    <span class="badge badge-info">
                                                        <i class="fas fa-eye mr-1"></i>Em Revisão
                                                    </span>
                                                    @break
                                                @case('aprovado')
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check mr-1"></i>Aprovado
                                                    </span>
                                                    @break
                                                @case('publicado')
                                                    <span class="badge badge-dark">
                                                        <i class="fas fa-share mr-1"></i>Publicado
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="badge badge-secondary">{{ ucfirst($materia->status) }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.materias.show', $materia) }}" 
                                                   class="btn btn-info" title="Visualizar">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.materias.edit', $materia) }}" 
                                                   class="btn btn-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger" 
                                                        onclick="excluirMateria({{ $materia->id }})" title="Excluir">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <br>Nenhuma matéria encontrada.
                                            @if(request()->hasAny(['search', 'tipo_id', 'orgao_id', 'status', 'data_inicio']))
                                                <br><small>Tente ajustar os filtros de pesquisa.</small>
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    @if($materias->hasPages())
                        <div class="row mt-3 align-items-center">
                            <div class="col-sm-6">
                                <div class="dataTables_info">
                                    <small class="text-muted text-nowrap">
                                        Exibindo {{ $materias->firstItem() ?? 0 }} a {{ $materias->lastItem() ?? 0 }} de {{ $materias->total() }} matérias
                                    </small>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="dataTables_paginate float-sm-right">
                                    {{ $materias->appends(request()->query())->links('custom-pagination') }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="mt-3">
                            <small class="text-muted">
                                Total: {{ $materias->total() }} matérias
                            </small>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        .dataTables_info {
            padding-top: 8px;
            white-space: nowrap;
        }
        
        .dataTables_paginate {
            padding-top: 8px;
        }
        
        .pagination {
            margin: 0;
        }
        
        .pagination-sm .page-link {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.25;
            border-radius: 0.2rem;
        }
        
        .pagination-sm .page-item:first-child .page-link {
            border-top-left-radius: 0.2rem;
            border-bottom-left-radius: 0.2rem;
        }
        
        .pagination-sm .page-item:last-child .page-link {
            border-top-right-radius: 0.2rem;
            border-bottom-right-radius: 0.2rem;
        }
        
        .pagination .page-link {
            color: #495057;
            background-color: #fff;
            border: 1px solid #dee2e6;
            text-decoration: none;
        }
        
        .pagination .page-link:hover {
            color: #0056b3;
            background-color: #e9ecef;
            border-color: #dee2e6;
        }
        
        .pagination .page-item.active .page-link {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }
        
        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #fff;
            border-color: #dee2e6;
            cursor: not-allowed;
        }
        
        /* Responsive adjustments */
        @media (max-width: 576px) {
            .dataTables_info, .dataTables_paginate {
                text-align: center !important;
                float: none !important;
            }
            
            .dataTables_info {
                white-space: normal;
                margin-bottom: 15px;
            }
            
            .pagination {
                justify-content: center !important;
            }
        }
        
        /* Table responsive improvements */
        .table-responsive {
            border: none;
        }
        
        .text-truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
@stop

@section('js')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function excluirMateria(id) {
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
                        url: `/admin/materias/${id}`,
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
                                text: 'Erro ao excluir matéria.'
                            });
                        }
                    });
                }
            });
        }

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

