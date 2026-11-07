<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Redireciona após login bem-sucedido.
     */
    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Verifica se o usuário está ativo após autenticar.
     * Se inativo, desloga e retorna erro.
     */
    protected function authenticated(Request $request, $user)
    {
        if (!$user->ativo) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => 'Sua conta está desativada. Entre em contato com o suporte.']);
        }
    }

    /**
     * Admin vai direto para o painel; usuários vão para home.
     */
    protected function redirectTo()
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return route('admin.dashboard');
        }
        return route('home');
    }
}
