<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gabaritos_oficiais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questao_id')->unique()->constrained('questoes')->cascadeOnDelete();
            $table->foreignId('alternativa_id')->nullable()->constrained('alternativas')->nullOnDelete();
            // Letra da resposta correta (redundante com alternativa_id, mas garante integridade)
            $table->char('resposta_correta', 1);
            // Fonte do gabarito: URL do site oficial da instituição
            $table->string('fonte_url', 1000)->nullable();
            $table->string('fonte_descricao')->nullable();
            // Data de publicação do gabarito pela instituição
            $table->date('data_publicacao')->nullable();
            // Cadastrado por qual admin
            $table->foreignId('cadastrado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('questao_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gabaritos_oficiais');
    }
};
