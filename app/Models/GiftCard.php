<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCard extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'id_category',
        'title',
        'description',
        'amount',
        'price',
        'discount_percent',
        'image',
        'stock'
    ];

    protected $casts = [
        'discount_percent' => 'integer',
    ];

    // Se exponen en el JSON de la API para que el frontend muestre el precio ya con descuento.
    protected $appends = [
        'has_discount',
        'final_price',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, "id_category");
    }

    public function cartItems()
    {
        return $this->belongsToMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'gift_card_id');
    }

    public function getHasDiscountAttribute(): bool
    {
        return $this->discount_percent !== null && $this->discount_percent > 0;
    }

    /**
     * Precio a cobrar: el de lista menos el descuento activo. Devuelve un string
     * con el mismo formato "1234.56" que el cast decimal de `price`.
     */
    public function getFinalPriceAttribute(): string
    {
        if (! $this->has_discount) {
            return (string) $this->price;
        }

        $final = round((float) $this->price * (100 - $this->discount_percent) / 100, 2);

        return number_format(max(0, $final), 2, '.', '');
    }
}
