@extends('senaapicola::layouts.master')

@section('content')
<div class="container pt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Registrar Producción</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('senaapicola.admin.productions.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="date">Fecha</label>
                    <input type="date" name="date" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="apiary_id">Apiario</label>
                    <select name="apiary_id" id="apiary_id" class="form-control" required>
                        <option value="">Seleccione un apiario</option>
                        @foreach($apiaries as $apiary)
                        <option value="{{ $apiary->id }}">{{ $apiary->name }}</option>
                        @endforeach
                        <option>Sin Apiario</option>
                    </select>
                </div>


                <div class="mb-3">
                    <label for="product">Producto</label>
                    <input type="text" name="product" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="quantity">Cantidad (Botella 375 ml)</label>
                    <input type="number" name="quantity" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="action">Tipo</label>
                    <select name="action" id="action" class="form-control" required onchange="updateDestination()">
                        <option value="entry">Entrada</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="destination_or_origin" class="form-label">Destino/Origen</label>
                    <select name="destination_or_origin" id="destination_or_origin" class="form-control" disabled>
                        <option value="Bodega">Bodega</option>
                    </select>
                </div>

                <!-- Este input oculto se enviará con el formulario -->
                <input type="hidden" name="destination_or_origin" id="hidden_destination">

                <script>
                    function updateDestination() {
                        const action = document.getElementById('action').value;
                        const destinationSelect = document.getElementById('destination_or_origin');
                        const hiddenInput = document.getElementById('hidden_destination');

                        if (action === 'entry') {
                            destinationSelect.value = 'Bodega';
                            hiddenInput.value = 'Bodega';
                        } else if (action === 'exit') {
                            destinationSelect.value = 'Agroindustria';
                            hiddenInput.value = 'Agroindustria';
                        }
                    }

                    // Ejecutar al cargar la página por si ya hay un valor definido
                    document.addEventListener('DOMContentLoaded', updateDestination);
                </script>



                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('senaapicola.admin.productions.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection