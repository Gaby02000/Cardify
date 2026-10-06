<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Borra las órdenes anteriores al 27/08/2026: son datos de prueba del seeder
 * (estados viejos en inglés, sin items) que ensucian el panel y la numeración.
 *
 * Los order_items asociados se eliminan solos: la FK order_items.order_id
 * está declarada ON DELETE CASCADE.
 *
 * Irreversible: no se guarda copia de las filas borradas.
 */
return new class extends Migration
{
    private const CUTOFF = '2026-08-27 00:00:00';

    public function up(): void
    {
        DB::table('orders')->where('created_at', '<', self::CUTOFF)->delete();
    }

    public function down(): void
    {
        // No se puede deshacer: las órdenes borradas no se conservan.
    }
};
