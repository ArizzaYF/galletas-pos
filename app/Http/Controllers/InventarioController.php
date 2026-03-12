<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index()
    {
        $productos = Producto::activos()->orderBy('es_combo')->orderBy('nombre')->get();
        return view('inventario.index', compact('productos'));
    }

    public function actualizar(Request $request, Producto $producto)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $producto->update(['stock' => $request->stock]);

        return redirect()->route('inventario.index')
                         ->with('success', "Stock de '{$producto->nombre}' actualizado a {$request->stock} unidades.");
    }
}