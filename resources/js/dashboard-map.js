// Dashboard Map JavaScript (leaflet integration)
// This script assumes Leaflet JS is loaded globally via CDN in the layout.

let map;
let markersLayer = L.layerGroup();
let variableActual = 'co2';
let geojsonData = null;
let baseLayers = {};
let activeTileLayer = null;

document.addEventListener('DOMContentLoaded', function () {
    // Base layers
    baseLayers.streets = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap | AirSense CEFA'
    });
    baseLayers.satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 19,
        attribution: '© Esri WorldImagery | AirSense CEFA'
    });
    baseLayers.dark = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        maxZoom: 19,
        attribution: '© CartoDB Dark | AirSense CEFA'
    });

    map = L.map('mapa-dashboard-ehs', {
        center: [2.4412, -75.6410],
        zoom: 16,
        layers: [baseLayers.streets]
    });

    activeTileLayer = baseLayers.streets;
    markersLayer.addTo(map);

    L.control.layers({
        "🗺️ Calles (OSM)": baseLayers.streets,
        "🛰️ Satelital HD": baseLayers.satellite,
        "🌙 Vista Oscura": baseLayers.dark
    }).addTo(map);

    cargarNodosEnMapa();
    setInterval(cargarNodosEnMapa, 30000);
});

function cambiarTipoMapa(tipo) {
    if (!baseLayers[tipo]) return;
    map.removeLayer(activeTileLayer);
    baseLayers[tipo].addTo(map);
    activeTileLayer = baseLayers[tipo];

    ['streets', 'satellite', 'dark'].forEach(t => {
        const btn = document.getElementById('btn-map-' + t);
        if (btn) {
            if (t === tipo) {
                btn.className = "px-3 py-1 rounded-lg text-xs font-bold transition-all bg-[#002235] text-white shadow-xs";
            } else {
                btn.className = "px-3 py-1 rounded-lg text-xs font-bold transition-all text-slate-600 hover:bg-white";
            }
        }
    });
}

function cargarNodosEnMapa() {
    fetch("{{ route('ehscefa.mapa.geojson') }}")
        .then(res => res.json())
        .then(data => {
            geojsonData = data;
            renderizarPuntosEnMapa();
        })
        .catch(err => console.error("Error cargando GeoJSON:", err));
}

function filtrarVariable(variable) {
    variableActual = variable;
    ['co2', 'temp', 'hum'].forEach(v => {
        const btn = document.getElementById('btn-var-' + v);
        if (btn) {
            if (v === variable) {
                btn.className = "px-3 py-1 rounded-lg text-xs font-bold transition-all bg-[#39A900] text-white shadow-xs";
            } else {
                btn.className = "px-3 py-1 rounded-lg text-xs font-bold transition-all text-slate-600 hover:bg-white";
            }
        }
    });
    if (geojsonData) renderizarPuntosEnMapa();
}

function renderizarPuntosEnMapa() {
    markersLayer.clearLayers();
    if (!geojsonData || !geojsonData.features) return;

    geojsonData.features.forEach(feature => {
        const props = feature.properties;
        const geom = feature.geometry;

        if (geom.type === 'Polygon') {
            const polygonLayer = L.geoJSON(feature, {
                style: {
                    color: props.stroke || '#002235',
                    weight: props['stroke-width'] || 3,
                    fillColor: props.fill || '#39A900',
                    fillOpacity: props['fill-opacity'] || 0.15,
                    dashArray: '5, 5'
                }
            });
            polygonLayer.bindTooltip(`<b>${props.name}</b><br><small>${props.description}</small>`, { sticky: true });
            markersLayer.addLayer(polygonLayer);
            return;
        }

        if (geom.type === 'Point') {
            const coords = geom.coordinates;
            let pulseClass = "pulse-green";
            if (props.co2 >= 1200) pulseClass = "pulse-red";
            else if (props.co2 >= 800) pulseClass = "pulse-yellow";

            const customIcon = L.divIcon({
                className: 'custom-marker',
                html: `<div class="custom-marker ${pulseClass}" style="background-color: ${props.color};">${Math.round(props.co2)}</div>`,
                iconSize: [32, 32],
                iconAnchor: [16, 16]
            });

            const marker = L.marker([coords[1], coords[0]], { icon: customIcon });

            const popupContent = `
                <div style="font-family: 'Inter', sans-serif; width: 220px;">
                    <div style="background-color: #002235; color: white; padding: 8px 12px; border-radius: 8px 8px 0 0; margin: -14px -20px 10px -20px;">
                        <h6 style="margin: 0; font-weight: 700; font-size: 13px; color: #86efac;">${props.ambiente}</h6>
                        <small style="font-size: 10px; color: #cbd5e1;">Nodo: ${props.name}</small>
                    </div>
                    <div style="font-size: 12px; display: grid; gap: 4px;">
                        <div><strong>💨 CO₂:</strong> <span style="color: ${props.color}; font-weight: 800;">${props.co2} PPM</span></div>
                        <div><strong>🌡️ Temperatura:</strong> ${props.temperatura} °C</div>
                        <div><strong>💧 Humedad:</strong> ${props.humedad} %</div>
                        <div style="margin-top: 6px; padding-top: 6px; border-top: 1px solid #e2e8f0; font-size: 10.5px; color: #64748b;">
                            Estado SST: <strong>${props.status_text}</strong>
                        </div>
                    </div>
                </div>`;
            marker.bindPopup(popupContent);
            markersLayer.addLayer(marker);
        }
    });

    if (markersLayer.getLayers().length > 0) {
        const bounds = L.featureGroup(markersLayer.getLayers()).getBounds();
        if (bounds.isValid()) {
            map.fitBounds(bounds, { padding: [30, 30] });
        }
    }
}
