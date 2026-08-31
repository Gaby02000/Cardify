<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecuta los seeders en orden para respetar relaciones y dependencias
        $this->call([
            UserSeeder::class,
            UserClientSeeder::class,
            CategorySeeder::class,
            GiftCardSeeder::class,
            CartSeeder::class,
            CartItemSeeder::class, 
            OrderSeeder::class,
            OrderItemSeeder::class,
        ]);

        // Crear usuario de prueba adicional con factory si quieres
        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
