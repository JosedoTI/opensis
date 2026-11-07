<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('denuncias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resposta_id')->constrained('respostas_comunidade')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->enum('motivo', [
                'incorreta',
                'ofensiva',
                'spam',
                'plagio',
                'outro'
            ]);
            $table->text('descricao')->nullable();
            // pendente, analisada, encerrada
            $table->enum('status', ['pendente', 'analisada', 'encerrada'])->default('pendente');
            $table->foreignId('analisada_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('analisada_em')->nullable();
            $table->text('parecer_admin')->nullable();
            $table->timestamps();

            // Um usuário só pode denunciar cada resposta uma vez
            $table->unique(['resposta_id', 'user_id']);
            $table->index(['status', 'created_at']);
            $table->index('resposta_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('denuncias');
    }
};
