@extends('senaapicola::layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="row w-100">
                        <div class="col-md-6 d-flex align-items-center">
                            <h5 class="mb-0">Listado de Colmenas</h5>
                        </div>
                        <div class="col-md-6 text-right d-flex justify-content-end align-items-center gap-2">
                            <a href="{{ route('senaapicola.admin.hives.create') }}" class="btn btn-light btn-sm text-dark">
                                <i class="fas fa-plus-circle mr-1"></i> Nueva Colmena
                            </a>
                            <a href="{{ route('senaapicola.admin.hives.report', ['apiary_id' => request('apiary_id')]) }}" class="btn btn-danger btn-sm">
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
                                <strong>Total de Colmenas:</strong><br>{{ $totalHives }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-success text-center">
                                <strong>Primer Registro:</strong><br>{{ $firstHive ? $firstHive->created_at->format('d/m/Y') : 'N/A' }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-warning text-center">
                                <strong>Último Registro:</strong><br>{{ $lastHive ? $lastHive->created_at->format('d/m/Y') : 'N/A' }}
                            </div>
                        </div>
                    </div>

                    {{-- Filtro por apiario --}}
                    <form method="GET" action="{{ route('senaapicola.admin.hives.index') }}" class="mb-3">
                        <div class="form-inline d-flex justify-content-end">
                            <label for="apiary_id" class="mr-2">Filtrar por apiario:</label>
                            <select name="apiary_id" id="apiary_id" class="form-control mr-2" onchange="this.form.submit()">
                                <option value="">-- Todos --</option>
                                @foreach($apiaries as $apiary)
                                <option value="{{ $apiary->id }}" {{ request('apiary_id') == $apiary->id ? 'selected' : '' }}>
                                    {{ $apiary->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </form>

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
                                    <th>Apiario</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="hiveTableBody">
                                @forelse($hives as $hive)
                                <tr>
                                    <td>{{ $hive->id }}</td>
                                    <td>
                                        @if(!empty($hive->apiary?->image_url))
                                            <img src="{{ $hive->apiary->image_url }}" alt="Apiario" style="width:56px;height:56px;object-fit:cover;border-radius:6px;">
                                        @else
                                            <span class="text-muted">Sin imagen</span>
                                        @endif
                                    </td>
                                    <td>{{ $hive->name }}</td>
                                    <td>{{ $hive->apiary->name }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('senaapicola.admin.hives.edit', $hive->id) }}" class="btn btn-warning btn-sm" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <br>
                                            <form action="{{ route('senaapicola.admin.hives.destroy', $hive->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Está seguro de que desea eliminar esta colmena?')">
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
                                    <td colspan="5" class="text-center">No hay colmenas registradas</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-center mt-3" id="paginationContainer">
                        {{ $hives->appends(['apiary_id' => request('apiary_id')])->links() }}
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
        const input = document.getElementById('searchHive');
        const tableBody = document.getElementById('hiveTableBody');
        const paginationContainer = document.getElementById('paginationContainer');
        let allHives = [];

        // Cargar todas las colmenas para búsqueda instantánea
        fetch('/senaapicola/colmenas/search')
            .then(response => response.json())
            .then(data => {
                allHives = data;
            })
            .catch(error => {
                console.error('Error:', error);
            });

        input.addEventListener('input', function() {
            const query = input.value.toLowerCase();
            
            if (query.length === 0) {
                paginationContainer.style.display = 'block';
                location.reload();
                return;
            }

            paginationContainer.style.display = 'none';

            const filteredHives = allHives.filter(hive => 
                hive.name.toLowerCase().includes(query) || 
                (hive.apiary && hive.apiary.name && hive.apiary.name.toLowerCase().includes(query))
            );

            renderFilteredResults(filteredHives);
        });

        function renderFilteredResults(hives) {
            if (hives.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="4" class="text-center">No se encontraron resultados</td></tr>';
                return;
            }

            const rows = hives.map(hive => `
                <tr>
                    <td>${hive.id}</td>
                    <td>${hive.name}</td>
                    <td>${hive.apiary ? hive.apiary.name : 'N/A'}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="/senaapicola/colmenas/${hive.id}/edit" class="btn btn-warning btn-sm" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="/senaapicola/colmenas/${hive.id}" method="POST" style="display: inline;" onsubmit="return confirm('¿Está seguro de que desea eliminar la colmena \\'${hive.name}\\'?')">
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