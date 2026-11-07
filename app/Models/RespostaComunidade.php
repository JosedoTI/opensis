<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RespostaComunidade extends Model
{
    use SoftDeletes;

    protected $table = 'respostas_comunidade';

    protected $fillable = [
        'questao_id',
        'user_id',
        'conteudo',
        'resposta_indicada',
        'votos_util',
        'total_denuncias',
        'oculta',
        'motivo_ocultacao',
        'ocultada_por',
        'ocultada_em',
    ];

    protected $casts = [
        'votos_util'      => 'integer',
        'total_denuncias' => 'integer',
        'oculta'          => 'boolean',
        'ocultada_em'     => 'datetime',
    ];

    // ─── Relacionamentos ──────────────────────────────────────────────────

    public function questao(): BelongsTo
    {
        return $this->belongsTo(Questao::class);
    }

    public function autor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ocultadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ocultada_por');
    }

    public function interacoes(): HasMany
    {
        return $this->hasMany(InteracaoResposta::class, 'resposta_id');
    }

    public function denuncias(): HasMany
    {
        return $this->hasMany(Denuncia::class, 'resposta_id');
    }
}
