<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use App\Services\WebPushService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PromotionController extends Controller
{
    public function index(): View
    {
        return view('promotions.index', [
            'subscribers' => PushSubscription::count(),
        ]);
    }

    public function send(Request $request, WebPushService $push): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:80'],
            'body' => ['required', 'string', 'max:180'],
            'url' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $result = $push->send([
                'title' => $data['title'],
                'body' => $data['body'],
                'url' => $data['url'] ?: '/',
                'tag' => 'cardify-promo',
            ]);
        } catch (\Throwable $e) {
            return back()->with('error', 'No se pudo enviar: '.$e->getMessage());
        }

        return back()->with('success', sprintf(
            'Notificación enviada a %d dispositivos (bajas: %d, fallos: %d).',
            $result['ok'],
            $result['gone'],
            $result['failed'],
        ));
    }
}
