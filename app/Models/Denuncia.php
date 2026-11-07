<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Denuncia extends Model
{
    protected $table = 'denuncias';

    protected $fillable = [
        'resposta_id',
        'user_id',
        'motivo',
        'descricao',
        'status',
        'analisada_por',
        'analisada_em',
        'parecer_admin',
    ];

    protected $casts = [
        'analisada_em' => 'datetime',
    ];

    public function resposta(): BelongsTo
    {
        return $this->belongsTo(RespostaComunidade::class, 'resposta_id');
    }

    public function denunciante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function analisadaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'analisada_por');
    }
}
