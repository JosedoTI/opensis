<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            InstituicaoSeeder::class,
            DisciplinaSeeder::class,
            ProvaSeeder::class,
            // DocumentoSeeder::class,  // Executar manualmente: php artisan db:seed --class=DocumentoSeeder
            // O DocumentoSeeder exibe instruções sobre como cadastrar documentos reais do Drive.
            // Não cria dados falsos, por isso é opcional no seed inicial.
        ]);
    }
}
