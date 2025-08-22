@extends('layouts.adminlte')

@section('title', 'Dados da Entidade')
@section('page-title', 'Dados da Entidade')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-building mr-2"></i>
                        Configurações da Entidade
                    </h3>
                </div>
                <div class="card-body">
                    <form id="formEntidade" method="POST" action="{{ route('admin.entidade.update') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="mb-3">Dados Básicos</h5>
                                
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="nome">Razão Social *</label>
                                            <input type="text" class="form-control" id="nome" name="nome" 
                                                   value="Prefeitura Municipal de Augustinópolis" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="nome_fantasia">Nome Fantasia</label>
                                            <input type="text" class="form-control" id="nome_fantasia" name="nome_fantasia" 
                                                   value="Prefeitura de Augustinópolis">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="cnpj">CNPJ *</label>
                                            <input type="text" class="form-control" id="cnpj" name="cnpj" 
                                                   value="01.234.567/0001-89" data-mask="00.000.000/0000-00" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="inscricao_estadual">Inscrição Estadual</label>
                                            <input type="text" class="form-control" id="inscricao_estadual" name="inscricao_estadual" 
                                                   value="123.456.789.012">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="inscricao_municipal">Inscrição Municipal</label>
                                            <input type="text" class="form-control" id="inscricao_municipal" name="inscricao_municipal" 
                                                   value="123456">
                                        </div>
                                    </div>
                                </div>

                                <h5 class="mb-3 mt-4">Endereço</h5>
                                
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="endereco">Logradouro *</label>
                                            <input type="text" class="form-control" id="endereco" name="endereco" 
                                                   value="Rua Principal" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="numero">Número *</label>
                                            <input type="text" class="form-control" id="numero" name="numero" 
                                                   value="123" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="complemento">Complemento</label>
                                            <input type="text" class="form-control" id="complemento" name="complemento" 
                                                   value="Centro">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="bairro">Bairro *</label>
                                            <input type="text" class="form-control" id="bairro" name="bairro" 
                                                   value="Centro" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="cidade">Cidade *</label>
                                            <input type="text" class="form-control" id="cidade" name="cidade" 
                                                   value="Augustinópolis" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="estado">Estado *</label>
                                            <select class="form-control" id="estado" name="estado" required>
                                                <option value="TO" selected>TO</option>
                                                <option value="AC">AC</option>
                                                <option value="AL">AL</option>
                                                <!-- outros estados -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="cep">CEP *</label>
                                            <input type="text" class="form-control" id="cep" name="cep" 
                                                   value="77940-000" data-mask="00000-000" required>
                                        </div>
                                    </div>
                                </div>

                                <h5 class="mb-3 mt-4">Contato</h5>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="telefone">Telefone</label>
                                            <input type="text" class="form-control" id="telefone" name="telefone" 
                                                   value="(63) 3484-1234" data-mask="(00) 0000-0000">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="email">E-mail</label>
                                            <input type="email" class="form-control" id="email" name="email" 
                                                   value="contato@augustinopolis.to.gov.br">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="site">Site</label>
                                            <input type="url" class="form-control" id="site" name="site" 
                                                   value="https://augustinopolis.to.gov.br">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <h5 class="mb-3">Logo da Entidade</h5>
                                <div class="text-center">
                                    <div class="mb-3">
                                        <img src="{{ asset('images/logo-entidade.png') }}" 
                                             alt="Logo Atual" 
                                             class="img-thumbnail" 
                                             style="max-width: 200px; max-height: 200px;"
                                             id="preview-logo">
                                    </div>
                                    <div class="form-group">
                                        <label for="logo">Alterar Logo</label>
                                        <input type="file" class="form-control-file" id="logo" name="logo" 
                                               accept="image/*" onchange="previewLogo(this)">
                                        <small class="text-muted">Formatos aceitos: JPG, PNG, GIF. Máximo: 2MB</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Salvar Alterações
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                    <i class="fas fa-undo"></i> Cancelar
                                </button>
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
function previewLogo(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#preview-logo').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function resetForm() {
    if (confirm('Deseja desfazer as alterações?')) {
        document.getElementById('formEntidade').reset();
        location.reload();
    }
}

$(document).ready(function() {
    // Aplicar máscaras
    $('#cnpj').mask('00.000.000/0000-00');
    $('#cep').mask('00000-000');
    $('#telefone').mask('(00) 0000-0000');
});
</script>
@endpush
