@extends('layouts.app')

@section('title', 'Registro — SENA APICOLA')

@section('content')
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 col-xl-7">
            <div class="card shadow border-0 rounded-4 overflow-hidden">
                <div class="card-header text-white border-0 py-3" style="background: linear-gradient(135deg, #2d8000 0%, #39a900 100%);">
                    <h1 class="h4 mb-0 fw-bold">Crear cuenta — Usuario informativo</h1>
                    <p class="small mb-0 opacity-90">Podrás consultar la wiki de apicultura y el contenido del SENA. No incluye acceso al panel de gestión.</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    @if ($errors->any())
                        <div class="alert alert-danger small">
                            <ul class="mb-0 ps-3">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('senaapicola.register.store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Usuario (nickname)</label>
                                <input type="text" name="nickname" class="form-control" value="{{ old('nickname') }}" required maxlength="255">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Correo electrónico</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contraseña</label>
                                <input type="password" name="password" class="form-control" required minlength="8">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirmar contraseña</label>
                                <input type="password" name="password_confirmation" class="form-control" required minlength="8">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tipo de documento</label>
                                <select name="document_type" class="form-select" required>
                                    @foreach ([
                                        'Cédula de ciudadanía','Tarjeta de identidad','Cédula de extranjería','Pasaporte',
                                        'Documento nacional de identidad','Registro civil','Número de Identificación Tributaria',
                                    ] as $dt)
                                        <option value="{{ $dt }}" @selected(old('document_type') === $dt)>{{ $dt }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Número de documento</label>
                                <input type="text" name="document_number" class="form-control" value="{{ old('document_number') }}" required inputmode="numeric" pattern="[0-9]{5,15}" title="Solo números, entre 5 y 15 dígitos">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Primer nombre</label>
                                <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Primer apellido</label>
                                <input type="text" name="first_last_name" class="form-control" value="{{ old('first_last_name') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Segundo apellido</label>
                                <input type="text" name="second_last_name" class="form-control" value="{{ old('second_last_name') }}">
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mt-4">
                            <button type="submit" class="btn text-white px-4" style="background: linear-gradient(180deg,#39a900,#2d8000); border: none;">Registrarme</button>
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary">Volver al login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
