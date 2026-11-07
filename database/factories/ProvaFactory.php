<?php

namespace Database\Factories;

use App\Models\Instituicao;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProvaFactory extends Factory
{
    private static int $etapaCounter = 0;

    public function definition(): array
    {
        // Garante combinação única de ano+etapa por instituição nos testes
        self::$etapaCounter++;
        $ano   = 2016 + (self::$etapaCounter % 11);
        $etapas = ['1ª Etapa', '2ª Etapa', '3ª Etapa', 'PSC 1', 'PSC 2', 'PSC 3'];
        $etapa = $etapas[self::$etapaCounter % count($etapas)];

        return [
            'instituicao_id'            => Instituicao::factory(),
            'ano'                       => $ano,
            'etapa'                     => $etapa . ' ' . self::$etapaCounter,
            'nome'                      => "Prova {$ano} — {$etapa} " . self::$etapaCounter,
            'tipo'                      => 'objetiva',
            'total_questoes'            => 40,
            'questoes_individualizadas' => $ano >= 2020,
            'gabarito_disponivel'       => false,
            'resolucao_disponivel'      => false,
            'ativa'                     => true,
        ];
    }
}
