<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interacoes_respostas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resposta_id')->constrained('respostas_comunidade')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            // util = marcou como útil; neutro = removeu marcação
            $table->enum('tipo', ['util', 'neutro'])->default('util');
            $table->timestamps();

            // Um usuário só pode ter uma interação por resposta
            $table->unique(['resposta_id', 'user_id']);
            $table->index(['resposta_id', 'tipo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interacoes_respostas');
    }
};
