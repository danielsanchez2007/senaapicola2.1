@extends('senaapicola::layouts.masterpas')

@section('content')
<div class="container pt-4">
    <div class="card">
        <div class="card-header bg-success text-white">
            <div class="row w-100">
                <div class="col-md-6 d-flex align-items-center">
                    <h5 class="mb-0">Entradas - Bodega</h5>
                </div>
                <div class="col-md-6 text-right d-flex justify-content-end align-items-center">
                    <form method="GET" action="{{ route('senaapicola.intern.movements.bodega') }}" class="form-inline">
                        <select name="apiary_id" class="form-control mr-2" onchange="this.form.submit()">
                            <option value="">Todos los Apiarios</option>
                            @foreach($apiaries as $apiary)
                                <option value="{{ $apiary->id }}" {{ request('apiary_id') == $apiary->id ? 'selected' : '' }}>
                                    {{ $apiary->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
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
                    @foreach($entries as $e)
                    <tr>
                        <td>{{ $e->date }}</td>
                        <td>{{ $e->apiary->name ?? 'N/A' }}</td>
                        <td>{{ $e->product }}</td>
                        <td>{{ $e->quantity }}</td>
                        <td>{{ $e->destination_or_origin ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                    @if($entries->isEmpty())
                        <tr><td colspan="5" class="text-center">No hay registros.</td></tr>
                    @endif
                </tbody>
            </table>
            
            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-3">
                {{ $entries->appends(['apiary_id' => request('apiary_id')])->links() }}
            </div>
            
            <a href="{{ route('senaapicola.intern.movements.index') }}" class="btn btn-secondary mt-3">Volver</a>
        </div>
    </div>
</div>
@endsection