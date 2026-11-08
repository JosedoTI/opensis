<?php

namespace Tests\Feature\Admin;

use App\Models\Instituicao;
use App\Models\Prova;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminInstituicaoTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin', 'ativo' => true]);
    }

    private function usuario(): User
    {
        return User::factory()->create(['role' => 'usuario', 'ativo' => true]);
    }

    // ─── Listagem ─────────────────────────────────────────────────────────

    public function test_admin_lista_instituicoes(): void
    {
        $inst = Instituicao::factory()->create(['sigla' => 'TST']);

        $this->actingAs($this->admin())
            ->get('/admin/instituicoes')
            ->assertStatus(200)
            ->assertSee('TST');
    }

    // ─── Criação ──────────────────────────────────────────────────────────

    public function test_admin_acessa_form_criar_instituicao(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin/instituicoes/criar')
            ->assertStatus(200);
    }

    public function test_admin_cria_instituicao(): void
    {
        $this->actingAs($this->admin())
            ->post('/admin/instituicoes', [
                'sigla'         => 'NOVA',
                'nome'          => 'Universidade Nova',
                'nome_processo' => 'Vestibular',
                'cor_primaria'  => '#123456',
            ])
            ->assertRedirect('/admin/instituicoes');

        $this->assertDatabaseHas('instituicoes', ['sigla' => 'NOVA']);
    }

    public function test_validacao_rejeita_sigla_duplicada(): void
    {
        Instituicao::factory()->create(['sigla' => 'DUP']);

        $this->actingAs($this->admin())
            ->post('/admin/instituicoes', [
                'sigla'         => 'DUP',
                'nome'          => 'Outra Universidade',
                'nome_processo' => 'Vestibular',
            ])
            ->assertSessionHasErrors('sigla');
    }

    // ─── Edição ───────────────────────────────────────────────────────────

    public function test_admin_edita_instituicao(): void
    {
        $inst = Instituicao::factory()->create(['sigla' => 'EDT']);

        $this->actingAs($this->admin())
            ->put("/admin/instituicoes/{$inst->id}", [
                'sigla'         => 'EDT',
                'nome'          => 'Nome Atualizado',
                'nome_processo' => 'Processo Atualizado',
                'ativa'         => '1',
            ])
            ->assertRedirect('/admin/instituicoes');

        $this->assertDatabaseHas('instituicoes', [
            'id'   => $inst->id,
            'nome' => 'Nome Atualizado',
        ]);
    }

    // ─── Exclusão ─────────────────────────────────────────────────────────

    public function test_admin_exclui_instituicao_sem_provas(): void
    {
        $inst = Instituicao::factory()->create(['sigla' => 'DEL']);

        $this->actingAs($this->admin())
            ->delete("/admin/instituicoes/{$inst->id}")
            ->assertRedirect('/admin/instituicoes');

        $this->assertDatabaseMissing('instituicoes', ['id' => $inst->id]);
    }

    public function test_admin_nao_pode_excluir_instituicao_com_provas_ativas(): void
    {
        $inst  = Instituicao::factory()->create();
        Prova::factory()->create(['instituicao_id' => $inst->id]);

        $this->actingAs($this->admin())
            ->delete("/admin/instituicoes/{$inst->id}")
            ->assertRedirect();

        // Instituição deve continuar existindo
        $this->assertDatabaseHas('instituicoes', ['id' => $inst->id]);
    }

    public function test_admin_nao_pode_excluir_instituicao_com_provas_soft_deleted(): void
    {
        $inst  = Instituicao::factory()->create();
        $prova = Prova::factory()->create(['instituicao_id' => $inst->id]);
        // Soft-delete a prova
        $prova->delete();

        $this->actingAs($this->admin())
            ->delete("/admin/instituicoes/{$inst->id}")
            ->assertRedirect();

        // Instituição deve continuar existindo mesmo com prova na lixeira
        $this->assertDatabaseHas('instituicoes', ['id' => $inst->id]);
    }

    public function test_mensagem_de_erro_ao_tentar_excluir_com_provas(): void
    {
        $inst = Instituicao::factory()->create(['sigla' => 'BLQ']);
        Prova::factory()->create(['instituicao_id' => $inst->id]);

        $this->actingAs($this->admin())
            ->delete("/admin/instituicoes/{$inst->id}")
            ->assertSessionHas('erro');
    }

    public function test_usuario_comum_nao_pode_excluir_instituicao(): void
    {
        $inst = Instituicao::factory()->create();

        $this->actingAs($this->usuario())
            ->delete("/admin/instituicoes/{$inst->id}")
            ->assertForbidden();
    }

    public function test_visitante_nao_pode_excluir_instituicao(): void
    {
        $inst = Instituicao::factory()->create();

        $this->delete("/admin/instituicoes/{$inst->id}")
            ->assertRedirect('/login');
    }

    public function test_instituicao_permanece_integra_apos_bloqueio(): void
    {
        $inst  = Instituicao::factory()->create(['nome' => 'Universidade Protegida']);
        Prova::factory()->count(3)->create(['instituicao_id' => $inst->id]);

        $this->actingAs($this->admin())
            ->delete("/admin/instituicoes/{$inst->id}");

        // Todos os dados devem estar intactos
        $this->assertDatabaseHas('instituicoes', ['id' => $inst->id, 'nome' => 'Universidade Protegida']);
        $this->assertEquals(3, Prova::where('instituicao_id', $inst->id)->count());
    }
}
