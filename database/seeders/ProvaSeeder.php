<?php

namespace Database\Seeders;

use App\Models\Instituicao;
use App\Models\Prova;
use Illuminate\Database\Seeder;

class ProvaSeeder extends Seeder
{
    public function run(): void
    {
        $uea  = Instituicao::where('sigla', 'UEA')->first();
        $ufam = Instituicao::where('sigla', 'UFAM')->first();

        if (!$uea || !$ufam) {
            $this->command->warn('Instituições não encontradas. Execute InstituicaoSeeder primeiro.');
            return;
        }

        // ─── SIS/UEA — etapas: 1ª Etapa, 2ª Etapa, 3ª Etapa ─────────────
        // 2016-2019: disponíveis como documento, sem questões individualizadas
        // 2020-2026: questões individualizadas (cadastradas gradualmente)

        $etapasSIS = ['1ª Etapa', '2ª Etapa', '3ª Etapa'];

        for ($ano = 2016; $ano <= 2026; $ano++) {
            $individualizada = ($ano >= 2020);
            foreach ($etapasSIS as $etapa) {
                Prova::firstOrCreate(
                    [
                        'instituicao_id' => $uea->id,
                        'ano'            => $ano,
                        'etapa'          => $etapa,
                    ],
                    [
                        'nome'                      => "SIS {$ano} — {$etapa}",
                        'tipo'                      => 'objetiva',
                        'total_questoes'            => 40,
                        'questoes_individualizadas' => $individualizada,
                        'gabarito_disponivel'       => false,
                        'resolucao_disponivel'      => false,
                        'ativa'                     => true,
                    ]
                );
            }
        }

        // ─── PSC/UFAM — etapas: PSC 1, PSC 2, PSC 3 ─────────────────────
        $etapasPSC = ['PSC 1', 'PSC 2', 'PSC 3'];

        for ($ano = 2016; $ano <= 2026; $ano++) {
            $individualizada = ($ano >= 2020);
            foreach ($etapasPSC as $etapa) {
                Prova::firstOrCreate(
                    [
                        'instituicao_id' => $ufam->id,
                        'ano'            => $ano,
                        'etapa'          => $etapa,
                    ],
                    [
                        'nome'                      => "PSC {$ano} — {$etapa}",
                        'tipo'                      => 'objetiva',
                        'total_questoes'            => 45,
                        'questoes_individualizadas' => $individualizada,
                        'gabarito_disponivel'       => false,
                        'resolucao_disponivel'      => false,
                        'ativa'                     => true,
                    ]
                );
            }
        }
    }
}
