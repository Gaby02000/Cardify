<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Order extends Model
{
    use HasFactory;

    /**
     * Variantes de "pendiente": checkouts sin finalizar. No se listan en el
     * panel ni consumen número correlativo.
     */
    public const PENDING_STATUSES = ['pendiente', 'pending', 'processing', 'in_process'];

    protected $fillable = [
        'user_client_id',
        'cart_id',
        'total_price',
        'status',
        'codes',
        'created_at',
        'mp_preference_id',
        'init_point',
    ];

    protected $casts = [
        'codes' => 'array',
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

    /** Órdenes que el panel lista y numera: todas menos las pendientes. */
    public function scopeNumbered(Builder $query): Builder
    {
        return $query->whereNotIn('status', self::PENDING_STATUSES);
    }

    /**
     * Mapa id => número correlativo, para listados. Una sola consulta y el
     * número no depende de filtros, orden ni paginación: el mismo id siempre
     * muestra el mismo número y la secuencia no salta.
     *
     * @return Collection<int, int>
     */
    public static function panelNumbers(): Collection
    {
        return static::numbered()
            ->orderBy('created_at')
            ->orderBy('id')
            ->pluck('id')
            ->flip()
            ->map(fn ($pos) => $pos + 1);
    }

    /**
     * Número correlativo de esta orden (su posición cronológica entre las no
     * pendientes). null si es pendiente, porque esas no se numeran.
     */
    public function panelNumber(): ?int
    {
        if (in_array($this->status, self::PENDING_STATUSES, true)) {
            return null;
        }

        return static::numbered()
            ->where(fn (Builder $q) => $q
                ->where('created_at', '<', $this->created_at)
                ->orWhere(fn (Builder $q2) => $q2
                    ->where('created_at', $this->created_at)
                    ->where('id', '<=', $this->id)))
            ->count();
    }
}
