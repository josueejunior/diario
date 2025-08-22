@extends('layouts.adminlte')

@section('title', 'Configurar Diário')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1><i class="fas fa-cogs mr-2"></i>Configurar Diário</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Configurar Diário</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <!-- Card Template do Diário -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title">
                        <i class="fas fa-file-alt mr-1"></i>
                        Template do Diário
                    </h3>
                </div>
                <div class="card-body">
                    <form id="formTemplate">
                        @csrf
                        <div class="form-group">
                            <label for="cabecalho">Cabeçalho:</label>
                            <textarea class="form-control" name="cabecalho" id="cabecalho" rows="4" placeholder="Digite o cabeçalho do diário...">{{ $configuracoes['cabecalho'] ?? '' }}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="rodape">Rodapé:</label>
                            <textarea class="form-control" name="rodape" id="rodape" rows="3" placeholder="Digite o rodapé do diário...">{{ $configuracoes['rodape'] ?? '' }}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="logo">Logo:</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="logo" id="logo" accept="image/*">
                                <label class="custom-file-label" for="logo">Escolher arquivo...</label>
                            </div>
                            @if(isset($configuracoes['logo']) && $configuracoes['logo'])
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $configuracoes['logo']) }}" alt="Logo atual" class="img-thumbnail" style="max-height: 100px;">
                                    <small class="text-muted d-block">Logo atual</small>
                                </div>
                            @endif
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="margem_superior">Margem Superior (mm):</label>
                                    <input type="number" class="form-control" name="margem_superior" id="margem_superior" value="{{ $configuracoes['margem_superior'] ?? 20 }}" min="0" max="100">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="margem_inferior">Margem Inferior (mm):</label>
                                    <input type="number" class="form-control" name="margem_inferior" id="margem_inferior" value="{{ $configuracoes['margem_inferior'] ?? 20 }}" min="0" max="100">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="margem_esquerda">Margem Esquerda (mm):</label>
                                    <input type="number" class="form-control" name="margem_esquerda" id="margem_esquerda" value="{{ $configuracoes['margem_esquerda'] ?? 15 }}" min="0" max="100">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="margem_direita">Margem Direita (mm):</label>
                                    <input type="number" class="form-control" name="margem_direita" id="margem_direita" value="{{ $configuracoes['margem_direita'] ?? 15 }}" min="0" max="100">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="fonte">Fonte:</label>
                            <select class="form-control" name="fonte" id="fonte">
                                <option value="Arial" {{ ($configuracoes['fonte'] ?? '') == 'Arial' ? 'selected' : '' }}>Arial</option>
                                <option value="Times New Roman" {{ ($configuracoes['fonte'] ?? '') == 'Times New Roman' ? 'selected' : '' }}>Times New Roman</option>
                                <option value="Helvetica" {{ ($configuracoes['fonte'] ?? '') == 'Helvetica' ? 'selected' : '' }}>Helvetica</option>
                                <option value="Courier New" {{ ($configuracoes['fonte'] ?? '') == 'Courier New' ? 'selected' : '' }}>Courier New</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="tamanho_fonte">Tamanho da Fonte:</label>
                            <select class="form-control" name="tamanho_fonte" id="tamanho_fonte">
                                <option value="10" {{ ($configuracoes['tamanho_fonte'] ?? '') == '10' ? 'selected' : '' }}>10pt</option>
                                <option value="11" {{ ($configuracoes['tamanho_fonte'] ?? '') == '11' ? 'selected' : '' }}>11pt</option>
                                <option value="12" {{ ($configuracoes['tamanho_fonte'] ?? '') == '12' ? 'selected' : '' }}>12pt</option>
                                <option value="14" {{ ($configuracoes['tamanho_fonte'] ?? '') == '14' ? 'selected' : '' }}>14pt</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Salvar Template
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Card Assinatura Digital -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success">
                    <h3 class="card-title">
                        <i class="fas fa-signature mr-1"></i>
                        Assinatura Digital
                    </h3>
                </div>
                <div class="card-body">
                    <form id="formAssinatura">
                        @csrf
                        <div class="form-group">
                            <label>Tipo de Assinatura:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_assinatura" id="assinatura_automatica" value="automatica" {{ ($configuracoes['tipo_assinatura'] ?? '') == 'automatica' ? 'checked' : '' }}>
                                <label class="form-check-label" for="assinatura_automatica">
                                    Assinatura Automática
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_assinatura" id="assinatura_manual" value="manual" {{ ($configuracoes['tipo_assinatura'] ?? '') == 'manual' ? 'checked' : '' }}>
                                <label class="form-check-label" for="assinatura_manual">
                                    Assinatura Manual
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group" id="grupo_certificado" style="{{ ($configuracoes['tipo_assinatura'] ?? '') == 'automatica' ? '' : 'display:none;' }}">
                            <label for="certificado_id">Certificado:</label>
                            <select class="form-control" name="certificado_id" id="certificado_id">
                                <option value="">Selecione um certificado</option>
                                @foreach($certificados as $certificado)
                                    <option value="{{ $certificado->id }}" {{ ($configuracoes['certificado_id'] ?? '') == $certificado->id ? 'selected' : '' }}>
                                        {{ $certificado->nome }} - Válido até {{ $certificado->validade->format('d/m/Y') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="posicao_assinatura">Posição da Assinatura:</label>
                            <select class="form-control" name="posicao_assinatura" id="posicao_assinatura">
                                <option value="final" {{ ($configuracoes['posicao_assinatura'] ?? '') == 'final' ? 'selected' : '' }}>Final do documento</option>
                                <option value="rodape" {{ ($configuracoes['posicao_assinatura'] ?? '') == 'rodape' ? 'selected' : '' }}>Rodapé de cada página</option>
                                <option value="primeira_pagina" {{ ($configuracoes['posicao_assinatura'] ?? '') == 'primeira_pagina' ? 'selected' : '' }}>Primeira página</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="texto_assinatura">Texto da Assinatura:</label>
                            <textarea class="form-control" name="texto_assinatura" id="texto_assinatura" rows="3" placeholder="Ex: Este documento foi assinado digitalmente...">{{ $configuracoes['texto_assinatura'] ?? '' }}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="mostrar_carimbo_tempo" id="mostrar_carimbo_tempo" value="1" {{ ($configuracoes['mostrar_carimbo_tempo'] ?? false) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="mostrar_carimbo_tempo">Mostrar carimbo de tempo</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="validacao_online" id="validacao_online" value="1" {{ ($configuracoes['validacao_online'] ?? false) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="validacao_online">Habilitar validação online</label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fas fa-save"></i> Salvar Configurações
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Card Preview -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">
                        <i class="fas fa-eye mr-1"></i>
                        Preview do Template
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-info btn-sm" onclick="atualizarPreview()">
                            <i class="fas fa-sync"></i> Atualizar Preview
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="preview-container" class="border p-3" style="min-height: 400px; background: white;">
                        <div id="preview-content">
                            <!-- Preview será carregado aqui -->
                            <div class="text-center text-muted">
                                <i class="fas fa-file-alt fa-3x mb-3"></i>
                                <p>Clique em "Atualizar Preview" para visualizar o template</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Toggle certificado baseado no tipo de assinatura
    $('input[name="tipo_assinatura"]').change(function() {
        if($(this).val() === 'automatica') {
            $('#grupo_certificado').show();
        } else {
            $('#grupo_certificado').hide();
        }
    });
    
    // Custom file input label
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
    });
    
    // Form template
    $('#formTemplate').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("admin.configurar-diario.template") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: response.message,
                        timer: 3000
                    });
                    atualizarPreview();
                }
            },
            error: function(xhr) {
                let message = 'Erro ao salvar template.';
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
    
    // Form assinatura
    $('#formAssinatura').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '{{ route("admin.configurar-diario.assinatura") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if(response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: response.message,
                        timer: 3000
                    });
                }
            },
            error: function(xhr) {
                let message = 'Erro ao salvar configurações.';
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
    
    // Atualizar preview automaticamente quando campos mudarem
    $('#cabecalho, #rodape, #fonte, #tamanho_fonte').on('input change', function() {
        clearTimeout(window.previewTimeout);
        window.previewTimeout = setTimeout(atualizarPreview, 1000);
    });
});

function atualizarPreview() {
    const dados = {
        cabecalho: $('#cabecalho').val(),
        rodape: $('#rodape').val(),
        fonte: $('#fonte').val(),
        tamanho_fonte: $('#tamanho_fonte').val(),
        margem_superior: $('#margem_superior').val(),
        margem_inferior: $('#margem_inferior').val(),
        margem_esquerda: $('#margem_esquerda').val(),
        margem_direita: $('#margem_direita').val()
    };
    
    // Simulação do preview
    let previewHtml = `
        <div style="font-family: ${dados.fonte}; font-size: ${dados.tamanho_fonte}pt; margin: ${dados.margem_superior}mm ${dados.margem_direita}mm ${dados.margem_inferior}mm ${dados.margem_esquerda}mm;">
            <div style="text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px;">
                <h3 style="margin: 0; color: #333;">${dados.cabecalho || 'DIÁRIO OFICIAL'}</h3>
            </div>
            
            <div style="min-height: 200px; padding: 20px;">
                <h4>EDIÇÃO Nº XXX - ${new Date().toLocaleDateString('pt-BR')}</h4>
                <p><strong>EXEMPLO DE MATÉRIA</strong></p>
                <p>Este é um exemplo de como o conteúdo aparecerá no diário oficial com as configurações atuais.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
            </div>
            
            <div style="text-align: center; border-top: 1px solid #666; padding-top: 10px; margin-top: 20px; font-size: smaller;">
                ${dados.rodape || 'www.exemplo.com.br - Página 1'}
            </div>
        </div>
    `;
    
    $('#preview-content').html(previewHtml);
}
</script>
@stop
