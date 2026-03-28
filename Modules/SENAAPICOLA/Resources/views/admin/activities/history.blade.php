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
            backdrop-filter: blur(22px);
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

        .table-container {
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        .table thead {
            background: linear-gradient(90deg, var(--sena-green-dark), var(--sena-green));
            color: white;
        }

        .badge {
            font-size: 0.85rem;
            padding: 6px 12px;
            border-radius: 50px;
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
            text-shadow: 0 0 25px var(--sena-gold);
        }

        .form-control, .form-select {
            border-radius: 16px;
            padding: 12px 16px;
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
                            <div class="col-md-8">
                                <h4 class="mb-0 fw-bold">
                                    <i class="fas fa-history me-3 glow-gold"></i>
                                    Historial de Actividades
                                </h4>
                            </div>
                            <div class="col-md-4 text-end">
                                <small class="opacity-75">Registro de acciones del sistema</small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-5">

                        <!-- Filtros -->
                        <form method="GET" class="row g-3 mb-5">
                            <div class="col-md-4">
                                <label class="form-label text-light fw-semibold">Usuario:</label>
                                <select name="user_id" class="form-select">
                                    <option value="">— Todos los usuarios —</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->nickname }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-light fw-semibold">Acción:</label>
                                <select name="action" class="form-select">
                                    <option value="">— Todas las acciones —</option>
                                    @foreach($actions as $action)
                                        <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                                            {{ ucfirst($action) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-sena flex-grow-1">
                                    <i class="fas fa-filter me-2"></i> Filtrar
                                </button>
                                <a href="{{ route('senaapicola.admin.activities.history') }}" 
                                   class="btn btn-outline-light flex-grow-1">
                                    Limpiar
                                </a>
                            </div>
                        </form>

                        <!-- Tabla -->
                        <div class="table-container">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="ps-4">Fecha y Hora</th>
                                            <th>Usuario</th>
                                            <th>Rol</th>
                                            <th>Acción</th>
                                            <th>Descripción</th>
                                            <th>IP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($logs as $log)
                                            <tr>
                                                <td class="ps-4 fw-semibold">
                                                    {{ optional($log->logged_at)->format('d/m/Y H:i:s') ?? 
                                                       ($log->created_at ? $log->created_at->format('d/m/Y H:i:s') : '-') }}
                                                </td>
                                                <td>{{ $log->user_nickname ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        {{ $log->role_name ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge 
                                                        {{ $log->action === 'create' || $log->action === 'store' ? 'bg-success' : 
                                                           ($log->action === 'update' || $log->action === 'edit' ? 'bg-warning text-dark' : 
                                                           ($log->action === 'delete' ? 'bg-danger' : 'bg-info')) }}">
                                                        {{ ucfirst($log->action) }}
                                                    </span>
                                                </td>
                                                <td>{{ $log->description ?? '—' }}</td>
                                                <td class="font-monospace small text-muted">
                                                    {{ $log->ip_address ?? '—' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-5 text-muted">
                                                    <i class="fas fa-history fa-4x mb-4 opacity-25"></i><br>
                                                    No hay registros de historial de actividades.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Paginación -->
                        <div class="d-flex justify-content-center mt-5">
                            {{ $logs->links() }}
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