<?php

namespace Database\Factories;

use App\Models\Questao;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RespostaComunidadeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'questao_id'        => Questao::factory(),
            'user_id'           => User::factory(),
            'conteudo'          => $this->faker->paragraphs(2, true),
            'resposta_indicada' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E', null]),
            'votos_util'        => 0,
            'total_denuncias'   => 0,
            'oculta'            => false,
        ];
    }
}
