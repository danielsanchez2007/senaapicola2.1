@extends('senaapicola::layouts.master')

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --sena-green: #39a900;
            --sena-green-dark: #2d8000;
            --sena-orange: #ff9f1c;
            --sena-gold: #f5c518;
            --glass: rgba(255, 255, 255, 0.08);
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
            100% { transform: translateY(-120px) rotate(720deg); }
        }

        .form-card {
            background: var(--glass);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(245, 197, 24, 0.3);
            border-radius: 28px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.55);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, var(--sena-green-dark) 0%, var(--sena-orange) 100%) !important;
            color: white;
            border: none;
            padding: 2.2rem 2.5rem;
            position: relative;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 280px;
            height: 280px;
            background: radial-gradient(circle, rgba(255,255,255,0.22), transparent);
            border-radius: 50%;
        }

        .form-control, .form-select {
            background: rgba(255,255,255,0.95);
            border: 2px solid #ddd;
            border-radius: 16px;
            padding: 16px 20px;
            font-size: 1.05rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--sena-orange);
            box-shadow: 0 0 0 5px rgba(255, 159, 28, 0.25);
            outline: none;
        }

        .form-label {
            font-weight: 600;
            color: #e0f2e0;
            margin-bottom: 10px;
            font-size: 1.05rem;
        }

        .btn-update {
            background: linear-gradient(90deg, var(--sena-green), var(--sena-orange));
            color: white;
            border: none;
            border-radius: 50px;
            padding: 14px 40px;
            font-weight: 700;
            font-size: 1.1rem;
            box-shadow: 0 12px 35px rgba(57, 169, 0, 0.4);
        }

        .btn-update:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 45px rgba(255, 159, 28, 0.5);
        }

        .glow-gold {
            text-shadow: 0 0 20px var(--sena-gold);
        }
    </style>
@endpush

@section('content')
    <div class="container py-5 position-relative" style="z-index: 2;">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8 col-xl-7">
                <div class="form-card">

                    <!-- Header -->
                    <div class="card-header text-center">
                        <div class="mb-3">
                            <i class="fas fa-edit fa-4x glow-gold"></i>
                        </div>
                        <h3 class="fw-bold mb-1">Editar Colmena</h3>
                        <p class="opacity-75 mb-0">Actualiza los datos de la colmena para mantener el seguimiento preciso</p>
                    </div>

                    <div class="card-body p-5">
                        <form action="{{ route('senaapicola.admin.hives.update', $hive->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label class="form-label">Nombre de la Colmena</label>
                                <input type="text" name="name" id="name" class="form-control" 
                                       value="{{ old('name', $hive->name) }}" 
                                       placeholder="Ej: Colmena Principal A-03" required>
                            </div>

                            <div class="mb-5">
                                <label class="form-label">Apiario</label>
                                <select name="apiary_id" id="apiary_id" class="form-select" required>
                                    <option value="">— Seleccione un apiario —</option>
                                    @foreach($apiaries as $apiary)
                                        <option value="{{ $apiary->id }}" 
                                            {{ $apiary->id == $hive->apiary_id ? 'selected' : '' }}>
                                            {{ $apiary->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="d-flex gap-3 mt-4">
                                <button type="submit" class="btn btn-update flex-grow-1">
                                    <i class="fas fa-save me-2"></i> Actualizar Colmena
                                </button>
                                <a href="{{ route('senaapicola.admin.hives.index') }}" 
                                   class="btn btn-outline-light flex-grow-1">
                                    Cancelar
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Footer informativo -->
                    <div class="card-footer bg-transparent border-0 text-center py-4 small text-white-50">
                        Los cambios se reflejarán inmediatamente en el mapa de apiarios y en los reportes.
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
    // Partículas de polen flotando (efecto esporas)
    function createPollen() {
        const container = document.getElementById('pollen-container');
        const particle = document.createElement('div');
        particle.classList.add('pollen-particle');

        const size = Math.random() * 8 + 5;
        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;
        particle.style.left = `${Math.random() * 100}vw`;
        particle.style.opacity = Math.random() * 0.7 + 0.4;

        const duration = Math.random() * 32 + 22;
        particle.style.animationDuration = `${duration}s`;
        particle.style.animationDelay = `-${Math.random() * 25}s`;

        container.appendChild(particle);

        setTimeout(() => particle.remove(), duration * 1000 + 1500);
    }

    // Crear partículas continuamente
    setInterval(() => {
        if (Math.random() > 0.28) createPollen();
    }, 200);

    // Inicializar varias al cargar
    for (let i = 0; i < 20; i++) {
        setTimeout(createPollen, i * 110);
    }
</script>
@endpush