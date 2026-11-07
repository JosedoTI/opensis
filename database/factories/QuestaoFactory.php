<?php

namespace Database\Factories;

use App\Models\Prova;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestaoFactory extends Factory
{
    public function definition(): array
    {
        static $contador = 0;
        $contador++;

        return [
            'prova_id'     => Prova::factory(),
            'numero'       => $contador,
            'enunciado'    => $this->faker->paragraphs(2, true),
            'status'       => 'completa',
            'ativa'        => true,
        ];
    }
}
