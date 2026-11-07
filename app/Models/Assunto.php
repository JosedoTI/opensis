<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Assunto extends Model
{
    protected $fillable = [
        'disciplina_id',
        'nome',
        'slug',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($assunto) {
            if (empty($assunto->slug)) {
                $assunto->slug = Str::slug($assunto->nome);
            }
        });
    }

    public function disciplina(): BelongsTo
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function questoes(): HasMany
    {
        return $this->hasMany(Questao::class);
    }
}
