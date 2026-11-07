<?php

namespace Tests\Feature\Admin;

use App\Models\Instituicao;
use App\Models\Prova;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProvaTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin', 'ativo' => true]);
    }

    private function instituicao(): Instituicao
    {
        return Instituicao::factory()->create();
    }

    public function test_admin_lista_provas(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin/provas')
            ->assertStatus(200);
    }

    public function test_admin_acessa_form_criar_prova(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin/provas/criar')
            ->assertStatus(200);
    }

    public function test_admin_cria_prova(): void
    {
        $inst = $this->instituicao();

        $this->actingAs($this->admin())
            ->post('/admin/provas', [
                'instituicao_id'            => $inst->id,
                'ano'                       => 2025,
                'etapa'                     => '1ª Etapa',
                'nome'                      => 'SIS 2025 — 1ª Etapa',
                'tipo'                      => 'objetiva',
                'total_questoes'            => 40,
                'questoes_individualizadas' => '1',
                'gabarito_disponivel'       => '0',
                'resolucao_disponivel'      => '0',
                'ativa'                     => '1',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('provas', [
            'nome' => 'SIS 2025 — 1ª Etapa',
            'ano'  => 2025,
        ]);
    }

    public function test_admin_edita_prova(): void
    {
        $inst  = $this->instituicao();
        $prova = Prova::factory()->create([
            'nome'           => 'Prova Original',
            'instituicao_id' => $inst->id,
            'ano'            => 2022,
            'etapa'          => '2ª Etapa',
        ]);

        $this->actingAs($this->admin())
            ->put("/admin/provas/{$prova->id}", [
                'instituicao_id' => $prova->instituicao_id,
                'ano'            => $prova->ano,
                'etapa'          => $prova->etapa,
                'nome'           => 'Prova Atualizada',
                'tipo'           => 'objetiva',
                'ativa'          => '1',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('provas', ['nome' => 'Prova Atualizada']);
    }

    public function test_admin_remove_prova_soft_delete(): void
    {
        $inst  = $this->instituicao();
        $prova = Prova::factory()->create(['instituicao_id' => $inst->id]);

        $this->actingAs($this->admin())
            ->delete("/admin/provas/{$prova->id}")
            ->assertRedirect();

        $this->assertSoftDeleted('provas', ['id' => $prova->id]);
    }

    public function test_nao_admin_nao_pode_criar_prova(): void
    {
        $user = User::factory()->create(['role' => 'usuario']);

        $this->actingAs($user)
            ->post('/admin/provas', [])
            ->assertForbidden();
    }

    public function test_validacao_rejeita_ano_invalido(): void
    {
        $inst = $this->instituicao();

        $this->actingAs($this->admin())
            ->post('/admin/provas', [
                'instituicao_id' => $inst->id,
                'ano'            => 1999,  // menor que 2016
                'etapa'          => '1ª Etapa',
                'nome'           => 'Prova Inválida',
                'tipo'           => 'objetiva',
            ])
            ->assertSessionHasErrors('ano');
    }
}
