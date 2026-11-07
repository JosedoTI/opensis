<?php

namespace App\Http\Controllers;

use App\Models\InteracaoResposta;
use App\Models\Questao;
use App\Models\RespostaComunidade;
use App\Http\Requests\StoreRespostaRequest;
use Illuminate\Http\Request;

class RespostaComunidadeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except([]);
    }

    public function store(StoreRespostaRequest $request, Questao $questao)
    {
        abort_if(!$questao->ativa, 404);

        $resposta = RespostaComunidade::create([
            'questao_id'        => $questao->id,
            'user_id'           => $request->user()->id,
            'conteudo'          => $request->conteudo,
            'resposta_indicada' => $request->resposta_indicada,
        ]);

        return redirect()
            ->route('questoes.show', $questao)
            ->with('sucesso', 'Sua resposta foi publicada com sucesso!')
            ->withFragment('resposta-' . $resposta->id);
    }

    public function edit(RespostaComunidade $resposta)
    {
        $this->authorize('update', $resposta);
        $resposta->load('questao.prova.instituicao');
        return view('questoes.editar-resposta', compact('resposta'));
    }

    public function update(StoreRespostaRequest $request, RespostaComunidade $resposta)
    {
        $this->authorize('update', $resposta);

        $resposta->update([
            'conteudo'          => $request->conteudo,
            'resposta_indicada' => $request->resposta_indicada,
        ]);

        return redirect()
            ->route('questoes.show', $resposta->questao_id)
            ->with('sucesso', 'Resposta atualizada com sucesso!')
            ->withFragment('resposta-' . $resposta->id);
    }

    public function destroy(RespostaComunidade $resposta)
    {
        $this->authorize('delete', $resposta);
        $questaoId = $resposta->questao_id;
        $resposta->delete();

        return redirect()
            ->route('questoes.show', $questaoId)
            ->with('sucesso', 'Resposta excluída com sucesso.');
    }

    public function votar(Request $request, RespostaComunidade $resposta)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Autenticação necessária.'], 401);
        }

        // Não pode votar na própria resposta
        if ($resposta->user_id === $user->id) {
            return response()->json(['message' => 'Você não pode marcar sua própria resposta como útil.'], 422);
        }

        $existente = InteracaoResposta::where('resposta_id', $resposta->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existente) {
            // Toggle: remove voto
            $existente->delete();
            $resposta->decrement('votos_util');
            return response()->json(['acao' => 'removido', 'votos' => $resposta->fresh()->votos_util]);
        }

        InteracaoResposta::create([
            'resposta_id' => $resposta->id,
            'user_id'     => $user->id,
            'tipo'        => 'util',
        ]);
        $resposta->increment('votos_util');

        return response()->json(['acao' => 'adicionado', 'votos' => $resposta->fresh()->votos_util]);
    }
}
