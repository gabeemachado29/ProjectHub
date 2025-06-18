<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Importe a classe Hash

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Apaga os usuários existentes para evitar duplicatas ao rodar o seeder
        User::query()->delete();

        // Cria um usuário Admin
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Senha: password
            'role' => 'admin', // AQUI definimos a função!
        ]);

        // Cria um usuário comum
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'), // Senha: password
            'role' => 'user',
        ]);
    }
}