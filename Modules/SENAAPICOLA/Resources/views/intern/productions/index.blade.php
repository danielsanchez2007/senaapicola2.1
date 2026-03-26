@extends('senaapicola::layouts.masterpas')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="row w-100">
                        <div class="col-md-6 d-flex align-items-center">
                            <h5 class="mb-0">Registro de Entradas de Producción</h5>
                        </div>
                        <div class="col-md-6 text-right d-flex justify-content-end align-items-center gap-2">
                            <a href="{{ route('senaapicola.intern.productions.create') }}" class="btn btn-light btn-sm text-dark mr-2">
                                <i class="fas fa-plus-circle mr-1"></i> Nuevo Registro
                            </a>
                            <a href="{{ route('senaapicola.intern.productions.create_exit') }}" class="btn btn-warning btn-sm text-dark mr-2">
                                <i class="fas fa-minus-circle mr-1"></i> Nueva Salida
                            </a>
                            
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Estadísticas globales -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="alert alert-info text-center">
                                <strong>Total de Producción:</strong><br>{{ $total }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-success text-center">
                                <strong>Primer Registro:</strong><br>{{ $firstProduction ? $firstProduction->date : 'N/A' }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-warning text-center">
                                <strong>Último Registro:</strong><br>{{ $lastProduction ? $lastProduction->date : 'N/A' }}
                            </div>
                        </div>
                    </div>

                    {{-- Filtro por apiario --}}
                    <form method="GET" action="{{ route('senaapicola.intern.productions.index') }}" class="mb-3">
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

                    <!-- Tabla de Entradas de Producción -->
                    <h6 class="text-primary mb-3">
                        <i class="fas fa-plus-circle me-2"></i>
                        Entradas de Producción Registradas
                    </h6>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Imagen</th>
                                    <th>Apiario</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="productionTableBody">
                                @forelse($productions as $p)
                                <tr>
                                    <td>{{ $p->date }}</td>
                                    <td>
                                        @if(!empty($p->apiary?->image_url))
                                            <img src="{{ $p->apiary->image_url }}" alt="Apiario" style="width:56px;height:56px;object-fit:cover;border-radius:6px;">
                                        @else
                                            <span class="text-muted">Sin imagen</span>
                                        @endif
                                    </td>
                                    <td>{{ $p->apiary->name }}</td>
                                    <td>{{ $p->product }}</td>
                                    <td>{{ $p->quantity }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('senaapicola.intern.productions.edit', $p->id) }}" class="btn btn-warning btn-sm" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('senaapicola.intern.productions.destroy', $p->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Está seguro de que desea eliminar esta producción?')">
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
                                    <td colspan="6" class="text-center">No hay registros de producción</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-center mt-3" id="paginationContainer">
                        {{ $productions->appends(['apiary_id'=>request('apiary_id')])->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

