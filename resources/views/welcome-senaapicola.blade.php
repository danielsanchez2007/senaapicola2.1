@php
    $viewerIsInfoOnly = $viewerIsInfoOnly ?? false;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SENA APICOLA — Apicultura y gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        :root {
            --sena-green: #39a900;
            --sena-green-dark: #2d8000;
        }
        body { font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; scroll-behavior: smooth; }
        .navbar-sena { background: linear-gradient(135deg, var(--sena-green) 0%, var(--sena-green-dark) 100%); }
        .hero-sena {
            background: linear-gradient(160deg, var(--sena-green-dark) 0%, var(--sena-green) 45%, #1a5c00 100%);
            color: #fff;
            padding: 4rem 0 5rem;
        }
        .btn-sena {
            background-color: #fff;
            color: var(--sena-green-dark);
            font-weight: 600;
            border: none;
        }
        .btn-sena:hover { background-color: #f0f7ec; color: var(--sena-green-dark); }
        .section-alt { background-color: #f8faf8; }
        .wiki-section h2 { color: var(--sena-green-dark); font-weight: 700; }
        .wiki-img { border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,.12); width: 100%; height: auto; object-fit: cover; display: block; background: #f1f8e9; }
        .wiki-hero-img { max-height: 220px; width: auto; margin: 0 auto; display: block; filter: drop-shadow(0 6px 20px rgba(0,0,0,.2)); }
        .wiki-caption { font-size: .78rem; color: #6c757d; margin-top: .35rem; }
        footer { background: #1e3a1e; color: #c8e6c9; }
        .toc-card { border-left: 4px solid var(--sena-green); }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    @if (session('warning'))
        <div class="alert alert-warning rounded-0 mb-0 text-center small">{{ session('warning') }}</div>
    @endif

    <nav class="navbar navbar-expand-lg navbar-dark navbar-sena shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('cefa.welcome') }}">SENA APICOLA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav ms-auto align-items-lg-center flex-wrap gap-lg-1">
                    <li class="nav-item"><a class="nav-link" href="#inicio">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="#apicultura-sena">Apicultura en el SENA</a></li>
                    <li class="nav-item"><a class="nav-link" href="#apicultura-general">Apicultura general</a></li>
                    <li class="nav-item"><a class="nav-link" href="#que-es">Qué es la plataforma</a></li>
                    <li class="nav-item"><a class="nav-link" href="#funciones-sistema">El sistema</a></li>
                    @auth
                        @if ($viewerIsInfoOnly)
                            <li class="nav-item"><span class="badge bg-light text-dark ms-lg-2">Solo informativo</span></li>
                        @else
                            <li class="nav-item"><a class="nav-link" href="{{ route('cefa.home') }}">Panel</a></li>
                        @endif
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline ms-lg-2">
                                @csrf
                                <button type="submit" class="btn btn-sena btn-sm">Salir</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="btn btn-sena btn-sm px-3 ms-lg-2" href="{{ route('login', ['redirect' => url()->current()]) }}">Ingresar</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <header id="inicio" class="hero-sena">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <p class="text-white-50 text-uppercase small mb-2">Servicio Nacional de Aprendizaje</p>
                    <h1 class="display-5 fw-bold mb-3">Sistema de información para la gestión apícola</h1>
                    <p class="lead mb-4 opacity-90">
                        Registro de apiarios, colmenas, producción de miel y seguimiento del estado de las colmenas
                        en un solo lugar, alineado con la formación y la práctica del programa SENA.
                    </p>
                    @guest
                        <a href="{{ route('login', ['redirect' => url()->current()]) }}" class="btn btn-sena btn-lg me-2">Acceder al sistema</a>
                        <a href="{{ route('senaapicola.register') }}" class="btn btn-outline-light btn-lg">Registrarse (solo contenido informativo)</a>
                    @endguest
                    @auth
                        @if ($viewerIsInfoOnly)
                            <p class="mb-0 small text-white-50">Estás en el modo de consulta: navega las secciones para aprender sobre apicultura y el SENA.</p>
                        @else
                            <a href="{{ route('cefa.home') }}" class="btn btn-sena btn-lg">Ir al panel</a>
                        @endif
                    @endauth
                </div>
                <div class="col-lg-5 mt-4 mt-lg-0 text-center">
                    <img src="{{ asset('images/wiki/abeja-panal.svg') }}" alt="Abeja y panal" class="wiki-hero-img mb-3" width="320" height="200" decoding="async">
                    <div class="rounded-4 bg-white bg-opacity-10 p-4 border border-white border-opacity-25">
                        <p class="mb-0 small text-white-50">Módulo académico y de práctica</p>
                        <p class="fs-5 fw-semibold mb-0 mt-2">Apicultura — trazabilidad y monitoreo</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow-1">
        {{-- Contenido primordial: apicultura en el SENA --}}
        <section id="apicultura-sena" class="py-5 wiki-section">
            <div class="container">
                <h2 class="h3 mb-3">La apicultura en el SENA</h2>
                <p class="text-muted lead fs-6">
                    En el SENA, la apicultura se trabaja como una línea de formación técnica vinculada al campo, la sostenibilidad
                    y el emprendimiento rural. Los centros promueven prácticas seguras, manejo sanitario de colmenas,
                    buenas prácticas de manufactura en productos de la colmena y articulación con productores locales.
                </p>
                <p class="text-muted">
                    Los aprendices y comunidad educativa pueden acercarse al mundo de las abejas melíferas desde lo pedagógico:
                    visitas a apiarios demostrativos, registros de campo, análisis de producción y trazabilidad básica.
                    Esta plataforma apoya ese proceso al centralizar información de apiarios, colmenas y producción cuando
                    el usuario cuenta con rol de gestión; para el público general y usuarios informativos, aquí encontrarás
                    referencia teórica y contexto del programa.
                </p>
                <div class="row g-4 mt-2 align-items-start">
                    <div class="col-md-6">
                        <figure class="mb-0">
                            <img class="wiki-img" loading="lazy" width="800" height="500" decoding="async"
                                 src="{{ asset('images/wiki/abeja-panal.svg') }}"
                                 alt="Ilustración: abeja y panal">
                            <figcaption class="wiki-caption">Ilustración: abeja melífera y panal — material local SENA APICOLA.</figcaption>
                        </figure>
                    </div>
                    <div class="col-md-6">
                        <h3 class="h5 text-success">Enfoque formativo</h3>
                        <ul class="text-muted small">
                            <li>Biología básica de la colmena y roles de obreras, zánganos y reina.</li>
                            <li>Manejo del apiario: ubicación, sombra, agua y calendario florístico.</li>
                            <li>Seguridad y uso de equipos de protección (EPP).</li>
                            <li>Introducción a la comercialización y normativa aplicable (referencia general).</li>
                        </ul>
                        <figure class="mt-3 mb-0">
                            <img class="wiki-img" loading="lazy" width="800" height="450" decoding="async"
                                 src="{{ asset('images/wiki/abejas-polen.svg') }}"
                                 alt="Ilustración: abejas y flores">
                            <figcaption class="wiki-caption">Ilustración: polinización y entorno floral — material local SENA APICOLA.</figcaption>
                        </figure>
                    </div>
                </div>
            </div>
        </section>

        {{-- Apicultura en general (tipo wiki) --}}
        <section id="apicultura-general" class="py-5 section-alt wiki-section">
            <div class="container">
                <h2 class="h3 mb-4">Apicultura en general</h2>
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="card h-100 border-0 shadow-sm toc-card">
                            <div class="card-body">
                                <h3 class="h6 fw-bold text-success">La colmena</h3>
                                <p class="text-muted small mb-0">
                                    Una colmena social está organizada en torno a una reina fecunda, zánganos en época reproductiva
                                    y miles de obreras que cuidan cría, limpian la colmena, defienden el nido y forrajean.
                                    El panal es donde se almacenan miel, polen y jalea real.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card h-100 border-0 shadow-sm toc-card">
                            <div class="card-body">
                                <h3 class="h6 fw-bold text-success">Productos apícolas</h3>
                                <p class="text-muted small mb-0">
                                    <strong>Miel</strong>: alimento elaborado a partir del néctar. <strong>Polen</strong>: fuente proteica.
                                    <strong>Propóleo</strong>: resinas vegetales mezcladas con secreciones de las abejas. <strong>Cera</strong>:
                                    utilizada para construir panales. Cada producto requiere buenas prácticas de extracción e higiene.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card h-100 border-0 shadow-sm toc-card">
                            <div class="card-body">
                                <h3 class="h6 fw-bold text-success">Sanidad y manejo</h3>
                                <p class="text-muted small mb-0">
                                    Vigilar la fuerza de la colonia, presencia de cría tapada, alimentación en épocas de escasez
                                    y signos de plagas o enfermedades es esencial. El asesoramiento técnico oficial complementa
                                    la labor del apicultor.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-4 mt-2">
                    <div class="col-md-6">
                        <figure>
                            <img class="wiki-img" loading="lazy" width="800" height="520" decoding="async"
                                 src="{{ asset('images/wiki/abeja-flor.svg') }}"
                                 alt="Ilustración: abeja en flor">
                            <figcaption class="wiki-caption">Ilustración: forrajeo en flor — material local SENA APICOLA.</figcaption>
                        </figure>
                    </div>
                    <div class="col-md-6">
                        <figure>
                            <img class="wiki-img" loading="lazy" width="800" height="520" decoding="async"
                                 src="{{ asset('images/wiki/abeja-aterrizaje.svg') }}"
                                 alt="Ilustración: abeja aterrizando en flor">
                            <figcaption class="wiki-caption">Ilustración: aproximación a la flor — material local SENA APICOLA.</figcaption>
                        </figure>
                    </div>
                </div>
                <div class="alert alert-light border border-success-subtle mt-4 mb-0 small text-muted">
                    <strong>Nota:</strong> este apartado es divulgativo. Para tratamientos veterinarios, normativa sanitaria o
                    registro de establecimientos, consulta siempre las fuentes oficiales del ICA, Ministerio de Agricultura y
                    normativa vigente en Colombia.
                </div>
            </div>
        </section>

        {{-- Separado: qué es la plataforma --}}
        <section id="que-es" class="py-5 wiki-section">
            <div class="container">
                <h2 class="h3 mb-3">¿Qué es SENA APICOLA (la plataforma)?</h2>
                <p class="text-muted lead fs-6">
                    Es la aplicación web del proyecto para apoyar la gestión documental y operativa de unidades apícolas cuando
                    el usuario tiene permisos de administrador o pasante: ubicación de apiarios, inventario de colmenas,
                    entradas y salidas de producción y reportes de seguimiento.
                </p>
                <p class="text-muted mb-0">
                    Si te registraste como <strong>usuario informativo</strong>, no ingresas a esos módulos: tu cuenta sirve para
                    leer este contenido de referencia y mantenerte enlazado al ecosistema formativo del SENA en apicultura.
                </p>
            </div>
        </section>

        <section id="funciones-sistema" class="py-5 section-alt wiki-section">
            <div class="container">
                <h2 class="h3 mb-4">Funciones del sistema (usuarios con panel)</h2>
                <div class="row g-4">
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <h3 class="h6 fw-bold">Apiarios y mapa</h3>
                                <p class="text-muted small mb-0">Registro de apiarios con referencia geográfica y vista en mapa.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <h3 class="h6 fw-bold">Colmenas y monitoreo</h3>
                                <p class="text-muted small mb-0">Estado de colmenas y seguimiento para apoyo técnico.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <h3 class="h6 fw-bold">Producción</h3>
                                <p class="text-muted small mb-0">Control de entradas y salidas de miel u otros productos registrados.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="py-4 mt-auto">
        <div class="container small text-center">
            <span class="text-white-50">SENA APICOLA — {{ date('Y') }}</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
