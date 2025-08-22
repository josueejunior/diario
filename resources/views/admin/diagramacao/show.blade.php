@extends('layouts.adminlte')

@section('title', 'Visualizar Edição')
@section('page-title', 'Visualizar Edição')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-eye mr-2"></i>
                        Edição {{ $edicao->numero ?? 'N/A' }} - {{ $edicao->data ? $edicao->data->format('d/m/Y') : 'N/A' }}
                    </h3>
                    <div class="card-tools">
                        @if(!$edicao->publicado)
                            <a href="{{ route('admin.diagramacao.edit', $edicao->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                        @endif
                        <a href="{{ route('admin.diagramacao.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informações da Edição</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Número:</th>
                                    <td>{{ $edicao->numero ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Data da Edição:</th>
                                    <td>{{ $edicao->data ? $edicao->data->format('d/m/Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($edicao->publicado)
                                            <span class="badge badge-success">Publicado</span>
                                        @elseif($edicao->descricao)
                                            <span class="badge badge-info">Pronto</span>
                                        @else
                                            <span class="badge badge-warning">Rascunho</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Data de Publicação:</th>
                                    <td>{{ $edicao->data_publicacao ? $edicao->data_publicacao->format('d/m/Y H:i') : 'Não publicado' }}</td>
                                </tr>
                                <tr>
                                    <th>Total de Matérias:</th>
                                    <td>{{ $edicao->materias->count() }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Estatísticas</h5>
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-eye"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Visualizações</span>
                                    <span class="info-box-number">{{ $edicao->visualizacoes ?? 0 }}</span>
                                </div>
                            </div>
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-download"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Downloads</span>
                                    <span class="info-box-number">{{ $edicao->downloads ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($edicao->descricao)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Descrição</h5>
                            <div class="alert alert-info">
                                {{ $edicao->descricao }}
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Matérias da Edição</h5>
                            @if($edicao->materias->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Número</th>
                                                <th>Título</th>
                                                <th>Tipo</th>
                                                <th>Órgão</th>
                                                <th>Data</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($edicao->materias as $materia)
                                                <tr>
                                                    <td>{{ $materia->numero ?? 'S/N' }}</td>
                                                    <td>{{ $materia->titulo }}</td>
                                                    <td>
                                                        <span class="badge badge-secondary">
                                                            {{ $materia->tipo->nome ?? 'N/A' }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $materia->orgao->nome ?? 'N/A' }}</td>
                                                    <td>{{ $materia->data ? $materia->data->format('d/m/Y') : $materia->created_at->format('d/m/Y') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Nenhuma matéria associada a esta edição.
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($edicao->publicado && $edicao->caminho_arquivo)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Arquivo da Edição</h5>
                            <div class="card">
                                <div class="card-body">
                                    <p><strong>Arquivo:</strong> {{ basename($edicao->caminho_arquivo) }}</p>
                                    <p><strong>Tamanho:</strong> {{ $edicao->tamanho ? number_format($edicao->tamanho / 1024, 2) . ' KB' : 'N/A' }}</p>
                                    <p><strong>Hash:</strong> {{ $edicao->hash ?? 'N/A' }}</p>
                                    <a href="{{ asset($edicao->caminho_arquivo) }}" target="_blank" class="btn btn-primary">
                                        <i class="fas fa-file-pdf"></i> Visualizar PDF
                                    </a>
                                    <a href="{{ asset($edicao->caminho_arquivo) }}" download class="btn btn-success">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
