@extends('senaapicola::layouts.master')

@section('content')
<div class="container pt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Editar Colmena</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('senaapicola.admin.hives.update', $hive->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name">Nombre de la Colmena</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $hive->name) }}" required>
                </div>

                <div class="mb-3">
                    <label for="apiary_id">Apiario</label>
                    <select name="apiary_id" id="apiary_id" class="form-control" required>
                        <option value="">Seleccione un apiario</option>
                        @foreach($apiaries as $apiary)
                            <option value="{{ $apiary->id }}" {{ $apiary->id == $hive->apiary_id ? 'selected' : '' }}>
                                {{ $apiary->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="{{ route('senaapicola.admin.hives.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection
