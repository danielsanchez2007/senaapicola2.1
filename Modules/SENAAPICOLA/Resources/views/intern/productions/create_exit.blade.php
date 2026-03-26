@extends('senaapicola::layouts.masterpas')

@section('content')
<div class="container pt-4">
    <div class="card">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">Registrar Salida de Producción</h5>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Error:</strong>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('senaapicola.intern.productions.store_exit') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="date">Fecha</label>
                    <input type="date" name="date" class="form-control" required>
                </div>



                <div class="mb-3">
                    <label for="product">Producto</label>
                    <input type="text" name="product" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="quantity">Cantidad (Botella 375 ml)</label>
                    <input type="number" name="quantity" class="form-control" required min="1" step="0.01">
                </div>

                <div class="mb-3">
                    <label for="action">Tipo</label>
                    <select name="action" id="action" class="form-control" required onchange="updateDestination()">
                        <option value="entry">Salida</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="destination_or_origin" class="form-label">Destino/Origen</label>
                    <select name="destination_or_origin" id="destination_or_origin" class="form-control" required>
                        <option value="Agroindustria" selected>Agroindustria</option>
                    </select>
                </div>

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

                
                <button type="submit" class="btn btn-warning">Guardar Salida</button>
                <a href="{{ route('senaapicola.intern.productions.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection
