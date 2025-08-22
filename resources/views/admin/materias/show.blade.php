@extends('layouts.adminlte')

@section('title', 'Detalhes da Matéria')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1><i class="fas fa-file-alt mr-2"></i>Detalhes da Matéria</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.materias.index') }}">Matérias</a></li>
                <li class="breadcrumb-item active">{{ $materia->numero }}</li>
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
                                <i class="fas fa-file-alt mr-1"></i>
                                Matéria {{ $materia->numero }}
                            </h3>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="{{ route('admin.materias.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Voltar
                            </a>
                            <a href="{{ route('admin.materias.edit', $materia) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <button type="button" class="btn btn-danger" onclick="excluirMateria({{ $materia->id }})">
                                <i class="fas fa-trash"></i> Excluir
                            </button>
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

                    <div class="row">
                        <!-- Informações Principais -->
                        <div class="col-md-8">
                            <div class="card card-outline card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Informações da Matéria
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <dl class="row">
                                        <dt class="col-sm-3">Número:</dt>
                                        <dd class="col-sm-9"><strong>{{ $materia->numero }}</strong></dd>
                                        
                                        <dt class="col-sm-3">Título:</dt>
                                        <dd class="col-sm-9">{{ $materia->titulo }}</dd>
                                        
                                        <dt class="col-sm-3">Tipo:</dt>
                                        <dd class="col-sm-9">
                                            <span class="badge badge-primary">{{ $materia->tipo->nome ?? 'N/A' }}</span>
                                        </dd>
                                        
                                        <dt class="col-sm-3">Órgão:</dt>
                                        <dd class="col-sm-9">{{ $materia->orgao->nome ?? 'N/A' }}</dd>
                                        
                                        <dt class="col-sm-3">Data:</dt>
                                        <dd class="col-sm-9">{{ $materia->data ? $materia->data->format('d/m/Y') : 'N/A' }}</dd>
                                        
                                        <dt class="col-sm-3">Status:</dt>
                                        <dd class="col-sm-9">
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
                                        </dd>
                                        
                                        <dt class="col-sm-3">Criado em:</dt>
                                        <dd class="col-sm-9">{{ $materia->created_at->format('d/m/Y H:i:s') }}</dd>
                                        
                                        <dt class="col-sm-3">Atualizado em:</dt>
                                        <dd class="col-sm-9">{{ $materia->updated_at->format('d/m/Y H:i:s') }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>

                        <!-- Ações e Status -->
                        <div class="col-md-4">
                            <div class="card card-outline card-secondary">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-cogs mr-1"></i>
                                        Ações
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="btn-group-vertical btn-block">
                                        <a href="{{ route('admin.materias.edit', $materia) }}" class="btn btn-warning mb-2">
                                            <i class="fas fa-edit mr-1"></i>
                                            Editar Matéria
                                        </a>
                                        
                                        @if($materia->arquivo)
                                            <a href="{{ Storage::url($materia->arquivo) }}" target="_blank" class="btn btn-info mb-2">
                                                <i class="fas fa-download mr-1"></i>
                                                Baixar Arquivo
                                            </a>
                                        @endif
                                        
                                        @if($materia->status === 'pendente')
                                            <button type="button" class="btn btn-success mb-2" onclick="aprovarMateria({{ $materia->id }})">
                                                <i class="fas fa-check mr-1"></i>
                                                Aprovar
                                            </button>
                                            <button type="button" class="btn btn-warning mb-2" onclick="enviarRevisao({{ $materia->id }})">
                                                <i class="fas fa-eye mr-1"></i>
                                                Enviar para Revisão
                                            </button>
                                        @endif
                                        
                                        <button type="button" class="btn btn-danger" onclick="excluirMateria({{ $materia->id }})">
                                            <i class="fas fa-trash mr-1"></i>
                                            Excluir Matéria
                                        </button>
                                    </div>
                                </div>
                            </div>

                            @if($materia->arquivo)
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-file mr-1"></i>
                                        Arquivo Anexo
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <i class="fas fa-file-alt fa-3x text-info mb-3"></i>
                                        <p class="mb-2"><strong>{{ basename($materia->arquivo) }}</strong></p>
                                        <a href="{{ Storage::url($materia->arquivo) }}" target="_blank" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye mr-1"></i>
                                            Visualizar
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Conteúdo da Matéria -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-outline card-success">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-file-text mr-1"></i>
                                        Conteúdo da Matéria
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="content-display">
                                        {!! nl2br(e($materia->texto)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Revisão -->
    <div class="modal fade" id="modalRevisao" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        <i class="fas fa-eye mr-1"></i>
                        Enviar para Revisão
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="formRevisao" method="POST" action="{{ route('admin.materias.revisar', $materia) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="notas_revisao">Notas de Revisão <span class="text-danger">*</span></label>
                            <textarea name="notas_revisao" id="notas_revisao" rows="4" class="form-control" required
                                      placeholder="Descreva os ajustes necessários..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i>
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-eye mr-1"></i>
                            Enviar para Revisão
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        .content-display {
            line-height: 1.6;
            font-size: 1rem;
        }
        
        .btn-group-vertical .btn {
            text-align: left;
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
                                    window.location.href = '{{ route("admin.materias.index") }}';
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

        function aprovarMateria(id) {
            Swal.fire({
                title: 'Aprovar Matéria?',
                text: 'Esta matéria será marcada como aprovada.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sim, aprovar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/materias/${id}/aprovar`,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if(response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Aprovado!',
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
                                text: 'Erro ao aprovar matéria.'
                            });
                        }
                    });
                }
            });
        }

        function enviarRevisao(id) {
            $('#modalRevisao').modal('show');
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
