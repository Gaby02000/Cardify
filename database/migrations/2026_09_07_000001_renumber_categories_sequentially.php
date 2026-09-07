<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Compacta los IDs de `categories` a 1..N (quedaron huecos por categorías
 * borradas). Arrastra el cambio a `gift_cards.id_category` y, en Postgres,
 * reajusta la secuencia. Cambia las URLs públicas /categoria/:id.
 *
 * Irreversible: no se guardan los IDs originales.
 */
return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        DB::transaction(function () use ($driver) {
            $ids = DB::table('categories')->orderBy('id')->pluck('id')->all();

            if ($ids === []) {
                return;
            }

            // old id => new id (1..N, respetando el orden actual)
            $map = [];
            foreach ($ids as $i => $old) {
                $map[(int) $old] = $i + 1;
            }

            // Ya está 1..N: no hay nada que hacer.
            if (! array_filter($map, fn ($new, $old) => $new !== $old, ARRAY_FILTER_USE_BOTH)) {
                return;
            }

            $orphans = DB::table('gift_cards')
                ->whereNotIn('id_category', fn ($q) => $q->select('id')->from('categories'))
                ->count();

            if ($orphans > 0) {
                throw new RuntimeException(
                    "Hay {$orphans} gift card(s) con id_category inexistente. Corregilas antes de re-enumerar."
                );
            }

            if ($driver === 'pgsql') {
                DB::statement('ALTER TABLE gift_cards DROP CONSTRAINT IF EXISTS gift_cards_id_category_foreign');
            } elseif ($driver === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = OFF');
            }

            // Corre todo fuera del rango final para no chocar PKs durante el remapeo.
            $offset = ((int) max($ids)) + 100000;

            DB::table('categories')->update(['id' => DB::raw("id + {$offset}")]);
            DB::table('gift_cards')
                ->whereNotNull('id_category')
                ->update(['id_category' => DB::raw("id_category + {$offset}")]);

            foreach ($map as $old => $new) {
                DB::table('categories')->where('id', $old + $offset)->update(['id' => $new]);
                DB::table('gift_cards')->where('id_category', $old + $offset)->update(['id_category' => $new]);
            }

            if ($driver === 'pgsql') {
                DB::statement('ALTER TABLE gift_cards ADD CONSTRAINT gift_cards_id_category_foreign FOREIGN KEY (id_category) REFERENCES categories (id)');
                DB::statement("SELECT setval(pg_get_serial_sequence('categories', 'id'), (SELECT COALESCE(MAX(id), 1) FROM categories))");
            } elseif ($driver === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = ON');
            }
        });
    }

    public function down(): void
    {
        // No se puede: los IDs originales no se conservan.
    }
};
