@extends('layouts.app')
@section('title', 'Créditos Pendientes')
@section('page-title', '💳 Créditos Pendientes')

@section('content')

@if($ventasPendientes->isEmpty())
    <div style="text-align:center; padding:60px 20px;">
        <div style="font-size:48px; margin-bottom:16px;">🎉</div>
        <div style="font-family:'DM Serif Display',serif; font-size:24px; color:var(--brown-dark); margin-bottom:8px;">
            ¡Todo al día!
        </div>
        <div style="color:var(--text-muted); font-size:14px;">No hay ventas a crédito pendientes.</div>
    </div>
@else
    <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap:20px;">
        @foreach($ventasPendientes as $venta)
        @php
            $totalPagado = $venta->pagos->sum('monto');
            $saldo = $venta->total - $totalPagado;
        @endphp
        <div class="card" style="border-top: 3px solid var(--red);">
            <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
                <span>{{ $venta->cliente->nombre }}</span>
                <span class="tag tag-red">${{ number_format($saldo, 0, ',', '.') }} pendiente</span>
            </div>
            <div class="card-body">
                <div style="font-size:12px; color:var(--text-muted); margin-bottom:12px;">
                    Venta #{{ $venta->id }} · {{ $venta->created_at->format('d/m/Y H:i') }}
                </div>

                <table class="data-table" style="margin-bottom:12px;">
                    <tbody>
                        @foreach($venta->detalles as $d)
                        <tr>
                            <td style="padding:6px 0;">{{ $d->cantidad }}× {{ $d->producto->nombre }}</td>
                            <td style="padding:6px 0; text-align:right;">${{ number_format($d->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                        <tr style="font-weight:700; border-top: 1px solid var(--border);">
                            <td style="padding:8px 0;">Total</td>
                            <td style="padding:8px 0; text-align:right;">${{ number_format($venta->total, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>

                @if($venta->pagos->count() > 0)
                <div style="margin-bottom:12px; padding:10px; background:var(--green-light); border-radius:8px;">
                    <div style="font-size:11px; font-weight:600; color:var(--green); margin-bottom:6px;">ABONOS REGISTRADOS</div>
                    @foreach($venta->pagos as $pago)
                    <div style="font-size:12px; color:var(--green); display:flex; justify-content:space-between;">
                        <span>{{ $pago->fecha_pago->format('d/m/Y') }} · {{ $pago->forma_pago }}</span>
                        <span>${{ number_format($pago->monto, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
                @endif

                <form action="{{ route('pagos.store', $venta) }}" method="POST"
                      style="display:flex; gap:8px; align-items:center;">
                    @csrf
                    <input type="number" name="monto" class="input-field"
                           placeholder="Monto" max="{{ $saldo }}" required
                           style="flex:1;">
                    <select name="forma_pago" class="input-field" required style="flex:1;">
                        <option value="">Medio...</option>
                        <option value="efectivo">💵 Efectivo</option>
                        <option value="nequi">💜 Nequi</option>
                        <option value="daviplata">🟣 Daviplata</option>
                    </select>
                    <button type="submit" class="btn btn-danger btn-sm">Pagar</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
@endif

@endsection