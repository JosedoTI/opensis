<?php

namespace Tests\Feature;

use App\Models\Instituicao;
use App\Models\Prova;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FiltroProvasTest extends TestCase
{
    use RefreshDatabase;

    private function criarProvas(): void
    {
        $uea  = Instituicao::factory()->create(['sigla' => 'UEA', 'ativa' => true]);
        $ufam = Instituicao::factory()->create(['sigla' => 'UFAM', 'ativa' => true]);

        foreach (['1ª Etapa', '2ª Etapa', '3ª Etapa'] as $etapa) {
            Prova::factory()->create([
                'instituicao_id' => $uea->id,
                'ano'            => 2024,
                'etapa'          => $etapa,
                'nome'           => "SIS 2024 — {$etapa}",
                'ativa'          => true,
            ]);
        }

        foreach (['PSC 1', 'PSC 2', 'PSC 3'] as $etapa) {
            Prova::factory()->create([
                'instituicao_id' => $ufam->id,
                'ano'            => 2024,
                'etapa'          => $etapa,
                'nome'           => "PSC 2024 — {$etapa}",
                'ativa'          => true,
            ]);
        }
    }

    public function test_listagem_provas_exibe_select_de_etapas(): void
    {
        $this->criarProvas();

        $response = $this->get('/provas');
        $response->assertStatus(200);
        // Verifica que o select de etapa está presente
        $response->assertSee('name="etapa"', false);
        $response->assertSee('1ª Etapa');
        $response->assertSee('PSC 1');
    }

    public function test_filtro_por_etapa_retorna_apenas_provas_da_etapa(): void
    {
        $this->criarProvas();

        $response = $this->get('/provas?etapa=1%C2%AA+Etapa');
        $response->assertStatus(200);
        $response->assertSee('SIS 2024 — 1ª Etapa');
        $response->assertDontSee('SIS 2024 — 2ª Etapa');
        $response->assertDontSee('PSC 2024 — PSC 1');
    }

    public function test_filtro_combinado_instituicao_e_etapa(): void
    {
        $this->criarProvas();

        $uea = Instituicao::where('sigla', 'UEA')->first();

        $response = $this->get("/provas?instituicao={$uea->id}&etapa=2%C2%AA+Etapa");
        $response->assertStatus(200);
        $response->assertSee('SIS 2024 — 2ª Etapa');
        $response->assertDontSee('SIS 2024 — 1ª Etapa');
        $response->assertDontSee('PSC 2024');
    }

    public function test_paginacao_preserva_filtros(): void
    {
        // Cria 20 provas da mesma instituição/etapa para forçar paginação (paginate=18)
        $uea = Instituicao::factory()->create(['sigla' => 'UEA', 'ativa' => true]);
        for ($ano = 2016; $ano <= 2026; $ano++) {
            foreach (['1ª Etapa', '2ª Etapa'] as $etapa) {
                Prova::factory()->create([
                    'instituicao_id' => $uea->id,
                    'ano'            => $ano,
                    'etapa'          => $etapa,
                    'nome'           => "SIS {$ano} — {$etapa}",
                    'ativa'          => true,
                ]);
            }
        }
        // 22 provas no total — página 1 terá 18, página 2 terá 4

        // Página 1: verifica que os links de paginação existem e preservam filtros
        $response = $this->get("/provas?instituicao={$uea->id}&page=1");
        $response->assertStatus(200);
        // withQueryString() garante que os parâmetros aparecem nos links de paginação
        $response->assertSee('instituicao=' . $uea->id, false);
        $response->assertSee('page=2', false);

        // Página 2: deve carregar e manter o filtro de instituição
        $response2 = $this->get("/provas?instituicao={$uea->id}&page=2");
        $response2->assertStatus(200);
        // Deve mostrar provas da UEA na página 2
        $response2->assertSee('SIS');
    }

    public function test_filtro_por_etapa_psc(): void
    {
        $this->criarProvas();

        $response = $this->get('/provas?etapa=PSC+2');
        $response->assertStatus(200);
        $response->assertSee('PSC 2024 — PSC 2');
        $response->assertDontSee('PSC 2024 — PSC 1');
        $response->assertDontSee('SIS 2024');
    }

    public function test_sem_filtro_exibe_todas_as_provas_ativas(): void
    {
        $this->criarProvas();

        $response = $this->get('/provas');
        $response->assertStatus(200);
        $response->assertSee('SIS 2024 — 1ª Etapa');
        $response->assertSee('PSC 2024 — PSC 1');
    }
}
