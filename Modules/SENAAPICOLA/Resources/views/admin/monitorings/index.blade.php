@extends('senaapicola::layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="row w-100">
                        <div class="col-md-6 d-flex align-items-center">
                            <h5 class="mb-0">Listado de Seguimientos</h5>
                        </div>
                        <div class="col-md-6 text-right d-flex justify-content-end align-items-center gap-2">
                            <a href="{{ route('senaapicola.admin.monitorings.create') }}" class="btn btn-light btn-sm text-dark">
                                <i class="fas fa-plus-circle mr-1"></i> Agregar Seguimiento
                            </a>
                            <a href="{{ route('senaapicola.admin.monitorings.report') }}" class="btn btn-danger btn-sm">
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
                                <strong>Total de Seguimientos:</strong><br>{{ $totalMonitorings }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-success text-center">
                                <strong>Primer Registro:</strong><br>{{ $firstMonitoring ? $firstMonitoring->date : 'N/A' }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-warning text-center">
                                <strong>Último Registro:</strong><br>{{ $lastMonitoring ? $lastMonitoring->date : 'N/A' }}
                            </div>
                        </div>
                    </div>

                    {{-- Filtro por apiario --}}
                    <form method="GET" action="{{ route('senaapicola.admin.monitorings.index') }}" class="mb-3">
                        <div class="form-inline d-flex justify-content-end">
                            <label for="apiary_id" class="mr-2">Filtrar por apiario:</label>
                            <select name="apiary_id" id="apiary_id" class="form-control mr-2" onchange="this.form.submit()">
                                <option value="">-- Todos --</option>
                                @foreach($apiaries as $apiary)
                                <option value="{{ $apiary->id }}" {{ $selectedApiary == $apiary->id ? 'selected' : '' }}>
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
                                    <th>Fecha</th>
                                    <th>Imagen</th>
                                    <th>Apiario</th>
                                    <th>Responsable</th>
                                    <th>Rol</th>
                                    <th>Descripción</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($monitorings as $m)
                                <tr>
                                    <td>{{ $m->date }}</td>
                                    <td>
                                        @if(!empty($m->apiary?->image_url))
                                            <img src="{{ $m->apiary->image_url }}" alt="Apiario" style="width:56px;height:56px;object-fit:cover;border-radius:6px;">
                                        @else
                                            <span class="text-muted">Sin imagen</span>
                                        @endif
                                    </td>
                                    <td>{{ $m->apiary->name ?? 'Sin apiario' }}</td>
                                    <td>{{ $m->user_nickname ?? 'N/A' }}</td>
                                    <td>
                                        @if($m->role === 'senaapicola.admin')
                                        Administrador
                                        @elseif($m->role === 'senaapicola.intern')
                                        Pasante
                                        @else
                                        {{ $m->role }}
                                        @endif
                                    </td>
                                    <td>{{ $m->description }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('senaapicola.admin.monitorings.edit', $m->id) }}" class="btn btn-warning btn-sm" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('senaapicola.admin.monitorings.destroy', $m->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Está seguro de que desea eliminar este seguimiento?')">
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
                                    <td colspan="7" class="text-center">No hay seguimientos registrados</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $monitorings->appends(['apiary_id'=>request('apiary_id')])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection