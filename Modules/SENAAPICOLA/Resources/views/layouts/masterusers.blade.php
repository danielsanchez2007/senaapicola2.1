@extends('senaapicola::layouts.master')

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --sena-green: #39a900;
            --sena-green-dark: #2d8000;
            --sena-orange: #ff9f1c;
            --sena-gold: #f5c518;
        }

        body {
            background: linear-gradient(180deg, #0a1f0a 0%, #0f2a0f 100%);
            color: white;
            overflow-x: hidden;
        }

        /* Partículas de polen flotando */
        .pollen-particle {
            position: fixed;
            width: 8px;
            height: 8px;
            background: radial-gradient(circle, var(--sena-gold), #ffeb3b);
            border-radius: 50%;
            box-shadow: 0 0 15px var(--sena-gold);
            pointer-events: none;
            z-index: 1;
            opacity: 0.7;
            animation: floatPollen linear infinite;
        }

        @keyframes floatPollen {
            0%   { transform: translateY(100vh) rotate(0deg); }
            100% { transform: translateY(-200px) rotate(720deg); }
        }

        .hero-section {
            background: linear-gradient(rgba(10, 31, 10, 0.85), rgba(15, 42, 15, 0.85)), 
                        url('https://images.unsplash.com/photo-1587049352851-8d4e89133924?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .navbar {
            background: rgba(10, 31, 10, 0.95) !important;
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(245, 197, 24, 0.3);
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 2.1rem;
            color: white;
        }

        .navbar-brand span {
            color: var(--sena-gold);
        }

        .nav-link {
            color: #e0f2e0 !important;
            font-weight: 500;
            transition: all 0.3s;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--sena-gold) !important;
        }

        .hero-title {
            font-size: 3.8rem;
            font-weight: 800;
            line-height: 1.1;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.6);
        }

        .hero-subtitle {
            font-size: 1.35rem;
            max-width: 720px;
            opacity: 0.95;
        }

        .btn-sena {
            background: linear-gradient(90deg, var(--sena-green), var(--sena-orange));
            color: white;
            border: none;
            border-radius: 50px;
            padding: 14px 36px;
            font-weight: 700;
            font-size: 1.1rem;
            box-shadow: 0 10px 30px rgba(57, 169, 0, 0.4);
            transition: all 0.3s;
        }

        .btn-sena:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(255, 159, 28, 0.5);
        }

        .feature-card {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(245, 197, 24, 0.25);
            border-radius: 24px;
            padding: 2.5rem 1.8rem;
            height: 100%;
            transition: all 0.4s ease;
        }

        .feature-card:hover {
            transform: translateY(-12px);
            border-color: var(--sena-gold);
            background: rgba(255,255,255,0.12);
        }

        .feature-icon {
            font-size: 3.2rem;
            color: var(--sena-gold);
            margin-bottom: 1.5rem;
        }

        .section-title {
            color: white;
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -12px;
            left: 0;
            width: 60%;
            height: 4px;
            background: linear-gradient(to right, var(--sena-green), var(--sena-orange));
            border-radius: 2px;
        }

        .glow-gold {
            text-shadow: 0 0 30px var(--sena-gold);
        }
    </style>
@endpush

@section('content')
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-hive me-2"></i><span>Sena</span>Apicola
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Infórmate</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Conócenos</a>
                    </li>
                    @if(Auth::check() && checkRol('senaapicola.admin'))
                        <li class="nav-item">
                            <a href="{{ route('senaapicola.admin.welcome') }}" class="nav-link">Administrador</a>
                        </li>
                    @endif
                    @if(Auth::check() && checkRol('senaapicola.intern'))
                        <li class="nav-item">
                            <a href="{{ route('senaapicola.intern.panelpas') }}" class="nav-link">Pasante</a>
                        </li>
                    @endif
                </ul>
                <div class="d-flex">
                    <a href="{{ route('login') }}" class="btn btn-sena">
                        <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container hero-content">
            <div class="row justify-content-center text-center">
                <div class="col-lg-10">
                    <h1 class="hero-title glow-gold mb-4">
                        Gestión Inteligente<br>de la Apicultura
                    </h1>
                    <p class="hero-subtitle mx-auto">
                        Plataforma especializada del Sena para el registro, seguimiento y análisis de apiarios, 
                        colmenas y producción de miel.
                    </p>
                    <div class="mt-5">
                        <a href="{{ route('login') }}" class="btn btn-sena btn-lg px-5 me-3">
                            Acceder al Sistema
                        </a>
                        <a href="#" class="btn btn-outline-light btn-lg px-5">
                            Conocer más
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Partículas de polen -->
        <div id="pollen-container"></div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="section-title display-5 fw-bold">Características Principales</h2>
                <p class="lead text-white-50 mt-3">Herramientas diseñadas para la gestión eficiente de tu apiario</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-hive"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Gestión de Colmenas</h4>
                        <p class="text-white-70">
                            Registro detallado de cada colmena, estado de salud, ubicación y seguimiento técnico.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Control de Producción</h4>
                        <p class="text-white-70">
                            Entradas y salidas de miel, polen y otros productos. Reportes de cosecha neta en tiempo real.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Tareas y Seguimientos</h4>
                        <p class="text-white-70">
                            Asignación de tareas a pasantes, seguimiento de actividades y registro de observaciones.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 mb-4">
                    <h3 class="fw-bold text-white mb-3">
                        <i class="fas fa-hive me-2"></i>Sena<span class="text-warning">Apicola</span>
                    </h3>
                    <p class="text-white-50">
                        Sistema de gestión apícola desarrollado para el programa Sena. 
                        Monitoreo, registro y análisis integral de la producción apícola.
                    </p>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="text-white mb-3">Enlaces</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white-50 text-decoration-none">Inicio</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Infórmate</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Conócenos</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="text-white mb-3">Sistema</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('login') }}" class="text-white-50 text-decoration-none">Iniciar Sesión</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 mb-4">
                    <h5 class="text-white mb-3">Contacto Sena</h5>
                    <p class="text-white-50 small">
                        Centro de Formación Agropecuaria<br>
                        Neiva - Huila
                    </p>
                </div>
            </div>
            <hr class="border-secondary">
            <div class="text-center text-white-50 small pt-3">
                © 2026 Sena Apícola - Todos los derechos reservados
            </div>
        </div>
    </footer>
@endsection

@push('scripts')
<script>
    // Partículas de polen flotando en la landing
    function createPollen() {
        const container = document.getElementById('pollen-container') || document.body;
        const particle = document.createElement('div');
        particle.classList.add('pollen-particle');

        const size = Math.random() * 9 + 6;
        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;
        particle.style.left = `${Math.random() * 100}vw`;
        particle.style.opacity = Math.random() * 0.6 + 0.4;

        const duration = Math.random() * 40 + 25;
        particle.style.animationDuration = `${duration}s`;
        particle.style.animationDelay = `-${Math.random() * 30}s`;

        container.appendChild(particle);
        setTimeout(() => particle.remove(), duration * 1000 + 2000);
    }

    setInterval(() => {
        if (Math.random() > 0.2) createPollen();
    }, 160);

    for (let i = 0; i < 30; i++) {
        setTimeout(createPollen, i * 80);
    }
</script>
@endpush