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
            border: 1px solid rgba(245, 197, 24, 0.35);
            border-radius: 28px;
            box-shadow: 0 30px 85px rgba(0, 0, 0, 0.6);
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

        .monitoring-table {
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        .monitoring-table thead {
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
                                    <i class="fas fa-clipboard-list me-3 glow-gold"></i>
                                    Listado de Seguimientos
                                </h4>
                            </div>
                            <div class="col-md-6 text-end d-flex justify-content-end gap-3">
                                <a href="{{ route('senaapicola.admin.monitorings.create') }}" class="btn btn-sena">
                                    <i class="fas fa-plus-circle me-2"></i> Agregar Seguimiento
                                </a>
                                <a href="{{ route('senaapicola.admin.monitorings.report') }}" class="btn btn-danger">
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
                                    <i class="fas fa-clipboard-list fa-3x mb-3" style="color: #ffeb3b;"></i>
                                    <h5 class="mb-2">Total de Seguimientos</h5>
                                    <h2 class="fw-bold mb-0">{{ $totalMonitorings }}</h2>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card text-white">
                                    <i class="fas fa-calendar-alt fa-3x mb-3"></i>
                                    <h5 class="mb-2">Primer Registro</h5>
                                    <h4 class="fw-bold mb-0">
                                        {{ $firstMonitoring ? $firstMonitoring->date : 'N/A' }}
                                    </h4>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card text-white">
                                    <i class="fas fa-clock fa-3x mb-3"></i>
                                    <h5 class="mb-2">Último Registro</h5>
                                    <h4 class="fw-bold mb-0">
                                        {{ $lastMonitoring ? $lastMonitoring->date : 'N/A' }}
                                    </h4>
                                </div>
                            </div>
                        </div>

                        <!-- Filtro por Apiario -->
                        <form method="GET" action="{{ route('senaapicola.admin.monitorings.index') }}" class="mb-4">
                            <div class="d-flex justify-content-end align-items-center gap-3">
                                <label class="form-label fw-semibold text-light mb-0">Filtrar por apiario:</label>
                                <select name="apiary_id" class="form-select" style="width: auto; border-radius: 50px; padding: 10px 20px;" onchange="this.form.submit()">
                                    <option value="">— Todos los apiarios —</option>
                                    @foreach($apiaries as $apiary)
                                        <option value="{{ $apiary->id }}" {{ $selectedApiary == $apiary->id ? 'selected' : '' }}>
                                            {{ $apiary->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Tabla -->
                        <div class="monitoring-table">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="ps-4">Fecha</th>
                                            <th>Imagen</th>
                                            <th>Apiario</th>
                                            <th>Responsable</th>
                                            <th>Rol</th>
                                            <th>Descripción</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($monitorings as $m)
                                        <tr>
                                            <td class="ps-4 fw-semibold">{{ $m->date }}</td>
                                            <td>
                                                @if(!empty($m->apiary?->image_url))
                                                    <img src="{{ $m->apiary->image_url }}" 
                                                         alt="Apiario" class="apiary-img">
                                                @else
                                                    <div class="text-muted small">Sin imagen</div>
                                                @endif
                                            </td>
                                            <td>{{ $m->apiary->name ?? 'Sin apiario' }}</td>
                                            <td>{{ $m->user_nickname ?? 'N/A' }}</td>
                                            <td>
                                                @if($m->role === 'senaapicola.admin')
                                                    <span class="badge bg-success">Administrador</span>
                                                @elseif($m->role === 'senaapicola.intern')
                                                    <span class="badge bg-info">Pasante</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $m->role }}</span>
                                                @endif
                                            </td>
                                            <td class="text-truncate" style="max-width: 280px;">{{ $m->description }}</td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('senaapicola.admin.monitorings.edit', $m->id) }}" 
                                                       class="btn btn-warning btn-sm px-3" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('senaapicola.admin.monitorings.destroy', $m->id) }}" 
                                                          method="POST" style="display: inline;" 
                                                          onsubmit="return confirm('¿Eliminar este seguimiento?')">
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
                                            <td colspan="7" class="text-center py-5 text-muted">
                                                <i class="fas fa-clipboard-list fa-4x mb-4 opacity-25"></i><br>
                                                No hay seguimientos registrados
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Paginación -->
                        <div class="d-flex justify-content-center mt-5">
                            {{ $monitorings->appends(['apiary_id' => request('apiary_id')])->links() }}
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
        setTimeout(createPollen, i * 110);
    }
</script>
@endpush