@extends('senaapicola::layouts.masterpas')
@section('content')
    <div class="container-fluid pt-2">
        <h2>Bienvenido Pasante</h2>
        <p class="text-muted">Consulta general y mapa de apiarios del programa.</p>

        @include('senaapicola::partials.apiary-map', ['apiaryMapMarkers' => $apiaryMapMarkers ?? collect()])

        <h4 class="mt-2">Funciones del apartado de Pasante</h4>
        <p class="lead">
            Registro y consulta de información operativa del sistema apícola en los apartados habilitados.
        </p>
    </div>
@endsection
