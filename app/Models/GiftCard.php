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
        'image',
        'stock'
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
}
