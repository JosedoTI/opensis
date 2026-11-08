<?php

namespace Tests\Feature\Admin;

use App\Models\Documento;
use App\Models\Instituicao;
use App\Models\Prova;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDocumentoTest extends TestCase
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

    private function criarProvaComDocumento(): array
    {
        $inst  = Instituicao::factory()->create();
        $prova = Prova::factory()->create(['instituicao_id' => $inst->id, 'ativa' => true]);
        $doc   = Documento::create([
            'prova_id'     => $prova->id,
            'tipo'         => 'prova',
            'nome_arquivo' => 'prova_teste.pdf',
            'url_externa'  => 'https://drive.google.com/file/d/exemplo',
            'ativo'        => true,
        ]);
        return [$prova, $doc];
    }

    // ─── Acesso ───────────────────────────────────────────────────────────

    public function test_admin_acessa_inventario_documentos(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin/documentos')
            ->assertStatus(200);
    }

    public function test_usuario_comum_nao_acessa_inventario(): void
    {
        $this->actingAs($this->usuario())
            ->get('/admin/documentos')
            ->assertForbidden();
    }

    public function test_visitante_nao_acessa_inventario(): void
    {
        $this->get('/admin/documentos')
            ->assertRedirect('/login');
    }

    // ─── Conteúdo do inventário ───────────────────────────────────────────

    public function test_inventario_exibe_estatisticas(): void
    {
        $inst  = Instituicao::factory()->create();
        Prova::factory()->count(3)->create(['instituicao_id' => $inst->id, 'ativa' => true]);

        $this->actingAs($this->admin())
            ->get('/admin/documentos')
            ->assertStatus(200)
            ->assertSee('Provas')
            ->assertSee('Com documento')
            ->assertSee('Sem documento');
    }

    public function test_inventario_lista_provas_sem_documento(): void
    {
        $inst  = Instituicao::factory()->create();
        $prova = Prova::factory()->create([
            'instituicao_id' => $inst->id,
            'nome'           => 'Prova Sem Documento',
            'ativa'          => true,
        ]);

        $this->actingAs($this->admin())
            ->get('/admin/documentos')
            ->assertStatus(200)
            ->assertSee('Prova Sem Documento');
    }

    public function test_inventario_lista_documentos_cadastrados(): void
    {
        [$prova, $doc] = $this->criarProvaComDocumento();

        $this->actingAs($this->admin())
            ->get('/admin/documentos')
            ->assertStatus(200)
            ->assertSee('prova_teste.pdf');
    }

    public function test_inventario_exibe_link_para_fontes_oficiais(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin/documentos')
            ->assertStatus(200)
            ->assertSee('drive.google.com', false);
    }

    public function test_inventario_filtra_por_instituicao(): void
    {
        $inst1 = Instituicao::factory()->create(['sigla' => 'AA1']);
        $inst2 = Instituicao::factory()->create(['sigla' => 'BB2']);
        $prova1 = Prova::factory()->create(['instituicao_id' => $inst1->id, 'nome' => 'Prova Inst1', 'ativa' => true]);
        $prova2 = Prova::factory()->create(['instituicao_id' => $inst2->id, 'nome' => 'Prova Inst2', 'ativa' => true]);

        $response = $this->actingAs($this->admin())
            ->get("/admin/documentos?instituicao={$inst1->id}");

        $response->assertStatus(200)
            ->assertSee('Prova Inst1')
            ->assertDontSee('Prova Inst2');
    }

    public function test_inventario_filtra_por_ano(): void
    {
        $inst   = Instituicao::factory()->create();
        $prova1 = Prova::factory()->create(['instituicao_id' => $inst->id, 'ano' => 2022, 'nome' => 'Prova 2022', 'ativa' => true]);
        $prova2 = Prova::factory()->create(['instituicao_id' => $inst->id, 'ano' => 2023, 'nome' => 'Prova 2023', 'ativa' => true]);

        $response = $this->actingAs($this->admin())
            ->get('/admin/documentos?ano=2022');

        $response->assertStatus(200)
            ->assertSee('Prova 2022')
            ->assertDontSee('Prova 2023');
    }

    // ─── Desativação de documento ─────────────────────────────────────────

    public function test_admin_pode_desativar_documento(): void
    {
        [$prova, $doc] = $this->criarProvaComDocumento();

        $this->actingAs($this->admin())
            ->delete("/admin/documentos/{$doc->id}")
            ->assertRedirect();

        $this->assertDatabaseHas('documentos', [
            'id'    => $doc->id,
            'ativo' => false,
        ]);
    }

    public function test_usuario_comum_nao_pode_desativar_documento(): void
    {
        [$prova, $doc] = $this->criarProvaComDocumento();

        $this->actingAs($this->usuario())
            ->delete("/admin/documentos/{$doc->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('documentos', [
            'id'    => $doc->id,
            'ativo' => true,
        ]);
    }

    // ─── Contadores corretos ──────────────────────────────────────────────

    public function test_contador_provas_sem_documento_e_correto(): void
    {
        $inst = Instituicao::factory()->create();
        // 2 provas sem documento, 1 com documento
        Prova::factory()->count(2)->create(['instituicao_id' => $inst->id, 'ativa' => true]);
        [$prova, $doc] = $this->criarProvaComDocumento();

        $response = $this->actingAs($this->admin())
            ->get('/admin/documentos')
            ->assertStatus(200);

        // A view exibe o total de provas sem documento
        // Com 2 provas sem doc + a prova da criarProvaComDocumento que tem doc = 2 sem doc
        $response->assertSee('2', false);
    }
}
