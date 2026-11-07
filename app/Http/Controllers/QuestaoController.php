<?php

namespace App\Http\Controllers;

use App\Models\Assunto;
use App\Models\Disciplina;
use App\Models\Instituicao;
use App\Models\Prova;
use App\Models\Questao;
use Illuminate\Http\Request;

class QuestaoController extends Controller
{
    public function index(Request $request)
    {
        $instituicoes = Instituicao::where('ativa', true)->get();
        $disciplinas  = Disciplina::where('ativa', true)->orderBy('nome')->get();

        $query = Questao::with(['prova.instituicao', 'disciplina', 'gabaritoOficial'])
            ->where('ativa', true)
            ->where('status', 'completa');

        if ($request->filled('instituicao')) {
            $query->whereHas('prova', fn ($q) => $q->where('instituicao_id', $request->instituicao));
        }

        if ($request->filled('ano')) {
            $query->whereHas('prova', fn ($q) => $q->where('ano', $request->ano));
        }

        if ($request->filled('etapa')) {
            $query->whereHas('prova', fn ($q) => $q->where('etapa', $request->etapa));
        }

        if ($request->filled('disciplina')) {
            $query->where('disciplina_id', $request->disciplina);
        }

        if ($request->filled('assunto')) {
            $query->where('assunto_id', $request->assunto);
        }

        if ($request->filled('numero')) {
            $query->where('numero', $request->numero);
        }

        $questoes = $query->orderByDesc(
            Prova::select('ano')->whereColumn('provas.id', 'questoes.prova_id')
        )->orderBy('numero')->paginate(20)->withQueryString();

        $anos   = Prova::selectRaw('DISTINCT ano')->orderByDesc('ano')->pluck('ano');
        $etapas = Prova::selectRaw('DISTINCT etapa')->orderBy('etapa')->pluck('etapa');

        return view('questoes.index', compact('questoes', 'instituicoes', 'disciplinas', 'anos', 'etapas'));
    }

    public function show(Questao $questao)
    {
        abort_if(!$questao->ativa, 404);

        $questao->load([
            'prova.instituicao',
            'disciplina',
            'assunto',
            'alternativas',
            'gabaritoOficial',
            'resolucaoOficial',
            'respostasComunidade.autor',
            'respostasComunidade.interacoes',
        ]);

        // IDs de respostas que o usuário autenticado marcou como útil
        $interacoesUsuario = [];
        if (auth()->check()) {
            $interacoesUsuario = $questao->respostasComunidade
                ->flatMap(fn ($r) => $r->interacoes)
                ->where('user_id', auth()->id())
                ->where('tipo', 'util')
                ->pluck('resposta_id')
                ->toArray();
        }

        return view('questoes.show', compact('questao', 'interacoesUsuario'));
    }
}
