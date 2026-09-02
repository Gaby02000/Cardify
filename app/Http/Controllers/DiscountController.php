<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\GiftCard;
use App\Services\WebPushService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DiscountController extends Controller
{
    public function index(): View
    {
        return view('discounts.index', [
            'giftcards'  => GiftCard::with('category')->orderBy('title')->get(),
            'categories' => Category::withCount('giftCards')->orderBy('name')->get(),
            'active'     => GiftCard::with('category')
                ->where('discount_percent', '>', 0)
                ->orderBy('title')
                ->get(),
        ]);
    }

    /**
     * Aplica un mismo % de descuento a las tarjetas seleccionadas o a toda una
     * categoría.
     */
    public function store(Request $request, WebPushService $push): RedirectResponse
    {
        $data = $request->validate([
            'scope'           => ['required', 'in:cards,category'],
            'percent'         => ['required', 'integer', 'min:1', 'max:95'],
            'gift_card_ids'   => ['array'],
            'gift_card_ids.*' => ['integer', 'exists:gift_cards,id'],
            'category_id'     => ['nullable', 'integer', 'exists:categories,id'],
            'notify'          => ['nullable', 'boolean'],
        ]);

        $query = GiftCard::query();
        $categoryName = null;

        if ($data['scope'] === 'category') {
            if (empty($data['category_id'])) {
                return back()->with('error', 'Elegí una categoría.');
            }
            $query->where('id_category', $data['category_id']);
            $categoryName = optional(Category::find($data['category_id']))->name;
        } else {
            if (empty($data['gift_card_ids'])) {
                return back()->with('error', 'Seleccioná al menos una tarjeta.');
            }
            $query->whereIn('id', $data['gift_card_ids']);
        }

        // Títulos de las tarjetas afectadas (para nombrarlas en la notificación),
        // tomados antes de actualizar. Alcanza con los primeros para el texto.
        $titles = (clone $query)->orderBy('title')->limit(3)->pluck('title')->all();

        $count = $query->update(['discount_percent' => $data['percent']]);

        $message = sprintf(
            'Descuento del %d%% aplicado a %d %s.',
            $data['percent'],
            $count,
            $count === 1 ? 'tarjeta' : 'tarjetas',
        );

        if ($request->boolean('notify') && $count > 0) {
            $message .= $this->notifyDiscount($push, $data['percent'], $titles, $count, $categoryName);
        }

        return back()->with('success', $message);
    }

    /**
     * Avisa a los suscriptores que hay una nueva promoción de precio,
     * nombrando la(s) tarjeta(s) o la categoría afectada.
     */
    private function notifyDiscount(
        WebPushService $push,
        int $percent,
        array $titles,
        int $count,
        ?string $categoryName,
    ): string {
        if ($categoryName && $count > 1) {
            $detail = "las gift cards de {$categoryName}";
        } elseif ($count === 1 && isset($titles[0])) {
            $detail = "«{$titles[0]}»";
        } elseif ($count > 0 && $count <= count($titles)) {
            $detail = $this->joinTitles(array_slice($titles, 0, $count));
        } elseif ($count > 0 && $titles !== []) {
            $quoted = array_map(fn ($t) => "«{$t}»", $titles);
            $detail = implode(', ', $quoted) . ' y ' . ($count - count($titles)) . ' más';
        } else {
            $detail = $count === 1 ? 'una gift card' : "{$count} gift cards";
        }

        try {
            $result = $push->send([
                'title' => "{$percent}% OFF en Cardify",
                'body'  => "Aprovechá {$percent}% de descuento en {$detail}.",
                'url'   => '/',
                'tag'   => 'cardify-descuento',
            ]);
        } catch (\Throwable $e) {
            return ' No se pudo enviar la notificación: '.$e->getMessage();
        }

        return sprintf(
            ' Notificación enviada a %d %s.',
            $result['ok'],
            $result['ok'] === 1 ? 'dispositivo' : 'dispositivos',
        );
    }

    /**
     * "«A»" · "«A» y «B»" · "«A», «B» y «C»"
     */
    private function joinTitles(array $titles): string
    {
        $quoted = array_map(fn ($t) => "«{$t}»", $titles);

        if (count($quoted) <= 1) {
            return $quoted[0] ?? '';
        }

        $last = array_pop($quoted);

        return implode(', ', $quoted) . ' y ' . $last;
    }

    /**
     * Quita el descuento de las tarjetas seleccionadas (o de todas).
     */
    public function clear(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'gift_card_ids'   => ['array'],
            'gift_card_ids.*' => ['integer', 'exists:gift_cards,id'],
            'all'             => ['nullable', 'boolean'],
        ]);

        $query = GiftCard::query()->where('discount_percent', '>', 0);

        if (! $request->boolean('all')) {
            if (empty($data['gift_card_ids'])) {
                return back()->with('error', 'No hay tarjetas seleccionadas.');
            }
            $query->whereIn('id', $data['gift_card_ids']);
        }

        $count = $query->update(['discount_percent' => null]);

        return back()->with('success', sprintf(
            'Descuento quitado de %d %s.',
            $count,
            $count === 1 ? 'tarjeta' : 'tarjetas',
        ));
    }
}
