<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Denuncia;
use App\Models\RespostaComunidade;
use Illuminate\Http\Request;

class ModeracaoController extends Controller
{
    public function index(Request $request)
    {
        $query = Denuncia::with(['resposta.autor', 'resposta.questao.prova.instituicao', 'denunciante']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'pendente');
        }

        $denuncias = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('admin.moderacao.index', compact('denuncias'));
    }

    public function show(Denuncia $denuncia)
    {
        $denuncia->load(['resposta.autor', 'resposta.questao.prova.instituicao', 'denunciante']);
        return view('admin.moderacao.show', compact('denuncia'));
    }

    public function analisar(Request $request, Denuncia $denuncia)
    {
        $request->validate([
            'acao'          => 'required|in:ocultar,manter,encerrar',
            'parecer_admin' => 'nullable|string|max:1000',
        ]);

        $denuncia->update([
            'status'        => 'analisada',
            'analisada_por' => $request->user()->id,
            'analisada_em'  => now(),
            'parecer_admin' => $request->parecer_admin,
        ]);

        if ($request->acao === 'ocultar') {
            $denuncia->resposta->update([
                'oculta'           => true,
                'motivo_ocultacao' => "Ocultada por moderação. Motivo da denúncia: {$denuncia->motivo}.",
                'ocultada_por'     => $request->user()->id,
                'ocultada_em'      => now(),
            ]);
        }

        // Encerra todas as denúncias pendentes da mesma resposta
        if (in_array($request->acao, ['ocultar', 'encerrar'])) {
            Denuncia::where('resposta_id', $denuncia->resposta_id)
                ->where('status', 'pendente')
                ->update(['status' => 'encerrada']);
        }

        return redirect()->route('admin.moderacao.index')
            ->with('sucesso', 'Denúncia analisada com sucesso.');
    }

    public function respostas(Request $request)
    {
        $query = RespostaComunidade::with(['autor', 'questao.prova.instituicao'])
            ->withTrashed();

        if ($request->filled('oculta')) {
            $query->where('oculta', $request->oculta);
        }

        $respostas = $query->orderByDesc('created_at')->paginate(25)->withQueryString();

        return view('admin.moderacao.respostas', compact('respostas'));
    }

    public function toggleOcultar(RespostaComunidade $resposta)
    {
        if ($resposta->oculta) {
            $resposta->update([
                'oculta'           => false,
                'motivo_ocultacao' => null,
                'ocultada_por'     => null,
                'ocultada_em'      => null,
            ]);
            $msg = 'Resposta tornada visível.';
        } else {
            $resposta->update([
                'oculta'           => true,
                'motivo_ocultacao' => 'Ocultada manualmente pelo administrador.',
                'ocultada_por'     => auth()->id(),
                'ocultada_em'      => now(),
            ]);
            $msg = 'Resposta ocultada.';
        }

        return back()->with('sucesso', $msg);
    }
}
