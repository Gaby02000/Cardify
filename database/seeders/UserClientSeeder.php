<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserClient;
use Illuminate\Support\Facades\Hash;

class UserClientSeeder extends Seeder {
    public function run(): void {
        UserClient::truncate(); // ⚠️ Importante para evitar IDs viejos rotos
        for ($i = 1; $i <= 5; $i++) {
            UserClient::create([
                'name' => "Cliente $i",
                'email' => "cliente$i@example.com",
                'password' => Hash::make("password$i"),
            ]);
        }
    }
}
