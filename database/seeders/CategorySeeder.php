<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Counter Strike'],
            ['name' => 'Valorant'],
            ['name' => 'League of Legends'],      
            ['name' => 'Teamfight Tactics'],
            ['name' => 'Steam'],
            ['name' => 'Epic Games'],
            ['name' => 'PlayStation'],
            ['name' => 'Xbox'],
            ['name' => 'Nintendo'],
            ['name' => 'Google Play'],
            ['name' => 'Apple Store'],
            ['name' => 'Amazon'],
            ['name' => 'Netflix'],
            ['name' => 'Spotify'],
            ['name' => 'YouTube Premium'],
            ['name' => 'Disney+'],
            ['name' => 'HBO Max'],
            ['name' => 'Paramount+'],
            ['name' => 'Hulu'],
            ['name' => 'Twitch'],
            ['name' => 'TikTok'],
            ['name' => 'Snapchat'],
            ['name' => 'Instagram'],
            ['name' => 'Facebook'],
            ['name' => 'Twitter'],
            ['name' => 'LinkedIn'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['name' => $category['name']]);
        }
    }
}
