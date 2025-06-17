<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id', // <- para carritos anónimos
    ];

    public function user()
    {
        return $this->belongsTo(UserClient::class, 'user_id');
    }

    public function order() 
    {
        return $this->hasOne(Order::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'cart_id');
    }
}
