<?php

namespace Tests\Feature;

use App\Models\Alternativa;
use App\Models\GabaritoOficial;
use App\Models\Instituicao;
use App\Models\Prova;
use App\Models\Questao;
use App\Models\ResolucaoOficial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProvasQuestoesPublicoTest extends TestCase
{
    use RefreshDatabase;

    private function criarQuestaoCompleta(): Questao
    {
        $inst  = Instituicao::factory()->create();
        $prova = Prova::factory()->create(['instituicao_id' => $inst->id, 'ativa' => true]);
        $questao = Questao::factory()->create([
            'prova_id' => $prova->id,
            'numero'   => 1,
            'status'   => 'completa',
            'ativa'    => true,
        ]);
        foreach (['A', 'B', 'C', 'D', 'E'] as $letra) {
            Alternativa::create([
                'questao_id' => $questao->id,
                'letra'      => $letra,
                'texto'      => "Alternativa {$letra}",
            ]);
        }
        return $questao;
    }

    // ─── Provas ───────────────────────────────────────────────────────────

    public function test_listagem_provas_acessivel_sem_login(): void
    {
        $this->get('/provas')->assertStatus(200);
    }

    public function test_listagem_provas_exibe_provas_ativas(): void
    {
        $inst  = Instituicao::factory()->create();
        $prova = Prova::factory()->create(['instituicao_id' => $inst->id, 'ativa' => true]);

        $this->get('/provas')->assertSee($prova->nome);
    }

    public function test_listagem_provas_nao_exibe_provas_inativas(): void
    {
        $inst  = Instituicao::factory()->create();
        $prova = Prova::factory()->create(['instituicao_id' => $inst->id, 'ativa' => false]);

        $this->get('/provas')->assertDontSee($prova->nome);
    }

    public function test_detalhe_prova_acessivel(): void
    {
        $inst  = Instituicao::factory()->create();
        $prova = Prova::factory()->create(['instituicao_id' => $inst->id, 'ativa' => true]);

        $this->get("/provas/{$prova->id}")->assertStatus(200)->assertSee($prova->nome);
    }

    public function test_filtro_provas_por_instituicao(): void
    {
        $inst1 = Instituicao::factory()->create();
        $inst2 = Instituicao::factory()->create();
        $prova1 = Prova::factory()->create(['instituicao_id' => $inst1->id, 'ativa' => true]);
        $prova2 = Prova::factory()->create(['instituicao_id' => $inst2->id, 'ativa' => true]);

        $response = $this->get("/provas?instituicao={$inst1->id}");
        $response->assertStatus(200)->assertSee($prova1->nome)->assertDontSee($prova2->nome);
    }

    // ─── Questões ─────────────────────────────────────────────────────────

    public function test_listagem_questoes_acessivel_sem_login(): void
    {
        $this->get('/questoes')->assertStatus(200);
    }

    public function test_detalhe_questao_exibe_enunciado_e_alternativas(): void
    {
        $questao = $this->criarQuestaoCompleta();

        $this->get("/questoes/{$questao->id}")
            ->assertStatus(200)
            ->assertSee($questao->enunciado)
            ->assertSee('Alternativa A');
    }

    public function test_detalhe_questao_exibe_gabarito_oficial(): void
    {
        $questao = $this->criarQuestaoCompleta();
        $admin   = User::factory()->create(['role' => 'admin']);

        GabaritoOficial::create([
            'questao_id'       => $questao->id,
            'resposta_correta' => 'A',
            'fonte_descricao'  => 'Gabarito oficial UEA',
            'cadastrado_por'   => $admin->id,
        ]);

        $this->get("/questoes/{$questao->id}")
            ->assertStatus(200)
            ->assertSee('Gabarito Oficial')
            ->assertSee('Gabarito oficial UEA');
    }

    public function test_detalhe_questao_exibe_mensagem_quando_gabarito_indisponivel(): void
    {
        $questao = $this->criarQuestaoCompleta();

        $this->get("/questoes/{$questao->id}")
            ->assertStatus(200)
            ->assertSee('Gabarito oficial não disponível');
    }

    public function test_detalhe_questao_exibe_resolucao_oficial(): void
    {
        $questao = $this->criarQuestaoCompleta();
        $admin   = User::factory()->create(['role' => 'admin']);

        ResolucaoOficial::create([
            'questao_id'      => $questao->id,
            'conteudo'        => 'Esta é a resolução oficial detalhada da questão.',
            'fonte_descricao' => 'Resolução UEA',
            'cadastrado_por'  => $admin->id,
            'ativa'           => true,
        ]);

        $this->get("/questoes/{$questao->id}")
            ->assertStatus(200)
            ->assertSee('Resolução Oficial')
            ->assertSee('Esta é a resolução oficial detalhada da questão.');
    }

    public function test_detalhe_questao_exibe_respostas_comunidade(): void
    {
        $questao = $this->criarQuestaoCompleta();
        $user    = User::factory()->create(['ativo' => true]);

        \App\Models\RespostaComunidade::create([
            'questao_id' => $questao->id,
            'user_id'    => $user->id,
            'conteudo'   => 'Esta é minha explicação para a questão.',
            'oculta'     => false,
        ]);

        $this->get("/questoes/{$questao->id}")
            ->assertStatus(200)
            ->assertSee('Respostas da comunidade')
            ->assertSee('Esta é minha explicação para a questão.');
    }

    public function test_questao_inativa_retorna_404(): void
    {
        $inst    = Instituicao::factory()->create();
        $prova   = Prova::factory()->create(['instituicao_id' => $inst->id]);
        $questao = Questao::factory()->create([
            'prova_id' => $prova->id,
            'numero'   => 1,
            'ativa'    => false,
        ]);

        $this->get("/questoes/{$questao->id}")->assertStatus(404);
    }

    public function test_gabarito_e_resposta_comunidade_sao_claramente_separados(): void
    {
        $questao = $this->criarQuestaoCompleta();
        $admin   = User::factory()->create(['role' => 'admin']);
        $user    = User::factory()->create(['ativo' => true]);

        GabaritoOficial::create([
            'questao_id'       => $questao->id,
            'resposta_correta' => 'B',
            'cadastrado_por'   => $admin->id,
        ]);

        \App\Models\RespostaComunidade::create([
            'questao_id'        => $questao->id,
            'user_id'           => $user->id,
            'conteudo'          => 'Minha explicação da questão.',
            'resposta_indicada' => 'C',
            'oculta'            => false,
        ]);

        $response = $this->get("/questoes/{$questao->id}");
        $response->assertStatus(200);
        // Gabarito oficial presente
        $response->assertSee('Gabarito Oficial');
        // Aviso de que respostas da comunidade não são gabaritos oficiais
        $response->assertSee('não representam gabaritos oficiais');
    }
}
