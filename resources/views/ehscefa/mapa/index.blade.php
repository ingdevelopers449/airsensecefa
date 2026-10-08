@extends('layouts.sidebarehscefa')
@section('tituloPagina', 'Mapa Interactivo de Calidad del Aire CEFA')

@section('css')
<!-- Leaflet.js CSS (Libre de pago y sin claves API) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
<style>
    #mapa-cefa {
        height: calc(100vh - 170px);
        width: 100%;
        border-radius: 1rem;
        z-index: 1;
    }
    .custom-marker {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        color: white;
        font-weight: bold;
        font-size: 11px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        border: 2px solid white;
        transition: transform 0.2s ease-in-out;
    }
    .custom-marker:hover {
        transform: scale(1.25);
    }
    .pulse-green { animation: pulseGreen 2s infinite; }
    .pulse-yellow { animation: pulseYellow 1.5s infinite; }
    .pulse-red { animation: pulseRed 0.8s infinite; }

    @keyframes pulseGreen {
        0% { box-shadow: 0 0 0 0 rgba(57, 169, 0, 0.7); }
        70% { box-shadow: 0 0 0 12px rgba(57, 169, 0, 0); }
        100% { box-shadow: 0 0 0 0 rgba(57, 169, 0, 0); }
    }
    @keyframes pulseYellow {
        0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7); }
        70% { box-shadow: 0 0 0 12px rgba(245, 158, 11, 0); }
        100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
    }
    @keyframes pulseRed {
        0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.8); }
        70% { box-shadow: 0 0 0 14px rgba(239, 68, 68, 0); }
        100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }
</style>
@endsection

@section('content')
<div class="space-y-4">
    <!-- Encabezado con Selectores de Filtro -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex flex-col md:flex-row items-md-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2 m-0">
                <i class="fas fa-map-marked-alt text-[#39A900]"></i> Mapa Geolocalizado de Nodos IoT
            </h2>
            <p class="text-xs text-slate-500 m-0 mt-1">Ubicación satelital en tiempo real de los prototipos AirSense en los ambientes del CEFA La Angostura.</p>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            <!-- Selector de Tipo de Vista de Mapa -->
            <div class="bg-slate-100 p-1.5 rounded-xl flex items-center gap-1 border border-slate-200">
                <button type="button" id="btn-map-streets" onclick="cambiarTipoMapa('streets')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-[#002235] text-white shadow-xs">
                    <i class="fas fa-map"></i> Calles
                </button>
                <button type="button" id="btn-map-satellite" onclick="cambiarTipoMapa('satellite')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-slate-600 hover:bg-white">
                    <i class="fas fa-satellite"></i> Satelital
                </button>
                <button type="button" id="btn-map-dark" onclick="cambiarTipoMapa('dark')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-slate-600 hover:bg-white">
                    <i class="fas fa-moon"></i> Oscuro
                </button>
            </div>

            <!-- Selector de Variable -->
            <div class="bg-slate-100 p-1.5 rounded-xl flex items-center gap-1 border border-slate-200">
                <button type="button" id="btn-var-co2" onclick="filtrarVariable('co2')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-[#39A900] text-white shadow-xs">CO₂ (PPM)</button>
                <button type="button" id="btn-var-temp" onclick="filtrarVariable('temp')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-slate-600 hover:bg-white">Temperatura (°C)</button>
                <button type="button" id="btn-var-hum" onclick="filtrarVariable('hum')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-slate-600 hover:bg-white">Humedad (%)</button>
            </div>

            <button onclick="cargarNodosEnMapa()" class="btn btn-sm btn-light bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold flex items-center gap-2 hover:bg-slate-50">
                <i class="fas fa-sync-alt text-[#39A900]" id="icon-refresh"></i> Actualizar
            </button>
        </div>
    </div>

    <!-- Canvas del Mapa -->
    <div class="bg-white p-2 rounded-2xl border border-slate-200 shadow-xs relative">
        <div id="mapa-cefa"></div>
    </div>
</div>
@endsection

@section('js')
<!-- Leaflet JS (Código Abierto Gratuito) -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
    let map;
    let markersLayer = L.layerGroup();
    let variableActual = 'co2';
    let geojsonData = null;
    let baseLayers = {};
    let activeTileLayer = null;

    document.addEventListener('DOMContentLoaded', function () {
        // Inicializar Capas Base de Mapa
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

        // Inicializar Mapa centrado en el CEFA La Angostura
        map = L.map('mapa-cefa', {
            center: [2.4412, -75.6410],
            zoom: 16,
            layers: [baseLayers.streets]
        });

        activeTileLayer = baseLayers.streets;
        markersLayer.addTo(map);

        // Selector de Capas Nativo en la esquina superior derecha
        L.control.layers({
            "🗺️ Calles (OSM)": baseLayers.streets,
            "🛰️ Satelital HD": baseLayers.satellite,
            "🌙 Vista Oscura": baseLayers.dark
        }).addTo(map);

        // Cargar primera vez
        cargarNodosEnMapa();

        // Refresco automático cada 30 segundos
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
                    btn.className = "px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-[#002235] text-white shadow-xs";
                } else {
                    btn.className = "px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-slate-600 hover:bg-white";
                }
            }
        });
    }

    function cargarNodosEnMapa() {
        const iconRefresh = document.getElementById('icon-refresh');
        if (iconRefresh) iconRefresh.classList.add('fa-spin');

        fetch("{{ route('ehscefa.mapa.geojson') }}")
            .then(res => res.json())
            .then(data => {
                geojsonData = data;
                renderizarPuntosEnMapa();
            })
            .catch(err => console.error("Error cargando GeoJSON:", err))
            .finally(() => {
                if (iconRefresh) iconRefresh.classList.remove('fa-spin');
            });
    }

    function filtrarVariable(variable) {
        variableActual = variable;
        
        ['co2', 'temp', 'hum'].forEach(v => {
            const btn = document.getElementById('btn-var-' + v);
            if (btn) {
                if (v === variable) {
                    btn.className = "px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-[#39A900] text-white shadow-xs";
                } else {
                    btn.className = "px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-slate-600 hover:bg-white";
                }
            }
        });

        if (geojsonData) {
            renderizarPuntosEnMapa();
        }
    }

    function renderizarPuntosEnMapa() {
        markersLayer.clearLayers();

        if (!geojsonData || !geojsonData.features) return;

        geojsonData.features.forEach(feature => {
            const props = feature.properties;
            const geom = feature.geometry;

            // RENDERIZADO DE POLÍGONOS (Delimitación del CEFA)
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

            // RENDERIZADO DE PUNTOS (Nodos IoT)
            if (geom.type === 'Point') {
                const coords = geom.coordinates; // [lng, lat]
                let pulseClass = "pulse-green";

                if (props.co2 >= 1200) pulseClass = "pulse-red";
                else if (props.co2 >= 800) pulseClass = "pulse-yellow";

                const customIcon = L.divIcon({
                    className: 'custom-leaflet-icon',
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
                                Estado: <strong>${props.status_text}</strong><br>
                                <span style="font-size: 9.5px;">Actualizado: ${props.last_seen}</span>
                            </div>
                        </div>
                    </div>
                `;

                marker.bindPopup(popupContent);
                markersLayer.addLayer(marker);
            }
        });

        // Ajustar la vista del mapa automáticamente para encuadrar todos los nodos y polígonos
        if (markersLayer.getLayers().length > 0) {
            const bounds = L.featureGroup(markersLayer.getLayers()).getBounds();
            if (bounds.isValid()) {
                map.fitBounds(bounds, { padding: [30, 30] });
            }
        }
    }
</script>
@endsection



