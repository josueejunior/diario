<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tipo;
use App\Models\Orgao;
use App\Models\Edicao;
use App\Models\Materia;
use App\Models\User;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário admin se não existir
        $admin = User::firstOrCreate(
            ['email' => 'admin@diario.gov.br'],
            [
                'name' => 'Elaine Maria Ferreira Costa',
                'password' => bcrypt('admin123'),
                'cpf' => '00788952943',
                'cargo' => 'Expedidora',
                'ac_certificado' => 'AC SAFEWEB RFB v5',
                'pode_assinar' => true,
            ]
        );
        
        // Criar tipos de matéria
        $tipos = [
            ['nome' => 'Decreto', 'slug' => 'decreto', 'descricao' => 'Atos administrativos da competência exclusiva do Chefe do Executivo'],
            ['nome' => 'Portaria', 'slug' => 'portaria', 'descricao' => 'Atos administrativos internos'],
            ['nome' => 'Edital', 'slug' => 'edital', 'descricao' => 'Comunicação oficial de atos administrativos'],
            ['nome' => 'Lei', 'slug' => 'lei', 'descricao' => 'Regra de direito ditada pela autoridade estatal'],
            ['nome' => 'Resolução', 'slug' => 'resolucao', 'descricao' => 'Deliberações de órgãos colegiados'],
        ];
        
        foreach ($tipos as $tipo) {
            Tipo::firstOrCreate(
                ['nome' => $tipo['nome']],
                $tipo
            );
        }
        
        // Criar órgãos
        $orgaos = [
            ['nome' => 'Gabinete do Prefeito', 'sigla' => 'GAB', 'slug' => 'gabinete-do-prefeito', 'descricao' => 'Gabinete do Prefeito Municipal'],
            ['nome' => 'Secretaria de Administração', 'sigla' => 'SAD', 'slug' => 'secretaria-de-administracao', 'descricao' => 'Secretaria Municipal de Administração'],
            ['nome' => 'Secretaria de Finanças', 'sigla' => 'SF', 'slug' => 'secretaria-de-financas', 'descricao' => 'Secretaria Municipal de Finanças'],
            ['nome' => 'Secretaria de Educação', 'sigla' => 'SE', 'slug' => 'secretaria-de-educacao', 'descricao' => 'Secretaria Municipal de Educação'],
            ['nome' => 'Secretaria de Saúde', 'sigla' => 'SS', 'slug' => 'secretaria-de-saude', 'descricao' => 'Secretaria Municipal de Saúde'],
        ];
        
        foreach ($orgaos as $orgao) {
            Orgao::firstOrCreate(
                ['nome' => $orgao['nome']],
                $orgao
            );
        }
        
        // Criar edições
        $edicoes = [];
        for ($i = 1; $i <= 5; $i++) {
            $edicao = Edicao::firstOrCreate(
                ['numero' => $i],
                [
                    'data' => now()->subDays(30 - $i * 5),
                    'tipo' => 'normal',
                    'hash' => Str::random(16),
                    'caminho_arquivo' => 'edicoes/' . $i . '.pdf',
                    'carimbo_tempo' => now(),
                    'signatario' => 'Autoridade Certificadora',
                    'ac' => 'AC-DIARIO',
                    'algoritmo' => 'SHA-256',
                    'tamanho' => rand(1000000, 5000000)
                ]
            );
            
            // Criar arquivo PDF fake se não existir
            $caminhoCompleto = storage_path('app/public/' . $edicao->caminho_arquivo);
            $diretorio = dirname($caminhoCompleto);
            if (!file_exists($diretorio)) {
                mkdir($diretorio, 0755, true);
            }
            if (!file_exists($caminhoCompleto)) {
                // Criar um PDF simples mas válido
                $pdfContent = '%PDF-1.4
1 0 obj
<<
/Type /Catalog
/Pages 2 0 R
>>
endobj

2 0 obj
<<
/Type /Pages
/Kids [3 0 R]
/Count 1
>>
endobj

3 0 obj
<<
/Type /Page
/Parent 2 0 R
/MediaBox [0 0 612 792]
/Contents 4 0 R
>>
endobj

4 0 obj
<<
/Length 44
>>
stream
BT
/F1 12 Tf
72 720 Td
(Edição ' . $edicao->numero . ' - ' . $edicao->data->format('d/m/Y') . ') Tj
ET
endstream
endobj

xref
0 5
0000000000 65535 f 
0000000010 00000 n 
0000000053 00000 n 
0000000125 00000 n 
0000000221 00000 n 
trailer
<<
/Size 5
/Root 1 0 R
>>
startxref
314
%%EOF';
                file_put_contents($caminhoCompleto, $pdfContent);
            }
            
            // Criar assinatura para a edição
            \App\Models\Assinatura::firstOrCreate(
                ['edicao_id' => $edicao->id],
                [
                    'signatario' => 'Elaine Maria Ferreira Costa',
                    'ac' => 'AC SAFEWEB RFB v5',
                    'algoritmo' => 'SHA-256 with RSA',
                    'hash' => hash('sha256', 'edicao_' . $edicao->numero . '_' . $edicao->data->format('Y-m-d')),
                    'carimbo_tempo' => $edicao->carimbo_tempo ?? now(),
                    'signed_by' => $admin->id,
                    'cadeia_certificados' => [
                        'certificado_principal' => 'CN=Elaine Maria Ferreira Costa',
                        'autoridade_certificadora' => 'AC SAFEWEB RFB v5',
                        'validade' => now()->addYear()->toISOString()
                    ]
                ]
            );
            
            // Criar algumas visualizações e downloads para estatísticas
            for ($v = 0; $v < rand(20, 100); $v++) {
                \App\Models\Visualizacao::create([
                    'edicao_id' => $edicao->id,
                    'ip' => '192.168.' . rand(1, 255) . '.' . rand(1, 255),
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'created_at' => now()->subDays(rand(0, 30))
                ]);
            }
            
            for ($d = 0; $d < rand(5, 30); $d++) {
                \App\Models\Download::create([
                    'edicao_id' => $edicao->id,
                    'ip' => '192.168.' . rand(1, 255) . '.' . rand(1, 255),
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'created_at' => now()->subDays(rand(0, 30))
                ]);
            }
            
            $edicoes[] = $edicao;
        }
        
        // Criar matérias para cada edição
        $tiposIds = Tipo::all()->pluck('id')->toArray();
        $orgaosIds = Orgao::all()->pluck('id')->toArray();
        
        foreach ($edicoes as $edicao) {
            // Criar entre 3 e 5 matérias por edição
            $numMaterias = rand(3, 5);
            
            for ($i = 1; $i <= $numMaterias; $i++) {
                // Verificar se já existe uma matéria com este número para o tipo e ano
                $tipoId = $tiposIds[array_rand($tiposIds)];
                $orgaoId = $orgaosIds[array_rand($orgaosIds)];
                $numero = rand(1, 500) . '/' . date('Y');
                
                // Tenta criar uma matéria, evitando duplicação de números
                try {
                    $materia = Materia::create([
                        'tipo_id' => $tipoId,
                        'orgao_id' => $orgaoId,
                        'numero' => $numero,
                        'data' => now()->subDays(rand(1, 30)),
                        'titulo' => "Matéria #{$i} da Edição #{$edicao->numero}: " . $this->getFakeTitle(),
                        'texto' => $this->getFakeText(),
                        'status' => 'aprovado',
                        'created_by' => $admin->id,
                        'approved_by' => $admin->id,
                        'approved_at' => now()
                    ]);
                    
                    // Associar matéria à edição
                    $edicao->materias()->attach($materia->id);
                } catch (\Exception $e) {
                    // Se houver erro (provavelmente devido à restrição unique), apenas continua
                    continue;
                }
            }
        }
    }
    
    /**
     * Gerar um título fake para matéria
     */
    private function getFakeTitle(): string
    {
        $actions = ['Dispõe sobre', 'Altera', 'Regulamenta', 'Estabelece', 'Institui', 'Nomeia'];
        $subjects = [
            'a reorganização administrativa', 
            'o plano diretor municipal', 
            'o código de obras',
            'a política de desenvolvimento urbano',
            'o conselho municipal',
            'o plano de cargos e salários',
            'o regime jurídico dos servidores',
            'o calendário de feriados municipais'
        ];
        
        return $actions[array_rand($actions)] . ' ' . $subjects[array_rand($subjects)];
    }
    
    /**
     * Gerar um texto fake para matéria
     */
    private function getFakeText(): string
    {
        $paragraphs = [
            "O PREFEITO MUNICIPAL, no uso de suas atribuições legais conferidas pela Lei Orgânica do Município, DECRETA:",
            "Art. 1º - Fica aprovado nos termos deste decreto, conforme documentação e parecer constante do Processo Administrativo.",
            "Art. 2º - Os órgãos competentes deverão adotar as medidas necessárias para a implementação e fiscalização das disposições contidas neste decreto.",
            "Art. 3º - As despesas decorrentes da execução deste decreto correrão à conta de dotações orçamentárias próprias.",
            "Art. 4º - Este decreto entra em vigor na data de sua publicação, revogando-se as disposições em contrário.",
            "Registre-se, publique-se, cumpra-se.",
            "Gabinete do Prefeito Municipal, em " . now()->format('d/m/Y') . "."
        ];
        
        return implode("\n\n", $paragraphs);
    }
}
