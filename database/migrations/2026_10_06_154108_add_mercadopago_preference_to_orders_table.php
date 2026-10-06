<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Preferencia de Checkout Pro de la orden. Se guarda para que, si el
            // cliente vuelve a tocar "Pagar" sin haber pagado, reciba el mismo
            // link en vez de generar otra orden pendiente.
            $table->string('mp_preference_id')->nullable()->after('codes');
            $table->text('init_point')->nullable()->after('mp_preference_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['mp_preference_id', 'init_point']);
        });
    }
};