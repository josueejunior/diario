<?php

namespace App\Services;

use App\Models\Edicao;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
    /**
     * Gerar PDF para uma edição
     */
    public function gerarPdf(Edicao $edicao)
    {
        // Configurar Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->setPaper('A4', 'portrait');

        // Gerar conteúdo HTML
        $html = view('pdf.edicao', compact('edicao'))->render();

        // Carregar HTML no Dompdf
        $dompdf->loadHtml($html);

        // Renderizar PDF
        $dompdf->render();

        // Salvar arquivo
        $filename = "edicoes/{$edicao->numero}-{$edicao->data->format('Y-m-d')}.pdf";
        Storage::put("public/{$filename}", $dompdf->output());

        // Atualizar caminho do arquivo na edição
        $edicao->update([
            'arquivo_pdf' => $filename,
            'tamanho' => Storage::size("public/{$filename}"),
            'hash' => hash_file('sha256', storage_path("app/public/{$filename}"))
        ]);

        return $filename;
    }
}
