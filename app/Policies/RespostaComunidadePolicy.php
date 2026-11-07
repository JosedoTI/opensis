<?php

namespace App\Policies;

use App\Models\RespostaComunidade;
use App\Models\User;

class RespostaComunidadePolicy
{
    /**
     * Admins podem tudo.
     */
    public function before(User $user): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        return null;
    }

    public function create(User $user): bool
    {
        return $user->ativo;
    }

    public function update(User $user, RespostaComunidade $resposta): bool
    {
        return $user->id === $resposta->user_id && $user->ativo;
    }

    public function delete(User $user, RespostaComunidade $resposta): bool
    {
        return $user->id === $resposta->user_id && $user->ativo;
    }

    public function ocultar(User $user): bool
    {
        return $user->isAdmin();
    }
}
