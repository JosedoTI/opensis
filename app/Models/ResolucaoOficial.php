<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResolucaoOficial extends Model
{
    protected $table = 'resolucoes_oficiais';

    protected $fillable = [
        'questao_id',
        'conteudo',
        'fonte_url',
        'fonte_descricao',
        'data_publicacao',
        'cadastrado_por',
        'ativa',
    ];

    protected $casts = [
        'data_publicacao' => 'date',
        'ativa'           => 'boolean',
    ];

    public function questao(): BelongsTo
    {
        return $this->belongsTo(Questao::class);
    }

    public function cadastradoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cadastrado_por');
    }
}
