<?php

namespace Database\Factories;

use App\Models\Tipo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tipo>
 */
class TipoFactory extends Factory
{
    protected $model = Tipo::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nome = $this->faker->unique()->randomElement([
            'Lei Municipal',
            'Decreto Municipal',
            'Portaria',
            'Edital',
            'Resolução',
            'Instrução Normativa'
        ]);

        return [
            'nome' => $nome,
            'slug' => Str::slug($nome),
            'template' => null,
            'descricao' => $this->faker->sentence(),
            'ativo' => true,
        ];
    }
}
