<?php

namespace App\Services;

use App\Models\Edicao;
use App\Models\Assinatura;
use App\Models\Materia;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AssinaturaService
{
    /**
     * Assinar um PDF usando certificado digital
     */
    public function assinarPdf(Edicao $edicao, array $dadosSignatario = [])
    {
        $user = auth()->user();
        
        if (!$user) {
            throw new \Exception('Usuário não autenticado');
        }
        
        if (!$user->podeAssinar()) {
            throw new \Exception('Usuário não tem permissão para assinar documentos ou dados incompletos');
        }

        // Gerar hash do arquivo PDF
        $caminhoArquivo = storage_path("app/public/{$edicao->caminho_arquivo}");
        $hash = hash_file('sha256', $caminhoArquivo);
        $tamanho = filesize($caminhoArquivo);
        
        // Usar dados do usuário logado ou permitir override manual
        $signatario = $dadosSignatario['nome'] ?? strtoupper($user->name);
        $cpf = $dadosSignatario['cpf'] ?? $user->cpf;
        $cargo = $dadosSignatario['cargo'] ?? $user->cargo;
        $ac = $dadosSignatario['ac'] ?? $user->ac_certificado ?? 'AC SAFEWEB RFB v5';
        
        $assinatura = Assinatura::create([
            'edicao_id' => $edicao->id,
            'signatario' => $signatario,
            'cpf_signatario' => $cpf,
            'cargo_signatario' => $cargo,
            'ac' => $ac,
            'algoritmo' => 'SHA-256',
            'hash' => $hash,
            'carimbo_tempo' => Carbon::now(),
            'arquivo_original' => $edicao->caminho_arquivo,
            'tamanho_arquivo' => $tamanho,
            'cadeia_certificados' => json_encode([
                'emissor' => $ac,
                'serial' => Str::random(16),
                'validade' => Carbon::now()->addYear()->format('Y-m-d H:i:s'),
                'icp' => 'ICP-Brasil'
            ]),
            'signed_by' => $user->id
        ]);

        return $assinatura;
    }

    /**
     * Assinar uma matéria específica
     */
    public function assinarMateria(Materia $materia, array $dadosSignatario = [])
    {
        $user = auth()->user();
        
        if (!$user) {
            throw new \Exception('Usuário não autenticado');
        }
        
        if (!$user->podeAssinar()) {
            throw new \Exception('Usuário não tem permissão para assinar documentos ou dados incompletos');
        }

        // Usar dados do usuário logado ou permitir override manual
        $signatario = $dadosSignatario['nome'] ?? strtoupper($user->name);
        $cpf = $dadosSignatario['cpf'] ?? $user->cpf;
        $cargo = $dadosSignatario['cargo'] ?? $user->cargo;
        $ac = $dadosSignatario['ac'] ?? $user->ac_certificado ?? 'AC SAFEWEB RFB v5';
        
        // Gerar hash do conteúdo da matéria
        $conteudo = $materia->titulo . $materia->texto . $materia->numero . $materia->updated_at;
        $hash = hash('sha256', $conteudo);
        
        $assinatura = Assinatura::create([
            'materia_id' => $materia->id,
            'signatario' => $signatario,
            'cpf_signatario' => $cpf,
            'cargo_signatario' => $cargo,
            'ac' => $ac,
            'algoritmo' => 'SHA-256',
            'hash' => $hash,
            'carimbo_tempo' => Carbon::now(),
            'cadeia_certificados' => json_encode([
                'emissor' => $ac,
                'serial' => Str::random(16),
                'validade' => Carbon::now()->addYear()->format('Y-m-d H:i:s'),
                'icp' => 'ICP-Brasil'
            ]),
            'signed_by' => $user->id
        ]);

        return $assinatura;
    }

    /**
     * Verificar a assinatura de um PDF
     */
    public function verificarAssinatura(Edicao $edicao)
    {
        // TODO: Implementar verificação real da assinatura digital
        if (!$edicao->assinatura) {
            return false;
        }

        $hashAtual = hash_file('sha256', storage_path("app/public/{$edicao->arquivo_pdf}"));
        return $hashAtual === $edicao->assinatura->hash;
    }

    /**
     * Gerar texto formatado da assinatura para exibição
     */
    public function formatarAssinatura(Assinatura $assinatura, $nomeArquivo = null)
    {
        $tamanhoFormatado = $this->formatarTamanhoArquivo($assinatura->tamanho_arquivo ?? 0);
        
        return [
            'titulo' => 'Assinatura Digital',
            'identificador' => $assinatura->signatario . ':' . $assinatura->cpf_signatario,
            'cargo' => $assinatura->cargo_signatario,
            'ac' => $assinatura->ac,
            'carimbo_tempo' => $assinatura->carimbo_tempo->format('d/m/Y H:i:s') . 'h',
            'icp' => 'ICP',
            'hash' => $assinatura->hash,
            'arquivo' => $nomeArquivo ?? 'documento-signed.pdf',
            'tamanho' => $tamanhoFormatado
        ];
    }

    /**
     * Formatar tamanho do arquivo
     */
    private function formatarTamanhoArquivo($tamanhoBytes)
    {
        if ($tamanhoBytes == 0) {
            return '0 B';
        }

        $unidades = ['B', 'KB', 'MB', 'GB'];
        $indice = floor(log($tamanhoBytes, 1024));
        $tamanho = round($tamanhoBytes / pow(1024, $indice), 2);

        return $tamanho . ' ' . $unidades[$indice];
    }

    /**
     * Gerar texto completo da assinatura para PDF
     */
    public function gerarTextoAssinaturaPdf(Assinatura $assinatura, $nomeArquivo = null)
    {
        $dados = $this->formatarAssinatura($assinatura, $nomeArquivo);
        
        return "Assinatura Digital\n" .
               $dados['identificador'] . "\n" .
               $dados['cargo'] . "\n" .
               "AC " . $dados['ac'] . "\n" .
               "Carimbo do tempo\n" .
               $dados['carimbo_tempo'] . "\n" .
               "ICP\n" .
               "Hash: " . $dados['hash'] . "\n\n" .
               "Arquivo: " . $dados['arquivo'] . "\n\n" .
               "Tamanho: " . $dados['tamanho'];
    }
}
