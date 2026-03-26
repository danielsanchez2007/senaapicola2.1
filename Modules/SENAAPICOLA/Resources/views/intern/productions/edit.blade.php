@extends('senaapicola::layouts.masterpas')

@section('title', 'Editar Producción')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit text-warning"></i>
                        Editar Producción
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('senaapicola.intern.productions.update', $production->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date">Fecha de Producción <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                           id="date" name="date" value="{{ old('date', $production->date) }}" required>
                                    @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="apiary_id">Apiario <span class="text-danger">*</span></label>
                                    <select class="form-control @error('apiary_id') is-invalid @enderror" 
                                            id="apiary_id" name="apiary_id" required>
                                        <option value="">Seleccione un apiario</option>
                                        @foreach($apiaries as $apiary)
                                            <option value="{{ $apiary->id }}" 
                                                {{ old('apiary_id', $production->apiary_id) == $apiary->id ? 'selected' : '' }}>
                                                {{ $apiary->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('apiary_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="product">Producto <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('product') is-invalid @enderror" 
                                           id="product" name="product" value="{{ old('product', $production->product) }}" 
                                           placeholder="Ej: Miel, Cera, Polen" required>
                                    @error('product')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quantity">Cantidad (Botella 375 ml) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('quantity') is-invalid @enderror" 
                                           id="quantity" name="quantity" value="{{ old('quantity', $production->quantity) }}" 
                                           placeholder="0.00" required>
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="notes">Observaciones</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" 
                                      placeholder="Observaciones adicionales...">{{ old('notes', $production->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save"></i> Actualizar Producción
                            </button>
                            <a href="{{ route('senaapicola.intern.productions.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
