<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Documento extends Model
{
    protected $fillable = [
        'prova_id',
        'tipo',
        'nome_arquivo',
        'caminho',
        'url_externa',
        'mime_type',
        'tamanho_bytes',
        'total_paginas',
        'texto_extraivel',
        'observacoes',
        'ativo',
    ];

    protected $casts = [
        'texto_extraivel' => 'boolean',
        'ativo'           => 'boolean',
        'tamanho_bytes'   => 'integer',
        'total_paginas'   => 'integer',
    ];

    // ─── Relacionamentos ──────────────────────────────────────────────────

    public function prova(): BelongsTo
    {
        return $this->belongsTo(Prova::class);
    }

    public function fontes(): HasMany
    {
        return $this->hasMany(FonteDocumento::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────

    public function getUrlAcessoAttribute(): ?string
    {
        if ($this->caminho) {
            return Storage::url($this->caminho);
        }
        return $this->url_externa;
    }

    public function getTamanhoFormatadoAttribute(): string
    {
        if (!$this->tamanho_bytes) return '—';
        $kb = $this->tamanho_bytes / 1024;
        if ($kb < 1024) return round($kb, 1) . ' KB';
        return round($kb / 1024, 1) . ' MB';
    }
}
