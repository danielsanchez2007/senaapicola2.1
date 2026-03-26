@extends('senaapicola::layouts.master')
@section('content')
    <div class="container-fluid pt-2">
        <h2>Bienvenido Administrador</h2>
        <p class="text-muted">Resumen del sistema y ubicación de los apiarios.</p>

        @include('senaapicola::partials.apiary-map', ['apiaryMapMarkers' => $apiaryMapMarkers ?? collect()])

        <h4 class="mt-2">Funciones del apartado de Administrador</h4>
        <p class="lead">
            Este módulo permite agregar y listar información del apiario, colmenas, producción
            (entradas y salidas de miel), usuarios del sistema y el seguimiento del estado de cada colmena.
        </p>
    </div>
@endsection
