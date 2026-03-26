@extends('senaapicola::layouts.masterpas')

@section('title', 'Nueva Colmena - Panel de Pasante')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Registrar Nueva Colmena
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

                    <form action="{{ route('senaapicola.intern.hives.store') }}" method="POST">
                        @csrf
                        
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
                                           value="{{ old('name') }}" 
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
                                            <option value="{{ $apiary->id }}" {{ old('apiary_id') == $apiary->id ? 'selected' : '' }}>
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

                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-warning btn-lg">
                                    <i class="fas fa-save mr-2"></i>
                                    Registrar Colmena
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
