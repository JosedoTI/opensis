<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instituicoes', function (Blueprint $table) {
            $table->id();
            $table->string('sigla', 20)->unique();            // UEA, UFAM
            $table->string('nome');                            // Universidade do Estado do Amazonas
            $table->string('nome_processo');                   // SIS, PSC
            $table->string('descricao_processo')->nullable();  // Sistema de Ingresso Seriado
            $table->string('cidade')->default('Manaus');
            $table->string('estado', 2)->default('AM');
            $table->string('logo')->nullable();
            $table->string('cor_primaria', 10)->nullable();    // #hex para identidade visual
            $table->string('site_oficial')->nullable();
            $table->boolean('ativa')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instituicoes');
    }
};
