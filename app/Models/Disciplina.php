<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Disciplina extends Model
{
    protected $fillable = [
        'nome',
        'slug',
        'cor',
        'icone',
        'ativa',
    ];

    protected $casts = [
        'ativa' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($disciplina) {
            if (empty($disciplina->slug)) {
                $disciplina->slug = Str::slug($disciplina->nome);
            }
        });
    }

    public function assuntos(): HasMany
    {
        return $this->hasMany(Assunto::class);
    }

    public function questoes(): HasMany
    {
        return $this->hasMany(Questao::class);
    }

    public function scopeAtiva($query)
    {
        return $query->where('ativa', true);
    }
}
