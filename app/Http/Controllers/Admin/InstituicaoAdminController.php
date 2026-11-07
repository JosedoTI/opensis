<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instituicao;
use Illuminate\Http\Request;

class InstituicaoAdminController extends Controller
{
    public function index()
    {
        $instituicoes = Instituicao::withCount('provas')->get();
        return view('admin.instituicoes.index', compact('instituicoes'));
    }

    public function create()
    {
        return view('admin.instituicoes.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'sigla'              => 'required|string|max:20|unique:instituicoes,sigla',
            'nome'               => 'required|string|max:200',
            'nome_processo'      => 'required|string|max:50',
            'descricao_processo' => 'nullable|string|max:200',
            'cor_primaria'       => 'nullable|string|max:10',
            'site_oficial'       => 'nullable|url|max:300',
        ]);

        Instituicao::create($request->validated());

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

        $instituicao->update($request->all());

        return redirect()->route('admin.instituicoes.index')
            ->with('sucesso', 'Instituição atualizada!');
    }
}
