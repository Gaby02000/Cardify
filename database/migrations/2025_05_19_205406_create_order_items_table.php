<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            // Llaves foráneas
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('cart_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('gift_card_id')->constrained()->onDelete('cascade');

            // Otros campos
            $table->integer('quantity');
            $table->decimal('price', 10, 2);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};