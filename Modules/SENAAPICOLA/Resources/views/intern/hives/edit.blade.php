@extends('senaapicola::layouts.masterpas')

@section('title', 'Editar Colmena - Panel de Pasante')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-edit mr-2"></i>
                        Editar Colmena: {{ $hive->name }}
                    </h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Información actual de la colmena -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6 class="alert-heading">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Información Actual de la Colmena
                                </h6>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <strong>ID:</strong> {{ $hive->id }}<br>
                                        <strong>Nombre:</strong> {{ $hive->name }}<br>
                                        <strong>Apiario:</strong> {{ $hive->apiary->name ?? 'N/A' }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Fecha de Creación:</strong> {{ $hive->created_at ? $hive->created_at->format('d/m/Y H:i') : 'N/A' }}<br>
                                        <strong>Última Actualización:</strong> {{ $hive->updated_at ? $hive->updated_at->format('d/m/Y H:i') : 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('senaapicola.intern.hives.update', $hive->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">
                                        <strong>Nombre de la Colmena <span class="text-danger">*</span></strong>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $hive->name) }}" 
                                           placeholder="Ej: Colmena A1" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="apiary_id" class="form-label">
                                        <strong>Apiario <span class="text-danger">*</span></strong>
                                    </label>
                                    <select class="form-control @error('apiary_id') is-invalid @enderror" 
                                            id="apiary_id" 
                                            name="apiary_id" 
                                            required>
                                        <option value="">Seleccione un apiario</option>
                                        @foreach($apiaries as $apiary)
                                            <option value="{{ $apiary->id }}" {{ old('apiary_id', $hive->apiary_id) == $apiary->id ? 'selected' : '' }}>
                                                {{ $apiary->name }} - {{ $apiary->location }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('apiary_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <strong>Nota:</strong> Como pasante, puedes editar la información de las colmenas. 
                                    Los cambios se guardarán en el sistema y serán visibles para todos los usuarios.
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-warning btn-lg">
                                    <i class="fas fa-save mr-2"></i>
                                    Actualizar Colmena
                                </button>
                                <a href="{{ route('senaapicola.intern.hives.index') }}" class="btn btn-secondary btn-lg ml-2">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Volver al Listado
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
