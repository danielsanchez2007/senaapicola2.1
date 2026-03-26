@extends('senaapicola::layouts.masterpas')

@section('content')
<div class="container pt-4">
    <div class="card">
        <div class="card-header bg-warning text-white">
            <h5 class="mb-0">Salidas - Agroindustria</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Destino</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($exits as $e)
                    <tr>
                    <td>{{ \Carbon\Carbon::parse($e->date)->format('d/m/Y') }}</td>
                        <td>{{ $e->product }}</td>
                        <td>{{ $e->quantity }}</td>
                        <td>{{ $e->destination_or_origin ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                    @if($exits->isEmpty())
                        <tr><td colspan="4" class="text-center">No hay registros.</td></tr>
                    @endif
                </tbody>
            </table>
            <a href="{{ route('senaapicola.intern.movements.index') }}" class="btn btn-secondary mt-3">Volver</a>
        </div>
    </div>
</div>
@endsection