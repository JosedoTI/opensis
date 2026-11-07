<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assunto;
use App\Models\Disciplina;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DisciplinaAdminController extends Controller
{
    public function index()
    {
        $disciplinas = Disciplina::withCount('questoes')
            ->orderBy('nome')
            ->paginate(20);

        return view('admin.disciplinas.index', compact('disciplinas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:100|unique:disciplinas,nome',
            'cor'  => 'nullable|string|max:10',
        ]);

        Disciplina::create([
            'nome'  => $request->nome,
            'slug'  => Str::slug($request->nome),
            'cor'   => $request->cor,
            'ativa' => true,
        ]);

        return back()->with('sucesso', 'Disciplina cadastrada!');
    }

    public function update(Request $request, Disciplina $disciplina)
    {
        $request->validate([
            'nome' => 'required|string|max:100|unique:disciplinas,nome,' . $disciplina->id,
            'cor'  => 'nullable|string|max:10',
            'ativa' => 'boolean',
        ]);

        $disciplina->update([
            'nome'  => $request->nome,
            'cor'   => $request->cor,
            'ativa' => $request->boolean('ativa', true),
        ]);

        return back()->with('sucesso', 'Disciplina atualizada!');
    }

    public function storeAssunto(Request $request, Disciplina $disciplina)
    {
        $request->validate([
            'nome' => 'required|string|max:150',
        ]);

        Assunto::firstOrCreate(
            [
                'disciplina_id' => $disciplina->id,
                'slug'          => Str::slug($request->nome),
            ],
            [
                'nome'  => $request->nome,
                'ativo' => true,
            ]
        );

        return back()->with('sucesso', 'Assunto cadastrado!');
    }
}
