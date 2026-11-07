<?php

namespace Tests\Feature\Api;

use App\Models\Instituicao;
use App\Models\Prova;
use App\Models\Questao;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiProvaTest extends TestCase
{
    use RefreshDatabase;

    private function criarInstituicaoComProvas(int $qtd = 3): Instituicao
    {
        $inst = Instituicao::factory()->create();
        Prova::factory()->count($qtd)->create(['instituicao_id' => $inst->id]);
        return $inst;
    }

    public function test_api_instituicoes_retorna_lista(): void
    {
        Instituicao::factory()->count(2)->create();

        $this->getJson('/api/v1/instituicoes')
            ->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(2, 'data');
    }

    public function test_api_instituicao_individual(): void
    {
        $inst = Instituicao::factory()->create();

        $this->getJson("/api/v1/instituicoes/{$inst->id}")
            ->assertStatus(200)
            ->assertJsonPath('data.sigla', $inst->sigla);
    }

    public function test_api_provas_retorna_paginado(): void
    {
        $this->criarInstituicaoComProvas(5);

        $this->getJson('/api/v1/provas')
            ->assertStatus(200)
            ->assertJsonStructure(['data', 'current_page', 'total']);
    }

    public function test_api_provas_filtra_por_instituicao(): void
    {
        $inst1 = $this->criarInstituicaoComProvas(3);
        $inst2 = $this->criarInstituicaoComProvas(2);

        $response = $this->getJson("/api/v1/provas?instituicao_id={$inst1->id}");
        $response->assertStatus(200);

        foreach ($response->json('data') as $prova) {
            $this->assertEquals($inst1->id, $prova['instituicao_id']);
        }
    }

    public function test_api_questoes_retorna_lista_publica(): void
    {
        $inst  = Instituicao::factory()->create();
        $prova = Prova::factory()->create(['instituicao_id' => $inst->id]);
        Questao::factory()->count(3)->create(['prova_id' => $prova->id]);

        $this->getJson('/api/v1/questoes')
            ->assertStatus(200)
            ->assertJsonStructure(['data', 'total']);
    }

    public function test_api_gabarito_retorna_indisponivel(): void
    {
        $inst    = Instituicao::factory()->create();
        $prova   = Prova::factory()->create(['instituicao_id' => $inst->id]);
        $questao = Questao::factory()->create(['prova_id' => $prova->id, 'numero' => 1]);

        $this->getJson("/api/v1/questoes/{$questao->id}/gabarito")
            ->assertStatus(200)
            ->assertJson(['disponivel' => false]);
    }

    public function test_api_respostas_requer_autenticacao_para_postar(): void
    {
        $inst    = Instituicao::factory()->create();
        $prova   = Prova::factory()->create(['instituicao_id' => $inst->id]);
        $questao = Questao::factory()->create(['prova_id' => $prova->id, 'numero' => 1]);

        $this->postJson("/api/v1/questoes/{$questao->id}/respostas", [
            'conteudo' => 'Esta é uma resposta de teste suficientemente longa para validação.',
        ])->assertStatus(401);
    }

    public function test_api_usuario_autenticado_pode_postar_resposta(): void
    {
        $inst    = Instituicao::factory()->create();
        $prova   = Prova::factory()->create(['instituicao_id' => $inst->id]);
        $questao = Questao::factory()->create(['prova_id' => $prova->id, 'numero' => 1]);
        $user    = User::factory()->create(['ativo' => true]);

        $this->actingAs($user)
            ->postJson("/api/v1/questoes/{$questao->id}/respostas", [
                'conteudo' => 'Esta é uma resposta de teste suficientemente longa para passar na validação mínima.',
            ])
            ->assertStatus(201)
            ->assertJsonStructure(['message', 'data']);
    }

    public function test_api_usuario_pode_votar_em_resposta(): void
    {
        $autor   = User::factory()->create(['ativo' => true]);
        $votante = User::factory()->create(['ativo' => true]);
        $inst    = Instituicao::factory()->create();
        $prova   = Prova::factory()->create(['instituicao_id' => $inst->id]);
        $questao = Questao::factory()->create(['prova_id' => $prova->id, 'numero' => 1]);
        $resposta = \App\Models\RespostaComunidade::factory()->create([
            'user_id'    => $autor->id,
            'questao_id' => $questao->id,
        ]);

        $this->actingAs($votante)
            ->postJson("/api/v1/respostas/{$resposta->id}/votar")
            ->assertStatus(200)
            ->assertJson(['acao' => 'adicionado']);

        $this->assertDatabaseHas('respostas_comunidade', [
            'id'         => $resposta->id,
            'votos_util' => 1,
        ]);
    }

    public function test_api_usuario_nao_pode_votar_na_propria_resposta(): void
    {
        $user    = User::factory()->create(['ativo' => true]);
        $inst    = Instituicao::factory()->create();
        $prova   = Prova::factory()->create(['instituicao_id' => $inst->id]);
        $questao = Questao::factory()->create(['prova_id' => $prova->id, 'numero' => 1]);
        $resposta = \App\Models\RespostaComunidade::factory()->create([
            'user_id'    => $user->id,
            'questao_id' => $questao->id,
        ]);

        $this->actingAs($user)
            ->postJson("/api/v1/respostas/{$resposta->id}/votar")
            ->assertStatus(422);
    }
}
