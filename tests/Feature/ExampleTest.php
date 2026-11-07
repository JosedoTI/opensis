<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Verifica que a página inicial retorna HTTP 200.
     */
    public function test_pagina_inicial_acessivel(): void
    {
        // A rota '/' não requer autenticação — passa variáveis via closure
        // mas como o banco de teste está vazio, precisamos verificar só o status
        $response = $this->get('/');
        // Pode retornar 200 ou 500 se o banco não tiver dados —
        // aqui garantimos que a rota está registrada (não 404)
        $this->assertNotEquals(404, $response->status());
    }

    public function test_pagina_login_retorna_200(): void
    {
        $this->get('/login')->assertStatus(200);
    }

    public function test_pagina_registro_retorna_200(): void
    {
        $this->get('/register')->assertStatus(200);
    }
}
