<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Juan', 'email' => 'juan@example.com', 'password' => 'secreto123'],
            ['name' => 'Gabriel', 'email' => 'gabyfedejose2000@gmail.com', 'password' => 'Gaiman123'],
            ['name' => 'María', 'email' => 'maria@example.com', 'password' => 'mariapass'],
            ['name' => 'Carlos', 'email' => 'carlos@example.com', 'password' => 'carlospass'],
            ['name' => 'Carlos', 'email' => 'carlos2@example.com', 'password' => 'carlospass'],

        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make($user['password']),
                ]
            );
        }
    }
}
