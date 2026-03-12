@extends('layouts.app')
@section('title', 'Inventario')
@section('page-title', '📦 Inventario de Galletas')

@section('content')
<div class="card">
    <div class="card-header">Stock actual por producto</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Tipo</th>
                <th>Precio</th>
                <th style="text-align:center">Stock actual</th>
                <th style="text-align:center">Actualizar stock</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
            <tr>
                <td><strong>{{ $producto->nombre }}</strong></td>
                <td>
                    @if($producto->es_combo)
                        <span class="tag tag-orange">🎁 Combo</span>
                    @else
                        <span class="tag tag-gray">Unidad</span>
                    @endif
                </td>
                <td>${{ number_format($producto->precio, 0, ',', '.') }}</td>
                <td style="text-align:center">
                    <span class="tag {{ $producto->stock <= 5 ? 'tag-red' : ($producto->stock <= 15 ? 'tag-orange' : 'tag-green') }}">
                        {{ $producto->stock }}
                    </span>
                </td>
                <td style="text-align:center">
                    <form action="{{ route('inventario.actualizar', $producto) }}" method="POST"
                          style="display:flex; gap:8px; justify-content:center; align-items:center;">
                        @csrf
                        @method('PATCH')
                        <input type="number" name="stock" value="{{ $producto->stock }}"
                               min="0" class="input-field" style="width:80px; text-align:center;">
                        <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection