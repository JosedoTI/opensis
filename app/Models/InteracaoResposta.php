<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InteracaoResposta extends Model
{
    protected $table = 'interacoes_respostas';

    protected $fillable = [
        'resposta_id',
        'user_id',
        'tipo',
    ];

    public function resposta(): BelongsTo
    {
        return $this->belongsTo(RespostaComunidade::class, 'resposta_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
