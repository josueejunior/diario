<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tipo;
use App\Models\Orgao;
use App\Models\Materia;
use App\Models\Edicao;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DiarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário admin
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@diario.local',
            'password' => Hash::make('password')
        ]);

        // Criar tipos básicos
        $tipos = [
            'Lei Municipal' => 'Leis do município',
            'Decreto Municipal' => 'Decretos do município',
            'Portaria' => 'Portarias administrativas',
            'Edital' => 'Editais diversos',
            'Resolução' => 'Resoluções normativas',
            'Instrução Normativa' => 'Instruções normativas'
        ];

        foreach ($tipos as $nome => $descricao) {
            Tipo::create([
                'nome' => $nome,
                'slug' => str()->slug($nome),
                'descricao' => $descricao,
                'ativo' => true
            ]);
        }

        // Criar órgãos básicos
        $orgaos = [
            'Gabinete do Prefeito' => 'GAB',
            'Secretaria Municipal de Administração' => 'SEMAD',
            'Secretaria Municipal de Finanças' => 'SEMFIN',
            'Secretaria Municipal de Educação' => 'SEMED',
            'Secretaria Municipal de Saúde' => 'SEMSA',
            'Secretaria Municipal de Obras' => 'SEMOB'
        ];

        foreach ($orgaos as $nome => $sigla) {
            Orgao::create([
                'nome' => $nome,
                'slug' => str()->slug($nome),
                'sigla' => $sigla,
                'descricao' => "Responsável por {$nome}",
                'ativo' => true
            ]);
        }

        // Criar algumas matérias
        Materia::factory()
            ->count(20)
            ->published()
            ->create([
                'created_by' => $admin->id,
                'approved_by' => $admin->id
            ]);

        // Criar algumas edições
        Edicao::factory()
            ->count(5)
            ->create();
    }
}
