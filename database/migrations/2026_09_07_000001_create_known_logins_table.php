<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('known_logins', function (Blueprint $table) {
            $table->id();
            // A quién pertenece el inicio de sesión: App\Models\User (panel) o
            // App\Models\UserClient (tienda).
            $table->string('notifiable_type');
            $table->unsignedBigInteger('notifiable_id');
            // sha256(tipo | id | ip | user agent): identifica "este dispositivo
            // desde esta IP" para no volver a avisar del mismo acceso.
            $table->string('fingerprint', 64)->unique();
            $table->string('ip', 45)->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();

            $table->index(['notifiable_type', 'notifiable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('known_logins');
    }
};
