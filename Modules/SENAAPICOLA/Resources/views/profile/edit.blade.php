@extends($scope === 'admin' ? 'senaapicola::layouts.master' : 'senaapicola::layouts.masterpas')

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
        }

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
            0% { transform: translateY(100vh) rotate(0deg); }
            100% { transform: translateY(-140px) rotate(720deg); }
        }

        .main-card {
            background: var(--glass);
            backdrop-filter: blur(22px);
            border: 1px solid rgba(245, 197, 24, 0.35);
            border-radius: 28px;
            box-shadow: 0 30px 85px rgba(0, 0, 0, 0.6);
            overflow: hidden;
            position: relative;
        }

        .card-header {
            background: linear-gradient(135deg, var(--sena-green-dark) 0%, var(--sena-orange) 100%) !important;
            color: white;
            border: none;
            padding: 2rem 2.5rem;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
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
            text-shadow: 0 0 20px var(--sena-gold);
        }
    </style>
@endpush

@section('content')
@php
    $person = $user->person;
    $p = $person;
    $avatarUrl = null;
    if ($p && $p->avatar) {
        $avatarUrl = str_starts_with($p->avatar, 'http') ? $p->avatar : asset('storage/' . $p->avatar);
    }
    $updateRoute = $scope === 'admin' ? 'senaapicola.admin.profile.update' : 'senaapicola.intern.profile.update';
@endphp
<div class="container-fluid py-5 position-relative" style="z-index: 2;">
    <div class="row">
        <div class="col-12">
            <div class="main-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user-circle mr-2"></i>Mi perfil</h5>
                </div>
                <div class="card-body p-5">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if (!$p)
                <p class="text-danger">No hay registro de persona vinculado. Contacte al administrador.</p>
            @else
                <form action="{{ route($updateRoute) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-3 text-center mb-3">
                            @if ($avatarUrl)
                                <img src="{{ $avatarUrl }}" alt="Foto" class="img-fluid rounded border mb-2" style="max-height: 180px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded p-4 text-muted mb-2"><i class="fas fa-user fa-4x"></i></div>
                            @endif
                            <div class="custom-file">
                                <input type="file" name="avatar" id="avatar" class="custom-file-input @error('avatar') is-invalid @enderror" accept="image/*">
                                <label class="custom-file-label" for="avatar">Cambiar foto</label>
                            </div>
                            @error('avatar')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-9">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Nickname</label>
                                    <input type="text" name="nickname" class="form-control @error('nickname') is-invalid @enderror" value="{{ old('nickname', $user->nickname) }}" required>
                                    @error('nickname')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Correo de acceso (login)</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Nombres</label>
                                    <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $p->first_name) }}" required>
                                    @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Primer apellido</label>
                                    <input type="text" name="first_last_name" class="form-control @error('first_last_name') is-invalid @enderror" value="{{ old('first_last_name', $p->first_last_name) }}" required>
                                    @error('first_last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Segundo apellido</label>
                                    <input type="text" name="second_last_name" class="form-control @error('second_last_name') is-invalid @enderror" value="{{ old('second_last_name', $p->second_last_name) }}">
                                    @error('second_last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Correo personal</label>
                                    <input type="email" name="personal_email" class="form-control @error('personal_email') is-invalid @enderror" value="{{ old('personal_email', $p->personal_email) }}">
                                    @error('personal_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Teléfono</label>
                                    <input type="text" name="telephone1" class="form-control @error('telephone1') is-invalid @enderror" value="{{ old('telephone1', $p->telephone1) }}">
                                    @error('telephone1')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Dirección</label>
                                <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $p->address) }}">
                                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <hr>
                            <p class="text-muted small">Deje en blanco si no desea cambiar la contraseña.</p>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Nueva contraseña</label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Confirmar contraseña</label>
                                    <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Guardar cambios</button>
                        </div>
                    </div>
                </form>
            @endif
                </div>
            </div>
            <div id="pollen-container"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function createPollen() {
        const container = document.getElementById('pollen-container');
        if (!container) return;

        const particle = document.createElement('div');
        particle.classList.add('pollen-particle');

        const size = Math.random() * 8 + 6;
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

    for (let i = 0; i < 22; i++) {
        setTimeout(createPollen, i * 90);
    }
</script>
@endpush




