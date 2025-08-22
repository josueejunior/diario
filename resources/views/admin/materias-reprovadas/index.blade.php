@extends('layouts.adminlte')

@section('title', 'Matérias Reprovadas')
@section('page-title', 'Matérias Reprovadas')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-times-circle mr-2"></i>
                        Matérias Reprovadas
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-danger">{{ $materiasReprovadas->total() ?? 0 }} matérias</span>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($materiasReprovadas) && $materiasReprovadas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Tipo</th>
                                        <th>Órgão</th>
                                        <th>Data Reprovação</th>
                                        <th>Motivo</th>
                                        <th width="150">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($materiasReprovadas as $materia)
                                    <tr>
                                        <td>{{ $materia->titulo }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ $materia->tipo->nome ?? 'N/A' }}</span>
                                        </td>
                                        <td>{{ $materia->orgao->nome ?? 'N/A' }}</td>
                                        <td>{{ $materia->updated_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-info" data-toggle="popover" 
                                                    data-content="{{ $materia->observacoes ?? 'Sem observações' }}" 
                                                    title="Motivo da Reprovação">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" onclick="revisar({{ $materia->id }})" title="Enviar para Revisão">
                                                <i class="fas fa-redo"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="excluir({{ $materia->id }})" title="Excluir Definitivamente">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center">
                            {{ $materiasReprovadas->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h5>Nenhuma matéria reprovada</h5>
                            <p class="text-muted">Todas as matérias estão aprovadas ou em análise.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Revisar -->
<div class="modal fade" id="modalRevisar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Enviar para Revisão</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="formRevisar">
                    <input type="hidden" id="materia_id" name="materia_id">
                    <div class="form-group">
                        <label>Observações para Revisão</label>
                        <textarea class="form-control" name="observacoes" rows="4" 
                                  placeholder="Descreva as correções necessárias..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" form="formRevisar" class="btn btn-warning">Enviar para Revisão</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});

function revisar(materiaId) {
    $('#materia_id').val(materiaId);
    $('#modalRevisar').modal('show');
}

function excluir(materiaId) {
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
            // Implementar exclusão
            console.log('Excluindo matéria:', materiaId);
        }
    });
}

$('#formRevisar').on('submit', function(e) {
    e.preventDefault();
    // Implementar envio para revisão
    console.log('Enviando para revisão...');
    $('#modalRevisar').modal('hide');
});
</script>
@endpush
