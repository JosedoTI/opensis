<?php

namespace Tests\Feature;

use App\Models\Instituicao;
use App\Models\Prova;
use App\Models\Questao;
use App\Models\RespostaComunidade;
use App\Models\User;
use Database\Seeders\InstituicaoSeeder;
use Database\Seeders\ProvaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissoesTest extends TestCase
{
    use RefreshDatabase;

    private function criarQuestaoComProva(): Questao
    {
        $inst = Instituicao::factory()->create();
        $prova = Prova::factory()->create(['instituicao_id' => $inst->id]);
        return Questao::factory()->create(['prova_id' => $prova->id, 'numero' => 1]);
    }

    public function test_visitante_nao_acessa_painel_admin(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_usuario_comum_nao_acessa_painel_admin(): void
    {
        $user = User::factory()->create(['role' => 'usuario']);
        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_admin_acessa_painel(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->get('/admin')->assertStatus(200);
    }

    public function test_visitante_nao_pode_publicar_resposta(): void
    {
        $questao = $this->criarQuestaoComProva();

        $this->post("/questoes/{$questao->id}/respostas", [
            'conteudo' => 'Esta é minha explicação detalhada sobre a questão.',
        ])->assertRedirect('/login');
    }

    public function test_usuario_nao_pode_editar_resposta_de_outro(): void
    {
        $autor  = User::factory()->create(['role' => 'usuario', 'ativo' => true]);
        $outro  = User::factory()->create(['role' => 'usuario', 'ativo' => true]);
        $questao = $this->criarQuestaoComProva();

        $resposta = RespostaComunidade::factory()->create([
            'user_id'    => $autor->id,
            'questao_id' => $questao->id,
        ]);

        $this->actingAs($outro)
            ->put("/respostas/{$resposta->id}", [
                'conteudo' => 'tentativa de edição não autorizada com mais de 20 chars',
            ])
            ->assertForbidden();
    }

    public function test_usuario_pode_editar_propria_resposta(): void
    {
        $user    = User::factory()->create(['role' => 'usuario', 'ativo' => true]);
        $questao = $this->criarQuestaoComProva();

        $resposta = RespostaComunidade::factory()->create([
            'user_id'    => $user->id,
            'questao_id' => $questao->id,
        ]);

        $this->actingAs($user)
            ->put("/respostas/{$resposta->id}", [
                'conteudo' => 'Minha explicação atualizada com mais de vinte caracteres.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('respostas_comunidade', [
            'id'      => $resposta->id,
            'conteudo' => 'Minha explicação atualizada com mais de vinte caracteres.',
        ]);
    }

    public function test_usuario_pode_excluir_propria_resposta(): void
    {
        $user    = User::factory()->create(['role' => 'usuario', 'ativo' => true]);
        $questao = $this->criarQuestaoComProva();

        $resposta = RespostaComunidade::factory()->create([
            'user_id'    => $user->id,
            'questao_id' => $questao->id,
        ]);

        $this->actingAs($user)
            ->delete("/respostas/{$resposta->id}")
            ->assertRedirect();

        $this->assertSoftDeleted('respostas_comunidade', ['id' => $resposta->id]);
    }

    public function test_usuario_nao_pode_excluir_resposta_de_outro(): void
    {
        $autor  = User::factory()->create(['role' => 'usuario', 'ativo' => true]);
        $outro  = User::factory()->create(['role' => 'usuario', 'ativo' => true]);
        $questao = $this->criarQuestaoComProva();

        $resposta = RespostaComunidade::factory()->create([
            'user_id'    => $autor->id,
            'questao_id' => $questao->id,
        ]);

        $this->actingAs($outro)
            ->delete("/respostas/{$resposta->id}")
            ->assertForbidden();
    }
}
