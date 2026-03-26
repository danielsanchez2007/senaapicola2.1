@extends('senaapicola::layouts.master')

@section('title', 'Crear Usuario')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h3 class="card-title">Crear Nuevo Usuario</h3>
                </div>
                <div class="card-body">
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
                        <strong>Nota:</strong> Al crear un usuario, se le asignará automáticamente una persona del sistema. 
                        Solo puede seleccionar un rol del módulo SENAAPICOLA.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    <form action="{{ route('senaapicola.admin.users.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group mb-3">
                            <label for="nickname">Nombre Completo</label>
                            <input type="text" class="form-control" id="nickname" name="nickname" value="{{ old('nickname') }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="person_id">ID de Persona</label>
                            <input type="text" class="form-control" id="person_id" name="person_id" value="{{ old('person_id', $suggestedPersonId ?? '') }}" readonly>
                            <small class="form-text text-muted">Se asignará automáticamente una persona del sistema</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="role_id">Rol</label>
                            <select class="form-control" id="role_id" name="role_id" required>
                                <option value="">Seleccione un rol</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="password">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="password_confirmation">Confirmar Contraseña</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            <small class="form-text text-muted">Debe coincidir con la contraseña ingresada</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('senaapicola.admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save"></i> Crear Usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
