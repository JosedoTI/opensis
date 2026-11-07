<?php

namespace App\Http\Controllers;

use App\Models\Disciplina;
use App\Models\Instituicao;
use App\Models\Prova;
use Illuminate\Http\Request;

class ProvaController extends Controller
{
    public function index(Request $request)
    {
        $instituicoes = Instituicao::where('ativa', true)->get();
        $query = Prova::with('instituicao')->where('ativa', true);

        if ($request->filled('instituicao')) {
            $query->where('instituicao_id', $request->instituicao);
        }

        if ($request->filled('ano')) {
            $query->where('ano', $request->ano);
        }

        if ($request->filled('etapa')) {
            $query->where('etapa', $request->etapa);
        }

        $provas = $query->orderByDesc('ano')->orderBy('etapa')->paginate(18)->withQueryString();

        // Anos disponíveis para filtro
        $anos = Prova::selectRaw('DISTINCT ano')->orderByDesc('ano')->pluck('ano');

        return view('provas.index', compact('provas', 'instituicoes', 'anos'));
    }

    public function show(Prova $prova)
    {
        $prova->load([
            'instituicao',
            'documentos' => fn ($q) => $q->where('ativo', true),
            'questoes' => fn ($q) => $q->where('ativa', true)
                ->with(['disciplina', 'gabaritoOficial']),
        ]);

        return view('provas.show', compact('prova'));
    }
}
