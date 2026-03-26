@php
    $markers = $apiaryMapMarkers ?? collect();
@endphp
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-map-marked-alt mr-2"></i>Mapa de apiarios registrados</h5>
    </div>
    <div class="card-body">
        <p class="text-muted small mb-2">Todos los apiarios con coordenadas aparecen en el mapa. Use el filtro para centrar en uno; pase el cursor sobre un marcador para ver la imagen de referencia.</p>

        <div class="row g-3 align-items-start">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="apiaryMapFilter" class="mb-1">Filtrar apiario</label>
                    <select id="apiaryMapFilter" class="form-control">
                        <option value="">— Todos los apiarios —</option>
                        @foreach ($markers as $m)
                            @if (!empty($m['lat']) && !empty($m['lng']))
                                <option value="{{ $m['id'] }}">{{ $m['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div id="apiaryLeafletMap" style="height: 300px; width: 100%; border-radius: 6px; z-index: 1;"></div>
            </div>

            <div class="col-md-4">
                <div id="apiaryInfoPanel" class="bg-white border rounded p-3" style="min-height: 300px;">
                    <h6 class="mb-2 text-success"><i class="fas fa-info-circle mr-2"></i>Detalle del apiario</h6>
                    <p id="apiaryInfoPlaceholder" class="text-muted small mb-0">Seleccione un marcador (o use el filtro) para ver colmenas y producciones.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
(function () {
    const raw = @json($markers->values());
    const points = raw.filter(p => p.lat != null && p.lng != null && !isNaN(p.lat) && !isNaN(p.lng));
    const mapEl = document.getElementById('apiaryLeafletMap');
    if (!mapEl || typeof L === 'undefined') return;

    const map = L.map('apiaryLeafletMap', { scrollWheelZoom: true });
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
            ? '<div class="mb-2"><img src="' + img.replace(/"/g, '&quot;') + '" alt="" style="width:100%;max-height:110px;object-fit:cover;border-radius:8px;"></div>'
            : '';

        const hivesCount = (p.hives_count ?? 0);
        const totalQty = (p.productions_total_quantity ?? 0);
        const entryQty = (p.productions_entries_quantity ?? 0);
        const exitQty = (p.productions_exits_quantity ?? 0);
        const netQty = (p.cosecha_neta_quantity ?? 0);

        panel.innerHTML = `
            <h6 class="mb-1 text-success"><i class="fas fa-map-pin mr-2"></i>${escHtml(p.name || '')}</h6>
            <p class="text-muted small mb-2">${escHtml(p.location || '')}</p>
            ${imgHtml}
            <div class="mt-2">
                <div class="text-muted small">Colmenas</div>
                <div style="font-weight:600;">${escHtml(String(hivesCount))}</div>
            </div>
            <div class="mt-3">
                <div class="text-muted small">Producción total</div>
                <div style="font-weight:600;">${escHtml(String(totalQty))}</div>
            </div>
            <div class="mt-3">
                <div class="text-muted small">Entradas / Salidas</div>
                <div style="font-weight:600;">${escHtml(String(entryQty))} / ${escHtml(String(exitQty))}</div>
            </div>
            <div class="mt-3">
                <div class="text-muted small text-warning">Cosecha neta (Entradas - Salidas)</div>
                <div style="font-weight:700;">${escHtml(String(netQty))}</div>
            </div>
        `;
    }
    points.forEach(p => {
        pointsById[p.id] = p;
        const marker = L.marker([p.lat, p.lng]).addTo(map);
        const safeImgUrl = (typeof p.image_url === 'string' && /^https?:\/\//i.test(p.image_url)) ? p.image_url : '';
        const img = safeImgUrl
            ? '<div style="max-width:220px;"><img src="' + safeImgUrl.replace(/"/g, '&quot;') + '" alt="" style="width:100%;max-height:140px;object-fit:cover;border-radius:4px;margin-bottom:6px;"></div>'
            : '';
        const tip = '<div style="min-width:160px;">' + img + '<strong>' + escHtml(p.name) + '</strong><br><small>' + escHtml(p.location) + '</small></div>';
        marker.bindTooltip(tip, { direction: 'top', opacity: 1, className: 'apiary-map-tooltip' });
        marker.on('mouseover', function () { this.openTooltip(); });
        marker.on('mouseout', function () { this.closeTooltip(); });
        marker.on('click', function () { renderInfo(p); });
        bounds.push([p.lat, p.lng]);
        layersById[p.id] = { marker, lat: p.lat, lng: p.lng };
    });

    if (bounds.length) {
        map.fitBounds(bounds, { padding: [40, 40], maxZoom: 11 });
    } else {
        map.setView([4.65, -74.1], 7);
    }

    document.getElementById('apiaryMapFilter').addEventListener('change', function () {
        const id = this.value;
        if (!id || !layersById[id]) {
            if (bounds.length) map.fitBounds(bounds, { padding: [40, 40], maxZoom: 11 });
            return;
        }
        const o = layersById[id];
        map.setView([o.lat, o.lng], 14);
        o.marker.openTooltip();
        renderInfo(pointsById[id]);
    });

    // Vista inicial: si solo hay 1 punto, mostramos su info
    if (points.length === 1) {
        renderInfo(points[0]);
    }
})();
</script>
<style>
.leaflet-tooltip.apiary-map-tooltip {
    background: rgba(255,255,255,0.96);
    border: 1px solid #ccc;
    border-radius: 6px;
    padding: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}
</style>
