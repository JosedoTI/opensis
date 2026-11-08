<?php

namespace App\Http\Controllers;

use App\Models\Denuncia;
use App\Models\RespostaComunidade;
use App\Http\Requests\StoreDenunciaRequest;

class DenunciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(StoreDenunciaRequest $request, RespostaComunidade $resposta)
    {
        $this->authorize('create', Denuncia::class);

        // Verifica se já denunciou esta resposta
        $jaDenunciou = Denuncia::where('resposta_id', $resposta->id)
            ->where('user_id', $request->user()->id)
            ->exists();

        if ($jaDenunciou) {
            return back()->with('aviso', 'Você já denunciou esta resposta.');
        }

        try {
            \DB::transaction(function () use ($request, $resposta) {
                Denuncia::create([
                    'resposta_id' => $resposta->id,
                    'user_id'     => $request->user()->id,
                    'motivo'      => $request->motivo,
                    'descricao'   => $request->descricao,
                ]);

                $resposta->increment('total_denuncias');
            });
        } catch (\Illuminate\Database\QueryException $e) {
            // Constraint UNIQUE violada — denúncia duplicada por race condition
            return back()->with('aviso', 'Você já denunciou esta resposta.');
        }

        return back()->with('sucesso', 'Denúncia registrada. Nossa equipe irá analisar em breve.');
    }
}
