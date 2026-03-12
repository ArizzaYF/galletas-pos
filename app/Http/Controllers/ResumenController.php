<?php

namespace App\Http\Controllers;

use App\Models\{Venta, DetalleVenta};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResumenController extends Controller
{
    public function index(Request $request)
    {
        $fecha = $request->get('fecha', today()->toDateString());

        $ventas = Venta::with(['cliente', 'detalles.producto'])
            ->whereDate('created_at', $fecha)
            ->get();

        $porProducto = DetalleVenta::with('producto')
            ->whereHas('venta', fn($q) => $q->whereDate('created_at', $fecha))
            ->select('producto_id',
                     DB::raw('SUM(cantidad) as total_cantidad'),
                     DB::raw('SUM(subtotal) as total_monto'))
            ->groupBy('producto_id')
            ->get();

        $porFormaPago = Venta::whereDate('created_at', $fecha)
            ->where('estado', 'pagado')
            ->select('forma_pago',
                     DB::raw('COUNT(*) as total_ventas'),
                     DB::raw('SUM(total) as total_monto'))
            ->groupBy('forma_pago')
            ->get();

        $totalDia       = $ventas->where('estado', 'pagado')->sum('total');
        $totalPendiente = $ventas->where('estado', 'pendiente')->sum('total');
        $cantidadVentas = $ventas->count();

        return view('resumen.index', compact(
            'fecha', 'ventas', 'porProducto',
            'porFormaPago', 'totalDia', 'totalPendiente', 'cantidadVentas'
        ));
    }
}