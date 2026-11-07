<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestaoRequest;
use App\Models\Alternativa;
use App\Models\Assunto;
use App\Models\Disciplina;
use App\Models\GabaritoOficial;
use App\Models\Prova;
use App\Models\Questao;
use App\Models\ResolucaoOficial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestaoAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Questao::with(['prova.instituicao', 'disciplina']);

        if ($request->filled('prova_id')) {
            $query->where('prova_id', $request->prova_id);
        }
        if ($request->filled('disciplina_id')) {
            $query->where('disciplina_id', $request->disciplina_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $questoes    = $query->orderByDesc('created_at')->paginate(25)->withQueryString();
        $provas      = Prova::with('instituicao')->where('ativa', true)->orderByDesc('ano')->get();
        $disciplinas = Disciplina::where('ativa', true)->orderBy('nome')->get();

        return view('admin.questoes.index', compact('questoes', 'provas', 'disciplinas'));
    }

    public function create(Request $request)
    {
        $provas      = Prova::with('instituicao')->where('ativa', true)->orderByDesc('ano')->get();
        $disciplinas = Disciplina::where('ativa', true)->orderBy('nome')->get();
        $assuntos    = Assunto::where('ativo', true)->with('disciplina')->get();
        $provaId     = $request->query('prova_id');

        return view('admin.questoes.form', compact('provas', 'disciplinas', 'assuntos', 'provaId'));
    }

    public function store(StoreQuestaoRequest $request)
    {
        DB::beginTransaction();
        try {
            $dados = $request->validated();

            if ($request->hasFile('imagem')) {
                $dados['imagem'] = $request->file('imagem')->store('questoes/imagens', 'public');
            }

            $alternativasDados = $dados['alternativas'];
            unset($dados['alternativas']);

            $questao = Questao::create($dados);

            foreach ($alternativasDados as $alt) {
                Alternativa::create([
                    'questao_id' => $questao->id,
                    'letra'      => $alt['letra'],
                    'texto'      => $alt['texto'],
                ]);
            }

            DB::commit();
            return redirect()
                ->route('admin.questoes.show', $questao)
                ->with('sucesso', "Questão #{$questao->numero} cadastrada com sucesso!");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['geral' => 'Erro ao salvar questão: ' . $e->getMessage()]);
        }
    }

    public function show(Questao $questao)
    {
        $questao->load([
            'prova.instituicao',
            'disciplina',
            'assunto',
            'alternativas',
            'gabaritoOficial',
            'resolucaoOficial',
        ]);
        return view('admin.questoes.show', compact('questao'));
    }

    public function edit(Questao $questao)
    {
        $provas      = Prova::with('instituicao')->where('ativa', true)->orderByDesc('ano')->get();
        $disciplinas = Disciplina::where('ativa', true)->orderBy('nome')->get();
        $assuntos    = Assunto::where('ativo', true)->with('disciplina')->get();
        $questao->load('alternativas');

        return view('admin.questoes.form', compact('questao', 'provas', 'disciplinas', 'assuntos'));
    }

    public function update(StoreQuestaoRequest $request, Questao $questao)
    {
        DB::beginTransaction();
        try {
            $dados = $request->validated();

            if ($request->hasFile('imagem')) {
                $dados['imagem'] = $request->file('imagem')->store('questoes/imagens', 'public');
            }

            $alternativasDados = $dados['alternativas'];
            unset($dados['alternativas']);

            $questao->update($dados);

            // Recria as alternativas
            $questao->alternativas()->delete();
            foreach ($alternativasDados as $alt) {
                Alternativa::create([
                    'questao_id' => $questao->id,
                    'letra'      => $alt['letra'],
                    'texto'      => $alt['texto'],
                ]);
            }

            DB::commit();
            return redirect()
                ->route('admin.questoes.show', $questao)
                ->with('sucesso', "Questão atualizada com sucesso!");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['geral' => 'Erro ao atualizar questão: ' . $e->getMessage()]);
        }
    }

    public function destroy(Questao $questao)
    {
        $questao->delete();
        return redirect()->route('admin.questoes.index')
            ->with('sucesso', "Questão #{$questao->numero} removida.");
    }

    // ─── Gabarito Oficial ─────────────────────────────────────────────────

    public function storeGabarito(Request $request, Questao $questao)
    {
        $request->validate([
            'resposta_correta' => 'required|in:A,B,C,D,E',
            'fonte_url'        => 'nullable|url|max:1000',
            'fonte_descricao'  => 'nullable|string|max:300',
            'data_publicacao'  => 'nullable|date',
        ]);

        $alternativa = $questao->alternativas()->where('letra', $request->resposta_correta)->first();

        GabaritoOficial::updateOrCreate(
            ['questao_id' => $questao->id],
            [
                'alternativa_id'   => $alternativa?->id,
                'resposta_correta' => $request->resposta_correta,
                'fonte_url'        => $request->fonte_url,
                'fonte_descricao'  => $request->fonte_descricao,
                'data_publicacao'  => $request->data_publicacao,
                'cadastrado_por'   => $request->user()->id,
            ]
        );

        // Atualiza flag na prova
        $questao->prova->update(['gabarito_disponivel' => true]);

        return redirect()->route('admin.questoes.show', $questao)
            ->with('sucesso', 'Gabarito oficial registrado!');
    }

    // ─── Resolução Oficial ────────────────────────────────────────────────

    public function storeResolucao(Request $request, Questao $questao)
    {
        $request->validate([
            'conteudo'        => 'required|string|min:20',
            'fonte_url'       => 'nullable|url|max:1000',
            'fonte_descricao' => 'nullable|string|max:300',
            'data_publicacao' => 'nullable|date',
        ]);

        ResolucaoOficial::updateOrCreate(
            ['questao_id' => $questao->id],
            [
                'conteudo'        => $request->conteudo,
                'fonte_url'       => $request->fonte_url,
                'fonte_descricao' => $request->fonte_descricao,
                'data_publicacao' => $request->data_publicacao,
                'cadastrado_por'  => $request->user()->id,
                'ativa'           => true,
            ]
        );

        $questao->prova->update(['resolucao_disponivel' => true]);

        return redirect()->route('admin.questoes.show', $questao)
            ->with('sucesso', 'Resolução oficial registrada!');
    }
}
