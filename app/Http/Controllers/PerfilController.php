<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PerfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $user = auth()->user();
        $respostas = $user->respostas()
            ->with('questao.prova.instituicao')
            ->whereNull('deleted_at')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('perfil.show', compact('user', 'respostas'));
    }

    public function edit()
    {
        return view('perfil.edit', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'nullable|string|max:50|alpha_dash|unique:users,username,' . $user->id,
            'bio'      => 'nullable|string|max:500',
        ], [
            'name.required'     => 'O nome é obrigatório.',
            'username.unique'   => 'Este nome de usuário já está em uso.',
            'username.alpha_dash' => 'O nome de usuário pode conter apenas letras, números, hífens e underscores.',
        ]);

        $user->update($request->only('name', 'username', 'bio'));

        return redirect()->route('perfil.show')->with('sucesso', 'Perfil atualizado com sucesso!');
    }

    public function alterarSenha(Request $request)
    {
        $request->validate([
            'senha_atual' => 'required',
            'password'    => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'senha_atual.required' => 'Informe sua senha atual.',
            'password.required'    => 'Informe a nova senha.',
            'password.confirmed'   => 'A confirmação da senha não confere.',
            'password.min'         => 'A senha deve ter no mínimo 8 caracteres.',
        ]);

        if (!Hash::check($request->senha_atual, $request->user()->password)) {
            return back()->withErrors(['senha_atual' => 'Senha atual incorreta.']);
        }

        $request->user()->update(['password' => Hash::make($request->password)]);

        return back()->with('sucesso', 'Senha alterada com sucesso!');
    }
}
