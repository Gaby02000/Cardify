<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GiftCard;

class GiftCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        GiftCard::create([
            'id_category' => 1, // Asegurate que exista esta categoría en tu tabla categories
            'title' => 'League of Legends Gift Card',
            'description' => 'Disfrutá tus campeones y skins favoritas.',
            'amount' => 5000,
            'price' => 4900,
            'image' => 'images/giftcards/giftcard_leagueoflegends.jpg', // La ruta relativa desde /public
            'stock' => 20
        ]);

        GiftCard::create([
            'id_category' => 1,
            'title' => 'Counter Strike Gift Card',
            'description' => 'Abre cajas sin límites.',
            'amount' => 3000,
            'price' => 2900,
            'image' => 'images/giftcards/giftcard_counterstrike.jpg',
            'stock' => 15
        ]);
    }
}
