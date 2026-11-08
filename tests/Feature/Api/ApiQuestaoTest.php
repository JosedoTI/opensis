<?php

namespace Tests\Feature\Api;

use App\Models\Alternativa;
use App\Models\GabaritoOficial;
use App\Models\Instituicao;
use App\Models\Prova;
use App\Models\Questao;
use App\Models\ResolucaoOficial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiQuestaoTest extends TestCase
{
    use RefreshDatabase;

    private function criarQuestaoCompleta(): Questao
    {
        $inst    = Instituicao::factory()->create();
        $prova   = Prova::factory()->create(['instituicao_id' => $inst->id, 'ativa' => true]);
        $questao = Questao::factory()->create([
            'prova_id' => $prova->id,
            'numero'   => 1,
            'ativa'    => true,
            'status'   => 'completa',
        ]);

        foreach (['A', 'B', 'C', 'D', 'E'] as $letra) {
            Alternativa::create([
                'questao_id' => $questao->id,
                'letra'      => $letra,
                'texto'      => "Alternativa $letra",
                'ativa'      => true,
            ]);
        }

        return $questao;
    }

    // ─── Listagem ─────────────────────────────────────────────────────────

    public function test_api_questoes_retorna_somente_completas_e_ativas(): void
    {
        $inst  = Instituicao::factory()->create();
        $prova = Prova::factory()->create(['instituicao_id' => $inst->id, 'ativa' => true]);
        Questao::factory()->create(['prova_id' => $prova->id, 'numero' => 1, 'ativa' => true, 'status' => 'completa']);
        Questao::factory()->create(['prova_id' => $prova->id, 'numero' => 2, 'ativa' => true, 'status' => 'incompleta']);
        Questao::factory()->create(['prova_id' => $prova->id, 'numero' => 3, 'ativa' => false, 'status' => 'completa']);

        $response = $this->getJson('/api/v1/questoes');
        $response->assertStatus(200);
        $this->assertEquals(1, $response->json('total'));
    }

    public function test_api_questoes_filtra_por_prova(): void
    {
        $inst   = Instituicao::factory()->create();
        $prova1 = Prova::factory()->create(['instituicao_id' => $inst->id, 'ativa' => true]);
        $prova2 = Prova::factory()->create(['instituicao_id' => $inst->id, 'ativa' => true]);
        Questao::factory()->create(['prova_id' => $prova1->id, 'numero' => 1, 'ativa' => true, 'status' => 'completa']);
        Questao::factory()->create(['prova_id' => $prova2->id, 'numero' => 2, 'ativa' => true, 'status' => 'completa']);

        $this->getJson("/api/v1/questoes?prova_id={$prova1->id}")
            ->assertStatus(200)
            ->assertJsonPath('total', 1);
    }

    // ─── Detalhes ─────────────────────────────────────────────────────────

    public function test_api_questao_individual_retorna_dados(): void
    {
        $questao = $this->criarQuestaoCompleta();

        $this->getJson("/api/v1/questoes/{$questao->id}")
            ->assertStatus(200)
            ->assertJsonPath('data.id', $questao->id)
            ->assertJsonPath('data.numero', $questao->numero);
    }

    public function test_api_questao_inativa_retorna_404(): void
    {
        $inst    = Instituicao::factory()->create();
        $prova   = Prova::factory()->create(['instituicao_id' => $inst->id]);
        $questao = Questao::factory()->create(['prova_id' => $prova->id, 'numero' => 1, 'ativa' => false]);

        $this->getJson("/api/v1/questoes/{$questao->id}")
            ->assertStatus(404);
    }

    // ─── Alternativas ─────────────────────────────────────────────────────

    public function test_api_alternativas_retorna_lista(): void
    {
        $questao = $this->criarQuestaoCompleta();

        $this->getJson("/api/v1/questoes/{$questao->id}/alternativas")
            ->assertStatus(200)
            ->assertJsonPath('data.0.letra', 'A')
            ->assertJsonCount(5, 'data');
    }

    // ─── Gabarito ─────────────────────────────────────────────────────────

    public function test_api_gabarito_retorna_resposta_quando_disponivel(): void
    {
        $questao = $this->criarQuestaoCompleta();
        GabaritoOficial::create([
            'questao_id'       => $questao->id,
            'resposta_correta' => 'C',
            'fonte_descricao'  => 'Gabarito oficial UEA 2024',
        ]);

        $this->getJson("/api/v1/questoes/{$questao->id}/gabarito")
            ->assertStatus(200)
            ->assertJson([
                'disponivel'       => true,
                'resposta_correta' => 'C',
            ]);
    }

    public function test_api_gabarito_retorna_indisponivel_quando_ausente(): void
    {
        $questao = $this->criarQuestaoCompleta();

        $this->getJson("/api/v1/questoes/{$questao->id}/gabarito")
            ->assertStatus(200)
            ->assertJson(['disponivel' => false]);
    }

    // ─── Resolução ────────────────────────────────────────────────────────

    public function test_api_resolucao_retorna_conteudo_quando_disponivel(): void
    {
        $questao = $this->criarQuestaoCompleta();
        ResolucaoOficial::create([
            'questao_id'      => $questao->id,
            'conteudo'        => 'Esta é a resolução oficial completa e detalhada.',
            'fonte_descricao' => 'Resolução UEA',
            'ativa'           => true,
        ]);

        $this->getJson("/api/v1/questoes/{$questao->id}/resolucao")
            ->assertStatus(200)
            ->assertJson(['disponivel' => true])
            ->assertJsonPath('conteudo', 'Esta é a resolução oficial completa e detalhada.');
    }

    public function test_api_resolucao_retorna_indisponivel_quando_ausente(): void
    {
        $questao = $this->criarQuestaoCompleta();

        $this->getJson("/api/v1/questoes/{$questao->id}/resolucao")
            ->assertStatus(200)
            ->assertJson(['disponivel' => false]);
    }

    // ─── Rate limiting ────────────────────────────────────────────────────

    public function test_api_responde_com_200_em_acesso_normal(): void
    {
        // Verifica que a rota está funcional mesmo com throttle aplicado
        $this->getJson('/api/v1/questoes')
            ->assertStatus(200);
    }
}
