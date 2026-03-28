@extends('layouts.app')

@section('title', 'Ingresar — SENA APICOLA')

@section('content')
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
            background: linear-gradient(135deg, #0a1f0a 0%, #112911 100%);
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .card {
            background: transparent;
            border: none;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.5);
            max-width: 980px;
            margin: 40px auto;
        }

        .c1 {
            background: linear-gradient(135deg, var(--sena-green-dark), #1f4a00);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            padding: 3rem 2rem;
        }

        /* Partículas de polen en el lado izquierdo */
        .pollen-particle {
            position: absolute;
            width: 7px;
            height: 7px;
            background: radial-gradient(circle, var(--sena-gold), #ffeb3b);
            border-radius: 50%;
            box-shadow: 0 0 10px var(--sena-gold);
            pointer-events: none;
            opacity: 0.7;
            animation: floatPollen 25s linear infinite;
        }

        @keyframes floatPollen {
            0%   { transform: translateY(120%) rotate(0deg); }
            100% { transform: translateY(-120%) rotate(360deg); }
        }

        .login-hero-icon {
            font-size: 6.5rem;
            color: var(--sena-gold);
            text-shadow: 0 0 30px rgba(245, 197, 24, 0.6);
            margin-bottom: 2rem;
        }

        .wlcm {
            font-size: 2.8rem;
            font-weight: 800;
            background: linear-gradient(90deg, white, var(--sena-gold));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .c2 {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            padding: 3rem 2.5rem;
            border-radius: 0 24px 24px 0;
        }

        .login-brand {
            text-decoration: none;
            font-size: 1.85rem;
            font-weight: 700;
            color: var(--sena-green-dark);
            display: block;
        }

        .login-brand__mark {
            color: var(--sena-green);
        }

        .login-brand__name {
            background: linear-gradient(90deg, var(--sena-green), var(--sena-orange));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .login-brand__tagline {
            font-size: 0.95rem;
            color: #555;
            margin-top: 4px;
        }

        form label {
            font-weight: 600;
            color: #333;
            margin-bottom: 6px;
            display: block;
        }

        form input {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #ddd;
            border-radius: 12px;
            font-size: 1rem;
            margin-bottom: 1.2rem;
            background: white;
        }

        form input:focus {
            border-color: var(--sena-orange);
            box-shadow: 0 0 0 4px rgba(255, 159, 28, 0.15);
            outline: none;
        }

        .btlogin {
            background: linear-gradient(90deg, var(--sena-green), var(--sena-orange));
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.05rem;
            width: 100%;
            margin: 15px 0 8px;
            box-shadow: 0 8px 25px rgba(57, 169, 0, 0.3);
        }

        .btlogin:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(255, 159, 28, 0.4);
        }

        .forgot {
            color: #666;
            text-decoration: none;
            font-size: 0.95rem;
        }

        .forgot:hover {
            color: var(--sena-orange);
        }

        .hero-img {
            position: relative;
            z-index: 2;
        }
    </style>

    <div class="container-fluid min-vh-100 d-flex align-items-center">
        <div class="row justify-content-center w-100">
            <div class="col-12 col-lg-10 col-xl-9">
                <div class="card">
                    <div class="row g-0">

                        <!-- Lado izquierdo - Visual (con partículas) -->
                        <div class="col-md-5 c1">
                            <div class="hero-img text-center">
                                <div class="login-hero-icon">
                                    <i class="fas fa-bee"></i>
                                </div>
                            </div>
                            <div class="text-center px-4">
                                <h1 class="wlcm">Bienvenido</h1>
                                <p class="small opacity-90 mb-4">
                                    Gestión inteligente de apiarios, colmenas y producción de miel
                                </p>
                                <div class="d-flex justify-content-center gap-2">
                                    <span class="px-4 py-1 bg-white bg-opacity-25 rounded-pill"></span>
                                    <span class="px-3 py-1 bg-white bg-opacity-25 rounded-circle"></span>
                                    <span class="px-3 py-1 bg-white bg-opacity-25 rounded-circle"></span>
                                </div>
                            </div>

                            <!-- Contenedor de partículas -->
                            <div id="pollen-container" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; pointer-events: none; z-index: 1;"></div>
                        </div>

                        <!-- Lado derecho - Formulario -->
                        <div class="col-md-7 c2">
                            <div class="mb-4">
                                <a href="{{ route('cefa.welcome') }}" class="login-brand">
                                    <span class="login-brand__mark">SENA</span>
                                    <span class="login-brand__name"> APICOLA</span>
                                </a>
                                <p class="login-brand__tagline">Servicio Nacional de Aprendizaje · Apicultura Sostenible</p>
                            </div>

                            <form method="POST" action="{{ route('login') }}" class="px-2">
                                @csrf

                                @if (session('status'))
                                    <div class="alert alert-success small py-2">{{ session('status') }}</div>
                                @endif

                                <h3 class="font-weight-bold mb-4 text-dark">Iniciar Sesión</h3>

                                <label for="email">{{ __('E-Mail Address') }}</label>
                                <input id="email" type="email" class="@error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" required autofocus>

                                @error('email')
                                    <span class="invalid-feedback d-block mb-3" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <label for="password">{{ __('Password') }}</label>
                                <input id="password" type="password" class="@error('password') is-invalid @enderror"
                                       name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback d-block mb-3" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <button type="submit" class="btlogin">Ingresar al Sistema</button>

                                <div class="text-center mt-3">
                                    <a href="{{ route('senaapicola.register') }}" class="btn btlogin text-white" style="background: linear-gradient(90deg, #555, #333);">
                                        Registrarse
                                    </a>
                                </div>

                                <div class="text-center mt-4">
                                    <a class="forgot" href="{{ route('password.request') }}">
                                        ¿Olvidaste tu contraseña?
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Partículas de polen flotando en el lado izquierdo
        function createPollen() {
            const container = document.getElementById('pollen-container');
            const particle = document.createElement('div');
            particle.classList.add('pollen-particle');

            const size = Math.random() * 8 + 5;
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            particle.style.left = `${Math.random() * 100}%`;
            particle.style.top = `${Math.random() * 80 + 20}%`;
            particle.style.opacity = Math.random() * 0.6 + 0.5;

            const duration = Math.random() * 22 + 18;
            particle.style.animationDuration = `${duration}s`;
            particle.style.animationDelay = `-${Math.random() * 15}s`;

            container.appendChild(particle);

            setTimeout(() => particle.remove(), duration * 1000 + 2000);
        }

        // Crear partículas continuamente
        setInterval(() => {
            if (Math.random() > 0.35) createPollen();
        }, 220);

        // Inicializar algunas partículas
        for (let i = 0; i < 12; i++) {
            setTimeout(createPollen, i * 150);
        }
    </script>
@endsection