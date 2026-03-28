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

        .users-table {
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        .users-table thead {
            background: linear-gradient(90deg, var(--sena-green-dark), var(--sena-green));
            color: white;
        }

        .avatar-img {
            width: 52px;
            height: 52px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid var(--sena-gold);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
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
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0 fw-bold">
                                    <i class="fas fa-users me-3 glow-gold"></i>
                                    Gestión de Usuarios
                                </h4>
                            </div>
                            <div class="d-flex gap-3">
                                <a href="{{ route('senaapicola.admin.users.create') }}" class="btn btn-sena">
                                    <i class="fas fa-plus me-2"></i> Nuevo Usuario
                                </a>
                                <a href="{{ route('senaapicola.admin.users.report') }}" class="btn btn-danger">
                                    <i class="fas fa-file-pdf me-2"></i> Generar PDF
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-5">

                        <!-- Mensajes -->
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Tabla -->
                        <div class="users-table">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="ps-4">ID</th>
                                            <th>Foto</th>
                                            <th>Nombre / Nickname</th>
                                            <th>Email</th>
                                            <th>Rol</th>
                                            <th>Fecha de Creación</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($users as $user)
                                        <tr>
                                            <td class="ps-4 fw-bold text-success">{{ $user->id }}</td>
                                            <td>
                                                @php
                                                    $avatar = $user->person?->avatar;
                                                    $avatarUrl = $avatar ? (str_starts_with($avatar, 'http') ? $avatar : asset('storage/' . $avatar)) : null;
                                                @endphp
                                                @if($avatarUrl)
                                                    <img src="{{ $avatarUrl }}" alt="Avatar" class="avatar-img">
                                                @else
                                                    <div class="text-muted small">Sin foto</div>
                                                @endif
                                            </td>
                                            <td class="fw-semibold">{{ $user->nickname }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @foreach($user->roles as $role)
                                                    <span class="badge bg-info">{{ $role->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('senaapicola.admin.users.edit', $user->id) }}" 
                                                       class="btn btn-warning btn-sm px-3" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('senaapicola.admin.users.destroy', $user->id) }}" 
                                                          method="POST" style="display: inline;" 
                                                          onsubmit="return confirm('¿Eliminar al usuario {{ $user->nickname }}? Esta acción no se puede deshacer.')">
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
                                                <i class="fas fa-users fa-4x mb-4 opacity-25"></i><br>
                                                No hay usuarios registrados en este módulo
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Paginación -->
                        <div class="d-flex justify-content-center mt-5">
                            {{ $users->links() }}
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