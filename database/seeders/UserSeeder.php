<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Juan',
            'email' => 'juan@example.com',
            'password' => Hash::make('secreto123'),
        ]);
        User::create([
            'name' => 'Gabriel',
            'email' => 'gabyfedejose2000@gmail.com',
            'password' => Hash::make('123'),
        ]);
    }
}
