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

        .form-card {
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
            padding: 2.5rem 2.5rem 2rem;
            text-align: center;
            position: relative;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: -120px;
            right: -100px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.25), transparent);
            border-radius: 50%;
        }

        .form-control, .form-select {
            background: rgba(255,255,255,0.95);
            border: 2px solid #e0e0e0;
            border-radius: 16px;
            padding: 15px 20px;
            font-size: 1.05rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--sena-orange);
            box-shadow: 0 0 0 5px rgba(255, 159, 28, 0.25);
            outline: none;
        }

        .form-label {
            font-weight: 600;
            color: #e0f2e0;
            margin-bottom: 8px;
        }

        .btn-create {
            background: linear-gradient(90deg, var(--sena-green), var(--sena-orange));
            color: white;
            border: none;
            border-radius: 50px;
            padding: 14px 42px;
            font-weight: 700;
            font-size: 1.08rem;
            box-shadow: 0 10px 30px rgba(57, 169, 0, 0.35);
        }

        .btn-create:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 40px rgba(255, 159, 28, 0.45);
        }

        .glow-gold {
            text-shadow: 0 0 25px var(--sena-gold);
        }

        .note-box {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(245, 197, 24, 0.4);
            border-radius: 16px;
            padding: 1.5rem;
        }
    </style>
@endpush

@section('content')
    <div class="container py-5 position-relative" style="z-index: 2;">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-9 col-xl-7">
                <div class="form-card">

                    <!-- Header -->
                    <div class="card-header">
                        <div class="mb-4">
                            <i class="fas fa-user-plus fa-4x glow-gold"></i>
                        </div>
                        <h3 class="fw-bold mb-2">Crear Nuevo Usuario</h3>
                        <p class="opacity-80 small mb-0">
                            Registra un nuevo usuario con acceso al sistema SENA APICOLA
                        </p>
                    </div>

                    <div class="card-body p-5">

                        <!-- Nota informativa -->
                        <div class="note-box mb-5">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-info-circle me-2"></i> Información importante
                            </h6>
                            <p class="mb-2 small">
                                • Al crear el usuario se asignará automáticamente una persona del sistema.<br>
                                • Solo puede seleccionar roles del módulo SENA APICOLA.<br>
                                • La contraseña debe ser confirmada correctamente.
                            </p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('senaapicola.admin.users.store') }}" method="POST">
                            @csrf
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label">Nombre / Nickname</label>
                                    <input type="text" class="form-control" id="nickname" name="nickname" 
                                           value="{{ old('nickname') }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="{{ old('email') }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">ID de Persona</label>
                                    <input type="text" class="form-control" id="person_id" name="person_id" 
                                           value="{{ old('person_id', $suggestedPersonId ?? '') }}" readonly>
                                    <small class="form-text text-white-50">Se asignará automáticamente</small>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Rol</label>
                                    <select class="form-select" id="role_id" name="role_id" required>
                                        <option value="">— Seleccione un rol —</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Contraseña</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Confirmar Contraseña</label>
                                    <input type="password" class="form-control" id="password_confirmation" 
                                           name="password_confirmation" required>
                                </div>
                            </div>

                            <div class="d-flex gap-3 mt-5">
                                <button type="submit" class="btn btn-create flex-grow-1">
                                    <i class="fas fa-user-plus me-2"></i> Crear Usuario
                                </button>
                                <a href="{{ route('senaapicola.admin.users.index') }}" 
                                   class="btn btn-outline-light flex-grow-1">
                                    Cancelar
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Footer -->
                    <div class="card-footer bg-transparent border-0 text-center py-4 small text-white-50">
                        El usuario podrá acceder al sistema según el rol asignado.
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