<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Questao extends Model
{
    use SoftDeletes;

    protected $table = 'questoes';

    protected $fillable = [
        'prova_id',
        'disciplina_id',
        'assunto_id',
        'numero',
        'enunciado',
        'pagina_documento',
        'documento_id',
        'imagem',
        'imagem_alt',
        'conteudo_complementar',
        'status',
        'observacoes',
        'ativa',
    ];

    protected $casts = [
        'numero'            => 'integer',
        'pagina_documento'  => 'integer',
        'ativa'             => 'boolean',
    ];

    // ─── Relacionamentos ──────────────────────────────────────────────────

    public function prova(): BelongsTo
    {
        return $this->belongsTo(Prova::class);
    }

    public function disciplina(): BelongsTo
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function assunto(): BelongsTo
    {
        return $this->belongsTo(Assunto::class);
    }

    public function documento(): BelongsTo
    {
        return $this->belongsTo(Documento::class);
    }

    public function alternativas(): HasMany
    {
        return $this->hasMany(Alternativa::class)->orderBy('letra');
    }

    public function gabaritoOficial(): HasOne
    {
        return $this->hasOne(GabaritoOficial::class);
    }

    public function resolucaoOficial(): HasOne
    {
        return $this->hasOne(ResolucaoOficial::class);
    }

    public function respostasComunidade(): HasMany
    {
        return $this->hasMany(RespostaComunidade::class)
            ->where('oculta', false)
            ->orderByDesc('votos_util')
            ->orderBy('created_at');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────

    public function scopeAtiva($query)
    {
        return $query->where('ativa', true);
    }

    public function scopeCompleta($query)
    {
        return $query->where('status', 'completa');
    }
}
