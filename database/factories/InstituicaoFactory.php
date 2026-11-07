<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InstituicaoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sigla'          => $this->faker->unique()->lexify('????'),
            'nome'           => $this->faker->company(),
            'nome_processo'  => $this->faker->randomElement(['SIS', 'PSC', 'VEST']),
            'cidade'         => 'Manaus',
            'estado'         => 'AM',
            'cor_primaria'   => '#003087',
            'ativa'          => true,
        ];
    }
}
