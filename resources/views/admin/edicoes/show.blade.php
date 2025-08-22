@extends('layouts.adminlte')

@section('title', 'Detalhes da Edição')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1><i class="fas fa-newspaper mr-2"></i>Detalhes da Edição</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.edicoes.index') }}">Edições</a></li>
                <li class="breadcrumb-item active">Edição {{ $edicao->numero }}</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Card Principal -->
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="card-title">
                                <i class="fas fa-file-alt mr-1"></i>
                                Edição {{ $edicao->numero }}
                            </h3>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="{{ route('admin.edicoes.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Voltar
                            </a>
                            <a href="{{ route('admin.edicoes.edit', $edicao) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            @if (!$edicao->publicado)
                                <button type="button" class="btn btn-success" onclick="publicarEdicao({{ $edicao->id }})">
                                    <i class="fas fa-share"></i> Publicar
                                </button>
                            @endif
                            <button type="button" class="btn btn-primary" onclick="gerarPDF({{ $edicao->id }})">
                                <i class="fas fa-file-pdf"></i> Ver PDF
                            </button>
                            <button type="button" class="btn btn-danger" onclick="excluirEdicao({{ $edicao->id }})">
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
                        <!-- Informações Gerais -->
                        <div class="col-md-6">
                            <div class="card card-outline card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Informações Gerais
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <dl class="row">
                                        <dt class="col-sm-4">Número:</dt>
                                        <dd class="col-sm-8"><strong>{{ $edicao->numero }}</strong></dd>
                                        
                                        <dt class="col-sm-4">Data:</dt>
                                        <dd class="col-sm-8">{{ $edicao->data->format('d/m/Y') }}</dd>
                                        
                                        <dt class="col-sm-4">Tipo:</dt>
                                        <dd class="col-sm-8">
                                            <span class="badge badge-info">{{ ucfirst($edicao->tipo) }}</span>
                                        </dd>
                                        
                                        <dt class="col-sm-4">Status:</dt>
                                        <dd class="col-sm-8">
                                            @if ($edicao->publicado)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check mr-1"></i>Publicado
                                                </span>
                                            @else
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-edit mr-1"></i>Rascunho
                                                </span>
                                            @endif
                                        </dd>
                                        
                                        @if ($edicao->publicado && $edicao->data_publicacao)
                                        <dt class="col-sm-4">Data de Publicação:</dt>
                                        <dd class="col-sm-8">{{ $edicao->data_publicacao->format('d/m/Y H:i:s') }}</dd>
                                        @endif
                                        
                                        <dt class="col-sm-4">Tamanho:</dt>
                                        <dd class="col-sm-8">{{ number_format(($edicao->tamanho ?? 0) / 1024, 2) }} KB</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>

                        <!-- Assinatura Digital -->
                        <div class="col-md-6">
                            <div class="card card-outline card-success">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-signature mr-1"></i>
                                        Assinatura Digital
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <dl class="row">
                                        <dt class="col-sm-4">Hash:</dt>
                                        <dd class="col-sm-8">
                                            @if($edicao->hash)
                                                <code class="small">{{ substr($edicao->hash, 0, 20) }}...</code>
                                            @else
                                                <span class="text-muted">Não assinado</span>
                                            @endif
                                        </dd>
                                        
                                        <dt class="col-sm-4">Signatário:</dt>
                                        <dd class="col-sm-8">{{ $edicao->signatario ?? 'N/A' }}</dd>
                                        
                                        <dt class="col-sm-4">Autoridade Certificadora:</dt>
                                        <dd class="col-sm-8">{{ $edicao->ac ?? 'N/A' }}</dd>
                                        
                                        <dt class="col-sm-4">Algoritmo:</dt>
                                        <dd class="col-sm-8">{{ $edicao->algoritmo ?? 'N/A' }}</dd>
                                        
                                        @if($edicao->carimbo_tempo)
                                        <dt class="col-sm-4">Carimbo de Tempo:</dt>
                                        <dd class="col-sm-8">{{ $edicao->carimbo_tempo->format('d/m/Y H:i:s') }}</dd>
                                        @endif
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($edicao->descricao)
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-align-left mr-1"></i>
                                        Descrição
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <p>{{ $edicao->descricao }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Arquivo PDF -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-outline card-secondary">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-file-pdf mr-1"></i>
                                        Arquivo PDF
                                    </h3>
                                </div>
                                <div class="card-body">
                                    @if ($edicao->caminho_arquivo || $edicao->arquivo_pdf)
                                        <a href="{{ Storage::url($edicao->caminho_arquivo ?? $edicao->arquivo_pdf) }}" target="_blank" class="btn btn-primary">
                                            <i class="fas fa-download mr-2"></i>
                                            Visualizar PDF
                                        </a>
                                    @else
                                        <p class="text-danger">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            Nenhum arquivo PDF disponível.
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Matérias desta Edição -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list mr-1"></i>
                        Matérias desta Edição
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-secondary">{{ $materias->total() }} matérias</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="10%">Número</th>
                                    <th width="35%">Título</th>
                                    <th width="15%">Tipo</th>
                                    <th width="15%">Órgão</th>
                                    <th width="10%">Status</th>
                                    <th width="15%">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($materias as $materia)
                                    <tr>
                                        <td><strong>{{ $materia->numero }}</strong></td>
                                        <td>{{ Str::limit($materia->titulo, 50) }}</td>
                                        <td>
                                            <span class="badge badge-primary">{{ $materia->tipo->nome ?? 'N/A' }}</span>
                                        </td>
                                        <td>{{ $materia->orgao->nome ?? 'N/A' }}</td>
                                        <td>
                                            @switch($materia->status)
                                                @case('pendente')
                                                    <span class="badge badge-warning">Pendente</span>
                                                    @break
                                                @case('revisao')
                                                    <span class="badge badge-info">Em Revisão</span>
                                                    @break
                                                @case('aprovado')
                                                    <span class="badge badge-success">Aprovado</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-secondary">{{ ucfirst($materia->status) }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.materias.show', $materia) }}" class="btn btn-info" title="Visualizar">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.materias.edit', $materia) }}" class="btn btn-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <br>Nenhuma matéria encontrada nesta edição.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    @if($materias->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $materias->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
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
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: 'Erro ao publicar edição.'
                    });
                }
            });
        }
    });
}

function gerarPDF(id) {
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
                            window.location.href = '{{ route("admin.edicoes.index") }}';
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: 'Erro ao excluir edição.'
                    });
                }
            });
        }
    });
}
</script>
@stop
