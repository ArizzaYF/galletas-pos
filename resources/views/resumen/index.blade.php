@extends('layouts.app')
@section('title', 'Resumen Diario')
@section('page-title', '📊 Resumen del Día')

@section('content')

{{-- Filtro de fecha --}}
<form method="GET" action="{{ route('resumen.index') }}"
      style="display:flex; align-items:center; gap:10px; margin-bottom:24px;">
    <input type="date" name="fecha" value="{{ $fecha }}" class="input-field" style="width:180px;">
    <button type="submit" class="btn btn-primary">🔍 Ver</button>
</form>

{{-- Stats --}}
<div class="stats-row">
    <div class="stat-card accent">
        <div class="label">Ingresos del día</div>
        <div class="value">${{ number_format($totalDia, 0, ',', '.') }}</div>
        <div class="sub">{{ $cantidadVentas }} ventas registradas</div>
    </div>
    <div class="stat-card green">
        <div class="label">Ventas cobradas</div>
        <div class="value">{{ $ventas->where('estado','pagado')->count() }}</div>
        <div class="sub">Pagadas al contado o digital</div>
    </div>
    <div class="stat-card red">
        <div class="label">Créditos pendientes</div>
        <div class="value">${{ number_format($totalPendiente, 0, ',', '.') }}</div>
        <div class="sub">{{ $ventas->where('estado','pendiente')->count() }} ventas sin cobrar</div>
    </div>
    <div class="stat-card">
        <div class="label">Total facturado</div>
        <div class="value">${{ number_format($totalDia + $totalPendiente, 0, ',', '.') }}</div>
        <div class="sub">Cobrado + pendiente</div>
    </div>
</div>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:24px; margin-bottom:24px;">

    {{-- Por producto --}}
    <div class="card">
        <div class="card-header">🍪 Ventas por producto</div>
        @if($porProducto->isEmpty())
            <div style="padding:30px; text-align:center; color:var(--text-muted);">Sin ventas en esta fecha</div>
        @else
        <table class="data-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th style="text-align:center">Unidades</th>
                    <th style="text-align:right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($porProducto as $item)
                <tr>
                    <td>{{ $item->producto->nombre }}</td>
                    <td style="text-align:center">{{ $item->total_cantidad }}</td>
                    <td style="text-align:right">${{ number_format($item->total_monto, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    {{-- Por forma de pago --}}
    <div class="card">
        <div class="card-header">💳 Por forma de pago</div>
        @if($porFormaPago->isEmpty())
            <div style="padding:30px; text-align:center; color:var(--text-muted);">Sin ventas pagadas en esta fecha</div>
        @else
        <div class="card-body">
            @php
                $iconos = ['efectivo'=>'💵','nequi'=>'💜','daviplata'=>'🟣','credito'=>'📋'];
                $colores = ['efectivo'=>'var(--green)','nequi'=>'#7B1FA2','daviplata'=>'#1565C0','credito'=>'var(--red)'];
            @endphp
            @foreach($porFormaPago as $item)
            <div style="display:flex; justify-content:space-between; align-items:center;
                        padding:12px 0; border-bottom:1px solid var(--border);">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:10px; height:10px; border-radius:50%;
                                background:{{ $colores[$item->forma_pago] ?? '#999' }};"></div>
                    <div>
                        <div style="font-size:14px; font-weight:500;">
                            {{ $iconos[$item->forma_pago] ?? '' }} {{ ucfirst($item->forma_pago) }}
                        </div>
                        <div style="font-size:11px; color:var(--text-muted);">{{ $item->total_ventas }} ventas</div>
                    </div>
                </div>
                <div style="font-weight:700; font-size:15px;">
                    ${{ number_format($item->total_monto, 0, ',', '.') }}
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>

{{-- Lista de ventas del día --}}
<div class="card">
    <div class="card-header">📋 Detalle de ventas del día</div>
    @if($ventas->isEmpty())
        <div style="padding:40px; text-align:center; color:var(--text-muted);">
            No hay ventas registradas para esta fecha.
        </div>
    @else
    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Hora</th>
                <th>Cliente</th>
                <th>Productos</th>
                <th>Pago</th>
                <th style="text-align:right">Total</th>
                <th style="text-align:center">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ventas as $venta)
            <tr>
                <td>{{ $venta->id }}</td>
                <td>{{ $venta->created_at->format('H:i') }}</td>
                <td>{{ $venta->cliente->nombre }}</td>
                <td style="font-size:12px; color:var(--text-muted);">
                    {{ $venta->detalles->map(fn($d) => $d->cantidad.'× '.$d->producto->nombre)->implode(', ') }}
                </td>
                <td><span class="tag tag-gray" style="text-transform:uppercase; font-size:10px;">{{ $venta->forma_pago }}</span></td>
                <td style="text-align:right; font-weight:600;">${{ number_format($venta->total, 0, ',', '.') }}</td>
                <td style="text-align:center;">
                    @if($venta->estado === 'pagado')
                        <span class="tag tag-green">✓ Pagado</span>
                    @else
                        <span class="tag tag-red">⏳ Pendiente</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

@endsection