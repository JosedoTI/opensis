<?php

namespace App\Http\Controllers;

use App\Models\Instituicao;
use App\Models\Prova;
use App\Models\Questao;
use App\Models\User;

class HomePublicoController extends Controller
{
    public function index()
    {
        $instituicoes = Instituicao::where('ativa', true)->withCount('provas')->get();

        $stats = [
            'provas'   => Prova::where('ativa', true)->count(),
            'questoes' => Questao::where('ativa', true)->count(),
            'usuarios' => User::count(),
        ];

        $provasRecentes = Prova::with('instituicao')
            ->where('ativa', true)
            ->orderByDesc('ano')
            ->orderBy('etapa')
            ->take(6)
            ->get();

        return view('welcome', compact('instituicoes', 'stats', 'provasRecentes'));
    }
}
