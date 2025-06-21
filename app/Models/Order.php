<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_client_id',
        'cart_id',
        'total_price',
        'status',
        'created_at',
    ];

    public $timestamps = true;//se maneja a manopla el tiempo

    public function user()
    {
        return $this->belongsTo(UserClient::class, 'user_client_id');
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
