<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Producción</title>
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

        h1 {
            text-align: center;
            margin-top: 100px;
            font-size: 28px;
            color: #28a745;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 12px;
            text-align: center;
        }

        th {
            background-color: #28a745;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e6ffe6;
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

    <h1>Reporte de Producción</h1>
    <div style="text-align:center; margin-top:10px; font-size:14px; color:#6c757d; font-style:italic;">Panel de Visualización</div>

    <div style="background-color:#e3f2fd; border:1px solid #17a2b8; padding:10px; margin:20px 0; border-radius:5px;">
        <p style="margin:5px 0; font-size:12px; color:#0c5460;"><strong>Rol:</strong> {{ $generatedByRole ?? 'N/A' }}</p>
        <p style="margin:5px 0; font-size:12px; color:#0c5460;"><strong>Fecha de generación:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
        <p style="margin:5px 0; font-size:12px; color:#0c5460;"><strong>Total de registros:</strong> {{ $productions->count() }}</p>
        <p style="margin:5px 0; font-size:12px; color:#0c5460;"><strong>Nota:</strong> Este reporte es de solo lectura y no permite modificaciones</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Apiario</th>
                <th>Producto</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            @forelse($productions as $p)
            <tr>
                <td>{{ $p->date }}</td>
                <td>{{ $p->apiary->name ?? 'Sin asignar' }}</td>
                <td>{{ $p->product }}</td>
                <td>{{ $p->quantity }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4">No hay registros de producción.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
