<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Edicao>
 */
class EdicaoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'numero' => fake()->unique()->numberBetween(1, 999),
            'data' => fake()->dateTimeBetween('-30 days', 'now'),
            'tipo' => 'normal',
            'hash' => Str::random(16),
            'caminho_arquivo' => 'edicoes/' . fake()->unique()->numberBetween(1, 999) . '.pdf',
            'carimbo_tempo' => now(),
            'signatario' => 'Autoridade Certificadora',
            'ac' => 'AC-DIARIO',
            'algoritmo' => 'SHA-256',
            'tamanho' => fake()->numberBetween(1000000, 5000000),
            'publicado' => false,
            'data_publicacao' => null
        ];
    }
}
