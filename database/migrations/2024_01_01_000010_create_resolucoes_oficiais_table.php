<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resolucoes_oficiais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questao_id')->unique()->constrained('questoes')->cascadeOnDelete();
            $table->longText('conteudo');
            $table->string('fonte_url', 1000)->nullable();
            $table->string('fonte_descricao')->nullable();
            $table->date('data_publicacao')->nullable();
            $table->foreignId('cadastrado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('ativa')->default(true);
            $table->timestamps();

            $table->index('questao_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resolucoes_oficiais');
    }
};
