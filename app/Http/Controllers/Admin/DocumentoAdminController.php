<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Documento;
use App\Models\Instituicao;
use App\Models\Prova;
use Illuminate\Http\Request;

class DocumentoAdminController extends Controller
{
    public function index(Request $request)
    {
        $instituicoes = Instituicao::where('ativa', true)->get();

        // Provas SEM documentos (precisam de atenção)
        $queryPendentes = Prova::with('instituicao')
            ->whereDoesntHave('documentos', fn ($q) => $q->where('ativo', true))
            ->where('ativa', true);

        if ($request->filled('instituicao')) {
            $queryPendentes->where('instituicao_id', $request->instituicao);
        }
        if ($request->filled('ano')) {
            $queryPendentes->where('ano', $request->ano);
        }

        $provasSemDocumento = $queryPendentes
            ->orderByDesc('ano')->orderBy('etapa')
            ->paginate(20, ['*'], 'pendentes')
            ->withQueryString();

        // Documentos cadastrados
        $queryDocs = Documento::with(['prova.instituicao', 'fontes'])
            ->where('ativo', true);

        if ($request->filled('instituicao')) {
            $queryDocs->whereHas('prova', fn ($q) => $q->where('instituicao_id', $request->instituicao));
        }

        $documentos = $queryDocs
            ->orderByDesc('created_at')
            ->paginate(20, ['*'], 'docs')
            ->withQueryString();

        $anos = Prova::selectRaw('DISTINCT ano')->orderByDesc('ano')->pluck('ano');

        $stats = [
            'total_provas'          => Prova::where('ativa', true)->count(),
            'provas_com_documento'  => Prova::where('ativa', true)->whereHas('documentos', fn ($q) => $q->where('ativo', true))->count(),
            'provas_sem_documento'  => Prova::where('ativa', true)->whereDoesntHave('documentos', fn ($q) => $q->where('ativo', true))->count(),
            'total_documentos'      => Documento::where('ativo', true)->count(),
            'documentos_externos'   => Documento::where('ativo', true)->whereNotNull('url_externa')->count(),
            'documentos_locais'     => Documento::where('ativo', true)->whereNotNull('caminho')->count(),
        ];

        return view('admin.documentos.index', compact(
            'provasSemDocumento', 'documentos', 'instituicoes', 'anos', 'stats'
        ));
    }

    public function destroy(Documento $documento)
    {
        $documento->update(['ativo' => false]);

        return back()->with('sucesso', 'Documento desativado.');
    }
}
