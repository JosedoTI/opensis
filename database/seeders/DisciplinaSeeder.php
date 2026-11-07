<?php

namespace Database\Seeders;

use App\Models\Assunto;
use App\Models\Disciplina;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DisciplinaSeeder extends Seeder
{
    public function run(): void
    {
        $dados = [
            [
                'nome'  => 'Língua Portuguesa',
                'slug'  => 'lingua-portuguesa',
                'cor'   => '#e74c3c',
                'icone' => 'bi-book',
                'assuntos' => [
                    'Interpretação de Texto',
                    'Gramática',
                    'Ortografia e Acentuação',
                    'Morfologia',
                    'Sintaxe',
                    'Redação',
                    'Literatura Brasileira',
                    'Figuras de Linguagem',
                    'Coerência e Coesão',
                ],
            ],
            [
                'nome'  => 'Matemática',
                'slug'  => 'matematica',
                'cor'   => '#3498db',
                'icone' => 'bi-calculator',
                'assuntos' => [
                    'Aritmética',
                    'Álgebra',
                    'Funções',
                    'Geometria Plana',
                    'Geometria Espacial',
                    'Trigonometria',
                    'Estatística e Probabilidade',
                    'Progressões',
                    'Matrizes e Determinantes',
                    'Análise Combinatória',
                    'Logaritmos',
                    'Equações e Inequações',
                ],
            ],
            [
                'nome'  => 'Física',
                'slug'  => 'fisica',
                'cor'   => '#9b59b6',
                'icone' => 'bi-lightning',
                'assuntos' => [
                    'Mecânica',
                    'Cinemática',
                    'Dinâmica',
                    'Energia e Trabalho',
                    'Termologia',
                    'Óptica',
                    'Ondulatória',
                    'Eletricidade',
                    'Eletromagnetismo',
                    'Física Moderna',
                ],
            ],
            [
                'nome'  => 'Química',
                'slug'  => 'quimica',
                'cor'   => '#27ae60',
                'icone' => 'bi-droplet',
                'assuntos' => [
                    'Química Geral',
                    'Estrutura Atômica',
                    'Tabela Periódica',
                    'Ligações Químicas',
                    'Funções Inorgânicas',
                    'Reações Químicas',
                    'Estequiometria',
                    'Soluções',
                    'Termoquímica',
                    'Eletroquímica',
                    'Química Orgânica',
                    'Polímeros',
                ],
            ],
            [
                'nome'  => 'Biologia',
                'slug'  => 'biologia',
                'cor'   => '#2ecc71',
                'icone' => 'bi-diagram-3',
                'assuntos' => [
                    'Citologia',
                    'Histologia',
                    'Embriologia',
                    'Genética',
                    'Evolução',
                    'Ecologia',
                    'Fisiologia Animal',
                    'Fisiologia Vegetal',
                    'Microbiologia',
                    'Zoologia',
                    'Botânica',
                    'Saúde e Doenças',
                ],
            ],
            [
                'nome'  => 'História',
                'slug'  => 'historia',
                'cor'   => '#e67e22',
                'icone' => 'bi-hourglass',
                'assuntos' => [
                    'História Geral',
                    'História do Brasil',
                    'História da Amazônia',
                    'Pré-história',
                    'Idade Antiga',
                    'Idade Média',
                    'Idade Moderna',
                    'Idade Contemporânea',
                    'Brasil Colônia',
                    'Brasil Império',
                    'Brasil República',
                    'Ditadura Militar',
                    'Redemocratização',
                ],
            ],
            [
                'nome'  => 'Geografia',
                'slug'  => 'geografia',
                'cor'   => '#1abc9c',
                'icone' => 'bi-globe',
                'assuntos' => [
                    'Geografia Física',
                    'Geografia Humana',
                    'Cartografia',
                    'Climatologia',
                    'Geomorfologia',
                    'Hidrografia',
                    'Biogeografia',
                    'Geopolítica',
                    'Urbanização',
                    'Industrialização',
                    'Agropecuária',
                    'Amazônia',
                ],
            ],
            [
                'nome'  => 'Língua Inglesa',
                'slug'  => 'lingua-inglesa',
                'cor'   => '#c0392b',
                'icone' => 'bi-translate',
                'assuntos' => [
                    'Reading Comprehension',
                    'Grammar',
                    'Vocabulary',
                    'Writing',
                ],
            ],
            [
                'nome'  => 'Filosofia',
                'slug'  => 'filosofia',
                'cor'   => '#8e44ad',
                'icone' => 'bi-lightbulb',
                'assuntos' => [
                    'Filosofia Antiga',
                    'Filosofia Medieval',
                    'Filosofia Moderna',
                    'Filosofia Contemporânea',
                    'Ética',
                    'Lógica',
                    'Epistemologia',
                    'Política',
                ],
            ],
            [
                'nome'  => 'Sociologia',
                'slug'  => 'sociologia',
                'cor'   => '#d35400',
                'icone' => 'bi-people',
                'assuntos' => [
                    'Conceitos Sociológicos',
                    'Estrutura Social',
                    'Cultura',
                    'Movimentos Sociais',
                    'Trabalho e Sociedade',
                    'Desigualdades Sociais',
                    'Globalização',
                ],
            ],
        ];

        foreach ($dados as $disciplinaData) {
            $assuntos = $disciplinaData['assuntos'];
            unset($disciplinaData['assuntos']);

            $disciplina = Disciplina::firstOrCreate(
                ['slug' => $disciplinaData['slug']],
                $disciplinaData
            );

            foreach ($assuntos as $nomeAssunto) {
                Assunto::firstOrCreate(
                    [
                        'disciplina_id' => $disciplina->id,
                        'slug'          => Str::slug($nomeAssunto),
                    ],
                    [
                        'nome'  => $nomeAssunto,
                        'ativo' => true,
                    ]
                );
            }
        }
    }
}
