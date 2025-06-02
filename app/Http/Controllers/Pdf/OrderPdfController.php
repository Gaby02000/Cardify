<?php

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderPdfController extends Controller
{
    public function download(Order $order)
    {
        // Cargamos la vista 'pdf.invoice' con la orden
        $pdf = Pdf::loadView('pdf.invoice', ['order' => $order])
                  ->setPaper('a4'); // opcional: tamaño y orientación

        // ‘stream’ para ver en navegador, ‘download’ para descarga directa
        return $pdf->download("orden-{$order->id}.pdf");
    }
}