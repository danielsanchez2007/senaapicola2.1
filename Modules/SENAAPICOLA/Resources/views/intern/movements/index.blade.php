@extends('senaapicola::layouts.masterpas')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="row w-100">
                        <div class="col-md-6 d-flex align-items-center">
                            <h5 class="mb-0">Consulta de Movimientos</h5>
                        </div>
                        <div class="col-md-6 text-right d-flex justify-content-end align-items-center gap-2">
                        <a href="{{ route('senaapicola.intern.productions.create_exit') }}" class="btn btn-light btn-sm text-dark">
                                <i class="fas fa-plus-circle mr-1"></i> Nuevo Registro Salida
                            </a>
                            <a href="{{ route('senaapicola.intern.movements.report') }}" class="btn btn-danger btn-sm">
                                <i class="fas fa-file-pdf"></i> Exportar Movimientos
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body text-center">
                    <a href="{{ route('senaapicola.intern.movements.bodega') }}" class="btn btn-success btn-lg m-3">
                        <i class="fas fa-warehouse"></i> Entradas (Bodega)
                    </a>
                    <a href="{{ route('senaapicola.intern.movements.agroindustria') }}" class="btn btn-warning btn-lg m-3 text-white">
                        <i class="fas fa-dolly"></i> Salidas (Agroindustria)
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="alert alert-success text-center">
                <h5 class="mb-1">Entradas (Bodega)</h5>
                <h3 class="font-weight-bold">{{ $entradas }}</h3>
            </div>
        </div>

                        <div class="col-md-4">
                    <div class="alert alert-warning text-center">
                        <h5 class="mb-1">Salidas (Agroindustria)</h5>
                        <h3 class="font-weight-bold">{{ $salidas }}</h3>
                    </div>
                </div>

        <div class="col-md-4">
            <div class="alert alert-info text-center">
                <h5 class="mb-1">Disponible en Bodega</h5>
                <h3 class="font-weight-bold">{{ $disponibleBodega }}</h3>
            </div>
        </div>
    </div>
</div>
@endsection