<?php

namespace App\Http\Controllers;

use App\Models\{Venta, Pago};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagoController extends Controller
{
    public function index()
    {
        $ventasPendientes = Venta::with(['cliente', 'detalles.producto', 'pagos'])
            ->pendientes()
            ->orderByDesc('created_at')
            ->get();

        return view('pagos.index', compact('ventasPendientes'));
    }

    public function store(Request $request, Venta $venta)
    {
        $request->validate([
            'monto'      => 'required|numeric|min:1',
            'forma_pago' => 'required|in:efectivo,nequi,daviplata',
        ]);

        $totalPagado    = $venta->pagos->sum('monto');
        $saldoPendiente = $venta->total - $totalPagado;

        if ($request->monto > $saldoPendiente) {
            return back()->withErrors([
                'monto' => "El monto no puede superar el saldo pendiente de $" . number_format($saldoPendiente, 0, ',', '.')
            ]);
        }

        DB::beginTransaction();
        try {
            Pago::create([
                'venta_id'   => $venta->id,
                'monto'      => $request->monto,
                'forma_pago' => $request->forma_pago,
                'fecha_pago' => now()->toDateString(),
            ]);

            $nuevoTotal = $totalPagado + $request->monto;
            if ($nuevoTotal >= $venta->total) {
                $venta->update(['estado' => 'pagado']);
            }

            DB::commit();

            return redirect()->route('pagos.index')
                             ->with('success', "Pago de $" . number_format($request->monto, 0, ',', '.') . " registrado.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al registrar el pago.']);
        }
    }
}