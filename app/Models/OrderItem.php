<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'cart_item_id',
        'gift_card_id',
        'quantity',
        'price'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }

    public function cartItem()
    {
        return $this->belongsTo(CartItem::class);
    }

    public function giftCard()
    {
        return $this->belongsTo(GiftCard::class, 'gift_card_id');
    }
}
