<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instituicao;
use Illuminate\Http\Request;

class InstituicaoAdminController extends Controller
{
    public function index()
    {
        // withCount inclui provas soft-deleted para alinhar com a proteção do destroy
        $instituicoes = Instituicao::withCount(['provas', 'provas as provas_total_count' => fn ($q) => $q->withTrashed()])->get();
        return view('admin.instituicoes.index', compact('instituicoes'));
    }

    public function create()
    {
        return view('admin.instituicoes.form');
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'sigla'              => 'required|string|max:20|unique:instituicoes,sigla',
            'nome'               => 'required|string|max:200',
            'nome_processo'      => 'required|string|max:50',
            'descricao_processo' => 'nullable|string|max:200',
            'cor_primaria'       => 'nullable|string|max:10',
            'site_oficial'       => 'nullable|url|max:300',
        ]);

        Instituicao::create($dados);

        return redirect()->route('admin.instituicoes.index')
            ->with('sucesso', 'Instituição cadastrada com sucesso!');
    }

    public function edit(Instituicao $instituicao)
    {
        return view('admin.instituicoes.form', compact('instituicao'));
    }

    public function update(Request $request, Instituicao $instituicao)
    {
        $request->validate([
            'sigla'              => 'required|string|max:20|unique:instituicoes,sigla,' . $instituicao->id,
            'nome'               => 'required|string|max:200',
            'nome_processo'      => 'required|string|max:50',
            'descricao_processo' => 'nullable|string|max:200',
            'cor_primaria'       => 'nullable|string|max:10',
            'site_oficial'       => 'nullable|url|max:300',
            'ativa'              => 'boolean',
        ]);

        $instituicao->update($request->only([
            'sigla', 'nome', 'nome_processo', 'descricao_processo',
            'cor_primaria', 'site_oficial', 'ativa',
        ]));

        return redirect()->route('admin.instituicoes.index')
            ->with('sucesso', 'Instituição atualizada!');
    }

    public function destroy(Instituicao $instituicao)
    {
        // Conta provas incluindo as soft-deleted — qualquer vínculo impede a exclusão
        $totalProvas = $instituicao->provas()->withTrashed()->count();

        if ($totalProvas > 0) {
            return back()->with('erro', "Não é possível excluir \"{$instituicao->sigla}\" pois possui {$totalProvas} prova(s) associada(s). Desative a instituição em vez disso.");
        }

        $instituicao->delete();

        return redirect()->route('admin.instituicoes.index')
            ->with('sucesso', "Instituição \"{$instituicao->sigla}\" excluída com sucesso.");
    }
}
