<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GabaritoOficial extends Model
{
    protected $table = 'gabaritos_oficiais';

    protected $fillable = [
        'questao_id',
        'alternativa_id',
        'resposta_correta',
        'fonte_url',
        'fonte_descricao',
        'data_publicacao',
        'cadastrado_por',
    ];

    protected $casts = [
        'data_publicacao' => 'date',
    ];

    public function questao(): BelongsTo
    {
        return $this->belongsTo(Questao::class);
    }

    public function alternativa(): BelongsTo
    {
        return $this->belongsTo(Alternativa::class);
    }

    public function cadastradoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cadastrado_por');
    }
}
