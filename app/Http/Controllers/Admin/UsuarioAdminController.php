<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsuarioAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('busca')) {
            $busca = $request->busca;
            $query->where(fn ($q) => $q->where('name', 'like', "%{$busca}%")
                ->orWhere('email', 'like', "%{$busca}%"));
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('ativo')) {
            $query->where('ativo', $request->ativo);
        }

        $usuarios = $query->withTrashed()->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function show(User $usuario)
    {
        $usuario->load(['respostas.questao.prova.instituicao']);
        return view('admin.usuarios.show', compact('usuario'));
    }

    public function toggleAtivo(User $usuario)
    {
        if ($usuario->id === auth()->id()) {
            return back()->with('erro', 'Você não pode desativar sua própria conta.');
        }

        $usuario->update(['ativo' => !$usuario->ativo]);
        $status = $usuario->ativo ? 'ativado' : 'desativado';

        return back()->with('sucesso', "Usuário {$status} com sucesso.");
    }

    public function promoverAdmin(User $usuario)
    {
        if ($usuario->isAdmin()) {
            return back()->with('aviso', 'Usuário já é administrador.');
        }

        $usuario->update(['role' => 'admin']);

        return back()->with('sucesso', "{$usuario->name} agora é administrador.");
    }

    public function rebaixar(User $usuario)
    {
        if ($usuario->id === auth()->id()) {
            return back()->with('erro', 'Você não pode remover seu próprio acesso de admin.');
        }

        $usuario->update(['role' => 'usuario']);

        return back()->with('sucesso', "{$usuario->name} agora é usuário comum.");
    }
}
