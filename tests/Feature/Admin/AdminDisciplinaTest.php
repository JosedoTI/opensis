<?php

namespace Tests\Feature\Admin;

use App\Models\Disciplina;
use App\Models\Assunto;
use App\Models\Instituicao;
use App\Models\Prova;
use App\Models\Questao;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDisciplinaTest extends TestCase
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

    // ─── Acesso ───────────────────────────────────────────────────────────

    public function test_admin_lista_disciplinas(): void
    {
        Disciplina::create(['nome' => 'Matemática', 'slug' => 'matematica', 'ativa' => true]);

        $this->actingAs($this->admin())
            ->get('/admin/disciplinas')
            ->assertStatus(200)
            ->assertSee('Matemática');
    }

    public function test_usuario_comum_nao_acessa_disciplinas(): void
    {
        $this->actingAs($this->usuario())
            ->get('/admin/disciplinas')
            ->assertForbidden();
    }

    // ─── Criação ──────────────────────────────────────────────────────────

    public function test_admin_cria_disciplina(): void
    {
        $this->actingAs($this->admin())
            ->post('/admin/disciplinas', [
                'nome' => 'Química',
                'cor'  => '#ff6600',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('disciplinas', ['nome' => 'Química']);
    }

    public function test_validacao_rejeita_disciplina_com_nome_duplicado(): void
    {
        Disciplina::create(['nome' => 'Física', 'slug' => 'fisica', 'ativa' => true]);

        $this->actingAs($this->admin())
            ->post('/admin/disciplinas', ['nome' => 'Física'])
            ->assertSessionHasErrors('nome');
    }

    // ─── Edição ───────────────────────────────────────────────────────────

    public function test_admin_edita_disciplina(): void
    {
        $disc = Disciplina::create(['nome' => 'Biologia Antiga', 'slug' => 'biologia-antiga', 'ativa' => true]);

        $this->actingAs($this->admin())
            ->put("/admin/disciplinas/{$disc->id}", [
                'nome'  => 'Biologia',
                'cor'   => '#00aa00',
                'ativa' => '1',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('disciplinas', ['id' => $disc->id, 'nome' => 'Biologia']);
    }

    // ─── Exclusão ─────────────────────────────────────────────────────────

    public function test_admin_exclui_disciplina_sem_questoes(): void
    {
        $disc = Disciplina::create(['nome' => 'Para Excluir', 'slug' => 'para-excluir', 'ativa' => true]);

        $this->actingAs($this->admin())
            ->delete("/admin/disciplinas/{$disc->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('disciplinas', ['id' => $disc->id]);
    }

    public function test_admin_nao_pode_excluir_disciplina_com_questoes(): void
    {
        $inst  = Instituicao::factory()->create();
        $prova = Prova::factory()->create(['instituicao_id' => $inst->id]);
        $disc  = Disciplina::create(['nome' => 'Protegida', 'slug' => 'protegida', 'ativa' => true]);
        Questao::factory()->create(['prova_id' => $prova->id, 'numero' => 1, 'disciplina_id' => $disc->id]);

        $this->actingAs($this->admin())
            ->delete("/admin/disciplinas/{$disc->id}")
            ->assertRedirect()
            ->assertSessionHas('erro');

        $this->assertDatabaseHas('disciplinas', ['id' => $disc->id]);
    }

    // ─── Assuntos ─────────────────────────────────────────────────────────

    public function test_admin_cria_assunto(): void
    {
        $disc = Disciplina::create(['nome' => 'Matemática', 'slug' => 'matematica', 'ativa' => true]);

        $this->actingAs($this->admin())
            ->post("/admin/disciplinas/{$disc->id}/assuntos", [
                'nome' => 'Álgebra',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('assuntos', [
            'disciplina_id' => $disc->id,
            'nome' => 'Álgebra',
        ]);
    }

    public function test_admin_exclui_assunto_sem_questoes(): void
    {
        $disc    = Disciplina::create(['nome' => 'Matemática', 'slug' => 'matematica', 'ativa' => true]);
        $assunto = Assunto::create(['disciplina_id' => $disc->id, 'nome' => 'Assunto Vazio', 'slug' => 'assunto-vazio', 'ativo' => true]);

        $this->actingAs($this->admin())
            ->delete("/admin/assuntos/{$assunto->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('assuntos', ['id' => $assunto->id]);
    }
}
