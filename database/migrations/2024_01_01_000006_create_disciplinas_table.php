<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disciplinas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');          // Matemática, Português, Biologia...
            $table->string('slug')->unique();
            $table->string('cor', 10)->nullable(); // cor hex para badge visual
            $table->string('icone', 50)->nullable();
            $table->boolean('ativa')->default(true);
            $table->timestamps();
        });

        Schema::create('assuntos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disciplina_id')->constrained('disciplinas')->restrictOnDelete();
            $table->string('nome');           // Geometria, Funções, Probabilidade...
            $table->string('slug');
            $table->boolean('ativo')->default(true);
            $table->timestamps();

            $table->unique(['disciplina_id', 'slug']);
            $table->index('disciplina_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assuntos');
        Schema::dropIfExists('disciplinas');
    }
};
