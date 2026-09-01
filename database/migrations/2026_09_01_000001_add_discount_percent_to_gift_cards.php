<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gift_cards', function (Blueprint $table) {
            // % de descuento activo (1-95). null = sin promoción.
            $table->unsignedTinyInteger('discount_percent')->nullable()->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('gift_cards', function (Blueprint $table) {
            $table->dropColumn('discount_percent');
        });
    }
};
