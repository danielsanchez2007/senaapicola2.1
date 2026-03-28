@extends('senaapicola::layouts.masterpas')

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --sena-green: #39a900;
            --sena-green-dark: #2d8000;
            --sena-orange: #ff9f1c;
            --sena-gold: #f5c518;
            --glass: rgba(255, 255, 255, 0.07);
        }

        body {
            background: linear-gradient(180deg, #0a1f0a 0%, #0f2a0f 100%);
            position: relative;
            overflow-x: hidden;
        }

        .pollen-particle {
            position: fixed;
            width: 6px;
            height: 6px;
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
            100% { transform: translateY(-150px) rotate(720deg); }
        }

        .main-card {
            background: var(--glass);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(245, 197, 24, 0.25);
            border-radius: 28px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            position: relative;
        }

        .card-header {
            background: linear-gradient(135deg, var(--sena-green-dark) 0%, var(--sena-orange) 100%) !important;
            color: white;
            border: none;
            padding: 2rem 2.5rem;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: -80px;
            right: -80px;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(255,255,255,0.25), transparent);
            border-radius: 50%;
        }

        .stat-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(245, 197, 24, 0.3);
            border-radius: 20px;
            padding: 1.8rem 1rem;
            text-align: center;
            transition: all 0.4s ease;
        }

        .stat-card:hover {
            transform: translateY(-10px) scale(1.03);
            border-color: var(--sena-gold);
            box-shadow: 0 20px 40px rgba(245, 197, 24, 0.2);
        }

        .hive-table {
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        .hive-table thead {
            background: linear-gradient(90deg, var(--sena-green-dark), var(--sena-green));
            color: white;
        }

        .hive-img {
            width: 62px;
            height: 62px;
            object-fit: cover;
            border-radius: 14px;
            border: 3px solid var(--sena-gold);
            box-shadow: 0 6px 15px rgba(0,0,0,0.3);
        }

        .btn-sena {
            background: linear-gradient(90deg, var(--sena-green), var(--sena-orange));
            color: white;
            border: none;
            border-radius: 50px;
            padding: 12px 28px;
            font-weight: 700;
            box-shadow: 0 10px 30px rgba(57, 169, 0, 0.4);
        }

        .btn-sena:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 45px rgba(255, 159, 28, 0.5);
        }

        .apiary-filter {
            background: rgba(255,255,255,0.9);
            border: 2px solid var(--sena-orange);
            border-radius: 50px;
            padding: 10px 24px;
        }

        .glow-gold {
            text-shadow: 0 0 20px var(--sena-gold);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-5 position-relative" style="z-index: 2;">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="main-card">
                    <!-- Header futurista -->
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-hive fa-2x me-4 glow-gold"></i>
                                <div>
                                    <h3 class="mb-0 fw-bold">Gestión de Colmenas</h3>
                                    <p class="mb-0 opacity-75 small">Monitoreo y trazabilidad apícola • SENA APICOLA</p>
                                </div>
                            </div>
                            <div class="d-flex gap-3">
                                <a href="{{ route('senaapicola.intern.hives.create') }}" class="btn btn-sena">
                                    <i class="fas fa-plus-circle me-2"></i> Nueva Colmena
                                </a>
                                <a href="{{ route('senaapicola.intern.hives.report', ['apiary_id' => request('apiary_id')]) }}"
                                   class="btn btn-danger">
                                    <i class="fas fa-file-pdf me-2"></i> Generar PDF
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-5">
                        <!-- Estadísticas con estilo panal -->
                        <div class="row g-4 mb-5">
                            <div class="col-md-4">
                                <div class="stat-card text-white">
                                    <i class="fas fa-hive fa-3x mb-3" style="color: #ffeb3b;"></i>
                                    <h5 class="mb-2">Total Colmenas</h5>
                                    <h2 class="fw-bold mb-0">{{ $totalHives }}</h2>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card text-white">
                                    <i class="fas fa-calendar-alt fa-3x mb-3"></i>
                                    <h5 class="mb-2">Primer Registro</h5>
                                    <h4 class="fw-bold mb-0">
                                        {{ $firstHive ? $firstHive->created_at->format('d/m/Y') : 'N/A' }}
                                    </h4>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card text-white">
                                    <i class="fas fa-clock fa-3x mb-3"></i>
                                    <h5 class="mb-2">Último Registro</h5>
                                    <h4 class="fw-bold mb-0">
                                        {{ $lastHive ? $lastHive->created_at->format('d/m/Y') : 'N/A' }}
                                    </h4>
                                </div>
                            </div>
                        </div>

                        <!-- Filtro -->
                        <form method="GET" action="{{ route('senaapicola.intern.hives.index') }}" class="mb-5">
                            <div class="d-flex justify-content-end align-items-center gap-3">
                                <label class="form-label fw-semibold text-light mb-0">Filtrar por apiario:</label>
                                <select name="apiary_id" class="form-select apiary-filter w-auto" onchange="this.form.submit()">
                                    <option value="">— Todos los apiarios —</option>
                                    @foreach($apiaries as $apiary)
                                        <option value="{{ $apiary->id }}" {{ request('apiary_id') == $apiary->id ? 'selected' : '' }}>
                                            {{ $apiary->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-4">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Tabla moderna -->
                        <div class="hive-table">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="ps-4">ID</th>
                                            <th>Imagen</th>
                                            <th>Nombre de la Colmena</th>
                                            <th>Apiario</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="hiveTableBody">
                                        @forelse($hives as $hive)
                                            <tr>
                                                <td class="ps-4 fw-bold text-success">{{ $hive->id }}</td>
                                                <td>
                                                    @if(!empty($hive->apiary?->image_url))
                                                        <img src="{{ $hive->apiary->image_url }}"
                                                             alt="Colmena" class="hive-img">
                                                    @else
                                                        <div class="text-muted small">Sin imagen</div>
                                                    @endif
                                                </td>
                                                <td class="fw-semibold">{{ $hive->name }}</td>
                                                <td>{{ $hive->apiary->name ?? 'Sin asignar' }}</td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <a href="{{ route('senaapicola.intern.hives.edit', $hive->id) }}"
                                                           class="btn btn-warning btn-sm px-3" title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('senaapicola.intern.hives.destroy', $hive->id) }}"
                                                              method="POST" style="display: inline;"
                                                              onsubmit="return confirm('¿Está seguro de eliminar esta colmena definitivamente?')">
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
                                                <td colspan="5" class="text-center py-5">
                                                    <i class="fas fa-hive fa-4x mb-4 opacity-25"></i>
                                                    <p class="lead text-muted mb-0">No hay colmenas registradas aún</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Paginación -->
                        <div class="d-flex justify-content-center mt-5" id="paginationContainer">
                            {{ $hives->appends(['apiary_id' => request('apiary_id')])->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- Contenedor de partículas de polen -->
        <div id="pollen-container"></div>
    </div>
@endsection

@push('scripts')
<script>
    function createPollen() {
        const container = document.getElementById('pollen-container');
        const particle = document.createElement('div');
        particle.classList.add('pollen-particle');

        const size = Math.random() * 9 + 5;
        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;
        particle.style.left = `${Math.random() * 100}vw`;
        particle.style.opacity = Math.random() * 0.7 + 0.4;

        const duration = Math.random() * 35 + 25;
        particle.style.animationDuration = `${duration}s`;
        particle.style.animationDelay = `-${Math.random() * 20}s`;

        container.appendChild(particle);
        setTimeout(() => particle.remove(), duration * 1000 + 1000);
    }

    setInterval(() => {
        if (Math.random() > 0.25) createPollen();
    }, 180);

    for (let i = 0; i < 22; i++) {
        setTimeout(createPollen, i * 90);
    }
</script>
@endpush