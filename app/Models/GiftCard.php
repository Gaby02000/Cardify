<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCard extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'id_category',
        'title',
        'description',
        'amount',
        'price',
        'discount_percent',
        'image',
        'stock',
        'is_active',
    ];

    protected $casts = [
        'discount_percent' => 'integer',
        'is_active' => 'boolean',
    ];

    // Se exponen en el JSON de la API para que el frontend muestre el precio ya con descuento.
    protected $appends = [
        'has_discount',
        'final_price',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, "id_category");
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function cartItems()
    {
        return $this->belongsToMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'gift_card_id');
    }

    /**
     * URL de la imagen con transformaciones de Cloudinary.
     *
     * En la base queda la URL del archivo original que subió el admin (hasta
     * 2 MB), y el panel la mostraba tal cual en miniaturas de 48px. Cloudinary
     * aplica las transformaciones que se le pasen en el path, entre "/upload/"
     * y el resto: f_auto elige el formato según el navegador, q_auto ajusta la
     * calidad y c_fill recorta al recuadro pedido.
     *
     * Si la imagen no es de Cloudinary (o no hay), devuelve el valor original
     * sin tocarlo.
     */
    public function imageUrl(?int $width = null, ?int $height = null): ?string
    {
        $url = $this->image;

        if (! $url || ! preg_match('#^(https?://res\.cloudinary\.com/[^/]+/image/upload)/(.+)$#', $url, $m)) {
            return $url;
        }

        [, $base, $resto] = $m;

        // Ya tiene transformaciones aplicadas: no encadenamos otras.
        if (preg_match('#^[a-z]{1,3}_[^/]*/#', $resto)) {
            return $url;
        }

        $partes = ['f_auto', 'q_auto'];
        if ($width) {
            $partes[] = 'c_fill';
            $partes[] = "w_{$width}";
        }
        if ($height) {
            $partes[] = "h_{$height}";
        }

        return $base . '/' . implode(',', $partes) . '/' . $resto;
    }

    public function getHasDiscountAttribute(): bool
    {
        return $this->discount_percent !== null && $this->discount_percent > 0;
    }

    /**
     * Precio a cobrar: el de lista menos el descuento activo. Devuelve un string
     * con el mismo formato "1234.56" que el cast decimal de `price`.
     */
    public function getFinalPriceAttribute(): string
    {
        if (! $this->has_discount) {
            return (string) $this->price;
        }

        $final = round((float) $this->price * (100 - $this->discount_percent) / 100, 2);

        return number_format(max(0, $final), 2, '.', '');
    }
}
