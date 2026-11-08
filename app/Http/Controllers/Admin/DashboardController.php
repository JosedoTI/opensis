<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Denuncia;
use App\Models\Disciplina;
use App\Models\Instituicao;
use App\Models\Prova;
use App\Models\Questao;
use App\Models\RespostaComunidade;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_usuarios'           => User::count(),
            'total_provas'             => Prova::count(),
            'total_questoes'           => Questao::count(),
            'total_questoes_completas' => Questao::where('status', 'completa')->count(),
            'total_respostas'          => RespostaComunidade::count(),
            'total_denuncias'          => Denuncia::where('status', 'pendente')->count(),
            'total_disciplinas'        => Disciplina::count(),
        ];

        $provasRecentes  = Prova::with('instituicao')->latest()->take(5)->get();
        $denunciasPendentes = Denuncia::with(['resposta.autor', 'denunciante'])
            ->where('status', 'pendente')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'provasRecentes', 'denunciasPendentes'));
    }
}
