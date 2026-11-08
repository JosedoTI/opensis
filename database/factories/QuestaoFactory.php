<?php

namespace Database\Factories;

use App\Models\Prova;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestaoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'prova_id'     => Prova::factory(),
            'numero'       => $this->faker->unique()->numberBetween(1, 9999),
            'enunciado'    => $this->faker->paragraphs(2, true),
            'status'       => 'completa',
            'ativa'        => true,
        ];
    }
}
