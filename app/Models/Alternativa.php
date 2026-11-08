<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alternativa extends Model
{
    use HasFactory;

    protected $fillable = [
        'questao_id',
        'letra',
        'texto',
        'imagem',
        'imagem_alt',
        'ativa',
    ];

    protected $casts = [
        'ativa' => 'boolean',
    ];

    public function questao(): BelongsTo
    {
        return $this->belongsTo(Questao::class);
    }
}
