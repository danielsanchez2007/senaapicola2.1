@extends('senaapicola::layouts.master')

@section('content')
<div class="container pt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Registrar Seguimiento</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('senaapicola.admin.monitorings.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date">Fecha</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="apiary_id">Apiario</label>
                        <select name="apiary_id" class="form-control" required>
                            <option value="">Seleccione un apiario</option>
                            @foreach($apiaries as $apiary)
                                <option value="{{ $apiary->id }}">{{ $apiary->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="user_nickname">Usuario</label>
                        <select id="user_nickname" name="user_nickname" class="form-control" required>
                            <option value="">Seleccione un usuario</option>
                            @foreach($users as $user)
                                @php
                                    $userRole = $user->roles->first()?->slug ?? '';
                                @endphp
                                @if(in_array($userRole, ['senaapicola.admin', 'senaapicola.intern']))
                                    <option value="{{ $user->nickname }}" data-role="{{ $userRole }}">{{ $user->nickname }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="role">Rol</label>
                        <input type="text" name="role" id="role" class="form-control" readonly required>
                        <input type="hidden" name="role" id="real_role">

                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="description">Descripción</label>
                        <textarea name="description" rows="3" class="form-control" placeholder="Escriba una descripción..."></textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('senaapicola.admin.monitorings.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userSelect = document.getElementById('user_nickname');
        const roleInput = document.getElementById('role');

        const roleMapping = {
            'senaapicola.admin': 'Administrador',
            'senaapicola.intern': 'Pasante'
        };

        userSelect.addEventListener('change', function () {
            const selectedOption = userSelect.options[userSelect.selectedIndex];
            const role = selectedOption.getAttribute('data-role') || '';
            const readableRole = roleMapping[role] || '';
            roleInput.value = readableRole;

            // también actualizamos un input oculto para enviar el valor real
            document.getElementById('real_role').value = role;
        });
    });
</script>

@endsection
