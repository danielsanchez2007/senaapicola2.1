@extends('senaapicola::layouts.master')

@section('content')
<div class="container pt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Registrar Colmena</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('senaapicola.admin.hives.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Nombre de la Colmena</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Apiario</label>
                    <select name="apiary_id" class="form-control" required>
                        <option value="">Seleccione un apiario</option>
                        @foreach($apiaries as $apiary)
                            <option value="{{ $apiary->id }}">{{ $apiary->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('senaapicola.admin.hives.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection
