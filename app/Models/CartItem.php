<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'order_item_id',
        'gift_card_id',
        'quantity'
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function orderItem()
    {
        return $this->hasOne(OrderItem::class);
    }

    public function giftCard()
    {
        return $this->hasOne(GiftCard::class);
    }
}
