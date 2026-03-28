@extends('layouts.app')

@section('title', 'Registro — SENA APICOLA')

@section('content')
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
            background: linear-gradient(135deg, #0a1f0a 0%, #112911 100%);
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            min-height: 100vh;
            color: #e0f2e0;
        }

        .register-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            border: none;
            border-radius: 24px;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.45);
            overflow: hidden;
            max-width: 820px;
            margin: 40px auto;
        }

        .card-header {
            background: linear-gradient(135deg, var(--sena-green-dark) 0%, var(--sena-orange) 100%);
            color: white;
            border: none;
            padding: 2rem 2.5rem;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255,255,255,0.25), transparent);
            border-radius: 50%;
        }

        .form-control, .form-select {
            border: 2px solid #ddd;
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 1rem;
            background: white;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--sena-orange);
            box-shadow: 0 0 0 4px rgba(255, 159, 28, 0.18);
            outline: none;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .btn-register {
            background: linear-gradient(90deg, var(--sena-green), var(--sena-orange));
            color: white;
            border: none;
            padding: 14px 40px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.05rem;
            box-shadow: 0 10px 30px rgba(57, 169, 0, 0.35);
        }

        .btn-register:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 40px rgba(255, 159, 28, 0.45);
        }

        .btn-outline-secondary {
            border-radius: 50px;
            padding: 14px 32px;
        }

        .icon-bee {
            font-size: 3.8rem;
            color: var(--sena-gold);
            text-shadow: 0 0 25px rgba(245, 197, 24, 0.6);
        }
    </style>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-9 col-xl-8">
                <div class="register-card">

                    <!-- Header -->
                    <div class="card-header text-center">
                        <div class="icon-bee mb-3">
                            <i class="fas fa-bee"></i>
                        </div>
                        <h1 class="h3 fw-bold mb-2">Crear Cuenta</h1>
                        <p class="small opacity-90 mb-0">
                            Usuario informativo — Acceso a contenido educativo y wiki de apicultura del SENA
                        </p>
                    </div>

                    <!-- Body -->
                    <div class="card-body p-5">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('senaapicola.register.store') }}">
                            @csrf

                            <div class="row g-4">
                                <!-- Usuario y Correo -->
                                <div class="col-md-6">
                                    <label class="form-label">Usuario (nickname)</label>
                                    <input type="text" name="nickname" class="form-control" 
                                           value="{{ old('nickname') }}" required maxlength="255">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Correo electrónico</label>
                                    <input type="email" name="email" class="form-control" 
                                           value="{{ old('email') }}" required>
                                </div>

                                <!-- Contraseñas -->
                                <div class="col-md-6">
                                    <label class="form-label">Contraseña</label>
                                    <input type="password" name="password" class="form-control" 
                                           required minlength="8">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Confirmar contraseña</label>
                                    <input type="password" name="password_confirmation" class="form-control" 
                                           required minlength="8">
                                </div>

                                <!-- Documento -->
                                <div class="col-md-6">
                                    <label class="form-label">Tipo de documento</label>
                                    <select name="document_type" class="form-select" required>
                                        @foreach ([
                                            'Cédula de ciudadanía','Tarjeta de identidad','Cédula de extranjería','Pasaporte',
                                            'Documento nacional de identidad','Registro civil','Número de Identificación Tributaria',
                                        ] as $dt)
                                            <option value="{{ $dt }}" @selected(old('document_type') === $dt)>
                                                {{ $dt }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Número de documento</label>
                                    <input type="text" name="document_number" class="form-control" 
                                           value="{{ old('document_number') }}" required 
                                           inputmode="numeric" pattern="[0-9]{5,15}" 
                                           title="Solo números, entre 5 y 15 dígitos">
                                </div>

                                <!-- Nombres y Apellidos -->
                                <div class="col-md-4">
                                    <label class="form-label">Primer nombre</label>
                                    <input type="text" name="first_name" class="form-control" 
                                           value="{{ old('first_name') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Primer apellido</label>
                                    <input type="text" name="first_last_name" class="form-control" 
                                           value="{{ old('first_last_name') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Segundo apellido</label>
                                    <input type="text" name="second_last_name" class="form-control" 
                                           value="{{ old('second_last_name') }}">
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="d-flex flex-wrap gap-3 mt-5">
                                <button type="submit" class="btn btn-register flex-grow-1">
                                    <i class="fas fa-user-plus me-2"></i> Registrarme
                                </button>
                                <a href="{{ route('login') }}" class="btn btn-outline-secondary flex-grow-1">
                                    Volver al Login
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Footer sutil -->
                    <div class="card-footer bg-white border-0 text-center py-4 small text-muted">
                        Al registrarte aceptas los términos de uso como usuario informativo del SENA APICOLA.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection