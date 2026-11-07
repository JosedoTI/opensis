<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instituicao_id')->constrained('instituicoes')->restrictOnDelete();
            $table->year('ano');
            // Etapa: para SIS = 1ª Etapa, 2ª Etapa, 3ª Etapa; para PSC = PSC1, PSC2, PSC3
            $table->string('etapa', 50);
            // Nome completo da prova, ex: "SIS 2023 — 1ª Etapa"
            $table->string('nome');
            // Tipo: objetiva, redacao, etc.
            $table->enum('tipo', ['objetiva', 'redacao', 'mista'])->default('objetiva');
            // Número total de questões quando conhecido
            $table->unsignedSmallInteger('total_questoes')->nullable();
            // Ano está no período com questões individualizadas (2020+)
            $table->boolean('questoes_individualizadas')->default(false);
            // Gabarito oficial disponível
            $table->boolean('gabarito_disponivel')->default(false);
            // Resolução oficial disponível
            $table->boolean('resolucao_disponivel')->default(false);
            $table->boolean('ativa')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['instituicao_id', 'ano', 'etapa']);
            $table->index(['ano', 'instituicao_id']);
            $table->index('etapa');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provas');
    }
};
