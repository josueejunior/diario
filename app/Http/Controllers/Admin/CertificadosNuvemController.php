<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CertificadoNuvem;
use App\Models\ConfiguracaoSistema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class CertificadosNuvemController extends Controller
{
    public function index()
    {
        $certificados = CertificadoNuvem::orderBy('created_at', 'desc')->paginate(15);
        $configuracoes = ConfiguracaoSistema::pluck('valor', 'chave');
        
        return view('admin.certificados-nuvem.index', compact('certificados', 'configuracoes'));
    }

    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'arquivo' => 'required|file|mimes:p12,pfx|max:10240',
            'senha' => 'required|string|min:4',
            'titular' => 'required|string|max:255',
            'cpf_cnpj' => 'required|string|max:20',
            'data_inicio' => 'required|date',
            'data_vencimento' => 'required|date|after:data_inicio',
            'tipo' => 'required|in:A1,A3,S1,S3',
            'observacoes' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Verificar se o certificado pode ser lido com a senha fornecida
            $arquivoTemp = $request->file('arquivo')->getRealPath();
            $this->validarCertificado($arquivoTemp, $request->senha);

            // Armazenar o arquivo do certificado
            $arquivo = $request->file('arquivo');
            $nomeArquivo = time() . '_' . uniqid() . '.' . $arquivo->getClientOriginalExtension();
            $caminhoArquivo = $arquivo->storeAs('certificados', $nomeArquivo, 'local');

            // Criar registro no banco
            $certificado = CertificadoNuvem::create([
                'nome' => $request->nome,
                'arquivo_p12' => $caminhoArquivo,
                'senha_encrypted' => $request->senha, // Será criptografado pelo mutator do model
                'titular' => $request->titular,
                'cpf_cnpj' => $request->cpf_cnpj,
                'data_inicio' => $request->data_inicio,
                'data_vencimento' => $request->data_vencimento,
                'tipo' => $request->tipo,
                'status' => $request->has('ativo') ? 'ativo' : 'inativo',
                'observacoes' => $request->observacoes,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Certificado digital carregado com sucesso!',
                'data' => $certificado
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar certificado: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $certificado = CertificadoNuvem::findOrFail($id);
            
            // Remover arquivo físico
            if (Storage::disk('local')->exists($certificado->arquivo_p12)) {
                Storage::disk('local')->delete($certificado->arquivo_p12);
            }

            // Remover registro do banco
            $certificado->delete();

            return response()->json([
                'success' => true,
                'message' => 'Certificado excluído com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir certificado: ' . $e->getMessage()
            ], 500);
        }
    }

    public function test($id)
    {
        try {
            $certificado = CertificadoNuvem::findOrFail($id);
            $caminhoCompleto = storage_path('app/' . $certificado->arquivo_p12);
            
            if (!file_exists($caminhoCompleto)) {
                throw new \Exception('Arquivo do certificado não encontrado.');
            }

            // Validar certificado
            $this->validarCertificado($caminhoCompleto, $certificado->senha);

            // Verificar validade
            if ($certificado->isVencido()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Certificado está vencido desde ' . $certificado->data_vencimento->format('d/m/Y')
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Certificado está válido e funcionando corretamente.',
                'valid_until' => $certificado->data_vencimento->format('d/m/Y'),
                'days_remaining' => $certificado->diasParaVencimento()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao testar certificado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate certificate file with password.
     */
    private function validarCertificado($caminhoArquivo, $senha)
    {
        // Verificar se o arquivo existe
        if (!file_exists($caminhoArquivo)) {
            throw new \Exception('Arquivo do certificado não encontrado.');
        }

        // Tentar ler o certificado
        $certificado = file_get_contents($caminhoArquivo);
        if (!$certificado) {
            throw new \Exception('Não foi possível ler o arquivo do certificado.');
        }

        // Verificar se é um arquivo PKCS#12 válido
        $certs = [];
        
        if (!openssl_pkcs12_read($certificado, $certs, $senha)) {
            throw new \Exception('Senha do certificado inválida ou arquivo corrompido.');
        }

        // Verificar se contém certificado e chave privada
        if (!isset($certs['cert']) || !isset($certs['pkey'])) {
            throw new \Exception('Certificado não contém os componentes necessários.');
        }

        return true;
    }
}
