<?php

namespace Tests\Feature\Admin;

use App\Models\Denuncia;
use App\Models\Instituicao;
use App\Models\Prova;
use App\Models\Questao;
use App\Models\RespostaComunidade;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Testa especificamente a rota de moderação de respostas,
 * garantindo que "respostas/listar" não seja capturada pelo parâmetro {denuncia}.
 */
class AdminModeracaoRotaTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin', 'ativo' => true]);
    }

    private function criarResposta(): RespostaComunidade
    {
        $inst    = Instituicao::factory()->create();
        $prova   = Prova::factory()->create(['instituicao_id' => $inst->id]);
        $questao = Questao::factory()->create(['prova_id' => $prova->id, 'numero' => 1]);
        $autor   = User::factory()->create(['ativo' => true]);
        return RespostaComunidade::factory()->create([
            'questao_id' => $questao->id,
            'user_id'    => $autor->id,
        ]);
    }

    public function test_rota_respostas_listar_retorna_200_nao_404(): void
    {
        // Este teste falhou ANTES da correção do bug de roteamento,
        // onde "respostas/listar" era capturada como {denuncia}=respostas.
        $this->actingAs($this->admin())
            ->get('/admin/moderacao/respostas/listar')
            ->assertStatus(200);
    }

    public function test_rota_respostas_listar_exibe_view_correta(): void
    {
        $resposta = $this->criarResposta();

        $this->actingAs($this->admin())
            ->get('/admin/moderacao/respostas/listar')
            ->assertStatus(200)
            ->assertViewIs('admin.moderacao.respostas');
    }

    public function test_rota_denuncia_show_ainda_funciona_apos_correcao(): void
    {
        $resposta = $this->criarResposta();
        $denuncia = Denuncia::create([
            'resposta_id' => $resposta->id,
            'user_id'     => User::factory()->create(['ativo' => true])->id,
            'motivo'      => 'spam',
            'status'      => 'pendente',
        ]);

        $this->actingAs($this->admin())
            ->get("/admin/moderacao/{$denuncia->id}")
            ->assertStatus(200)
            ->assertViewIs('admin.moderacao.show');
    }

    public function test_toggle_ocultar_resposta_funciona(): void
    {
        $resposta = $this->criarResposta();

        $this->actingAs($this->admin())
            ->post("/admin/moderacao/respostas/{$resposta->id}/toggle")
            ->assertRedirect();

        $this->assertDatabaseHas('respostas_comunidade', [
            'id'     => $resposta->id,
            'oculta' => true,
        ]);
    }

    public function test_filtro_por_oculta_funciona(): void
    {
        $resposta = $this->criarResposta();
        $resposta->update(['oculta' => true]);

        $this->actingAs($this->admin())
            ->get('/admin/moderacao/respostas/listar?oculta=1')
            ->assertStatus(200);
    }
}
