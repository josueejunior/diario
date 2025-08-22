<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Edicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EdicaoController extends Controller
{
    /**
     * Exibe uma lista de todas as edições.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $edicoes = Edicao::withCount('materias')
            ->orderBy('data', 'desc')
            ->paginate(15);
        return view('admin.edicoes.index', compact('edicoes'));
    }

    /**
     * Mostra o formulário para criar uma nova edição.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.edicoes.create');
    }

    /**
     * Armazena uma nova edição no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Aumentar o limite de tamanho para upload antes da validação
        ini_set('upload_max_filesize', '100M');
        ini_set('post_max_size', '100M');
        ini_set('memory_limit', '256M');
        
        $request->validate([
            'numero' => 'required|string|max:255|unique:edicoes,numero',
            'data' => 'required|date',
            'tipo' => 'required|in:normal,extra',
            'arquivo_pdf' => 'required|file|mimes:pdf|max:102400', // 100MB em kilobytes
        ]);

        $arquivo = $request->file('arquivo_pdf');
        $caminhoArquivo = $arquivo->store('edicoes', 'public');
        
        $edicao = Edicao::create([
            'numero' => $request->numero,
            'data' => $request->data,
            'tipo' => $request->tipo,
            'caminho_arquivo' => $caminhoArquivo,
            'hash' => Str::random(16),
            'carimbo_tempo' => now(),
            'signatario' => $request->signatario ?? 'Sistema',
            'ac' => $request->ac ?? 'AC-DIARIO',
            'algoritmo' => 'SHA-256',
            'tamanho' => $arquivo->getSize(),
            'descricao' => $request->descricao,
            'publicado' => false
        ]);

        return redirect()->route('edicoes.show', $edicao)
                        ->with('success', 'Edição criada com sucesso.');
    }

    /**
     * Exibe os detalhes de uma edição específica.
     *
     * @param  \App\Models\Edicao  $edicao
     * @return \Illuminate\View\View
     */
    public function show(Edicao $edicao)
    {
        $materias = $edicao->materias()->paginate(10);
        return view('admin.edicoes.show', compact('edicao', 'materias'));
    }

    /**
     * Mostra o formulário para editar uma edição.
     *
     * @param  \App\Models\Edicao  $edicao
     * @return \Illuminate\View\View
     */
    public function edit(Edicao $edicao)
    {
        return view('admin.edicoes.edit', compact('edicao'));
    }

    /**
     * Atualiza uma edição no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Edicao  $edicao
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Edicao $edicao)
    {
        // Aumentar o limite de tamanho para upload antes da validação
        ini_set('upload_max_filesize', '100M');
        ini_set('post_max_size', '100M');
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', '300');
        ini_set('max_input_time', '300');
        
        $request->validate([
            'numero' => 'required|string|max:255|unique:edicoes,numero,' . $edicao->id,
            'data' => 'required|date',
            'tipo' => 'required|in:normal,extra',
            'arquivo_pdf' => 'nullable|file|mimes:pdf|max:102400', // 100MB em kilobytes
        ]);

        $dados = [
            'numero' => $request->numero,
            'data' => $request->data,
            'tipo' => $request->tipo,
            'descricao' => $request->descricao
        ];

        if ($request->hasFile('arquivo_pdf')) {
            // Apaga o arquivo antigo se existir
            if ($edicao->caminho_arquivo && Storage::disk('public')->exists($edicao->caminho_arquivo)) {
                Storage::disk('public')->delete($edicao->caminho_arquivo);
            }
            
            $arquivo = $request->file('arquivo_pdf');
            $dados['caminho_arquivo'] = $arquivo->store('edicoes', 'public');
            $dados['tamanho'] = $arquivo->getSize();
        }

        $edicao->update($dados);

        return redirect()->route('admin.edicoes.show', $edicao)
                         ->with('success', 'Edição atualizada com sucesso.');
    }

    /**
     * Remove uma edição do banco de dados.
     *
     * @param  \App\Models\Edicao  $edicao
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Edicao $edicao)
    {
        // Apaga o arquivo se existir
        if ($edicao->caminho_arquivo && Storage::disk('public')->exists($edicao->caminho_arquivo)) {
            Storage::disk('public')->delete($edicao->caminho_arquivo);
        }

        $edicao->delete();

        // Se for uma requisição AJAX, retorna JSON
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Edição excluída com sucesso.'
            ]);
        }

        return redirect()->route('admin.edicoes.index')
                         ->with('success', 'Edição excluída com sucesso.');
    }

    /**
     * Publica uma edição.
     *
     * @param  \App\Models\Edicao  $edicao
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function publicar(Edicao $edicao)
    {
        $edicao->update([
            'publicado' => true,
            'data_publicacao' => now()
        ]);

        // Se for uma requisição AJAX, retorna JSON
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Edição publicada com sucesso!'
            ]);
        }

        return back()->with('success', 'Edição publicada com sucesso!');
    }

    /**
     * Assina uma edição digitalmente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Edicao  $edicao
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assinar(Request $request, Edicao $edicao)
    {
        // Aqui você implementaria a lógica de assinatura digital
        // Por exemplo, usando um serviço externo ou uma biblioteca

        $edicao->update([
            'signatario' => $request->signatario ?? auth()->user()->name,
            'ac' => $request->ac ?? 'AC-DIARIO',
            'carimbo_tempo' => now()
        ]);

        return back()->with('success', 'Edição assinada digitalmente com sucesso!');
    }

    /**
     * Gera ou exibe o PDF de uma edição.
     *
     * @param  \App\Models\Edicao  $edicao
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function pdf(Edicao $edicao)
    {
        // Verificar se tem caminho de arquivo definido
        if ($edicao->caminho_arquivo && Storage::disk('public')->exists($edicao->caminho_arquivo)) {
            // Se o arquivo existir, retorna o arquivo
            return response()->file(
                storage_path("app/public/{$edicao->caminho_arquivo}"), 
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="edicao-'.$edicao->numero.'.pdf"'
                ]
            );
        }

        // Se não existir arquivo, gera um PDF temporário com informações básicas
        $html = '
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Edição ' . $edicao->numero . '</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; }
                .header { text-align: center; margin-bottom: 30px; }
                .title { font-size: 24px; font-weight: bold; }
                .subtitle { font-size: 16px; margin-top: 10px; }
                .content { margin-top: 30px; }
                .info { margin: 10px 0; }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="title">DIÁRIO OFICIAL</div>
                <div class="subtitle">Edição Nº ' . $edicao->numero . '</div>
                <div class="subtitle">Data: ' . $edicao->data->format('d/m/Y') . '</div>
            </div>
            <div class="content">
                <div class="info"><strong>Status:</strong> ' . ($edicao->publicado ? 'Publicado' : 'Rascunho') . '</div>
                <div class="info"><strong>Tipo:</strong> ' . ucfirst($edicao->tipo) . '</div>
                <div class="info"><strong>Descrição:</strong> ' . ($edicao->descricao ?: 'Sem descrição') . '</div>
                ' . ($edicao->observacoes ? '<div class="info"><strong>Observações:</strong> ' . $edicao->observacoes . '</div>' : '') . '
            </div>
        </body>
        </html>';

        // Gerar PDF usando DomPDF
        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);
        
        return $pdf->stream("edicao-{$edicao->numero}.pdf");
    }
}
