@extends('senaapicola::layouts.master')

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --sena-green: #39a900;
            --sena-green-dark: #2d8000;
            --sena-orange: #ff9f1c;
            --sena-gold: #f5c518;
            --glass: rgba(255, 255, 255, 0.09);
        }

        body {
            background: linear-gradient(180deg, #0a1f0a 0%, #0f2a0f 100%);
            position: relative;
            overflow-x: hidden;
        }

        /* Partículas de polen flotando */
        .pollen-particle {
            position: fixed;
            width: 7px;
            height: 7px;
            background: radial-gradient(circle, var(--sena-gold), #ffeb3b);
            border-radius: 50%;
            box-shadow: 0 0 12px var(--sena-gold);
            pointer-events: none;
            z-index: 1;
            opacity: 0.65;
            animation: floatPollen linear infinite;
        }

        @keyframes floatPollen {
            0%   { transform: translateY(100vh) rotate(0deg); }
            100% { transform: translateY(-140px) rotate(720deg); }
        }

        .main-card {
            background: var(--glass);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(245, 197, 24, 0.3);
            border-radius: 28px;
            box-shadow: 0 30px 85px rgba(0, 0, 0, 0.55);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, var(--sena-green-dark) 0%, var(--sena-orange) 100%) !important;
            color: white;
            border: none;
            padding: 2rem 2.5rem;
        }

        .stat-card {
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(245, 197, 24, 0.3);
            border-radius: 20px;
            padding: 1.8rem 1rem;
            text-align: center;
            transition: all 0.4s ease;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            border-color: var(--sena-gold);
        }

        .apiary-table {
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        .apiary-table thead {
            background: linear-gradient(90deg, var(--sena-green-dark), var(--sena-green));
            color: white;
        }

        .apiary-img {
            width: 62px;
            height: 62px;
            object-fit: cover;
            border-radius: 14px;
            border: 3px solid var(--sena-gold);
        }

        .btn-sena {
            background: linear-gradient(90deg, var(--sena-green), var(--sena-orange));
            color: white;
            border: none;
            border-radius: 50px;
            padding: 10px 26px;
            font-weight: 700;
            box-shadow: 0 8px 25px rgba(57, 169, 0, 0.35);
        }

        .btn-sena:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(255, 159, 28, 0.45);
        }

        .glow-gold {
            text-shadow: 0 0 20px var(--sena-gold);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-5 position-relative" style="z-index: 2;">
        <div class="row">
            <div class="col-12">
                <div class="main-card">

                    <!-- Header -->
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h4 class="mb-0 fw-bold">
                                    <i class="fas fa-map-marked-alt me-3 glow-gold"></i>
                                    Listado de Apiarios
                                </h4>
                            </div>
                            <div class="col-md-6 text-end d-flex justify-content-end gap-3">
                                <a href="{{ route('senaapicola.admin.apiaries.create') }}" class="btn btn-sena">
                                    <i class="fas fa-plus-circle me-2"></i> Nuevo Apiario
                                </a>
                                <a href="{{ route('senaapicola.admin.apiaries.report') }}" class="btn btn-danger">
                                    <i class="fas fa-file-pdf me-2"></i> Generar PDF
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-5">

                        <!-- Estadísticas -->
                        <div class="row g-4 mb-5">
                            <div class="col-md-4">
                                <div class="stat-card text-white">
                                    <i class="fas fa-map-marker-alt fa-3x mb-3" style="color: #ffeb3b;"></i>
                                    <h5 class="mb-2">Total de Apiarios</h5>
                                    <h2 class="fw-bold mb-0">{{ $totalApiaries }}</h2>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card text-white">
                                    <i class="fas fa-calendar-alt fa-3x mb-3"></i>
                                    <h5 class="mb-2">Primer Registro</h5>
                                    <h4 class="fw-bold mb-0">
                                        {{ $firstRegistered ? $firstRegistered->created_at->format('d/m/Y') : 'N/A' }}
                                    </h4>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card text-white">
                                    <i class="fas fa-clock fa-3x mb-3"></i>
                                    <h5 class="mb-2">Último Registro</h5>
                                    <h4 class="fw-bold mb-0">
                                        {{ $lastRegistered ? $lastRegistered->created_at->format('d/m/Y') : 'N/A' }}
                                    </h4>
                                </div>
                            </div>
                        </div>

                        <!-- Búsqueda -->
                        <div class="mb-4">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" id="searchApiary" class="form-control border-start-0" 
                                       placeholder="Buscar por nombre o ubicación...">
                            </div>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Tabla -->
                        <div class="apiary-table">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="ps-4">ID</th>
                                            <th>Imagen</th>
                                            <th>Nombre del Apiario</th>
                                            <th>Ubicación</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="apiaryTableBody">
                                        @forelse($apiaries as $apiary)
                                        <tr>
                                            <td class="ps-4 fw-bold text-success">{{ $apiary->id }}</td>
                                            <td>
                                                @if(!empty($apiary->image_url))
                                                    <img src="{{ $apiary->image_url }}" 
                                                         alt="Apiario" class="apiary-img">
                                                @else
                                                    <div class="text-muted small">Sin imagen</div>
                                                @endif
                                            </td>
                                            <td class="fw-semibold">{{ $apiary->name }}</td>
                                            <td>{{ $apiary->location }}</td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('senaapicola.admin.apiaries.edit', $apiary->id) }}" 
                                                       class="btn btn-warning btn-sm px-3" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('senaapicola.admin.apiaries.destroy', $apiary->id) }}" 
                                                          method="POST" style="display: inline;" 
                                                          onsubmit="return confirm('¿Eliminar este apiario? Esta acción no se puede deshacer.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm px-3" title="Eliminar">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted">
                                                <i class="fas fa-map-marker-alt fa-4x mb-4 opacity-25"></i><br>
                                                No hay apiarios registrados
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Paginación -->
                        <div class="d-flex justify-content-center mt-5" id="paginationContainer">
                            {{ $apiaries->appends(['q' => request('q')])->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenedor de partículas de polen -->
    <div id="pollen-container"></div>
@endsection

@push('scripts')
<script>
    // Partículas de polen flotando
    function createPollen() {
        const container = document.getElementById('pollen-container');
        const particle = document.createElement('div');
        particle.classList.add('pollen-particle');

        const size = Math.random() * 8 + 5;
        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;
        particle.style.left = `${Math.random() * 100}vw`;
        particle.style.opacity = Math.random() * 0.65 + 0.35;

        const duration = Math.random() * 35 + 22;
        particle.style.animationDuration = `${duration}s`;
        particle.style.animationDelay = `-${Math.random() * 25}s`;

        container.appendChild(particle);

        setTimeout(() => particle.remove(), duration * 1000 + 1000);
    }

    setInterval(() => {
        if (Math.random() > 0.25) createPollen();
    }, 180);

    for (let i = 0; i < 20; i++) {
        setTimeout(createPollen, i * 100);
    }

    // === BÚSQUEDA INSTANTÁNEA (funcionalidad original mantenida) ===
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('searchApiary');
        const tableBody = document.getElementById('apiaryTableBody');
        const paginationContainer = document.getElementById('paginationContainer');
        let allApiaries = [];

        fetch('/senaapicola/admin/apiaries/search')
            .then(response => response.json())
            .then(data => {
                allApiaries = data;
            })
            .catch(error => console.error('Error al cargar apiarios para búsqueda:', error));

        input.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();

            if (query.length === 0) {
                paginationContainer.style.display = 'block';
                location.reload();
                return;
            }

            paginationContainer.style.display = 'none';

            const filtered = allApiaries.filter(apiary => 
                apiary.name.toLowerCase().includes(query) || 
                (apiary.location && apiary.location.toLowerCase().includes(query))
            );

            renderFilteredResults(filtered);
        });

        function renderFilteredResults(apiaries) {
            if (apiaries.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="5" class="text-center py-5">No se encontraron resultados</td></tr>`;
                return;
            }

            const rows = apiaries.map(apiary => `
                <tr>
                    <td class="ps-4 fw-bold text-success">${apiary.id}</td>
                    <td>
                        ${apiary.image_url 
                            ? `<img src="${apiary.image_url}" alt="Apiario" class="apiary-img">` 
                            : '<span class="text-muted small">Sin imagen</span>'}
                    </td>
                    <td class="fw-semibold">${apiary.name}</td>
                    <td>${apiary.location || 'Sin ubicación'}</td>
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="/senaapicola/admin/apiaries/${apiary.id}/edit" class="btn btn-warning btn-sm px-3" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="/senaapicola/admin/apiaries/${apiary.id}" method="POST" style="display:inline;" 
                                  onsubmit="return confirm('¿Eliminar este apiario? Esta acción no se puede deshacer.')">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-danger btn-sm px-3" title="Eliminar">
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
@endpush