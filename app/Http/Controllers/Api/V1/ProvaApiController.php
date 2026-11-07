<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Prova;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProvaApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Prova::with('instituicao:id,sigla,nome,nome_processo,cor_primaria')
            ->where('ativa', true);

        if ($request->filled('instituicao_id')) {
            $query->where('instituicao_id', $request->instituicao_id);
        }

        if ($request->filled('ano')) {
            $query->where('ano', $request->ano);
        }

        if ($request->filled('etapa')) {
            $query->where('etapa', $request->etapa);
        }

        $provas = $query->orderByDesc('ano')
            ->orderBy('etapa')
            ->paginate($request->integer('por_pagina', 20));

        return response()->json($provas);
    }

    public function show(Prova $prova): JsonResponse
    {
        abort_if(!$prova->ativa, 404);

        $prova->load([
            'instituicao:id,sigla,nome,nome_processo,cor_primaria',
            'documentos' => fn ($q) => $q->where('ativo', true)
                ->select('id', 'prova_id', 'tipo', 'nome_arquivo', 'url_externa', 'caminho',
                         'total_paginas', 'texto_extraivel'),
        ]);

        $prova->documentos->each(function ($doc) {
            $doc->append('url_acesso');
        });

        return response()->json([
            'data' => $prova,
        ]);
    }
}
