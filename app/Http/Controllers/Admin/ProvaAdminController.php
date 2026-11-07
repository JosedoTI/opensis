<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProvaRequest;
use App\Models\Documento;
use App\Models\Instituicao;
use App\Models\Prova;
use Illuminate\Http\Request;

class ProvaAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Prova::with('instituicao');

        if ($request->filled('instituicao')) {
            $query->where('instituicao_id', $request->instituicao);
        }
        if ($request->filled('ano')) {
            $query->where('ano', $request->ano);
        }
        if ($request->filled('etapa')) {
            $query->where('etapa', $request->etapa);
        }

        $provas = $query->orderByDesc('ano')->orderBy('etapa')->paginate(20)->withQueryString();
        $instituicoes = Instituicao::where('ativa', true)->get();
        $anos   = Prova::selectRaw('DISTINCT ano')->orderByDesc('ano')->pluck('ano');
        $etapas = Prova::selectRaw('DISTINCT etapa')->orderBy('etapa')->pluck('etapa');

        return view('admin.provas.index', compact('provas', 'instituicoes', 'anos', 'etapas'));
    }

    public function create()
    {
        $instituicoes = Instituicao::where('ativa', true)->get();
        return view('admin.provas.form', compact('instituicoes'));
    }

    public function store(StoreProvaRequest $request)
    {
        $prova = Prova::create($request->validated());

        return redirect()
            ->route('admin.provas.show', $prova)
            ->with('sucesso', "Prova \"{$prova->nome}\" cadastrada com sucesso!");
    }

    public function show(Prova $prova)
    {
        $prova->load(['instituicao', 'documentos', 'questoes.disciplina']);
        return view('admin.provas.show', compact('prova'));
    }

    public function edit(Prova $prova)
    {
        $instituicoes = Instituicao::where('ativa', true)->get();
        return view('admin.provas.form', compact('prova', 'instituicoes'));
    }

    public function update(StoreProvaRequest $request, Prova $prova)
    {
        $prova->update($request->validated());

        return redirect()
            ->route('admin.provas.show', $prova)
            ->with('sucesso', "Prova atualizada com sucesso!");
    }

    public function destroy(Prova $prova)
    {
        // Soft delete — preserva dados
        $prova->delete();
        return redirect()->route('admin.provas.index')
            ->with('sucesso', "Prova \"{$prova->nome}\" removida.");
    }

    // Upload de documento (PDF) vinculado à prova
    public function storeDocumento(Request $request, Prova $prova)
    {
        $request->validate([
            'arquivo'      => 'nullable|file|mimes:pdf|max:51200',
            'url_externa'  => 'nullable|url|max:1000',
            'tipo'         => 'required|in:prova,gabarito,resolucao,edital,outro',
            'observacoes'  => 'nullable|string|max:500',
        ]);

        $caminho = null;
        if ($request->hasFile('arquivo')) {
            $caminho = $request->file('arquivo')->store(
                "documentos/{$prova->instituicao->sigla}/{$prova->ano}",
                'public'
            );
        }

        $doc = Documento::create([
            'prova_id'        => $prova->id,
            'tipo'            => $request->tipo,
            'nome_arquivo'    => $request->hasFile('arquivo')
                ? $request->file('arquivo')->getClientOriginalName()
                : "documento_{$prova->nome}",
            'caminho'         => $caminho,
            'url_externa'     => $request->url_externa,
            'mime_type'       => $request->hasFile('arquivo') ? $request->file('arquivo')->getMimeType() : null,
            'tamanho_bytes'   => $request->hasFile('arquivo') ? $request->file('arquivo')->getSize() : null,
            'observacoes'     => $request->observacoes,
            'ativo'           => true,
        ]);

        // Atualiza flag de disponibilidade na prova
        if ($request->tipo === 'gabarito') {
            $prova->update(['gabarito_disponivel' => true]);
        }
        if ($request->tipo === 'resolucao') {
            $prova->update(['resolucao_disponivel' => true]);
        }

        return redirect()->route('admin.provas.show', $prova)
            ->with('sucesso', 'Documento adicionado com sucesso!');
    }
}
