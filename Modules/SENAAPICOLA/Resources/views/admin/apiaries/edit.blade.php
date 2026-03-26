@extends('senaapicola::layouts.master')

@section('content')
<div class="container pt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Editar Apiario</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('senaapicola.admin.apiaries.update', $apiary->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label>Nombre del Apiario</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $apiary->name) }}" required>
                </div>
                <div class="mb-3">
                    <label>Ubicación (texto / referencia)</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location', $apiary->location) }}" required>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Latitud</label>
                        <input type="number" step="any" name="latitude" class="form-control" value="{{ old('latitude', $apiary->latitude) }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Longitud</label>
                        <input type="number" step="any" name="longitude" class="form-control" value="{{ old('longitude', $apiary->longitude) }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label>URL imagen</label>
                        <input type="text" name="image_url" class="form-control" value="{{ old('image_url', $apiary->image_url) }}">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="{{ route('senaapicola.admin.apiaries.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection
