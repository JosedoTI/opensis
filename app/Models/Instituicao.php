<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instituicao extends Model
{
    use HasFactory;
    protected $table = 'instituicoes';

    protected $fillable = [
        'sigla',
        'nome',
        'nome_processo',
        'descricao_processo',
        'cidade',
        'estado',
        'logo',
        'cor_primaria',
        'site_oficial',
        'ativa',
    ];

    protected $casts = [
        'ativa' => 'boolean',
    ];

    // ─── Relacionamentos ──────────────────────────────────────────────────

    public function provas(): HasMany
    {
        return $this->hasMany(Prova::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────

    public function scopeAtiva($query)
    {
        return $query->where('ativa', true);
    }
}
