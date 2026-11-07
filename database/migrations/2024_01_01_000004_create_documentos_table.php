<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prova_id')->constrained('provas')->cascadeOnDelete();
            // prova, gabarito, resolucao, edital, outro
            $table->enum('tipo', ['prova', 'gabarito', 'resolucao', 'edital', 'outro'])->default('prova');
            $table->string('nome_arquivo');
            // Caminho no storage local (storage/app/public/documentos/...)
            $table->string('caminho')->nullable();
            // URL externa (Google Drive, site oficial, etc.)
            $table->string('url_externa', 1000)->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('tamanho_bytes')->nullable();
            $table->unsignedSmallInteger('total_paginas')->nullable();
            // Se o PDF permite extração de texto
            $table->boolean('texto_extraivel')->nullable();
            $table->text('observacoes')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();

            $table->index(['prova_id', 'tipo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
