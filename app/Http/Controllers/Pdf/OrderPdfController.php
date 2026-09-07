<?php

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

/**
 * Factura de una orden para el panel administrativo.
 *
 * El middleware se declara acá además de en la ruta: el PDF expone datos del
 * cliente (nombre, qué compró, cuánto pagó) y los ids de orden son
 * correlativos, así que si la ruta queda fuera del grupo 'auth' cualquiera
 * podría enumerarlas. Con esto el controlador se protege solo.
 *
 * El cliente NO usa este endpoint: tiene el suyo en
 * OrderApiController::receipt(), que va por número correlativo por usuario.
 */
class OrderPdfController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return ['auth'];
    }

    public function download(Order $order)
    {
        $order->load(['orderItems.giftCard', 'user']);

        $pdf = Pdf::loadView('pdf.invoice', ['order' => $order])
                  ->setPaper('a4');

        return $pdf->download("orden-{$order->id}.pdf");
    }
}
