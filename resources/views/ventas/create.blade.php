@extends('layouts.app')
@section('title', 'Nueva Venta')
@section('page-title', '🛒 Registrar Nueva Venta')

@section('content')
<form action="{{ route('ventas.store') }}" method="POST">
@csrf

<div style="display:grid; grid-template-columns: 1fr 340px; gap:24px;">

    {{-- Columna izquierda: Productos --}}
    <div class="card">
        <div class="card-header">Selecciona los productos</div>
        <div class="card-body">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px;">
                @foreach($productos as $producto)
                <div style="border: 1px solid var(--border); border-radius:10px; padding:16px;
                            {{ $producto->es_combo ? 'border-color: var(--accent); background:#fffbf5;' : '' }}">
                    <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:10px;">
                        <div>
                            <div style="font-weight:600; font-size:14px;">{{ $producto->nombre }}</div>
                            <div style="color:var(--text-muted); font-size:13px;">${{ number_format($producto->precio, 0, ',', '.') }}</div>
                        </div>
                        <span class="tag {{ $producto->stock <= 5 ? 'tag-red' : 'tag-green' }}">
                            Stock: {{ $producto->stock }}
                        </span>
                    </div>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <label class="form-label" style="margin:0; white-space:nowrap;">Cantidad:</label>
                        <input type="number"
                               name="productos[{{ $loop->index }}][cantidad]"
                               value="0" min="0" max="{{ $producto->stock }}"
                               class="input-field cantidad-input"
                               data-precio="{{ $producto->precio }}"
                               style="width:70px; text-align:center;">
                        <input type="hidden" name="productos[{{ $loop->index }}][id]" value="{{ $producto->id }}">
                        <span class="subtotal-label" style="font-size:13px; color:var(--green); font-weight:600;">$0</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Columna derecha --}}
    <div style="display:flex; flex-direction:column; gap:16px;">

        {{-- Cliente --}}
        <div class="card">
            <div class="card-header">👤 Cliente</div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="cliente_nombre" class="input-field"
                           value="{{ old('cliente_nombre') }}" placeholder="Ej: Juan Pérez" required>
                </div>
                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="cliente_telefono" class="input-field"
                           value="{{ old('cliente_telefono') }}" placeholder="Opcional">
                </div>
            </div>
        </div>

        {{-- Forma de pago --}}
        <div class="card">
            <div class="card-header">💳 Forma de Pago</div>
            <div class="card-body">
                @foreach(['efectivo' => '💵 Efectivo', 'nequi' => '💜 Nequi', 'daviplata' => '🟣 Daviplata', 'credito' => '📋 Crédito'] as $valor => $label)
                <label style="display:flex; align-items:center; gap:10px; padding:8px; cursor:pointer;
                              border-radius:8px; margin-bottom:6px; border: 1px solid var(--border);">
                    <input type="radio" name="forma_pago" value="{{ $valor }}"
                           {{ old('forma_pago') == $valor ? 'checked' : '' }}>
                    <span style="font-size:14px;">{{ $label }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Total --}}
        <div style="background: var(--brown-dark); border-radius:12px; padding:20px; text-align:center;">
            <div style="color:#888; font-size:12px; margin-bottom:6px;">TOTAL A COBRAR</div>
            <div id="total-display" style="font-family:'DM Serif Display',serif; font-size:36px; color:var(--accent);">$0</div>
        </div>

        <button type="submit" class="btn btn-success" style="width:100%; justify-content:center; padding:14px; font-size:15px;">
            ✅ Registrar Venta
        </button>
    </div>

</div>
</form>
@endsection

@push('scripts')
<script>
    const inputs = document.querySelectorAll('.cantidad-input');
    const totalDisplay = document.getElementById('total-display');

    function actualizarTotal() {
        let total = 0;
        inputs.forEach(input => {
            const cantidad = parseInt(input.value) || 0;
            const precio   = parseFloat(input.dataset.precio);
            const subtotal = cantidad * precio;
            const label    = input.parentElement.querySelector('.subtotal-label');
            label.textContent = subtotal > 0 ? '$' + subtotal.toLocaleString('es-CO') : '$0';
            total += subtotal;
        });
        totalDisplay.textContent = '$' + total.toLocaleString('es-CO');
    }

    inputs.forEach(input => input.addEventListener('input', actualizarTotal));
</script>
@endpush