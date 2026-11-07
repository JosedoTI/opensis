<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRespostaRequest;
use App\Models\InteracaoResposta;
use App\Models\Questao;
use App\Models\RespostaComunidade;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RespostaApiController extends Controller
{
    public function index(Questao $questao): JsonResponse
    {
        abort_if(!$questao->ativa, 404);

        $respostas = RespostaComunidade::with('autor:id,name,username')
            ->where('questao_id', $questao->id)
            ->where('oculta', false)
            ->orderByDesc('votos_util')
            ->orderBy('created_at')
            ->paginate(10);

        return response()->json($respostas);
    }

    public function store(StoreRespostaRequest $request, Questao $questao): JsonResponse
    {
        abort_if(!$questao->ativa, 404);

        $resposta = RespostaComunidade::create([
            'questao_id'        => $questao->id,
            'user_id'           => $request->user()->id,
            'conteudo'          => $request->conteudo,
            'resposta_indicada' => $request->resposta_indicada,
        ]);

        $resposta->load('autor:id,name,username');

        return response()->json([
            'message' => 'Resposta publicada com sucesso.',
            'data'    => $resposta,
        ], 201);
    }

    public function update(StoreRespostaRequest $request, RespostaComunidade $resposta): JsonResponse
    {
        $this->authorize('update', $resposta);

        $resposta->update([
            'conteudo'          => $request->conteudo,
            'resposta_indicada' => $request->resposta_indicada,
        ]);

        return response()->json([
            'message' => 'Resposta atualizada.',
            'data'    => $resposta->fresh(),
        ]);
    }

    public function destroy(RespostaComunidade $resposta): JsonResponse
    {
        $this->authorize('delete', $resposta);
        $resposta->delete();

        return response()->json(['message' => 'Resposta excluída.']);
    }

    public function votar(Request $request, RespostaComunidade $resposta): JsonResponse
    {
        $user = $request->user();

        if ($resposta->user_id === $user->id) {
            return response()->json(['message' => 'Você não pode votar na própria resposta.'], 422);
        }

        $existente = InteracaoResposta::where('resposta_id', $resposta->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existente) {
            $existente->delete();
            $resposta->decrement('votos_util');
            return response()->json([
                'acao'  => 'removido',
                'votos' => $resposta->fresh()->votos_util,
            ]);
        }

        InteracaoResposta::create([
            'resposta_id' => $resposta->id,
            'user_id'     => $user->id,
            'tipo'        => 'util',
        ]);
        $resposta->increment('votos_util');

        return response()->json([
            'acao'  => 'adicionado',
            'votos' => $resposta->fresh()->votos_util,
        ]);
    }
}
