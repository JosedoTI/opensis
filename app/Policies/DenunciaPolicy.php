<?php

namespace App\Policies;

use App\Models\Denuncia;
use App\Models\User;

class DenunciaPolicy
{
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

    public function view(User $user, Denuncia $denuncia): bool
    {
        return $user->id === $denuncia->user_id;
    }

    public function analisar(User $user): bool
    {
        return $user->isAdmin();
    }
}
