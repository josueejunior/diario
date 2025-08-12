<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Edicao;
use App\Models\Materia;
use App\Models\User;
use App\Services\AssinaturaService;

class AssinarDocumento extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:assinar-documento {--edicao=} {--materia=} {--user=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assinar digitalmente uma edição ou matéria do diário oficial';

    protected $assinaturaService;

    public function __construct(AssinaturaService $assinaturaService)
    {
        parent::__construct();
        $this->assinaturaService = $assinaturaService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $edicaoId = $this->option('edicao');
        $materiaId = $this->option('materia');
        $userId = $this->option('user') ?? 1; // Padrão para admin

        // Autenticar o usuário
        $user = User::find($userId);
        if (!$user) {
            $this->error('Usuário não encontrado!');
            return 1;
        }

        auth()->login($user);

        try {
            if ($edicaoId) {
                $this->assinarEdicao($edicaoId);
            } elseif ($materiaId) {
                $this->assinarMateria($materiaId);
            } else {
                $this->error('Você deve especificar --edicao ou --materia');
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('Erro ao assinar documento: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    private function assinarEdicao($edicaoId)
    {
        $edicao = Edicao::find($edicaoId);
        if (!$edicao) {
            $this->error('Edição não encontrada!');
            return;
        }

        $this->info("Assinando edição #{$edicao->numero}...");
        $assinatura = $this->assinaturaService->assinarPdf($edicao);
        
        $this->info("Edição assinada com sucesso!");
        $this->info("Hash: {$assinatura->hash}");
        $this->info("Signatário: {$assinatura->signatario}");
    }

    private function assinarMateria($materiaId)
    {
        $materia = Materia::find($materiaId);
        if (!$materia) {
            $this->error('Matéria não encontrada!');
            return;
        }

        $this->info("Assinando matéria #{$materia->numero}...");
        $assinatura = $this->assinaturaService->assinarMateria($materia);
        
        $this->info("Matéria assinada com sucesso!");
        $this->info("Hash: {$assinatura->hash}");
        $this->info("Signatário: {$assinatura->signatario}");
    }
}
