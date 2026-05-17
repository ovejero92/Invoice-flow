<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1e293b; }
        h1 { font-size: 20px; margin: 0 0 6px 0; }
        .muted { color: #64748b; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #e2e8f0; padding: 8px; text-align: left; }
        th { background: #f1f5f9; font-size: 10px; text-transform: uppercase; }
        .right { text-align: right; }
        .totals { margin-top: 16px; width: 280px; margin-left: auto; }
        .totals td { border: none; padding: 4px 0; }
        .total-row { font-weight: bold; font-size: 12px; border-top: 2px solid #1e293b !important; }
    </style>
</head>
<body>
    <table style="border:none; margin:0;">
        <tr style="border:none;">
            <td style="border:none; width:60%; vertical-align:top;">
                @php $org = $factura->organization; @endphp
                <h1>Factura {{ $factura->numero }}</h1>
                @if ($org?->legal_name || $org?->name)
                    <p><strong>{{ $org->legal_name ?: $org->name }}</strong></p>
                @endif
                @if ($org?->tax_id)
                    <p class="muted">NIF/CIF: {{ $org->tax_id }}</p>
                @endif
                @if ($org?->legal_address)
                    <p class="muted" style="white-space:pre-line;">{{ $org->legal_address }}</p>
                @endif
                @if (! $org)
                    <p class="muted">{{ config('app.name') }}</p>
                @endif
                <p class="muted">Emitida: {{ $factura->fecha_emision->format('d/m/Y') }}</p>
            </td>
            <td style="border:none; vertical-align:top; text-align:right;">
                <p><strong>Cliente</strong></p>
                <p>{{ $factura->cliente->nombre }}</p>
                @if ($factura->cliente->email)
                    <p class="muted">{{ $factura->cliente->email }}</p>
                @endif
            </td>
        </tr>
    </table>

    <p class="muted" style="margin-top:12px;">Periodo facturado: {{ $factura->periodo_desde->format('d/m/Y') }} — {{ $factura->periodo_hasta->format('d/m/Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Tarea / proyecto</th>
                <th class="right">Horas</th>
                <th class="right">Tarifa</th>
                <th class="right">Importe</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($factura->registrosHoras as $r)
                @php $tarifa = (float) $r->tarea->proyecto->tarifa_hora; $imp = (float) $r->horas * $tarifa; @endphp
                <tr>
                    <td>{{ $r->fecha->format('d/m/Y') }}</td>
                    <td>{{ $r->tarea->titulo }}<br><span class="muted">{{ $r->tarea->proyecto->nombre }}</span></td>
                    <td class="right">{{ number_format((float) $r->horas, 2, ',', '.') }}</td>
                    <td class="right">{{ number_format($tarifa, 2, ',', '.') }} {{ $r->tarea->proyecto->moneda }}</td>
                    <td class="right">{{ number_format($imp, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($factura->notas)
        <p style="margin-top:16px;"><strong>Notas:</strong> {{ $factura->notas }}</p>
    @endif

    <table class="totals">
        <tr>
            <td>Subtotal</td>
            <td class="right">{{ number_format((float) $factura->subtotal, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Impuesto ({{ number_format((float) $factura->impuesto_pct, 2, ',', '.') }}%)</td>
            <td class="right">{{ number_format((float) $factura->impuesto_importe, 2, ',', '.') }}</td>
        </tr>
        <tr class="total-row">
            <td>Total</td>
            <td class="right">{{ number_format((float) $factura->total, 2, ',', '.') }}</td>
        </tr>
    </table>
</body>
</html>
