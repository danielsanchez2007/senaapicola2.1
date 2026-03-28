@php
    $markers = $apiaryMapMarkers ?? collect();
@endphp

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">

<div class="main-card mb-4">
    
    <!-- Header del Mapa -->
    <div class="card-header">
        <div class="d-flex align-items-center">
            <i class="fas fa-map-marked-alt fa-2x glow-gold me-3"></i>
            <div>
                <h4 class="fw-bold mb-1">Mapa de Apiarios Registrados</h4>
                <p class="opacity-75 small mb-0">
                    Visualiza la ubicación de todos los apiarios del programa
                </p>
            </div>
        </div>
    </div>

    <div class="card-body p-5">
        <p class="text-white-50 small mb-4">
            Todos los apiarios con coordenadas aparecen en el mapa. Usa el filtro para centrar en uno específico. 
            Pasa el cursor sobre un marcador para ver la imagen de referencia.
        </p>

        <div class="row g-4">
            <!-- Mapa -->
            <div class="col-lg-8">
                <div class="mb-3">
                    <label for="apiaryMapFilter" class="form-label fw-semibold text-light">Filtrar por Apiario</label>
                    <select id="apiaryMapFilter" class="form-select">
                        <option value="">— Todos los apiarios —</option>
                        @foreach ($markers as $m)
                            @if (!empty($m['lat']) && !empty($m['lng']))
                                <option value="{{ $m['id'] }}">{{ $m['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div id="apiaryLeafletMap" 
                     style="height: 460px; width: 100%; border-radius: 20px; border: 1px solid rgba(245,197,24,0.3);">
                </div>
            </div>

            <!-- Panel de Información -->
            <div class="col-lg-4">
                <div id="apiaryInfoPanel" 
                     class="h-100 bg-white bg-opacity-95 border border-white border-opacity-20 rounded-3 p-4 shadow-sm"
                     style="min-height: 460px;">
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-map-pin fa-3x mb-3 opacity-50"></i>
                        <h6 class="mb-2">Detalle del Apiario</h6>
                        <p class="small mb-0">
                            Selecciona un marcador en el mapa o usa el filtro<br>
                            para ver información detallada
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
(function () {
    const raw = @json($markers->values());
    const points = raw.filter(p => p.lat != null && p.lng != null && !isNaN(p.lat) && !isNaN(p.lng));
    
    const mapEl = document.getElementById('apiaryLeafletMap');
    if (!mapEl || typeof L === 'undefined') return;

    const map = L.map('apiaryLeafletMap', { 
        scrollWheelZoom: true,
        zoomControl: true 
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    const bounds = [];
    const layersById = {};
    const pointsById = {};

    function escHtml(t) {
        if (t == null || t === '') return '';
        const d = document.createElement('div');
        d.textContent = t;
        return d.innerHTML;
    }

    function safeImgUrl(u) {
        return (typeof u === 'string' && /^https?:\/\//i.test(u)) ? u : '';
    }

    function renderInfo(p) {
        const panel = document.getElementById('apiaryInfoPanel');
        if (!panel || !p) return;

        const img = safeImgUrl(p.image_url);
        const imgHtml = img 
            ? `<div class="mb-3"><img src="${img.replace(/"/g, '&quot;')}" alt="" style="width:100%; max-height:160px; object-fit:cover; border-radius:14px; border: 2px solid #f5c518;"></div>` 
            : '';

        const hivesCount = (p.hives_count ?? 0);
        const totalQty = (p.productions_total_quantity ?? 0);
        const entryQty = (p.productions_entries_quantity ?? 0);
        const exitQty = (p.productions_exits_quantity ?? 0);
        const netQty = (p.cosecha_neta_quantity ?? 0);

        panel.innerHTML = `
            <h5 class="fw-bold text-success mb-1">${escHtml(p.name || '')}</h5>
            <p class="text-muted small mb-3">${escHtml(p.location || 'Sin ubicación registrada')}</p>
            ${imgHtml}
            
            <div class="row g-3 text-center">
                <div class="col-6">
                    <div class="text-muted small">Colmenas</div>
                    <div class="fw-bold fs-4">${hivesCount}</div>
                </div>
                <div class="col-6">
                    <div class="text-muted small">Producción Total</div>
                    <div class="fw-bold fs-4">${totalQty}</div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top border-secondary">
                <div class="d-flex justify-content-between small">
                    <span class="text-muted">Entradas</span>
                    <span class="fw-semibold">${entryQty}</span>
                </div>
                <div class="d-flex justify-content-between small mt-1">
                    <span class="text-muted">Salidas</span>
                    <span class="fw-semibold">${exitQty}</span>
                </div>
                <div class="d-flex justify-content-between mt-3 pt-2 border-top">
                    <span class="text-warning fw-bold">Cosecha Neta</span>
                    <span class="fw-bold text-warning">${netQty}</span>
                </div>
            </div>
        `;
    }

    // Crear marcadores
    points.forEach(p => {
        pointsById[p.id] = p;

        const marker = L.marker([p.lat, p.lng]).addTo(map);

        const safeImg = safeImgUrl(p.image_url);
        const imgHtml = safeImg 
            ? `<div style="max-width:240px;"><img src="${safeImg.replace(/"/g, '&quot;')}" style="width:100%;max-height:140px;object-fit:cover;border-radius:8px;margin-bottom:8px;"></div>` 
            : '';

        const tooltipContent = `
            <div style="min-width:180px;">
                ${imgHtml}
                <strong>${escHtml(p.name)}</strong><br>
                <small class="text-muted">${escHtml(p.location || '')}</small>
            </div>
        `;

        marker.bindTooltip(tooltipContent, { 
            direction: 'top', 
            opacity: 1, 
            className: 'apiary-map-tooltip' 
        });

        marker.on('click', () => renderInfo(p));
        marker.on('mouseover', () => marker.openTooltip());

        bounds.push([p.lat, p.lng]);
        layersById[p.id] = { marker, lat: p.lat, lng: p.lng };
    });

    // Ajustar vista inicial
    if (bounds.length > 0) {
        map.fitBounds(bounds, { padding: [50, 50], maxZoom: 12 });
    } else {
        map.setView([4.65, -74.1], 6); // Centro aproximado de Colombia
    }

    // Filtro
    document.getElementById('apiaryMapFilter').addEventListener('change', function () {
        const id = this.value;
        if (!id || !layersById[id]) {
            if (bounds.length) map.fitBounds(bounds, { padding: [50, 50], maxZoom: 12 });
            return;
        }
        const o = layersById[id];
        map.setView([o.lat, o.lng], 15);
        o.marker.openTooltip();
        renderInfo(pointsById[id]);
    });

    // Si solo hay un apiario, mostrar su info automáticamente
    if (points.length === 1) {
        renderInfo(points[0]);
    }
})();
</script>

<style>
.leaflet-tooltip.apiary-map-tooltip {
    background: rgba(255,255,255,0.97);
    border: 1px solid #ddd;
    border-radius: 12px;
    padding: 10px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.25);
    font-size: 0.95rem;
}
</style>