@extends('senaapicola::layouts.master')

@section('title', 'Editar Usuario')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h3 class="card-title">Editar Usuario</h3>
                </div>
                <div class="card-body">
                    <!-- Información actual del usuario -->
                    <div class="alert alert-info">
                        <h6><strong>Información actual:</strong></h6>
                        <p class="mb-1"><strong>Nombre:</strong> {{ $user->nickname }}</p>
                        <p class="mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                        <p class="mb-0"><strong>Rol actual:</strong> {{ $user->roles->pluck('name')->join(', ') }}</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Nota:</strong> Puede modificar la información del usuario. Si cambia la contraseña, debe confirmarla. 
                        Solo puede seleccionar un rol del módulo SENAAPICOLA.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    <form action="{{ route('senaapicola.admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group mb-3">
                            <label for="nickname">Nombre Completo</label>
                            <input type="text" class="form-control" id="nickname" name="nickname" value="{{ old('nickname', $user->nickname) }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="role_id">Rol</label>
                            <select class="form-control" id="role_id" name="role_id" required>
                                <option value="">Seleccione un rol</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id', $user->roles->first()->id ?? '') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="password">Nueva Contraseña (dejar en blanco para mantener la actual)</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>

                        <div class="form-group mb-3">
                            <label for="password_confirmation">Confirmar Nueva Contraseña</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            <small class="form-text text-muted">Si cambias la contraseña, debes confirmarla. Debe coincidir con la nueva contraseña.</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('senaapicola.admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save"></i> Actualizar Usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
