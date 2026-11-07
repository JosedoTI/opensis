<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Questao;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuestaoApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Questao::with([
            'prova:id,nome,ano,etapa,instituicao_id',
            'prova.instituicao:id,sigla,nome_processo',
            'disciplina:id,nome,slug,cor',
        ])
        ->where('ativa', true)
        ->where('status', 'completa');

        if ($request->filled('prova_id')) {
            $query->where('prova_id', $request->prova_id);
        }

        if ($request->filled('disciplina_id')) {
            $query->where('disciplina_id', $request->disciplina_id);
        }

        if ($request->filled('instituicao_id')) {
            $query->whereHas('prova', fn ($q) => $q->where('instituicao_id', $request->instituicao_id));
        }

        if ($request->filled('ano')) {
            $query->whereHas('prova', fn ($q) => $q->where('ano', $request->ano));
        }

        $questoes = $query->orderBy('prova_id')->orderBy('numero')
            ->paginate($request->integer('por_pagina', 20));

        return response()->json($questoes);
    }

    public function show(Questao $questao): JsonResponse
    {
        abort_if(!$questao->ativa, 404);

        $questao->load([
            'prova.instituicao:id,sigla,nome,nome_processo,cor_primaria',
            'disciplina:id,nome,slug,cor',
            'assunto:id,nome',
            'alternativas:id,questao_id,letra,texto',
        ]);

        $resposta = [
            'data'    => $questao,
            'gabarito' => null,
            'resolucao' => null,
        ];

        // Retorna gabarito se disponível
        if ($questao->gabaritoOficial) {
            $resposta['gabarito'] = [
                'resposta_correta' => $questao->gabaritoOficial->resposta_correta,
                'fonte'            => $questao->gabaritoOficial->fonte_descricao,
                'data_publicacao'  => $questao->gabaritoOficial->data_publicacao,
            ];
        }

        return response()->json($resposta);
    }

    public function alternativas(Questao $questao): JsonResponse
    {
        abort_if(!$questao->ativa, 404);

        return response()->json([
            'data' => $questao->alternativas()->where('ativa', true)->get(['id', 'letra', 'texto']),
        ]);
    }

    public function gabarito(Questao $questao): JsonResponse
    {
        abort_if(!$questao->ativa, 404);

        $gabarito = $questao->gabaritoOficial;

        if (!$gabarito) {
            return response()->json([
                'disponivel' => false,
                'message'    => 'Gabarito oficial não disponível para esta questão.',
            ]);
        }

        return response()->json([
            'disponivel'       => true,
            'resposta_correta' => $gabarito->resposta_correta,
            'fonte_url'        => $gabarito->fonte_url,
            'fonte_descricao'  => $gabarito->fonte_descricao,
            'data_publicacao'  => $gabarito->data_publicacao,
        ]);
    }

    public function resolucao(Questao $questao): JsonResponse
    {
        abort_if(!$questao->ativa, 404);

        $resolucao = $questao->resolucaoOficial;

        if (!$resolucao || !$resolucao->ativa) {
            return response()->json([
                'disponivel' => false,
                'message'    => 'Resolução oficial não disponível para esta questão.',
            ]);
        }

        return response()->json([
            'disponivel'      => true,
            'conteudo'        => $resolucao->conteudo,
            'fonte_url'       => $resolucao->fonte_url,
            'fonte_descricao' => $resolucao->fonte_descricao,
            'data_publicacao' => $resolucao->data_publicacao,
        ]);
    }
}
