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

        .form-control, .form-select, textarea {
            background: rgba(255,255,255,0.95);
            border: 2px solid #e0e0e0;
            border-radius: 16px;
            padding: 15px 20px;
            font-size: 1.05rem;
        }

        .form-control:focus, .form-select:focus, textarea:focus {
            border-color: var(--sena-orange);
            box-shadow: 0 0 0 5px rgba(255, 159, 28, 0.25);
            outline: none;
        }

        .form-label {
            font-weight: 600;
            color: #e0f2e0;
            margin-bottom: 8px;
        }

        .btn-save {
            background: linear-gradient(90deg, var(--sena-green), var(--sena-orange));
            color: white;
            border: none;
            border-radius: 50px;
            padding: 14px 42px;
            font-weight: 700;
            font-size: 1.08rem;
            box-shadow: 0 10px 30px rgba(57, 169, 0, 0.35);
        }

        .btn-save:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 40px rgba(255, 159, 28, 0.45);
            color: white;
        }

        .btn-cancel {
            border: 2px solid rgba(255,255,255,0.4);
            color: white;
            border-radius: 50px;
            padding: 14px 42px;
            font-weight: 600;
            font-size: 1.08rem;
        }

        .btn-cancel:hover {
            background: rgba(255,255,255,0.1);
            border-color: white;
            color: white;
        }

        .glow-gold {
            text-shadow: 0 0 25px var(--sena-gold);
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.15);
            border: 1px solid rgba(220, 53, 69, 0.4);
            color: #ff8a8a;
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }
    </style>
@endpush

@section('content')
<div class="container py-5 position-relative" style="z-index: 2;">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <div class="form-card">
                <div class="card-header">
                    <div class="mb-4">
                        <i class="fas fa-clipboard-check fa-4x glow-gold"></i>
                    </div>
                    <h3 class="fw-bold mb-2">Registrar Nuevo Seguimiento</h3>
                    <p class="opacity-80 small mb-0">Registra una observación del apiario seleccionado</p>
                </div>
                <div class="card-body p-5">
            <form action="{{ route('senaapicola.intern.monitorings.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date" class="form-label">Fecha</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="apiary_id" class="form-label">Apiario</label>
                        <select name="apiary_id" class="form-control" required>
                            <option value="">Seleccione un apiario</option>
                            @foreach($apiaries as $apiary)
                                <option value="{{ $apiary->id }}">{{ $apiary->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="user_nickname" class="form-label">Usuario</label>
                        <select id="user_nickname" name="user_nickname" class="form-control" required>
                            <option value="">Seleccione un usuario</option>
                            @foreach($users as $user)
                                @php
                                    $userRole = $user->roles->first()?->slug ?? '';
                                @endphp
                                @if(in_array($userRole, ['senaapicola.admin', 'senaapicola.intern']))
                                    <option value="{{ $user->nickname }}" data-role="{{ $userRole }}">{{ $user->nickname }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="role" class="form-label">Rol</label>
                        <input type="text" name="role" id="role" class="form-control" readonly required>
                        <input type="hidden" name="role" id="real_role">

                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea name="description" rows="3" class="form-control" placeholder="Escriba una descripción..."></textarea>
                    </div>
                </div>

                <div class="d-flex gap-3 mt-5">
                    <button type="submit" class="btn btn-save flex-grow-1">
                        <i class="fas fa-save me-2"></i> Guardar Seguimiento
                    </button>
                    <a href="{{ route('senaapicola.intern.monitorings.index') }}" class="btn btn-cancel flex-grow-1 text-center">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userSelect = document.getElementById('user_nickname');
        const roleInput = document.getElementById('role');

        const roleMapping = {
            'senaapicola.admin': 'Administrador del Sistema',
            'senaapicola.intern': 'Pasante'
        };

        userSelect.addEventListener('change', function () {
            const selectedOption = userSelect.options[userSelect.selectedIndex];
            const role = selectedOption.getAttribute('data-role') || '';
            const readableRole = roleMapping[role] || '';
            roleInput.value = readableRole;

            // también actualizamos un input oculto para enviar el valor real
            document.getElementById('real_role').value = role;
        });
    });
</script>

    <div id="pollen-container"></div>

@endsection

@push('scripts')
<script>
    function createPollen() {
        const container = document.getElementById('pollen-container');
        if (!container) return;

        const particle = document.createElement('div');
        particle.classList.add('pollen-particle');

        const size = Math.random() * 8 + 5;
        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;
        particle.style.left = `${Math.random() * 100}vw`;
        particle.style.opacity = Math.random() * 0.7 + 0.3;

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