<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutenticacaoTest extends TestCase
{
    use RefreshDatabase;

    public function test_pagina_login_acessivel(): void
    {
        $this->get('/login')->assertStatus(200);
    }

    public function test_pagina_registro_acessivel(): void
    {
        $this->get('/register')->assertStatus(200);
    }

    public function test_usuario_pode_registrar(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'João Silva',
            'email'                 => 'joao@exemplo.com',
            'password'              => 'Senha@1234',
            'password_confirmation' => 'Senha@1234',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['email' => 'joao@exemplo.com']);
    }

    public function test_usuario_pode_fazer_login(): void
    {
        $user = User::factory()->create([
            'email'    => 'teste@exemplo.com',
            'password' => bcrypt('Senha@1234'),
            'ativo'    => true,
        ]);

        $this->post('/login', [
            'email'    => 'teste@exemplo.com',
            'password' => 'Senha@1234',
        ])->assertRedirect();

        $this->assertAuthenticatedAs($user);
    }

    public function test_usuario_inativo_e_deslogado_ao_acessar_rota_protegida(): void
    {
        $user = User::factory()->create([
            'email'    => 'inativo@exemplo.com',
            'password' => bcrypt('Senha@1234'),
            'ativo'    => false,
        ]);

        // O middleware CheckUserAtivo desconecta o usuário inativo ao acessar rota protegida
        $response = $this->actingAs($user)->get('/perfil');

        // Deve redirecionar para o login com mensagem de erro
        $response->assertRedirect('/login');
    }

    public function test_usuario_pode_fazer_logout(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/logout');
        $this->assertGuest();
    }

    public function test_senha_incorreta_nao_autentica(): void
    {
        $user = User::factory()->create(['password' => bcrypt('correta')]);

        $this->post('/login', [
            'email'    => $user->email,
            'password' => 'errada',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }
}
