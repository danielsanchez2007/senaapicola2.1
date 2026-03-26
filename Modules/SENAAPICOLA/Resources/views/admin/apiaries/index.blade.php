@extends('senaapicola::layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="row w-100">
                        <div class="col-md-6 d-flex align-items-center">
                            <h5 class="mb-0">Listado de Apiarios</h5>
                        </div>
                        <div class="col-md-6 text-right d-flex justify-content-end align-items-center gap-2">
                            <a href="{{ route('senaapicola.admin.apiaries.create') }}" class="btn btn-light btn-sm text-dark mr-2">
                                <i class="fas fa-plus-circle mr-1"></i> Nuevo Apiario
                            </a>
                            <a href="{{ route('senaapicola.admin.apiaries.report') }}" class="btn btn-danger btn-sm">
                                <i class="fas fa-file-pdf"></i> Generar PDF
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Estadísticas globales -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="alert alert-info text-center">
                                <strong>Total de Apiarios:</strong><br>{{ $totalApiaries }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-success text-center">
                                <strong>Primer Registro:</strong><br>{{ $firstRegistered ? $firstRegistered->created_at->format('d/m/Y') : 'N/A' }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-warning text-center">
                                <strong>Último Registro:</strong><br>{{ $lastRegistered ? $lastRegistered->created_at->format('d/m/Y') : 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <!-- Búsqueda instantánea -->
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="text" id="searchApiary" class="form-control" placeholder="Buscar...">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Imagen</th>
                                    <th>Nombre</th>
                                    <th>Ubicación (Latitud, Longitud)</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="apiaryTableBody">
                                @forelse($apiaries as $apiary)
                                <tr>
                                    <td>{{ $apiary->id }}</td>
                                    <td>
                                        @if(!empty($apiary->image_url))
                                            <img src="{{ $apiary->image_url }}" alt="Apiario" style="width:56px;height:56px;object-fit:cover;border-radius:6px;">
                                        @else
                                            <span class="text-muted">Sin imagen</span>
                                        @endif
                                    </td>
                                    <td class="apiary-name">{{ $apiary->name }}</td>
                                    <td class="apiary-location">{{ $apiary->location }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('senaapicola.admin.apiaries.edit', $apiary->id) }}" class="btn btn-warning btn-sm" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('senaapicola.admin.apiaries.destroy', $apiary->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Está seguro de que desea eliminar el apiario \'{{ $apiary->name }}\'? Esta acción no se puede deshacer.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No hay apiarios registrados</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-center mt-3" id="paginationContainer">
                        {{ $apiaries->appends(['q' => request('q')])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('searchApiary');
        const tableBody = document.getElementById('apiaryTableBody');
        const paginationContainer = document.getElementById('paginationContainer');
        let allApiaries = [];

        // Cargar todos los apiarios para búsqueda instantánea
        console.log('Iniciando carga de apiarios...');
        fetch('/senaapicola/admin/apiaries/search')
            .then(response => {
                console.log('Respuesta del servidor:', response.status);
                return response.json();
            })
            .then(data => {
                allApiaries = data;
                console.log('Apiarios cargados para búsqueda:', data.length);
                console.log('Primeros 3 apiarios:', data.slice(0, 3));
            })
            .catch(error => {
                console.error('Error al cargar apiarios:', error);
            });

        input.addEventListener('input', function() {
            const query = input.value.toLowerCase();
            console.log('Campo de búsqueda cambiado:', query);
            console.log('Longitud del campo:', query.length);
            
            if (query.length === 0) {
                // Restaurar tabla original y paginación
                paginationContainer.style.display = 'block';
                // En lugar de recargar, restaurar la tabla original
                location.reload();
                return;
            }

            // Ocultar paginación durante búsqueda
            paginationContainer.style.display = 'none';

            // Filtrar apiarios
            console.log('Apiarios disponibles para filtrar:', allApiaries.length);
            const filteredApiaries = allApiaries.filter(apiary => 
                apiary.name.toLowerCase().includes(query) || 
                apiary.location.toLowerCase().includes(query)
            );

            console.log('Resultados encontrados:', filteredApiaries.length);
            console.log('Query de búsqueda:', query);

            // Renderizar resultados filtrados
            renderFilteredResults(filteredApiaries);
        });

        function renderFilteredResults(apiaries) {
            if (apiaries.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="5" class="text-center">No se encontraron resultados</td></tr>';
                return;
            }

            const rows = apiaries.map(apiary => `
                <tr>
                    <td>${apiary.id}</td>
                    <td>${apiary.image_url ? `<img src="${apiary.image_url}" alt="Apiario" style="width:56px;height:56px;object-fit:cover;border-radius:6px;">` : '<span class="text-muted">Sin imagen</span>'}</td>
                    <td class="apiary-name">${apiary.name}</td>
                    <td class="apiary-location">${apiary.location}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="/senaapicola/admin/apiaries/${apiary.id}/edit" class="btn btn-warning btn-sm" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="/senaapicola/admin/apiaries/${apiary.id}" method="POST" style="display: inline;" onsubmit="return confirm('¿Está seguro de que desea eliminar el apiario \\'${apiary.name}\\'?')">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            `).join('');

            tableBody.innerHTML = rows;
        }
    });
</script>
@endsection