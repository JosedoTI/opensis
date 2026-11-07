<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fontes_documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('documento_id')->constrained('documentos')->cascadeOnDelete();
            // Descrição da fonte, ex: "Site oficial da UEA", "Google Drive institucional"
            $table->string('descricao');
            $table->string('url', 1000)->nullable();
            // Data em que o documento foi obtido da fonte
            $table->date('data_obtencao')->nullable();
            // Verificado manualmente por um administrador
            $table->boolean('verificado')->default(false);
            $table->foreignId('verificado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verificado_em')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fontes_documentos');
    }
};
