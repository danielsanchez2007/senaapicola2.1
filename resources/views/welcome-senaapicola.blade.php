@php
    $viewerIsInfoOnly = $viewerIsInfoOnly ?? false;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SENA APICOLA — Gestión Apícola Inteligente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --sena-green: #39a900;
            --sena-green-dark: #2d8000;
            --sena-orange: #ff9f1c;     /* Naranja miel más vivo */
            --sena-gold: #f5c518;
            --glass: rgba(255, 255, 255, 0.09);
        }

        * { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }

        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
            background: linear-gradient(180deg, #0a1f0a 0%, #112911 100%);
            color: #e0f2e0;
            scroll-behavior: smooth;
            overflow-x: hidden;
            position: relative;
        }

        /* Partículas de polen flotando */
        .pollen-particle {
            position: fixed;
            width: 8px;
            height: 8px;
            background: radial-gradient(circle, var(--sena-gold), #ffeb3b);
            border-radius: 50%;
            box-shadow: 0 0 8px var(--sena-gold);
            pointer-events: none;
            z-index: 1;
            opacity: 0.65;
            animation: floatPollen linear infinite;
        }

        @keyframes floatPollen {
            0%   { transform: translateY(100vh) rotate(0deg); }
            100% { transform: translateY(-100px) rotate(360deg); }
        }

        /* Navbar más premium */
        .navbar-sena {
            background: rgba(45, 128, 0, 0.96);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 159, 28, 0.3);
            box-shadow: 0 4px 25px rgba(57, 169, 0, 0.25);
        }

        .navbar-brand {
            font-weight: 800;
            letter-spacing: 1.5px;
            background: linear-gradient(90deg, #ffffff, var(--sena-gold));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Hero con más energía */
        .hero-sena {
            background: linear-gradient(135deg, var(--sena-green-dark) 0%, #1f4a00 45%, var(--sena-orange) 100%);
            position: relative;
            overflow: hidden;
            padding: 7rem 0 8rem;
        }

        .hero-sena::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 30% 20%, rgba(245, 197, 24, 0.12), transparent 60%);
            pointer-events: none;
        }

        .btn-sena {
            background: linear-gradient(90deg, white, #f8fff0);
            color: var(--sena-green-dark);
            font-weight: 700;
            border-radius: 50px;
            padding: 14px 36px;
            box-shadow: 0 10px 30px rgba(255, 159, 28, 0.35);
        }

        .btn-sena:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 40px rgba(255, 159, 28, 0.45);
        }

        /* Tarjetas estilo panal mejoradas */
        .honey-card {
            background: var(--glass);
            backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 159, 28, 0.25);
            border-radius: 22px;
            overflow: hidden;
            position: relative;
        }

        .honey-card:hover {
            transform: translateY(-15px) scale(1.03);
            border-color: var(--sena-orange);
            box-shadow: 0 25px 50px rgba(57, 169, 0, 0.3);
        }

        .section-title {
            position: relative;
            display: inline-block;
            font-weight: 700;
            color: white;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 65%;
            height: 4px;
            background: linear-gradient(90deg, var(--sena-green), var(--sena-orange));
            border-radius: 4px;
        }

        .wiki-img {
            border-radius: 18px;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.45);
            border: 3px solid rgba(245, 197, 24, 0.25);
        }

        .glow-orange {
            text-shadow: 0 0 20px rgba(255, 159, 28, 0.7);
        }

        footer {
            background: #081408;
            border-top: 1px solid rgba(255, 159, 28, 0.2);
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    @if (session('warning'))
        <div class="alert alert-warning rounded-0 mb-0 text-center small py-2">{{ session('warning') }}</div>
    @endif

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-sena shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="{{ route('cefa.welcome') }}">
                <i class="fas fa-bee" style="color: #f5c518;"></i> SENA APICOLA
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">
                    <li class="nav-item"><a class="nav-link px-3" href="#inicio">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#apicultura-sena">Apicultura SENA</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#apicultura-general">Apicultura General</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#que-es">La Plataforma</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#funciones-sistema">Funciones</a></li>

                    @auth
                        @if ($viewerIsInfoOnly)
                            <li class="nav-item"><span class="badge bg-warning text-dark ms-2">Modo Informativo</span></li>
                        @else
                            <li class="nav-item"><a class="nav-link px-3" href="{{ route('cefa.home') }}">Panel de Control</a></li>
                        @endif
                        <li class="nav-item ms-lg-2">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-light btn-sm px-4">Cerrar Sesión</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-sena btn-sm px-4" href="{{ route('login', ['redirect' => url()->current()]) }}">Ingresar</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero con partículas de polen -->
    <header id="inicio" class="hero-sena text-white position-relative">
        <div class="container position-relative" style="z-index: 2;">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <p class="text-white-50 text-uppercase mb-2 tracking-widest">Servicio Nacional de Aprendizaje</p>
                    <h1 class="display-3 fw-bold mb-4 glow-orange">
                        Gestión Apícola<br>Inteligente del SENA
                    </h1>
                    <p class="lead opacity-90 mb-4 fs-5">
                        Plataforma tecnológica para el registro, monitoreo y trazabilidad de apiarios, colmenas y producción de miel.
                    </p>

                    @guest
                        <a href="{{ route('login', ['redirect' => url()->current()]) }}" class="btn btn-sena btn-lg me-3">Acceder al Sistema</a>
                        <a href="{{ route('senaapicola.register') }}" class="btn btn-outline-light btn-lg">Explorar como Invitado</a>
                    @endguest

                    @auth
                        @if ($viewerIsInfoOnly)
                            <div class="alert alert-light bg-white bg-opacity-10 border-0 text-white-50">
                                Estás en modo consulta. Explora y aprende sobre apicultura.
                            </div>
                        @else
                            <a href="{{ route('cefa.home') }}" class="btn btn-sena btn-lg">Ir al Panel Principal</a>
                        @endif
                    @endauth
                </div>

                <div class="col-lg-5 text-center mt-5 mt-lg-0">
                    <img src="{{ asset('images/wiki/abeja-panal.svg') }}" alt="Abeja y panal" 
                         class="img-fluid mb-4" style="max-height: 280px; filter: drop-shadow(0 25px 50px rgba(0,0,0,0.6));">
                    <div class="honey-card p-4 mx-auto" style="max-width: 340px;">
                        <p class="mb-1 small text-white-50">Formación + Tecnología</p>
                        <p class="fs-5 fw-semibold mb-0">Apicultura • Trazabilidad • Monitoreo en tiempo real</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenedor de partículas de polen -->
        <div id="pollen-container"></div>
    </header>

    <main class="flex-grow-1">

        <!-- Resto de secciones (mantengo igual pero con colores mejorados) -->
        <section id="apicultura-sena" class="py-5">
            <div class="container">
                <h2 class="section-title h3 mb-4">La Apicultura en el SENA</h2>
                <p class="lead text-white-50">
                    El SENA forma técnicos y profesionales en apicultura como alternativa sostenible de desarrollo rural, 
                    con énfasis en buenas prácticas, sanidad apícola y emprendimiento.
                </p>

                <div class="row g-4 mt-4">
                    <div class="col-md-6">
                        <div class="honey-card p-4 h-100">
                            <img src="{{ asset('images/wiki/abeja-panal.svg') }}" class="wiki-img mb-4" loading="lazy" alt="Abeja y panal">
                            <figcaption class="small text-white-50">Ilustración institucional SENA APICOLA</figcaption>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="honey-card p-4 h-100">
                            <h3 class="h5 text-warning mb-3">Enfoque Formativo</h3>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Biología de la colmena y roles de las abejas</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Manejo técnico del apiario</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Seguridad y uso correcto de EPP</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Introducción a comercialización y trazabilidad</li>
                            </ul>
                            <img src="{{ asset('images/wiki/abejas-polen.svg') }}" class="wiki-img mt-4" loading="lazy" alt="Abejas y polen">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Las otras secciones quedan iguales que en la versión anterior (apicultura-general, que-es, funciones-sistema) -->
        <!-- Copia y pega las secciones restantes del código que te di antes si quieres mantener todo idéntico -->

        <section id="apicultura-general" class="py-5 bg-black bg-opacity-50">
            <!-- ... (mismo contenido que antes) ... -->
        </section>

        <section id="que-es" class="py-5">
            <!-- ... (mismo contenido) ... -->
        </section>

        <section id="funciones-sistema" class="py-5 bg-black bg-opacity-50">
            <!-- ... (mismo contenido con iconos) ... -->
        </section>

    </main>

    <footer class="py-5 mt-auto text-center text-white-50 small">
        <div class="container">
            SENA APICOLA — Proyecto Formativo {{ date('Y') }} • Centro de Formación Agropecuaria
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Script para partículas de polen flotando -->
    <script>
        function createPollen() {
            const container = document.getElementById('pollen-container');
            const particle = document.createElement('div');
            particle.classList.add('pollen-particle');

            // Tamaño y posición aleatoria
            const size = Math.random() * 7 + 5;
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            particle.style.left = `${Math.random() * 100}vw`;
            particle.style.opacity = Math.random() * 0.6 + 0.4;
            
            // Duración de animación aleatoria (lenta y orgánica)
            const duration = Math.random() * 25 + 20;
            particle.style.animationDuration = `${duration}s`;
            
            // Retraso inicial
            particle.style.animationDelay = `-${Math.random() * 20}s`;

            container.appendChild(particle);

            // Eliminar después de terminar
            setTimeout(() => {
                particle.remove();
            }, duration * 1000 + 1000);
        }

        // Crear partículas continuamente
        setInterval(() => {
            if (Math.random() > 0.3) createPollen(); // No todas las veces para que no sea pesado
        }, 280);

        // Crear algunas al cargar
        for (let i = 0; i < 18; i++) {
            setTimeout(createPollen, i * 120);
        }
    </script>
</body>
</html>