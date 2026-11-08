<?php

namespace Tests\Feature;

use App\Models\Denuncia;
use App\Models\Instituicao;
use App\Models\Prova;
use App\Models\Questao;
use App\Models\RespostaComunidade;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DenunciasModeracaoTest extends TestCase
{
    use RefreshDatabase;

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

    // ─── Denúncias ────────────────────────────────────────────────────────

    public function test_visitante_nao_pode_denunciar(): void
    {
        $resposta = $this->criarResposta();

        $this->post("/respostas/{$resposta->id}/denunciar", [
            'motivo' => 'incorreta',
        ])->assertRedirect('/login');
    }

    public function test_usuario_pode_denunciar_resposta_de_outro(): void
    {
        $resposta    = $this->criarResposta();
        $denunciante = User::factory()->create(['ativo' => true]);

        $this->actingAs($denunciante)
            ->post("/respostas/{$resposta->id}/denunciar", [
                'motivo'    => 'incorreta',
                'descricao' => 'Esta resposta está incorreta.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('denuncias', [
            'resposta_id' => $resposta->id,
            'user_id'     => $denunciante->id,
            'motivo'      => 'incorreta',
        ]);
    }

    public function test_usuario_nao_pode_denunciar_duas_vezes_mesma_resposta(): void
    {
        $resposta    = $this->criarResposta();
        $denunciante = User::factory()->create(['ativo' => true]);

        Denuncia::create([
            'resposta_id' => $resposta->id,
            'user_id'     => $denunciante->id,
            'motivo'      => 'spam',
        ]);

        $this->actingAs($denunciante)
            ->post("/respostas/{$resposta->id}/denunciar", [
                'motivo' => 'spam',
            ])
            ->assertRedirect();

        // Deve ter apenas 1 denúncia
        $this->assertDatabaseCount('denuncias', 1);
    }

    // ─── Moderação admin ──────────────────────────────────────────────────

    public function test_admin_acessa_painel_moderacao(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'ativo' => true]);

        $this->actingAs($admin)
            ->get('/admin/moderacao')
            ->assertStatus(200);
    }

    public function test_admin_pode_ocultar_resposta_via_moderacao(): void
    {
        $admin    = User::factory()->create(['role' => 'admin', 'ativo' => true]);
        $resposta = $this->criarResposta();

        $denuncia = Denuncia::create([
            'resposta_id' => $resposta->id,
            'user_id'     => User::factory()->create(['ativo' => true])->id,
            'motivo'      => 'ofensiva',
            'status'      => 'pendente',
        ]);

        $this->actingAs($admin)
            ->post("/admin/moderacao/{$denuncia->id}/analisar", [
                'acao'          => 'ocultar',
                'parecer_admin' => 'Conteúdo ofensivo confirmado.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('respostas_comunidade', [
            'id'     => $resposta->id,
            'oculta' => true,
        ]);

        $this->assertDatabaseHas('denuncias', [
            'id'     => $denuncia->id,
            'status' => 'analisada',
        ]);
    }

    public function test_admin_pode_toggle_ocultar_resposta(): void
    {
        $admin    = User::factory()->create(['role' => 'admin', 'ativo' => true]);
        $resposta = $this->criarResposta();

        // Ocultar
        $this->actingAs($admin)
            ->post("/admin/moderacao/respostas/{$resposta->id}/toggle")
            ->assertRedirect();

        $this->assertDatabaseHas('respostas_comunidade', [
            'id'     => $resposta->id,
            'oculta' => true,
        ]);

        // Tornar visível novamente
        $this->actingAs($admin)
            ->post("/admin/moderacao/respostas/{$resposta->id}/toggle")
            ->assertRedirect();

        $this->assertDatabaseHas('respostas_comunidade', [
            'id'     => $resposta->id,
            'oculta' => false,
        ]);
    }

    public function test_usuario_comum_nao_acessa_moderacao(): void
    {
        $user = User::factory()->create(['role' => 'usuario', 'ativo' => true]);

        $this->actingAs($user)
            ->get('/admin/moderacao')
            ->assertForbidden();
    }
}
