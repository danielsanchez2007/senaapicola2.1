@extends($scope === 'admin' ? 'senaapicola::layouts.master' : 'senaapicola::layouts.masterpas')

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
<div class="container pt-4 pb-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-user-circle mr-2"></i>Mi perfil</h5>
        </div>
        <div class="card-body">
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
</div>
@endsection
