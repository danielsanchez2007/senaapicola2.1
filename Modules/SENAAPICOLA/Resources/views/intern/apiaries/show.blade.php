@extends('senaapicola::layouts.masterpas')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="row w-100">
                        <div class="col-md-6 d-flex align-items-center">
                            <h5 class="mb-0">Detalles del Apiario - Vista de Pasante</h5>
                        </div>
                        <div class="col-md-6 text-right d-flex justify-content-end align-items-center gap-2">
                            <a href="{{ route('senaapicola.intern.apiaries.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left mr-1"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Información del Apiario</h6>
                            <table class="table table-bordered">
                                <tr>
                                    <th class="bg-light">ID:</th>
                                    <td>{{ $apiary->id }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Nombre:</th>
                                    <td>{{ $apiary->name }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Ubicación:</th>
                                    <td>{{ $apiary->location }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Fecha de Creación:</th>
                                    <td>{{ $apiary->created_at ? $apiary->created_at->format('d/m/Y H:i:s') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Última Actualización:</th>
                                    <td>{{ $apiary->updated_at ? $apiary->updated_at->format('d/m/Y H:i:s') : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-success">Estadísticas</h6>
                            <div class="alert alert-info">
                                <strong>Total de Colmenas:</strong>
                                <span class="badge badge-primary">{{ $apiary->total_hives }}</span>
                            </div>
                            <div class="alert alert-warning">
                                <strong>Total de Producción:</strong>
                                <span class="badge badge-warning">{{ number_format($apiary->entry_productions_sum) }} Botellas (375 mililitros)</span>
                            </div>
                            <div class="alert alert-secondary">
                                <strong>Total de Seguimientos:</strong>
                                <span class="badge badge-secondary">{{ $apiary->monitorings_count }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-info">Colmenas Asociadas</h6>
                            @if($apiary->hives && $apiary->hives->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="hivesTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Estado</th>
                                            <th>Fecha de Creación</th>
                                            <th>Última Actualización</th>
                                        </tr>
                                    </thead>
                                    <tbody id="hivesTableBody">
                                        @foreach($apiary->hives->take(10) as $hive)
                                        <tr>
                                            <td>{{ $hive->id }}</td>
                                            <td>{{ $hive->name }}</td>
                                            <td>
                                                <span class="badge badge-success">Activa</span>
                                            </td>
                                            <td>{{ $hive->created_at ? $hive->created_at->format('d/m/Y') : 'N/A' }}</td>
                                            <td>{{ $hive->updated_at ? $hive->updated_at->format('d/m/Y') : 'N/A' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            @if($apiary->hives->count() > 10)
                            <div class="text-center mt-3">
                                <nav aria-label="Paginación de colmenas">
                                    <ul class="pagination pagination-sm justify-content-center" id="hivesPagination">
                                        @for($page = 1; $page <= ceil($apiary->hives->count() / 10); $page++)
                                        <li class="page-item {{ $page == 1 ? 'active' : '' }}">
                                            <a class="page-link" href="#" onclick="changePage('hives', {{ $page }})">{{ $page }}</a>
                                        </li>
                                        @endfor
                                    </ul>
                                </nav>
                                <small class="text-muted" id="hivesPageInfo">
                                    Página 1 de {{ ceil($apiary->hives->count() / 10) }} - Total: {{ $apiary->hives->count() }} colmenas
                                </small>
                            </div>
                            @else
                            <div class="text-center mt-2">
                                <small class="text-muted">Total: {{ $apiary->hives->count() }} colmenas</small>
                            </div>
                            @endif
                            @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                No hay colmenas registradas para este apiario.
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-info">
                                <i class="fas fa-chart-line mr-2"></i>
                                Registros de Producción (Últimos 10)
                            </h6>
                            @if($apiary->entry_productions && $apiary->entry_productions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="productionsTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Producto</th>
                                            <th>Cantidad (Botellas)</th>
                                            <th>Origen/Destino</th>
                                            <th>Tipo</th>
                                        </tr>
                                    </thead>
                                    <tbody id="productionsTableBody">
                                        @foreach($apiary->entry_productions->take(10) as $production)
                                        <tr>
                                            <td>{{ $production->date ? \Carbon\Carbon::parse($production->date)->format('d/m/Y') : 'N/A' }}</td>
                                            <td>{{ $production->product }}</td>
                                            <td>
                                                <span class="badge badge-info">{{ number_format($production->quantity) }}</span>
                                            </td>
                                            <td>{{ $production->destination_or_origin ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge badge-success">Entrada</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-2">
                                <small class="text-muted">
                                    Mostrando {{ $apiary->entry_productions->take(10)->count() }} de {{ $apiary->entry_productions->count() }} registros de entrada
                                </small>
                            </div>
                            
                            @if($apiary->entry_productions->count() > 10)
                            <div class="text-center mt-3">
                                <nav aria-label="Paginación de producciones">
                                    <ul class="pagination pagination-sm justify-content-center" id="productionsPagination">
                                        @for($page = 1; $page <= ceil($apiary->entry_productions->count() / 10); $page++)
                                        <li class="page-item {{ $page == 1 ? 'active' : '' }}">
                                            <a class="page-link" href="#" onclick="changePage('productions', {{ $page }})">{{ $page }}</a>
                                        </li>
                                        @endfor
                                    </ul>
                                </nav>
                                <small class="text-muted" id="productionsPageInfo">
                                    Página 1 de {{ ceil($apiary->entry_productions->count() / 10) }} - Total: {{ $apiary->entry_productions->count() }} registros
                                </small>
                            </div>
                            @endif
                            @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                No hay registros de producción de entrada para este apiario.
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Seguimientos del Apiario -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-info">
                                <i class="fas fa-clipboard-check mr-2"></i>
                                Seguimientos del Apiario (Últimos 10)
                            </h6>
                            @if($apiary->monitorings && $apiary->monitorings->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="monitoringsTable">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>ID</th>
                                                <th>Fecha</th>
                                                <th>Usuario</th>
                                                <th>Rol</th>
                                                <th>Descripción del Seguimiento</th>
                                                <th>Fecha de Registro</th>
                                            </tr>
                                        </thead>
                                        <tbody id="monitoringsTableBody">
                                            @foreach($apiary->monitorings->take(10) as $monitoring)
                                            <tr>
                                                <td>{{ $monitoring->id }}</td>
                                                <td>{{ $monitoring->date ? \Carbon\Carbon::parse($monitoring->date)->format('d/m/Y') : 'N/A' }}</td>
                                                <td>
                                                    <span class="badge badge-info">{{ $monitoring->user_nickname ?? 'N/A' }}</span>
                                                </td>
                                                <td>
                                                    @if($monitoring->role)
                                                        @if(strpos(strtolower($monitoring->role), 'admin') !== false || strpos(strtolower($monitoring->role), 'administrador') !== false)
                                                            <span class="badge badge-success">Administrador del Sistema</span>
                                                        @elseif(strpos(strtolower($monitoring->role), 'pasante') !== false || strpos(strtolower($monitoring->role), 'intern') !== false)
                                                            <span class="badge badge-info">Pasante</span>
                                                        @else
                                                            <span class="badge badge-primary">{{ $monitoring->role }}</span>
                                                        @endif
                                                    @else
                                                        <span class="badge badge-secondary">N/A</span>
                                                    @endif
                                                </td>
                                                <td>{{ $monitoring->description ?? 'Sin descripción' }}</td>
                                                <td>{{ $monitoring->created_at ? $monitoring->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center mt-2">
                                    <small class="text-muted">
                                        Mostrando {{ $apiary->monitorings->take(10)->count() }} de {{ $apiary->monitorings->count() }} seguimientos
                                    </small>
                                </div>
                                
                                @if($apiary->monitorings->count() > 10)
                                <div class="text-center mt-3">
                                    <nav aria-label="Paginación de seguimientos">
                                        <ul class="pagination pagination-sm justify-content-center" id="monitoringsPagination">
                                            @for($page = 1; $page <= ceil($apiary->monitorings->count() / 10); $page++)
                                            <li class="page-item {{ $page == 1 ? 'active' : '' }}">
                                                <a class="page-link" href="#" onclick="changePage('monitorings', {{ $page }})">{{ $page }}</a>
                                            </li>
                                            @endfor
                                        </ul>
                                    </nav>
                                    <small class="text-muted" id="monitoringsPageInfo">
                                        Página 1 de {{ ceil($apiary->monitorings->count() / 10) }} - Total: {{ $apiary->monitorings->count() }} seguimientos
                                    </small>
                                </div>
                                @endif
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    No hay seguimientos registrados para este apiario.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
// Datos de las colecciones para paginación
const hivesData = @json($apiary->hives->toArray());
const productionsData = @json($apiary->entry_productions->toArray());
const monitoringsData = @json($apiary->monitorings->toArray());

// Función principal para cambiar página
function changePage(type, page) {
    const itemsPerPage = 10;
    const start = (page - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    
    let data, tableBodyId, paginationId, pageInfoId, totalItems;
    
    switch(type) {
        case 'hives':
            data = hivesData;
            tableBodyId = 'hivesTableBody';
            paginationId = 'hivesPagination';
            pageInfoId = 'hivesPageInfo';
            totalItems = hivesData.length;
            break;
        case 'productions':
            data = productionsData;
            tableBodyId = 'productionsTableBody';
            paginationId = 'productionsPagination';
            pageInfoId = 'productionsPageInfo';
            totalItems = productionsData.length;
            break;
        case 'monitorings':
            data = monitoringsData;
            tableBodyId = 'monitoringsTableBody';
            paginationId = 'monitoringsPagination';
            pageInfoId = 'monitoringsPageInfo';
            totalItems = monitoringsData.length;
            break;
        default:
            return;
    }
    
    // Obtener datos de la página
    const pageData = data.slice(start, end);
    
    // Actualizar tabla
    const tbody = document.getElementById(tableBodyId);
    if (tbody) {
        tbody.innerHTML = '';
        
        pageData.forEach(item => {
            let row = '';
            
            if (type === 'hives') {
                row = `
                    <tr>
                        <td>${item.id}</td>
                        <td>${item.name}</td>
                        <td><span class="badge badge-success">Activa</span></td>
                        <td>${item.created_at ? formatDate(item.created_at) : 'N/A'}</td>
                        <td>${item.updated_at ? formatDate(item.updated_at) : 'N/A'}</td>
                    </tr>
                `;
            } else if (type === 'productions') {
                row = `
                    <tr>
                        <td>${item.date ? formatDate(item.date) : 'N/A'}</td>
                        <td>${item.product}</td>
                        <td><span class="badge badge-info">${Number(item.quantity).toLocaleString()}</span></td>
                        <td>${item.destination_or_origin || 'N/A'}</td>
                        <td><span class="badge badge-success">Entrada</span></td>
                    </tr>
                `;
            } else if (type === 'monitorings') {
                const role = item.role || 'N/A';
                let roleBadge = '';
                
                if (role.toLowerCase().includes('admin') || role.toLowerCase().includes('administrador')) {
                    roleBadge = '<span class="badge badge-success">Administrador del Sistema</span>';
                } else if (role.toLowerCase().includes('pasante') || role.toLowerCase().includes('intern')) {
                    roleBadge = '<span class="badge badge-info">Pasante</span>';
                } else {
                    roleBadge = `<span class="badge badge-primary">${role}</span>`;
                }
                
                row = `
                    <tr>
                        <td>${item.id}</td>
                        <td>${item.date ? formatDate(item.date) : 'N/A'}</td>
                        <td><span class="badge badge-info">${item.user_nickname || 'N/A'}</span></td>
                        <td>${roleBadge}</td>
                        <td>${item.description || 'Sin descripción'}</td>
                        <td>${item.created_at ? formatDateTime(item.created_at) : 'N/A'}</td>
                    </tr>
                `;
            }
            
            tbody.innerHTML += row;
        });
    }
    
    // Actualizar paginación visual
    updatePaginationVisual(paginationId, page);
    
    // Actualizar información de página
    updatePageInfo(pageInfoId, page, Math.ceil(totalItems / itemsPerPage), totalItems, type);
}

// Función para actualizar la paginación visual
function updatePaginationVisual(paginationId, currentPage) {
    const pagination = document.getElementById(paginationId);
    if (pagination) {
        const pageItems = pagination.querySelectorAll('.page-item');
        pageItems.forEach(item => {
            item.classList.remove('active');
            if (item.textContent.trim() == currentPage) {
                item.classList.add('active');
            }
        });
    }
}

// Función para actualizar la información de página
function updatePageInfo(pageInfoId, currentPage, totalPages, totalItems, type) {
    const pageInfo = document.getElementById(pageInfoId);
    if (pageInfo) {
        let itemName = '';
        switch(type) {
            case 'hives':
                itemName = 'colmenas';
                break;
            case 'productions':
                itemName = 'registros';
                break;
            case 'monitorings':
                itemName = 'seguimientos';
                break;
        }
        pageInfo.textContent = `Página ${currentPage} de ${totalPages} - Total: ${totalItems} ${itemName}`;
    }
}

// Función para formatear fechas
function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES');
}

// Función para formatear fecha y hora
function formatDateTime(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleString('es-ES');
}
</script>
@endsection