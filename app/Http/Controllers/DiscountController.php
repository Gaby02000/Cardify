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
        $target = null; // qué nombrar en la notificación

        if ($data['scope'] === 'category') {
            if (empty($data['category_id'])) {
                return back()->with('error', 'Elegí una categoría.');
            }
            $query->where('id_category', $data['category_id']);
            $target = optional(Category::find($data['category_id']))->name;
        } else {
            if (empty($data['gift_card_ids'])) {
                return back()->with('error', 'Seleccioná al menos una tarjeta.');
            }
            $query->whereIn('id', $data['gift_card_ids']);
            if (count($data['gift_card_ids']) === 1) {
                $target = optional(GiftCard::find($data['gift_card_ids'][0]))->title;
            }
        }

        $count = $query->update(['discount_percent' => $data['percent']]);

        $message = sprintf(
            'Descuento del %d%% aplicado a %d %s.',
            $data['percent'],
            $count,
            $count === 1 ? 'tarjeta' : 'tarjetas',
        );

        if ($request->boolean('notify') && $count > 0) {
            $message .= $this->notifyDiscount($push, $data['percent'], $count, $target);
        }

        return back()->with('success', $message);
    }

    /**
     * Avisa a los suscriptores que hay una nueva promoción de precio.
     */
    private function notifyDiscount(WebPushService $push, int $percent, int $count, ?string $target): string
    {
        $detail = $target ?: ($count === 1 ? '1 tarjeta' : "{$count} tarjetas");

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
