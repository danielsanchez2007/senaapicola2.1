@extends('senaapicola::layouts.master')

@section('content')
<div class="container pt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Registrar Apiario</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('senaapicola.admin.apiaries.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name">Nombre del Apiario</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="location">Ubicación (texto / referencia)</label>
                    <input type="text" class="form-control" name="location" required placeholder="Ej. Vereda El Triunfo, La Mesa">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="latitude">Latitud (mapa)</label>
                        <input type="number" step="any" class="form-control" name="latitude" id="latitude" placeholder="Ej. 4.7061">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="longitude">Longitud (mapa)</label>
                        <input type="number" step="any" class="form-control" name="longitude" id="longitude" placeholder="Ej. -74.2302">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="image_url">URL imagen (vista previa en mapa)</label>
                        <input type="text" class="form-control" name="image_url" id="image_url" placeholder="https://...">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('senaapicola.admin.apiaries.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection