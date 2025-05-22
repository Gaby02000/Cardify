<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GiftCard;
use App\Models\Category;

class GiftCardSeeder extends Seeder
{
    private function randomGiftCardName()
    {
        $adjectives = ['Super', 'Mega', 'Ultra', 'Premium', 'Express', 'Legendary', 'Epic', 'Power', 'Turbo', 'Exclusive'];
        $items = ['Pack', 'Bundle', 'Card', 'Gift', 'Voucher', 'Token', 'Pass', 'Credit', 'Coupon', 'Bonus'];
        $games = ['Valorant', 'CSGO', 'LoL', 'TFT', 'Steam', 'Epic Games', 'PlayStation', 'Xbox', 'Nintendo', 'Google Play'];

        $adj = $adjectives[array_rand($adjectives)];
        $item = $items[array_rand($items)];
        $game = $games[array_rand($games)];

        return "$adj $game $item";
    }

    public function run(): void
    {
        $categoriesCount = Category::count();

        for ($i = 1; $i <= 50; $i++) {
            GiftCard::create([
                'id_category' => rand(1, $categoriesCount),
                'title' => $this->randomGiftCardName() . " #$i",
                'description' => "Giftcard genérica número $i para categoría #" . rand(1, $categoriesCount),
                'amount' => rand(1000, 10000),
                'price' => rand(900, 9500),
                'image' => 'images/giftcards/giftcard_default.jpg',
                'stock' => rand(1, 50),
            ]);

            // Muestra en consola el progreso cada 100 registros
            if ($i % 100 == 0) {
                echo "Se han creado $i giftcards\n";
            }
        }
    }
}
