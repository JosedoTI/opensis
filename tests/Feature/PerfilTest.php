<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PerfilTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitante_nao_acessa_perfil(): void
    {
        $this->get('/perfil')->assertRedirect('/login');
    }

    public function test_usuario_acessa_proprio_perfil(): void
    {
        $user = User::factory()->create(['ativo' => true]);

        $this->actingAs($user)
            ->get('/perfil')
            ->assertStatus(200)
            ->assertSee($user->name);
    }

    public function test_usuario_acessa_pagina_editar_perfil(): void
    {
        $user = User::factory()->create(['ativo' => true]);

        $this->actingAs($user)
            ->get('/perfil/editar')
            ->assertStatus(200);
    }

    public function test_usuario_atualiza_nome(): void
    {
        $user = User::factory()->create(['ativo' => true]);

        $this->actingAs($user)
            ->put('/perfil', [
                'name' => 'Nome Atualizado',
            ])
            ->assertRedirect('/perfil');

        $this->assertDatabaseHas('users', [
            'id'   => $user->id,
            'name' => 'Nome Atualizado',
        ]);
    }

    public function test_usuario_atualiza_username_e_bio(): void
    {
        $user = User::factory()->create(['ativo' => true]);

        $this->actingAs($user)
            ->put('/perfil', [
                'name'     => $user->name,
                'username' => 'novo_username',
                'bio'      => 'Minha bio de teste.',
            ])
            ->assertRedirect('/perfil');

        $this->assertDatabaseHas('users', [
            'id'       => $user->id,
            'username' => 'novo_username',
            'bio'      => 'Minha bio de teste.',
        ]);
    }

    public function test_username_duplicado_e_rejeitado(): void
    {
        $user1 = User::factory()->create(['username' => 'existente', 'ativo' => true]);
        $user2 = User::factory()->create(['ativo' => true]);

        $this->actingAs($user2)
            ->put('/perfil', [
                'name'     => $user2->name,
                'username' => 'existente',
            ])
            ->assertSessionHasErrors('username');
    }

    public function test_alteracao_senha_com_senha_atual_correta(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('SenhaAtual@1'),
            'ativo'    => true,
        ]);

        $this->actingAs($user)
            ->post('/perfil/senha', [
                'senha_atual'          => 'SenhaAtual@1',
                'password'             => 'NovaSenha@2',
                'password_confirmation' => 'NovaSenha@2',
            ])
            ->assertRedirect();

        // Verifica que a senha foi alterada (não é mais a antiga)
        $this->assertCredentials([
            'email'    => $user->email,
            'password' => 'NovaSenha@2',
        ]);
    }

    public function test_alteracao_senha_rejeita_senha_atual_incorreta(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('SenhaCorreta@1'),
            'ativo'    => true,
        ]);

        $this->actingAs($user)
            ->post('/perfil/senha', [
                'senha_atual'          => 'SenhaErrada@1',
                'password'             => 'NovaSenha@2',
                'password_confirmation' => 'NovaSenha@2',
            ])
            ->assertSessionHasErrors('senha_atual');
    }

    public function test_alteracao_senha_rejeita_senha_sem_numero(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('SenhaAtual@1'),
            'ativo'    => true,
        ]);

        $this->actingAs($user)
            ->post('/perfil/senha', [
                'senha_atual'          => 'SenhaAtual@1',
                'password'             => 'SenhaSemNumero',
                'password_confirmation' => 'SenhaSemNumero',
            ])
            ->assertSessionHasErrors('password');
    }

    public function test_alteracao_senha_rejeita_confirmacao_divergente(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('SenhaAtual@1'),
            'ativo'    => true,
        ]);

        $this->actingAs($user)
            ->post('/perfil/senha', [
                'senha_atual'          => 'SenhaAtual@1',
                'password'             => 'NovaSenha@2',
                'password_confirmation' => 'SenhaDiferente@3',
            ])
            ->assertSessionHasErrors('password');
    }
}
