@extends('senaapicola::layouts.masterpas')

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
    </style>
@endpush

@section('content')
<div class="container-fluid py-5 position-relative" style="z-index: 2;">
    <div class="row">
        <div class="col-12">
            <div class="main-card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-0 fw-bold">
                                <i class="fas fa-tasks me-3 glow-gold"></i>
                                Mis Tareas
                            </h4>
                        </div>
                        <div class="col-md-4 text-end">
                            <small class="opacity-75">Asignaciones y evidencias</small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-5">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Titulo</th>
                                        <th>Descripcion</th>
                                        <th>Fecha Límite</th>
                                        <th>Estado</th>
                                        <th class="text-center">Accion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tasks as $task)
                                        <tr>
                                            <td class="ps-4">
                                                <strong>{{ $task->title }}</strong>
                                            </td>
                                            <td>{{ $task->description ?? '-' }}</td>
                                            <td class="fw-semibold">
                                                {{ $task->due_date ? $task->due_date->format('d/m/Y') : '—' }}
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    {{ $task->status === 'completed' ? 'bg-success' : 
                                                       ($task->status === 'in_progress' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                                    {{ $task->status === 'pending' ? 'Pendiente' : 
                                                       ($task->status === 'in_progress' ? 'En Progreso' : 'Completada') }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if($task->status !== 'completed')
                                                    <form method="POST"
                                                          action="{{ route('senaapicola.intern.tasks.complete', $task->id) }}"
                                                          enctype="multipart/form-data"
                                                          class="d-inline-block text-start">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="file"
                                                               name="evidence"
                                                               accept="image/*"
                                                               class="form-control form-control-sm mb-2"
                                                               required>
                                                        <button type="submit" class="btn btn-sm btn-success px-3">
                                                            Completar
                                                        </button>
                                                    </form>
                                                @else
                                                    @if($task->evidence_path)
                                                        <a href="{{ asset('storage/' . $task->evidence_path) }}"
                                                           target="_blank"
                                                           class="btn btn-sm btn-outline-success">
                                                            <i class="fas fa-image me-1"></i>Ver evidencia
                                                        </a>
                                                    @else
                                                        <span class="text-muted small">Completada sin archivo</span>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted">
                                                <i class="fas fa-tasks fa-4x mb-4 opacity-25"></i><br>
                                                No tienes tareas asignadas.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-5">
                        {{ $tasks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="pollen-container"></div>
@endsection

@push('scripts')
<script>
    // Partículas de polen flotando (efecto esporas)
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
