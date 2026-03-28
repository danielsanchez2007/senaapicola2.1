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

        .welcome-header {
            background: linear-gradient(135deg, var(--sena-green-dark) 0%, var(--sena-orange) 100%);
            color: white;
            border-radius: 28px 28px 0 0;
            padding: 3rem 2.5rem 2rem;
            position: relative;
            overflow: hidden;
        }

        .welcome-header::before {
            content: '';
            position: absolute;
            top: -80px;
            right: -80px;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(255,255,255,0.25), transparent);
            border-radius: 50%;
        }

        .glow-gold {
            text-shadow: 0 0 30px var(--sena-gold);
        }

        .stat-card {
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(245, 197, 24, 0.3);
            border-radius: 20px;
            padding: 2rem 1.5rem;
            text-align: center;
            transition: all 0.4s ease;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            border-color: var(--sena-gold);
        }

        .btn-sena {
            background: linear-gradient(90deg, var(--sena-green), var(--sena-orange));
            color: white;
            border: none;
            border-radius: 50px;
            padding: 12px 28px;
            font-weight: 700;
            box-shadow: 0 8px 25px rgba(57, 169, 0, 0.35);
        }

        .btn-sena:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(255, 159, 28, 0.45);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-5 position-relative" style="z-index: 2;">
        <div class="row">
            <div class="col-12">

                <!-- Tarjeta Principal -->
                <div class="main-card">

                    <!-- Welcome Header -->
                    <div class="welcome-header">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <h1 class="fw-bold display-4 mb-2 glow-gold">
                                    <i class="fas fa-hive me-3"></i> Bienvenido, Administrador
                                </h1>
                                <p class="lead opacity-90 mb-0">
                                    Resumen del sistema apícola y gestión general de los apiarios
                                </p>
                            </div>
                            <div class="col-lg-4 text-end d-none d-lg-block">
                                <i class="fas fa-seedling fa-7x opacity-25"></i>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-5">

                        <!-- Mapa de Apiarios -->
                        <div class="mb-5">
                            @include('senaapicola::partials.apiary-map', ['apiaryMapMarkers' => $apiaryMapMarkers ?? collect()])
                        </div>

                        <!-- Estadísticas rápidas (opcional - puedes agregar tarjetas aquí) -->
                        <div class="row g-4 mb-5">
                            <div class="col-md-4">
                                <div class="stat-card text-white">
                                    <i class="fas fa-hive fa-3x mb-3" style="color: var(--sena-gold);"></i>
                                    <h5 class="mb-1">Apiarios Activos</h5>
                                    <h2 class="fw-bold mb-0">—</h2>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card text-white">
                                    <i class="fas fa-clipboard-check fa-3x mb-3" style="color: #ffeb3b;"></i>
                                    <h5 class="mb-1">Seguimientos Recientes</h5>
                                    <h2 class="fw-bold mb-0">—</h2>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card text-white">
                                    <i class="fas fa-boxes fa-3x mb-3" style="color: var(--sena-orange);"></i>
                                    <h5 class="mb-1">Producción Total</h5>
                                    <h2 class="fw-bold mb-0">—</h2>
                                </div>
                            </div>
                        </div>

                        <!-- Descripción del Módulo -->
                        <div class="bg-white bg-opacity-10 rounded-3 p-4 border border-white border-opacity-10">
                            <h4 class="fw-bold text-white mb-3">
                                <i class="fas fa-cogs me-2"></i> Funciones del Apartado de Administrador
                            </h4>
                            <p class="lead text-white-80 mb-0">
                                Este módulo permite gestionar toda la información del sistema apícola: 
                                agregar y listar apiarios, colmenas, registros de producción (entradas y salidas de miel), 
                                usuarios del sistema y el seguimiento detallado del estado de cada colmena.
                            </p>
                        </div>

                    </div>

                    <!-- Footer informativo -->
                    <div class="card-footer bg-transparent border-0 text-center py-4 small text-white-50">
                        Sistema de Gestión Apícola - Sena Apícola
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

    for (let i = 0; i < 25; i++) {
        setTimeout(createPollen, i * 90);
    }
</script>
@endpush