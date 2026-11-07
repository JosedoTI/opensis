<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Instituicao;
use Illuminate\Http\JsonResponse;

class InstituicaoApiController extends Controller
{
    public function index(): JsonResponse
    {
        $instituicoes = Instituicao::where('ativa', true)
            ->withCount('provas')
            ->get(['id', 'sigla', 'nome', 'nome_processo', 'descricao_processo',
                   'cidade', 'estado', 'cor_primaria', 'site_oficial']);

        return response()->json([
            'data' => $instituicoes,
        ]);
    }

    public function show(Instituicao $instituicao): JsonResponse
    {
        abort_if(!$instituicao->ativa, 404);

        $anos = $instituicao->provas()
            ->selectRaw('DISTINCT ano')
            ->orderByDesc('ano')
            ->pluck('ano');

        return response()->json([
            'data' => array_merge($instituicao->toArray(), [
                'anos_disponiveis' => $anos,
                'total_provas'     => $instituicao->provas()->count(),
            ]),
        ]);
    }
}
