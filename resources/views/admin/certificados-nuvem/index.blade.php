@extends('layouts.adminlte')

@section('title', 'Certificados em Nuvem')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1><i class="fas fa-cloud mr-2"></i>Certificados em Nuvem</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Certificados em Nuvem</li>
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
                                <i class="fas fa-certificate mr-1"></i>
                                Gerenciar Certificados Digitais
                            </h3>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalUploadCertificado">
                                <i class="fas fa-upload"></i> Fazer Upload
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Alertas -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Importante:</strong> Os certificados são armazenados de forma segura e criptografada. 
                                Apenas usuários autorizados podem visualizar e utilizar os certificados.
                            </div>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status:</label>
                                <select class="form-control" id="filtroStatus">
                                    <option value="">Todos</option>
                                    <option value="valido">Válido</option>
                                    <option value="vencido">Vencido</option>
                                    <option value="vencendo">Vencendo em 30 dias</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tipo:</label>
                                <select class="form-control" id="filtroTipo">
                                    <option value="">Todos</option>
                                    <option value="A1">A1</option>
                                    <option value="A3">A3</option>
                                    <option value="A4">A4</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Buscar:</label>
                                <input type="text" class="form-control" id="buscaCertificado" placeholder="Digite o nome ou titular...">
                            </div>
                        </div>
                    </div>

                    <!-- Tabela -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tabelaCertificados">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="20%">Nome</th>
                                    <th width="25%">Titular</th>
                                    <th width="10%">Tipo</th>
                                    <th width="15%">Validade</th>
                                    <th width="10%">Status</th>
                                    <th width="20%">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($certificados as $certificado)
                                    <tr>
                                        <td>
                                            <strong>{{ $certificado->nome }}</strong>
                                            @if($certificado->observacoes)
                                                <br><small class="text-muted">{{ Str::limit($certificado->observacoes, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $certificado->titular }}
                                            @if($certificado->cpf_cnpj)
                                                <br><small class="text-muted">{{ $certificado->cpf_cnpj_formatado }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $certificado->tipo }}</span>
                                        </td>
                                        <td>
                                            {{ $certificado->validade->format('d/m/Y') }}
                                            <br><small class="text-muted">{{ $certificado->dias_para_vencer }} dias</small>
                                        </td>
                                        <td>
                                            @if($certificado->esta_vencido)
                                                <span class="badge badge-danger">Vencido</span>
                                            @elseif($certificado->vence_em_30_dias)
                                                <span class="badge badge-warning">Vencendo</span>
                                            @else
                                                <span class="badge badge-success">Válido</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-info" onclick="visualizarCertificado({{ $certificado->id }})" title="Visualizar">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-primary" onclick="testarCertificado({{ $certificado->id }})" title="Testar">
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                                <button type="button" class="btn btn-warning" onclick="downloadCertificado({{ $certificado->id }})" title="Download">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger" onclick="excluirCertificado({{ $certificado->id }})" title="Excluir">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            <i class="fas fa-certificate fa-3x mb-3"></i>
                                            <br>Nenhum certificado digital cadastrado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    @if($certificados->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $certificados->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Upload Certificado -->
    <div class="modal fade" id="modalUploadCertificado" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h4 class="modal-title">
                        <i class="fas fa-upload"></i> Upload de Certificado Digital
                    </h4>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="formUploadCertificado" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Atenção:</strong> Certifique-se de que o arquivo do certificado está protegido por senha e que você possui autorização para fazer upload.
                        </div>

                        <div class="form-group">
                            <label for="nome">Nome do Certificado: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nome" id="nome" required placeholder="Ex: Certificado Prefeitura 2024">
                        </div>

                        <div class="form-group">
                            <label for="arquivo">Arquivo do Certificado: <span class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="arquivo" id="arquivo" required accept=".p12,.pfx">
                                <label class="custom-file-label" for="arquivo">Escolher arquivo (.p12, .pfx)...</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="senha">Senha do Certificado: <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="senha" id="senha" required placeholder="Digite a senha do certificado">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="titular">Titular:</label>
                                    <input type="text" class="form-control" name="titular" id="titular" placeholder="Nome do titular">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cpf_cnpj">CPF/CNPJ:</label>
                                    <input type="text" class="form-control" name="cpf_cnpj" id="cpf_cnpj" placeholder="000.000.000-00 ou 00.000.000/0000-00">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipo">Tipo:</label>
                                    <select class="form-control" name="tipo" id="tipo">
                                        <option value="">Selecione o tipo</option>
                                        <option value="A1">A1</option>
                                        <option value="A3">A3</option>
                                        <option value="A4">A4</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="validade">Data de Validade:</label>
                                    <input type="date" class="form-control" name="validade" id="validade">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="observacoes">Observações:</label>
                            <textarea class="form-control" name="observacoes" id="observacoes" rows="3" placeholder="Informações adicionais sobre o certificado..."></textarea>
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
                            <i class="fas fa-upload"></i> Fazer Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Visualizar Certificado -->
    <div class="modal fade" id="modalVisualizarCertificado" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">
                        <i class="fas fa-eye"></i> Detalhes do Certificado
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

    <!-- Modal Teste de Certificado -->
    <div class="modal fade" id="modalTesteCertificado" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">
                        <i class="fas fa-check-circle"></i> Teste de Certificado
                    </h4>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="conteudoTeste">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin fa-3x mb-3"></i>
                        <p>Testando certificado...</p>
                    </div>
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
    $('#filtroStatus, #filtroTipo').change(function() {
        filtrarTabela();
    });

    $('#buscaCertificado').on('keyup', function() {
        filtrarTabela();
    });

    // Custom file input label
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
    });

    // Máscara CPF/CNPJ
    $('#cpf_cnpj').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        
        if(value.length <= 11) {
            // CPF
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        } else {
            // CNPJ
            value = value.replace(/^(\d{2})(\d)/, '$1.$2');
            value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
            value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
            value = value.replace(/(\d{4})(\d)/, '$1-$2');
        }
        
        $(this).val(value);
    });

    // Form upload certificado
    $('#formUploadCertificado').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        
        // Mostrar loading
        Swal.fire({
            title: 'Fazendo Upload...',
            text: 'Por favor, aguarde enquanto processamos o certificado.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        $.ajax({
            url: '{{ route("admin.certificados-nuvem.upload") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.close();
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
                Swal.close();
                let message = 'Erro ao fazer upload do certificado.';
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
    });
});

function filtrarTabela() {
    const status = $('#filtroStatus').val().toLowerCase();
    const tipo = $('#filtroTipo').val().toLowerCase();
    const busca = $('#buscaCertificado').val().toLowerCase();

    $('#tabelaCertificados tbody tr').each(function() {
        const row = $(this);
        const nome = row.find('td:eq(0)').text().toLowerCase();
        const titular = row.find('td:eq(1)').text().toLowerCase();
        const tipoText = row.find('td:eq(2)').text().toLowerCase();
        const statusText = row.find('td:eq(4)').text().toLowerCase();

        let showRow = true;

        // Filtro por status
        if(status) {
            if(status === 'valido' && !statusText.includes('válido')) showRow = false;
            if(status === 'vencido' && !statusText.includes('vencido')) showRow = false;
            if(status === 'vencendo' && !statusText.includes('vencendo')) showRow = false;
        }

        // Filtro por tipo
        if(tipo && !tipoText.includes(tipo)) {
            showRow = false;
        }

        // Filtro por busca
        if(busca && !nome.includes(busca) && !titular.includes(busca)) {
            showRow = false;
        }

        row.toggle(showRow);
    });
}

function visualizarCertificado(id) {
    $.ajax({
        url: `/admin/certificados-nuvem/${id}`,
        method: 'GET',
        success: function(response) {
            $('#conteudoVisualizacao').html(response);
            $('#modalVisualizarCertificado').modal('show');
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'Erro ao carregar certificado.'
            });
        }
    });
}

function testarCertificado(id) {
    $('#modalTesteCertificado').modal('show');
    
    $.ajax({
        url: `/admin/certificados-nuvem/${id}/test`,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if(response.success) {
                $('#conteudoTeste').html(`
                    <div class="text-center text-success">
                        <i class="fas fa-check-circle fa-3x mb-3"></i>
                        <h5>Certificado Válido!</h5>
                        <p>${response.message}</p>
                    </div>
                `);
            } else {
                $('#conteudoTeste').html(`
                    <div class="text-center text-danger">
                        <i class="fas fa-times-circle fa-3x mb-3"></i>
                        <h5>Erro no Certificado</h5>
                        <p>${response.message}</p>
                    </div>
                `);
            }
        },
        error: function(xhr) {
            let message = 'Erro ao testar certificado.';
            if(xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            
            $('#conteudoTeste').html(`
                <div class="text-center text-danger">
                    <i class="fas fa-times-circle fa-3x mb-3"></i>
                    <h5>Erro no Teste</h5>
                    <p>${message}</p>
                </div>
            `);
        }
    });
}

function downloadCertificado(id) {
    window.open(`/admin/certificados-nuvem/${id}/download`, '_blank');
}

function excluirCertificado(id) {
    Swal.fire({
        title: 'Tem certeza?',
        text: 'Esta ação não pode ser desfeita e o certificado será permanentemente removido!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/certificados-nuvem/${id}`,
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
                        text: 'Erro ao excluir certificado.'
                    });
                }
            });
        }
    });
}
</script>
@stop
