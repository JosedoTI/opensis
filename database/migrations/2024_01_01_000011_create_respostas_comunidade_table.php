<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('respostas_comunidade', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questao_id')->constrained('questoes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->longText('conteudo');
            // Letra que o autor indica como correta (opcional, não é gabarito oficial)
            $table->char('resposta_indicada', 1)->nullable();
            // Contador de marcações como "útil"
            $table->unsignedInteger('votos_util')->default(0);
            // Contador de denúncias
            $table->unsignedInteger('total_denuncias')->default(0);
            // Oculta por moderação
            $table->boolean('oculta')->default(false);
            $table->text('motivo_ocultacao')->nullable();
            $table->foreignId('ocultada_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('ocultada_em')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['questao_id', 'oculta']);
            $table->index(['user_id']);
            $table->index('votos_util');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respostas_comunidade');
    }
};
