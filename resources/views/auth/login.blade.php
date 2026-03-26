@extends('layouts.app')

@section('title', 'Ingresar — SENA APICOLA')

@section('content')
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-10 col-lg-8 col-xl-8">
            <div class="card d-flex mx-auto my-5">
                <div class="row">
                    <div class="col-md-5 col-sm-12 col-xs-12 c1 p-5">
                        <div id="hero" class="bg-transparent h-auto order-1 order-lg-2 hero-img">
                            <div class="login-hero-icon" aria-hidden="true">
                                <i class="fas fa-seedling"></i>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="w-75 mx-md-5 mx-1 mx-sm-2 mb-5 mt-4 px-sm-5 px-md-2 px-xl-1 px-2">
                                <h1 class="wlcm">Bienvenido</h1>
                                <p class="small text-muted mb-2">Gestión de apiarios, colmenas y producción.</p>
                                <span class="sp1">
                                    <span class="px-3 rounded-pill"></span>
                                    <span class="ml-2 px-1 rounded-circle"></span>
                                    <span class="ml-2 px-1 rounded-circle"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7 col-sm-12 col-xs-12 c2 px-5 pt-5">
                        <div class="row mb-4 m-3">
                            <a href="{{ route('cefa.welcome') }}" class="login-brand">
                                <span class="login-brand__mark">SENA</span><span class="login-brand__name"> APICOLA</span>
                            </a>
                            <p class="login-brand__tagline">Servicio Nacional de Aprendizaje · Apicultura</p>
                        </div>
                        <form method="POST" action="{{ route('login') }}" class="px-5 pb-5" name="myform">
                            @csrf
                            @if (session('status'))
                                <div class="alert alert-success small py-2">{{ session('status') }}</div>
                            @endif
                            <div class="d-flex">
                                <h3 class="font-weight-bold">{{ __('Login') }}</h3>
                            </div>

                            <label for="email" class="col-md-12 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
                            <input id="email" type="text" class="@error('email') is-invalid @enderror" name="email"
                                value="{{ old('email') }}" required autofocus>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                            <label for="password" class="col-md-12 col-form-label text-md-right">{{ __('Password') }}</label>
                            <input id="password" type="password" class="@error('password') is-invalid @enderror"
                                name="password" required autocomplete="current-password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                            <button type="submit" class="text-white text-weight-bold btlogin">Login</button>
                            <a href="{{ route('senaapicola.register') }}" class="btn btlogin text-white">Registrarse</a>
                            <a class="btn btn-link forgot" href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
