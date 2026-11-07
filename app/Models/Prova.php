<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prova extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'instituicao_id',
        'ano',
        'etapa',
        'nome',
        'tipo',
        'total_questoes',
        'questoes_individualizadas',
        'gabarito_disponivel',
        'resolucao_disponivel',
        'ativa',
    ];

    protected $casts = [
        'ano'                       => 'integer',
        'total_questoes'            => 'integer',
        'questoes_individualizadas' => 'boolean',
        'gabarito_disponivel'       => 'boolean',
        'resolucao_disponivel'      => 'boolean',
        'ativa'                     => 'boolean',
    ];

    // ─── Relacionamentos ──────────────────────────────────────────────────

    public function instituicao(): BelongsTo
    {
        return $this->belongsTo(Instituicao::class);
    }

    public function questoes(): HasMany
    {
        return $this->hasMany(Questao::class)->orderBy('numero');
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(Documento::class);
    }

    public function documentoProva()
    {
        return $this->documentos()->where('tipo', 'prova')->where('ativo', true)->first();
    }

    // ─── Scopes ───────────────────────────────────────────────────────────

    public function scopeAtiva($query)
    {
        return $query->where('ativa', true);
    }

    public function scopePorInstituicao($query, int $instituicaoId)
    {
        return $query->where('instituicao_id', $instituicaoId);
    }

    public function scopePorAno($query, int $ano)
    {
        return $query->where('ano', $ano);
    }
}
