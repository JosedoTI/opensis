<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'bio',
        'avatar',
        'ativo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'ativo'             => 'boolean',
        ];
    }

    // ─── Helpers de perfil ────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUsuario(): bool
    {
        return $this->role === 'usuario';
    }

    // ─── Relacionamentos ──────────────────────────────────────────────────

    public function respostas(): HasMany
    {
        return $this->hasMany(RespostaComunidade::class);
    }

    public function interacoes(): HasMany
    {
        return $this->hasMany(InteracaoResposta::class);
    }

    public function denuncias(): HasMany
    {
        return $this->hasMany(Denuncia::class);
    }
}
