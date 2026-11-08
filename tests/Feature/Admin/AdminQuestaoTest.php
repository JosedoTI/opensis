<?php

namespace Tests\Feature\Admin;

use App\Models\Alternativa;
use App\Models\GabaritoOficial;
use App\Models\Instituicao;
use App\Models\Prova;
use App\Models\Questao;
use App\Models\ResolucaoOficial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminQuestaoTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin', 'ativo' => true]);
    }

    private function criarQuestaoComProva(): Questao
    {
        $inst  = Instituicao::factory()->create();
        $prova = Prova::factory()->create(['instituicao_id' => $inst->id]);
        return Questao::factory()->create(['prova_id' => $prova->id, 'numero' => 1]);
    }

    public function test_admin_lista_questoes(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin/questoes')
            ->assertStatus(200);
    }

    public function test_admin_acessa_form_criar_questao(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin/questoes/criar')
            ->assertStatus(200);
    }

    public function test_admin_cria_questao_com_alternativas(): void
    {
        $inst  = Instituicao::factory()->create();
        $prova = Prova::factory()->create(['instituicao_id' => $inst->id]);

        $this->actingAs($this->admin())
            ->post('/admin/questoes', [
                'prova_id'   => $prova->id,
                'numero'     => 1,
                'enunciado'  => 'Qual é a capital do Amazonas?',
                'status'     => 'completa',
                'alternativas' => [
                    ['letra' => 'A', 'texto' => 'Manaus'],
                    ['letra' => 'B', 'texto' => 'Belém'],
                    ['letra' => 'C', 'texto' => 'Macapá'],
                    ['letra' => 'D', 'texto' => 'Porto Velho'],
                    ['letra' => 'E', 'texto' => 'Boa Vista'],
                ],
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('questoes', ['numero' => 1, 'prova_id' => $prova->id]);
        $this->assertDatabaseHas('alternativas', ['letra' => 'A', 'texto' => 'Manaus']);
    }

    public function test_admin_edita_questao(): void
    {
        $questao = $this->criarQuestaoComProva();
        Alternativa::factory()->count(5)->sequence(
            ['letra' => 'A', 'texto' => 'Alt A'],
            ['letra' => 'B', 'texto' => 'Alt B'],
            ['letra' => 'C', 'texto' => 'Alt C'],
            ['letra' => 'D', 'texto' => 'Alt D'],
            ['letra' => 'E', 'texto' => 'Alt E'],
        )->create(['questao_id' => $questao->id]);

        $this->actingAs($this->admin())
            ->put("/admin/questoes/{$questao->id}", [
                'prova_id'   => $questao->prova_id,
                'numero'     => $questao->numero,
                'enunciado'  => 'Enunciado atualizado com mais de dez caracteres.',
                'status'     => 'completa',
                'alternativas' => [
                    ['letra' => 'A', 'texto' => 'Nova Alt A'],
                    ['letra' => 'B', 'texto' => 'Nova Alt B'],
                    ['letra' => 'C', 'texto' => 'Nova Alt C'],
                    ['letra' => 'D', 'texto' => 'Nova Alt D'],
                    ['letra' => 'E', 'texto' => 'Nova Alt E'],
                ],
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('questoes', [
            'id'       => $questao->id,
            'enunciado' => 'Enunciado atualizado com mais de dez caracteres.',
        ]);
    }

    public function test_admin_remove_questao_soft_delete(): void
    {
        $questao = $this->criarQuestaoComProva();

        $this->actingAs($this->admin())
            ->delete("/admin/questoes/{$questao->id}")
            ->assertRedirect();

        $this->assertSoftDeleted('questoes', ['id' => $questao->id]);
    }

    public function test_admin_cadastra_gabarito_oficial(): void
    {
        $questao = $this->criarQuestaoComProva();
        Alternativa::create(['questao_id' => $questao->id, 'letra' => 'A', 'texto' => 'Alternativa A']);

        $this->actingAs($this->admin())
            ->post("/admin/questoes/{$questao->id}/gabarito", [
                'resposta_correta' => 'A',
                'fonte_descricao'  => 'Gabarito oficial UEA 2024',
                'fonte_url'        => 'https://www.uea.edu.br/gabarito',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('gabaritos_oficiais', [
            'questao_id'       => $questao->id,
            'resposta_correta' => 'A',
        ]);
    }

    public function test_admin_cadastra_resolucao_oficial(): void
    {
        $questao = $this->criarQuestaoComProva();

        $this->actingAs($this->admin())
            ->post("/admin/questoes/{$questao->id}/resolucao", [
                'conteudo'        => 'Esta é a resolução oficial com mais de vinte caracteres para passar na validação.',
                'fonte_descricao' => 'Resolução oficial UEA',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('resolucoes_oficiais', [
            'questao_id' => $questao->id,
        ]);
    }

    public function test_validacao_rejeita_questao_sem_alternativas(): void
    {
        $inst  = Instituicao::factory()->create();
        $prova = Prova::factory()->create(['instituicao_id' => $inst->id]);

        $this->actingAs($this->admin())
            ->post('/admin/questoes', [
                'prova_id'  => $prova->id,
                'numero'    => 1,
                'enunciado' => 'Enunciado sem alternativas.',
                'status'    => 'completa',
            ])
            ->assertSessionHasErrors('alternativas');
    }
}
