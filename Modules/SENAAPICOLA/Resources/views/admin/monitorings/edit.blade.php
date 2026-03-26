@extends('senaapicola::layouts.master')

@section('content')
<div class="container pt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Editar Seguimiento</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('senaapicola.admin.monitorings.update', $monitoring->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date">Fecha</label>
                        <input type="date" name="date" class="form-control" value="{{ $monitoring->date }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="apiary_id">Apiario</label>
                        <select name="apiary_id" class="form-control" required>
                            <option value="">Seleccione un apiario</option>
                            @foreach($apiaries as $apiary)
                            <option value="{{ $apiary->id }}" {{ $apiary->id == $monitoring->apiary_id ? 'selected' : '' }}>{{ $apiary->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="user_nickname">Usuario</label>
                        <select id="user_nickname" name="user_nickname" class="form-control" required>
                            <option value="">Seleccione un usuario</option>
                            @foreach($users as $user)
                            @php $roleSlug = $user->roles->first()?->slug; @endphp
                            @if(in_array($roleSlug, ['senaapicola.admin', 'senaapicola.intern']))
                            <option value="{{ $user->nickname }}" data-role="{{ $roleSlug }}" {{ $user->nickname == $monitoring->user_nickname ? 'selected' : '' }}>
                                {{ $user->nickname }}
                            </option>
                            @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="role">Rol</label>
                        @php
                        $readableRole = $monitoring->role === 'senaapicola.admin' ? 'Administrador' :
                        ($monitoring->role === 'senaapicola.intern' ? 'Pasante' : '');
                        @endphp
                        <input type="text" name="role" id="role" class="form-control" value="{{ $readableRole }}" readonly required>
                        <input type="hidden" name="role" id="real_role" value="{{ $monitoring->role }}">
                    </div>


                    <div class="col-md-12 mb-3">
                        <label for="description">Descripción</label>
                        <textarea name="description" rows="3" class="form-control">{{ $monitoring->description }}</textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="{{ route('senaapicola.admin.monitorings.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userSelect = document.getElementById('user_nickname');
        const roleInput = document.getElementById('role');
        const hiddenRole = document.getElementById('real_role');

        const roleMapping = {
            'senaapicola.admin': 'Administrador',
            'senaapicola.intern': 'Pasante'
        };

        userSelect.addEventListener('change', function () {
            const selectedOption = userSelect.options[userSelect.selectedIndex];
            const role = selectedOption.getAttribute('data-role') || '';
            const readableRole = roleMapping[role] || '';
            roleInput.value = readableRole;
            hiddenRole.value = role;
        });
    });
</script>

@endsection