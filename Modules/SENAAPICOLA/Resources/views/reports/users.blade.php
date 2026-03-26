<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Usuarios - SENAAPICOLA</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
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
            height: 100px;
            /* ajusta tamaño si quieres */
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
        }

        th {
            background-color: #2E8B3E;
            color: #fff;
        }

        .text-right {
            text-align: right;
        }

        .small {
            font-size: 11px;
            color: #666;
        }

        .logo {
            position: absolute;
            top: 10px;
            right: 10px;
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

        .subtitle {
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
            color: #6c757d;
            font-style: italic;
        }

        .info-box {
            background-color: #e3f2fd;
            border: 1px solid #17a2b8;
            padding: 10px;
            margin: 20px 0;
            border-radius: 5px;
        }

        .info-box p {
            margin: 5px 0;
            font-size: 12px;
            color: #0c5460;
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
        <br>
    <div class="watermark">SENA EMPRESA</div>

    <h2>Usuarios - Módulo SENAAPICOLA</h2>
    <div class="subtitle">Panel de Visualización</div>

    <div style="background-color:#e3f2fd; border:1px solid #17a2b8; padding:10px; margin:20px 0; border-radius:5px;" class="info-box">
        <p style="margin:5px 0; font-size:12px; color:#0c5460;"><strong>Rol:</strong> {{ $generatedByRole ?? 'N/A' }}</p>
        <p style="margin:5px 0; font-size:12px; color:#0c5460;"><strong>Fecha de generación:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
        <p style="margin:5px 0; font-size:12px; color:#0c5460;"><strong>Total de usuarios:</strong> {{ $users->count() }}</p>
        <p style="margin:5px 0; font-size:12px; color:#0c5460;"><strong>Nota:</strong> Este reporte es de solo lectura y no permite modificaciones</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nickname</th>
                <th>Correo</th>
                <th>Roles</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $idx => $user)
            <tr>
                <td class="text-right">{{ $idx + 1 }}</td>
                <td>{{ $user->nickname }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p class="small">Generado: {{ now()->format('Y-m-d H:i') }}</p>
</body>

</html>