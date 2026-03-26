@extends('senaapicola::layouts.master')

@section('content')
<div class="container-fluid py-3">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white py-2">
                    <h4 class="mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        Ubicación de Apiarios
                    </h4>
                </div>
                <div class="card-body">


                    <div class="row">
                        <div class="col-md-8">
                            <div id="map" style="height: 550px; width: 100%; border-radius: 10px; border: 2px solid #ccc; position: relative;">
                                <button id="fullscreenBtn" class="btn btn-primary btn-sm" style="position: absolute; top: 10px; right: 10px; z-index: 1000;">
                                    <i class="fas fa-expand me-1"></i> Pantalla Completa
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header py-2">
                                    <h6 class="mb-0">
                                        <i class="fas fa-list me-2"></i>
                                        Lista de Apiarios
                                    </h6>
                                </div>
                                <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                                    <div id="apiariesList">
                                        @foreach($apiaries as $apiary)
                                        @php
                                        $coords = $apiary->lat_long;
                                        @endphp
                                        <div class="apiary-item mb-2 p-2 border rounded"
                                            onclick="centerOnApiary({{ $apiary->id }})"
                                            style="cursor: pointer;">
                                            <h6 class="mb-1">
                                                <i class="fas fa-bee text-warning me-2"></i>
                                                {{ $apiary->name }}
                                            </h6>
                                            <p class="mb-1 text-muted">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                {{ $apiary->location }}
                                            </p>
                                            <small class="text-info">
                                                <i class="fas fa-id-card me-1"></i>
                                                ID: {{ $apiary->id }}
                                            </small>
                                            @if($coords)
                                            <small class="text-success d-block mt-1">
                                                <i class="fas fa-check-circle me-1"></i> Coordenadas válidas
                                            </small>
                                            @else
                                            <small class="text-warning d-block mt-1">
                                                <i class="fas fa-exclamation-triangle me-1"></i> Coordenadas simuladas
                                            </small>
                                            @endif
                                            <small class="text-primary d-block mt-1">
                                                <i class="fas fa-mouse-pointer me-1"></i> Click para centrar en el mapa
                                            </small>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<style>
    .apiary-item {
        cursor: pointer;
        transition: all 0.3s ease;
        border-left: 4px solid #007bff !important;
        user-select: none;
    }

    .apiary-item:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-left-color: #0056b3;
    }

    .apiary-item:active {
        transform: translateX(3px) scale(0.98);
        background-color: #e9ecef;
    }

    #map {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border: 2px solid #e9ecef;
    }

    .card {
        border: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .card-header {
        border-bottom: none;
    }
</style>

<!-- OpenStreetMap con Leaflet - Mapa simple y confiable -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    let map;
    let markers = [];

    function initMap() {
        // Coordenadas por defecto (centro del mundo)
        const defaultCenter = [20.0, 0.0];

        // Crear mapa con OpenStreetMap
        map = L.map('map').setView(defaultCenter, 2);

        // Agregar capa de mapa
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Array para almacenar todas las coordenadas válidas
        let validCoordinates = [];

        // Agregar marcadores para cada apiario usando coordenadas reales
        @foreach($apiaries as $apiary)
        @php
        $coords = $apiary->lat_long;
        @endphp
        @if($coords)
        addApiaryMarker({
            name: '{{ $apiary->name }}',
            location: '{{ $apiary->location }}',
            id: {{ $apiary->id }},
            lat: {{ $coords['latitud'] }},
            lng: {{ $coords['longitud'] }},
            tieneCoordenadas: true
        });
        validCoordinates.push([{{ $coords['latitud'] }}, {{ $coords['longitud'] }}]);
        @else
        addApiaryMarker({
            name: '{{ $apiary->name }}',
            location: '{{ $apiary->location }}',
            id: {{ $apiary->id }},
            lat: null,
            lng: null,
            tieneCoordenadas: false
        });
        @endif
        @endforeach

        // Centrar el mapa en todos los marcadores válidos
        if (validCoordinates.length > 0) {
            const bounds = L.latLngBounds(validCoordinates);
            map.fitBounds(bounds, {
                padding: [20, 20]
            });
            console.log('Mapa centrado en todos los marcadores válidos');
        }

        console.log('Mapa inicializado correctamente');
    }

    function addApiaryMarker(apiary) {
        if (apiary.tieneCoordenadas && apiary.lat && apiary.lng) {
            // Usar coordenadas reales extraídas
            const lat = apiary.lat;
            const lng = apiary.lng;

            // Crear marcador con Leaflet en la posición real
            const marker = L.marker([lat, lng]).addTo(map);

            // Crear popup con información del apiario
            const popupContent = `
            <div class="p-2">
                <h6><i class="fas fa-bee text-warning me-2"></i>${apiary.name}</h6>
                <p><i class="fas fa-map-marker-alt me-2 text-danger"></i><strong>Ubicación:</strong> ${apiary.location}</p>
                <p><i class="fas fa-hive me-2 text-success"></i><strong>ID:</strong> ${apiary.id}</p>
            </div>
        `;

            marker.bindPopup(popupContent);
            marker.apiaryId = apiary.id; // Guardar el ID del apiario en el marcador
            markers.push(marker);

            console.log(`Marcador agregado para: ${apiary.name} en [${lat}, ${lng}] - COORDENADAS REALES`);
        } else {
            // Si no hay coordenadas, usar posición simulada y mostrar advertencia
            const lat = 4.5709 + (apiary.id * 0.01);
            const lng = -74.2973 + (apiary.id * 0.01);

            // Crear marcador con Leaflet en posición simulada
            const marker = L.marker([lat, lng]).addTo(map);

            // Crear popup con información del apiario y advertencia
            const popupContent = `
            <div class="p-2">
                <h6><i class="fas fa-bee text-warning me-2"></i>${apiary.name}</h6>
                <p><i class="fas fa-map-marker-alt me-2 text-danger"></i><strong>Ubicación:</strong> ${apiary.location}</p>
                <p><i class="fas fa-hive me-2 text-success"></i><strong>ID:</strong> ${apiary.id}</p>
                <p><i class="fas fa-exclamation-triangle me-2 text-warning"></i><strong>Coordenadas:</strong> ${lat.toFixed(6)}, ${lng.toFixed(6)} (SIMULADAS)</p>
                <p><i class="fas fa-info me-2 text-info"></i><strong>Nota:</strong> No se pudieron extraer coordenadas de: ${apiary.location}</p>
            </div>
        `;

            marker.bindPopup(popupContent);
            marker.apiaryId = apiary.id; // Guardar el ID del apiario en el marcador
            markers.push(marker);

            console.log(`Marcador agregado para: ${apiary.name} en [${lat}, ${lng}] - POSICIÓN SIMULADA`);
        }
    }

    // Función para pantalla completa
    function toggleFullscreen() {
        const mapContainer = document.getElementById('map');
        const fullscreenBtn = document.getElementById('fullscreenBtn');

        if (!document.fullscreenElement) {
            // Entrar en pantalla completa
            if (mapContainer.requestFullscreen) {
                mapContainer.requestFullscreen();
            } else if (mapContainer.webkitRequestFullscreen) {
                mapContainer.webkitRequestFullscreen();
            } else if (mapContainer.msRequestFullscreen) {
                mapContainer.msRequestFullscreen();
            }
            fullscreenBtn.innerHTML = '<i class="fas fa-compress me-1"></i> Salir Pantalla Completa';
            fullscreenBtn.classList.remove('btn-primary');
            fullscreenBtn.classList.add('btn-danger');
        } else {
            // Salir de pantalla completa
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
            fullscreenBtn.innerHTML = '<i class="fas fa-expand me-1"></i>Pantalla Completa';
            fullscreenBtn.classList.remove('btn-danger');
            fullscreenBtn.classList.add('btn-primary');
        }
    }

    // Función para centrar el mapa en un apiario específico
    function centerOnApiary(apiaryId) {
        const marker = markers.find(m => m.apiaryId === apiaryId);
        if (marker) {
            map.setView(marker.getLatLng(), 15);
            marker.openPopup();
            console.log(`Mapa centrado en apiario ID: ${apiaryId}`);
        }
    }

    // Inicializar mapa cuando se carga la página
    window.addEventListener('load', () => {
        console.log('Página cargada, inicializando mapa...');
        initMap();

        // Agregar evento al botón de pantalla completa
        document.getElementById('fullscreenBtn').addEventListener('click', toggleFullscreen);
    });
</script>
@endsection