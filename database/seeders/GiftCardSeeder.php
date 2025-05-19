<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GiftCard;

class GiftCardSeeder extends Seeder
{
    public function run(): void
    {
        $giftcards = [
            [
                'id_category' => 1, // Counter Strike
                'title' => 'Counter Strike Gift Card',
                'description' => 'Abre cajas sin límites y compra skins.',
                'amount' => 3000,
                'price' => 2900,
                'image' => 'images/giftcards/giftcard_counterstrike.jpg',
                'stock' => 15
            ],
            [
                'id_category' => 2, // Valorant
                'title' => 'Valorant Points',
                'description' => 'Compra skins y pases de batalla.',
                'amount' => 2000,
                'price' => 1900,
                'image' => 'images/giftcards/giftcard_valorant.jpg',
                'stock' => 25
            ],
            [
                'id_category' => 3, // League of Legends
                'title' => 'League of Legends RP',
                'description' => 'Disfrutá tus campeones y skins favoritas.',
                'amount' => 5000,
                'price' => 4900,
                'image' => 'images/giftcards/giftcard_leagueoflegends.jpg',
                'stock' => 20
            ],
            [
                'id_category' => 4, // Teamfight Tactics
                'title' => 'Teamfight Tactics Gold',
                'description' => 'Compra unidades y mejoras para tu equipo.',
                'amount' => 1500,
                'price' => 1400,
                'image' => 'images/giftcards/giftcard_tft.jpg',
                'stock' => 10
            ],
            [
                'id_category' => 5, // Steam
                'title' => 'Steam Wallet Gift Card',
                'description' => 'Para comprar juegos y DLC en Steam.',
                'amount' => 10000,
                'price' => 9500,
                'image' => 'images/giftcards/giftcard_steam.jpg',
                'stock' => 30
            ],
            [
                'id_category' => 6, // Epic Games
                'title' => 'Epic Games Gift Card',
                'description' => 'Compra juegos y contenido adicional.',
                'amount' => 5000,
                'price' => 4800,
                'image' => 'images/giftcards/giftcard_epicgames.jpg',
                'stock' => 18
            ],
            [
                'id_category' => 7, // PlayStation
                'title' => 'PlayStation Store Card',
                'description' => 'Compra juegos, DLC y suscripciones.',
                'amount' => 2000,
                'price' => 1900,
                'image' => 'images/giftcards/giftcard_playstation.jpg',
                'stock' => 22
            ],
            [
                'id_category' => 8, // Xbox
                'title' => 'Xbox Gift Card',
                'description' => 'Para juegos y suscripciones Xbox.',
                'amount' => 2500,
                'price' => 2400,
                'image' => 'images/giftcards/giftcard_xbox.jpg',
                'stock' => 15
            ],
            [
                'id_category' => 9, // Nintendo
                'title' => 'Nintendo eShop Card',
                'description' => 'Compra juegos para Nintendo Switch.',
                'amount' => 3500,
                'price' => 3400,
                'image' => 'images/giftcards/giftcard_nintendo.jpg',
                'stock' => 14
            ],
            [
                'id_category' => 10, // Google Play
                'title' => 'Google Play Gift Card',
                'description' => 'Compra apps, juegos y contenido en Google Play.',
                'amount' => 1500,
                'price' => 1450,
                'image' => 'images/giftcards/giftcard_googleplay.jpg',
                'stock' => 40
            ],
            [
                'id_category' => 11, // Apple Store
                'title' => 'Apple Store Gift Card',
                'description' => 'Compra apps, música y más en Apple Store.',
                'amount' => 2000,
                'price' => 1950,
                'image' => 'images/giftcards/giftcard_apple.jpg',
                'stock' => 25
            ],
            [
                'id_category' => 12, // Amazon
                'title' => 'Amazon Gift Card',
                'description' => 'Compra productos en Amazon.',
                'amount' => 10000,
                'price' => 9800,
                'image' => 'images/giftcards/giftcard_amazon.jpg',
                'stock' => 50
            ],
            [
                'id_category' => 13, // Netflix
                'title' => 'Netflix Gift Card',
                'description' => 'Suscripción mensual a Netflix.',
                'amount' => 3000,
                'price' => 2900,
                'image' => 'images/giftcards/giftcard_netflix.jpg',
                'stock' => 35
            ],
            [
                'id_category' => 14, // Spotify
                'title' => 'Spotify Premium Gift Card',
                'description' => 'Suscripción premium sin anuncios.',
                'amount' => 1500,
                'price' => 1450,
                'image' => 'images/giftcards/giftcard_spotify.jpg',
                'stock' => 30
            ],
        ];

        foreach ($giftcards as $giftcard) {
            GiftCard::updateOrCreate(
                ['title' => $giftcard['title']], 
                $giftcard
            );
        }
    }
}
