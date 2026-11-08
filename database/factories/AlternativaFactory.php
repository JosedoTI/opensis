<?php

namespace Database\Factories;

use App\Models\Questao;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlternativaFactory extends Factory
{
    private static int $letraIndex = 0;

    public function definition(): array
    {
        $letras = ['A', 'B', 'C', 'D', 'E'];
        $letra  = $letras[self::$letraIndex % 5];
        self::$letraIndex++;

        return [
            'questao_id' => Questao::factory(),
            'letra'      => $letra,
            'texto'      => $this->faker->sentence(),
            'ativa'      => true,
        ];
    }
}
