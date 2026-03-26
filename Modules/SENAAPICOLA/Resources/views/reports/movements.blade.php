<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte General de Movimientos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            position: relative;
        }

        /* Contenedor del logo */
        .logo-container {
            position: absolute;
            top: 5px;
            right: 20px;
        }

        /* Imagen del logo */
        .logo-container img {
            height: 100px; /* ajusta tamaño si quieres */
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            font-size: 60px;
            color: rgba(0, 0, 0, 0.09);
            transform: translate(-50%, -50%) rotate(-40deg);
            z-index: -1;
            text-align: center;
            width: 100%;
        }

        h1, h2 {
            text-align: center;
            margin-top: 100px;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        h2 {
            margin-top: 60px;
            font-size: 20px;
            color: #28a745;
        }

        h2.salidas {
            color: #ffc107;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            font-size: 11px;
            text-align: center;
        }

        th {
            background-color: #343a40;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

{{-- Logo en esquina superior derecha --}}
@php
    $logoDataUrl = 'data:image/png;base64,' . base64_encode(file_get_contents(base_path('Modules/SENAAPICOLA/Resources/logo/logoPDF.png')));
@endphp
    <div class="logo-container">
        <img src="{{ $logoDataUrl }}" alt="Logo">
    </div>

<div class="watermark">SENA EMPRESA</div>

<h1>Reporte General de Movimientos</h1>
<div style="text-align:center; margin-top:10px; font-size:14px; color:#6c757d; font-style:italic;">Panel de Visualización</div>

<div style="background-color:#e3f2fd; border:1px solid #17a2b8; padding:10px; margin:20px 0; border-radius:5px;">
    <p style="margin:5px 0; font-size:12px; color:#0c5460;"><strong>Rol:</strong> {{ $generatedByRole ?? 'N/A' }}</p>
    <p style="margin:5px 0; font-size:12px; color:#0c5460;"><strong>Fecha de generación:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
    <p style="margin:5px 0; font-size:12px; color:#0c5460;"><strong>Total entradas:</strong> {{ $entries->count() }} &nbsp; | &nbsp; <strong>Total salidas:</strong> {{ $exits->count() }}</p>
    <p style="margin:5px 0; font-size:12px; color:#0c5460;"><strong>Nota:</strong> Este reporte es de solo lectura y no permite modificaciones</p>
</div>

{{-- Entradas --}}
<h2>Entradas - Bodega</h2>
<table>
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Apiario</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Origen</th>
        </tr>
    </thead>
    <tbody>
        @forelse($entries as $e)
        <tr>
            <td>{{ $e->date }}</td>
            <td>{{ $e->apiary->name ?? 'N/A' }}</td>
            <td>{{ $e->product }}</td>
            <td>{{ $e->quantity }}</td>
            <td>{{ $e->destination_or_origin ?? 'N/A' }}</td>
        </tr>
        @empty
        <tr><td colspan="5">No hay registros de entradas.</td></tr>
        @endforelse
    </tbody>
</table>

<div class="page-break"></div>

{{-- Salidas --}}
<h2 class="salidas">Salidas - Agroindustria</h2>
<table>
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Destino</th>
        </tr>
    </thead>
    <tbody>
        @forelse($exits as $e)
        <tr>
            <td>{{ \Carbon\Carbon::parse($e->date)->format('d/m/Y') }}</td>
            <td>{{ $e->product }}</td>
            <td>{{ $e->quantity }}</td>
            <td>{{ $e->destination_or_origin ?? 'N/A' }}</td>
        </tr>
        @empty
        <tr><td colspan="4">No hay registros de salidas.</td></tr>
        @endforelse
    </tbody>
</table>

</body>
</html>
