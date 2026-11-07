<?php

namespace Database\Seeders;

use App\Models\Instituicao;
use Illuminate\Database\Seeder;

class InstituicaoSeeder extends Seeder
{
    public function run(): void
    {
        $instituicoes = [
            [
                'sigla'               => 'UEA',
                'nome'                => 'Universidade do Estado do Amazonas',
                'nome_processo'       => 'SIS',
                'descricao_processo'  => 'Sistema de Ingresso Seriado',
                'cidade'              => 'Manaus',
                'estado'              => 'AM',
                'cor_primaria'        => '#003087',
                'site_oficial'        => 'https://www.uea.edu.br',
                'ativa'               => true,
            ],
            [
                'sigla'               => 'UFAM',
                'nome'                => 'Universidade Federal do Amazonas',
                'nome_processo'       => 'PSC',
                'descricao_processo'  => 'Processo Seletivo Contínuo',
                'cidade'              => 'Manaus',
                'estado'              => 'AM',
                'cor_primaria'        => '#006400',
                'site_oficial'        => 'https://www.ufam.edu.br',
                'ativa'               => true,
            ],
        ];

        foreach ($instituicoes as $dados) {
            Instituicao::firstOrCreate(['sigla' => $dados['sigla']], $dados);
        }
    }
}
