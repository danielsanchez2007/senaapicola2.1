@extends('senaapicola::layouts.master')

@section('content')
<div class="container pt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Editar Producción</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('senaapicola.admin.productions.update', $production->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="date">Fecha</label>
                    <input type="date" name="date" class="form-control" value="{{ $production->date }}" required>
                </div>

                <div class="mb-3">
                    <label for="apiary_id">Apiario</label>
                    <select name="apiary_id" class="form-control" required>
                        @foreach($apiaries as $apiary)
                            <option value="{{ $apiary->id }}" {{ $apiary->id == $production->apiary_id ? 'selected' : '' }}>
                                {{ $apiary->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="product">Producto</label>
                    <input type="text" name="product" class="form-control" value="{{ $production->product }}" required>
                </div>

                <div class="mb-3">
                    <label for="quantity">Cantidad (Botella 375 ml)</label>
                    <input type="number" name="quantity" class="form-control" value="{{ $production->quantity }}" required>
                </div>

                <div class="mb-3">
                    <label for="action">Tipo</label>
                    <select name="action" class="form-control" required>
                        <option value="entry" {{ $production->action == 'entry' ? 'selected' : '' }}>Entrada</option>
                        <option value="exit" {{ $production->action == 'exit' ? 'selected' : '' }}>Salida</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="destination_or_origin">Destino/Origen</label>
                    <input type="text" name="destination_or_origin" class="form-control" value="{{ $production->destination_or_origin }}" required>
                </div>

                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="{{ route('senaapicola.admin.productions.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection
