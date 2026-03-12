@extends('layouts.app')
@section('title', 'Detalle de Venta')
@section('page-title', '🧾 Detalle de Venta #' . $venta->id)

@section('content')
<div style="display:grid; grid-template-columns: 1fr 340px; gap:24px;">

    {{-- Productos vendidos --}}
    <div class="card">
        <div class="card-header">Productos vendidos</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th style="text-align:center">Cantidad</th>
                    <th style="text-align:right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($venta->detalles as $detalle)
                <tr>
                    <td>{{ $detalle->producto->nombre }}</td>
                    <td style="text-align:center">{{ $detalle->cantidad }}</td>
                    <td style="text-align:right">${{ number_format($detalle->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background:var(--soft);">
                    <td colspan="2" style="padding:12px 14px; font-weight:700;">TOTAL</td>
                    <td style="padding:12px 14px; text-align:right; font-weight:700; font-size:16px;">
                        ${{ number_format($venta->total, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Info + Pago --}}
    <div style="display:flex; flex-direction:column; gap:16px;">

        <div class="card">
            <div class="card-header">Información de la venta</div>
            <div class="card-body">
                <div style="display:flex; flex-direction:column; gap:10px; font-size:14px;">
                    <div><span style="color:var(--text-muted)">Cliente:</span> <strong>{{ $venta->cliente->nombre }}</strong></div>
                    <div><span style="color:var(--text-muted)">Teléfono:</span> {{ $venta->cliente->telefono ?: '—' }}</div>
                    <div><span style="color:var(--text-muted)">Fecha:</span> {{ $venta->created_at->format('d/m/Y H:i') }}</div>
                    <div><span style="color:var(--text-muted)">Forma de pago:</span>
                        <span class="tag tag-orange" style="text-transform:uppercase;">{{ $venta->forma_pago }}</span>
                    </div>
                    <div><span style="color:var(--text-muted)">Estado:</span>
                        @if($venta->estado === 'pagado')
                            <span class="tag tag-green">✓ Pagado</span>
                        @else
                            <span class="tag tag-red">⏳ Pendiente</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($venta->estado === 'pendiente')
        @php
            $totalPagado = $venta->pagos->sum('monto');
            $saldo = $venta->total - $totalPagado;
        @endphp
        <div class="card" style="border: 2px solid var(--red);">
            <div class="card-header" style="background:var(--red-light); color:var(--red);">
                💳 Registrar Abono
            </div>
            <div class="card-body">
                <div style="font-size:14px; color:var(--red); margin-bottom:16px;">
                    Saldo pendiente: <strong>${{ number_format($saldo, 0, ',', '.') }}</strong>
                </div>
                <form action="{{ route('pagos.store', $venta) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Monto del abono</label>
                        <input type="number" name="monto" class="input-field"
                               placeholder="Monto" max="{{ $saldo }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Forma de pago</label>
                        <select name="forma_pago" class="input-field" required>
                            <option value="">Selecciona...</option>
                            <option value="efectivo">💵 Efectivo</option>
                            <option value="nequi">💜 Nequi</option>
                            <option value="daviplata">🟣 Daviplata</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-danger" style="width:100%; justify-content:center;">
                        Registrar Pago
                    </button>
                </form>
            </div>
        </div>
        @endif

        <div style="display:flex; gap:10px;">
            <a href="{{ route('ventas.create') }}" class="btn btn-primary" style="flex:1; justify-content:center;">
                🛒 Nueva Venta
            </a>
            <a href="{{ route('resumen.index') }}" class="btn btn-outline" style="flex:1; justify-content:center;">
                📊 Resumen
            </a>
        </div>

    </div>
</div>
@endsection