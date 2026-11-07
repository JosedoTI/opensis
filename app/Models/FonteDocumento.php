<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FonteDocumento extends Model
{
    protected $table = 'fontes_documentos';

    protected $fillable = [
        'documento_id',
        'descricao',
        'url',
        'data_obtencao',
        'verificado',
        'verificado_por',
        'verificado_em',
        'observacoes',
    ];

    protected $casts = [
        'verificado'    => 'boolean',
        'data_obtencao' => 'date',
        'verificado_em' => 'datetime',
    ];

    public function documento(): BelongsTo
    {
        return $this->belongsTo(Documento::class);
    }

    public function verificador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verificado_por');
    }
}
