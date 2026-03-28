@extends('senaapicola::layouts.masterpas')

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
                    <div class="d-flex align-items-center">
                        <i class="fas fa-dolly me-3 glow-gold"></i>
                        <h4 class="mb-0 fw-bold">Salidas - Agroindustria</h4>
                    </div>
                </div>

                <div class="card-body p-5">
                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Destino</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($exits as $e)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($e->date)->format('d/m/Y') }}</td>
                                            <td>{{ $e->product }}</td>
                                            <td>{{ $e->quantity }}</td>
                                            <td>{{ $e->destination_or_origin ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach

                                    @if($exits->isEmpty())
                                        <tr><td colspan="4" class="text-center">No hay registros.</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <a href="{{ route('senaapicola.intern.movements.index') }}" class="btn btn-secondary mt-4">
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