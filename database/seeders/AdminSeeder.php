<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@sispsc.local'],
            [
                'name'     => 'Administrador',
                'username' => 'admin',
                'email'    => 'admin@sispsc.local',
                'password' => Hash::make('Admin@2024!'),
                'role'     => 'admin',
                'ativo'    => true,
            ]
        );
    }
}
