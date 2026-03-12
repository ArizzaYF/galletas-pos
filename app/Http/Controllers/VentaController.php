<?php

namespace App\Http\Controllers;

use App\Models\{Venta, Producto, Cliente, DetalleVenta};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function create()
    {
        $productos = Producto::activos()->orderBy('es_combo')->orderBy('nombre')->get();
        return view('ventas.create', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_nombre'   => 'required|string|max:255',
            'cliente_telefono' => 'nullable|string|max:20',
            'forma_pago'       => 'required|in:efectivo,nequi,daviplata,credito',
            'productos'        => 'required|array|min:1',
            'productos.*.id'       => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $cliente = Cliente::firstOrCreate(
                ['nombre' => $request->cliente_nombre],
                ['telefono' => $request->cliente_telefono ?? '']
            );

            $total = 0;
            $items = [];

            foreach ($request->productos as $item) {
                if ((int)$item['cantidad'] === 0) continue;

                $producto = Producto::findOrFail($item['id']);

                if ($producto->stock < $item['cantidad']) {
                    return back()->withErrors([
                        'stock' => "Stock insuficiente para '{$producto->nombre}'. Disponible: {$producto->stock}"
                    ])->withInput();
                }

                $subtotal = $producto->precio * $item['cantidad'];
                $total   += $subtotal;
                $items[]  = [
                    'producto' => $producto,
                    'cantidad' => $item['cantidad'],
                    'subtotal' => $subtotal,
                ];
            }

            if (empty($items)) {
                return back()->withErrors(['productos' => 'Debes agregar al menos un producto.'])->withInput();
            }

            $estado = $request->forma_pago === 'credito' ? 'pendiente' : 'pagado';
            $venta  = Venta::create([
                'cliente_id' => $cliente->id,
                'total'      => $total,
                'forma_pago' => $request->forma_pago,
                'estado'     => $estado,
            ]);

            foreach ($items as $item) {
                DetalleVenta::create([
                    'venta_id'    => $venta->id,
                    'producto_id' => $item['producto']->id,
                    'cantidad'    => $item['cantidad'],
                    'subtotal'    => $item['subtotal'],
                ]);
                $item['producto']->decrement('stock', $item['cantidad']);
            }

            DB::commit();

            return redirect()->route('ventas.show', $venta)
                             ->with('success', "Venta registrada. Total: $" . number_format($total, 0, ',', '.'));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al registrar la venta: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(Venta $venta)
    {
        $venta->load(['cliente', 'detalles.producto', 'pagos']);
        return view('ventas.show', compact('venta'));
    }
}