@extends('senaapicola::layouts.master')

@push('styles')
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
            position: relative;
            overflow-x: hidden;
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
            0%   { transform: translateY(100vh) rotate(0deg); }
            100% { transform: translateY(-140px) rotate(720deg); }
        }

        .main-card {
            background: var(--glass);
            backdrop-filter: blur(22px);
            border: 1px solid rgba(245, 197, 24, 0.35);
            border-radius: 28px;
            box-shadow: 0 30px 85px rgba(0, 0, 0, 0.6);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, var(--sena-green-dark) 0%, var(--sena-orange) 100%) !important;
            color: white;
            border: none;
            padding: 2rem 2.5rem;
        }

        .table-container {
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        .table thead {
            background: linear-gradient(90deg, var(--sena-green-dark), var(--sena-green));
            color: white;
        }

        .apiary-filter {
            background: rgba(255,255,255,0.9);
            border: 2px solid var(--sena-orange);
            border-radius: 50px;
            padding: 10px 24px;
        }

        .btn-sena {
            background: linear-gradient(90deg, var(--sena-green), var(--sena-orange));
            color: white;
            border: none;
            border-radius: 50px;
            padding: 10px 26px;
            font-weight: 700;
            box-shadow: 0 8px 25px rgba(57, 169, 0, 0.35);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-sena:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(255, 159, 28, 0.45);
            color: white;
        }

        .glow-gold {
            text-shadow: 0 0 25px var(--sena-gold);
        }
    </style>
@endpush

@section('content')
<div class="container-fluid py-5 position-relative" style="z-index: 2;">
    <div class="row">
        <div class="col-12">
            <div class="main-card">
                <div class="card-header">
                    <div class="row align-items-center w-100">
                        <div class="col-md-6 d-flex align-items-center">
                            <h4 class="mb-0 fw-bold">
                                <i class="fas fa-warehouse me-3 glow-gold"></i>
                                Entradas - Bodega
                            </h4>
                        </div>
                        <div class="col-md-6 text-end">
                            <form method="GET" action="{{ route('senaapicola.admin.movements.bodega') }}" class="d-inline-block">
                                <select name="apiary_id" class="form-select apiary-filter w-auto" onchange="this.form.submit()">
                                    <option value="">Todos los Apiarios</option>
                                    @foreach($apiaries as $apiary)
                                        <option value="{{ $apiary->id }}" {{ request('apiary_id') == $apiary->id ? 'selected' : '' }}>
                                            {{ $apiary->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body p-5">
                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Apiario</th>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Origen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($entries as $e)
                                        <tr>
                                            <td>{{ $e->date }}</td>
                                            <td>{{ $e->apiary->name ?? 'N/A' }}</td>
                                            <td>{{ $e->product }}</td>
                                            <td>{{ $e->quantity }}</td>
                                            <td>{{ $e->destination_or_origin ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                    @if($entries->isEmpty())
                                        <tr><td colspan="5" class="text-center">No hay registros.</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $entries->appends(['apiary_id' => request('apiary_id')])->links() }}
                        </div>
                    </div>

                    <a href="{{ route('senaapicola.admin.movements.index') }}" class="btn btn-secondary mt-4">
                        Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div id="pollen-container"></div>
</div>
@endsection

@push('scripts')
<script>
    function createPollen() {
        const container = document.getElementById('pollen-container');
        const particle = document.createElement('div');
        particle.classList.add('pollen-particle');

        const size = Math.random() * 8 + 5;
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

    for (let i = 0; i < 20; i++) {
        setTimeout(createPollen, i * 110);
    }
</script>
@endpush
