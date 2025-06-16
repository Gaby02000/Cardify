<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable;

class UserClient extends Authenticatable 
{
    use HasFactory, Notifiable;

    protected $table = 'user_clients'; 

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function cart()
    {
        return $this->hasOne(Cart::class, 'user_client_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_client_id');
    }
}
