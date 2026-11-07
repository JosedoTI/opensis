<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prova_id')->constrained('provas')->restrictOnDelete();
            $table->foreignId('disciplina_id')->nullable()->constrained('disciplinas')->nullOnDelete();
            $table->foreignId('assunto_id')->nullable()->constrained('assuntos')->nullOnDelete();
            // Número da questão dentro da prova (ex: 1, 2, 3...)
            $table->unsignedSmallInteger('numero');
            // Enunciado da questão em texto (pode conter HTML/Markdown para formatação)
            $table->longText('enunciado');
            // Página no documento original
            $table->unsignedSmallInteger('pagina_documento')->nullable();
            // Referência ao documento de origem
            $table->foreignId('documento_id')->nullable()->constrained('documentos')->nullOnDelete();
            // Imagem associada à questão (path no storage)
            $table->string('imagem')->nullable();
            // Texto alternativo da imagem para acessibilidade
            $table->string('imagem_alt')->nullable();
            // Conteúdo complementar (tabelas, fórmulas em LaTeX, etc.)
            $table->longText('conteudo_complementar')->nullable();
            // Status do cadastro
            $table->enum('status', ['completa', 'incompleta', 'revisao'])->default('completa');
            // Observações internas
            $table->text('observacoes')->nullable();
            $table->boolean('ativa')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['prova_id', 'numero']);
            $table->index(['prova_id', 'disciplina_id']);
            $table->index('disciplina_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questoes');
    }
};
