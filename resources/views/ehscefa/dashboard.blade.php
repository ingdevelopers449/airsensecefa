@extends('layouts.sidebarehscefa')
@section('tituloPagina', 'Vista General - Monitoreo Ambiental CEFA EHS')

@section('css')
<!-- Leaflet.js CSS para el Mapa Interactivo del Dashboard -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
<style>
    #mapa-dashboard-ehs {
        height: 520px;
        width: 100%;
        border-radius: 1rem;
        z-index: 1;
    }
    /* Estilo de Alfiler tipo Gota (Teardrop Pin) */
    .marker-pin {
        width: 30px;
        height: 30px;
        border-radius: 50% 50% 50% 0;
        position: absolute;
        transform: rotate(-45deg);
        left: 50%;
        top: 50%;
        margin: -15px 0 0 -15px;
        border: 2px solid white;
        box-shadow: 2px 2px 6px rgba(0,0,0,0.4);
        transition: transform 0.2s ease;
    }
    .marker-pin::after {
        content: '';
        width: 10px;
        height: 10px;
        background: white;
        position: absolute;
        border-radius: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.2);
    }
    .marker-pin:hover {
        transform: rotate(-45deg) scale(1.1);
    }

    /* Tooltip encima del alfiler */
    .custom-tooltip {
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        padding: 4px 8px;
        font-family: 'Inter', sans-serif;
    }

    .pulse-green { animation: pulseGreen 2s infinite; }
    .pulse-yellow { animation: pulseYellow 1.5s infinite; }
    .pulse-red { animation: pulseRed 0.8s infinite; }

    @keyframes pulseGreen {
        0% { box-shadow: 0 0 0 0 rgba(57, 169, 0, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(57, 169, 0, 0); }
        100% { box-shadow: 0 0 0 0 rgba(57, 169, 0, 0); }
    }
    @keyframes pulseYellow {
        0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(245, 158, 11, 0); }
        100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
    }
    @keyframes pulseRed {
        0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.8); }
        70% { box-shadow: 0 0 0 12px rgba(239, 68, 68, 0); }
        100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }
</style>
@endsection

@section('content')
<div class="space-y-5">

    <!-- Subencabezado de estado de sincronización -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
        <div>
            <h2 class="text-xl font-extrabold text-slate-800 m-0">Vista general EHS / SST</h2>
            <p class="text-xs text-slate-500 m-0">Estado ambiental del CEFA en tiempo real</p>
        </div>
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 bg-white px-3 py-1.5 rounded-full border border-slate-200 w-fit">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
            Actualizado <span id="lbl-last-update">hace unos segundos</span>
        </div>
    </div>

    <!-- Banner de Alerta Crítica Activa (Dinámico) -->
    <div id="banner-alerta-critica" class="hidden bg-red-50 border border-red-200 p-4 rounded-2xl flex flex-col sm:flex-row items-sm-center justify-between gap-3 shadow-xs">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-red-500 text-white flex items-center justify-center shrink-0">
                <i class="fas fa-bell text-lg animate-bounce"></i>
            </div>
            <div>
                <h4 class="text-sm font-bold text-red-800 m-0">Alerta crítica activa</h4>
                <p class="text-xs text-red-600 m-0 mt-0.5" id="lbl-alerta-mensaje">Cargando reporte de alertas en tiempo real...</p>
            </div>
        </div>
        <a href="{{ route('ehscefa.mapa.index') }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl shadow-xs transition-all flex items-center gap-1 shrink-0 w-fit">
            Ver protocolo <i class="fas fa-chevron-right text-[10px]"></i>
        </a>
    </div>

    <!-- Tarjetas resumen KPI de la plataforma (4 Bloques) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- KPI 1 -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center text-lg">
                    <i class="fas fa-th-large"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase m-0">Ambientes monitoreados</p>
                    <h3 class="text-2xl font-black text-slate-800 m-0" id="kpi-total-nodos">0</h3>
                </div>
            </div>
            <span class="text-[11px] font-bold text-slate-400" id="kpi-online-nodos">0 en línea</span>
        </div>

        <!-- KPI 2 -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-[#39A900] flex items-center justify-center text-lg">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase m-0">Estado óptimo</p>
                    <h3 class="text-2xl font-black text-slate-800 m-0" id="kpi-optimo-nodos">0</h3>
                </div>
            </div>
            <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg" id="kpi-optimo-porcentaje">0% del total</span>
        </div>

        <!-- KPI 3 -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-lg">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase m-0">En precaución</p>
                    <h3 class="text-2xl font-black text-slate-800 m-0" id="kpi-precaucion-nodos">0</h3>
                </div>
            </div>
            <span class="text-[11px] font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-lg">Requieren atención</span>
        </div>

        <!-- KPI 4 -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center text-lg">
                    <i class="fas fa-fire-alt"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase m-0">Estado crítico</p>
                    <h3 class="text-2xl font-black text-slate-800 m-0" id="kpi-critico-nodos">0</h3>
                </div>
            </div>
            <span class="text-[11px] font-bold text-red-600 bg-red-50 px-2 py-1 rounded-lg">Acción inmediata</span>
        </div>
    </div>

    <!-- SECCIÓN PRINCIPAL DE 2 COLUMNAS: MAPA (65%) + PANEL DE DETALLE DEL NODO (35%) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">

        <!-- COLUMNA IZQUIERDA: MAPA AMBIENTAL DEL CEFA -->
        <div class="lg:col-span-8 bg-white p-4 rounded-2xl border border-slate-200 shadow-xs space-y-3">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-base font-bold text-slate-800 m-0 flex items-center gap-2">
                        <i class="fas fa-map-marked-alt text-[#39A900]"></i> Mapa ambiental del CEFA
                    </h3>
                    <p class="text-xs text-slate-500 m-0 mt-0.5">Seleccione un punto para consultar su lectura y recomendaciones EHS.</p>
                </div>

                <div class="flex items-center gap-2">
                    <div class="bg-slate-100 p-1 rounded-xl flex items-center gap-1 border border-slate-200">
                        <button type="button" id="btn-var-co2" onclick="filtrarVariable('co2')" class="px-3 py-1 rounded-lg text-xs font-bold transition-all bg-[#39A900] text-white shadow-xs">General</button>
                        <button type="button" id="btn-var-temp" onclick="filtrarVariable('temp')" class="px-3 py-1 rounded-lg text-xs font-bold transition-all text-slate-600 hover:bg-white">CO₂</button>
                        <button type="button" id="btn-var-hum" onclick="filtrarVariable('hum')" class="px-3 py-1 rounded-lg text-xs font-bold transition-all text-slate-600 hover:bg-white">Temperatura</button>
                    </div>
                </div>
            </div>

            <!-- Canvas del Mapa -->
            <div class="relative rounded-2xl overflow-hidden border border-slate-200">
                <div id="mapa-dashboard-ehs"></div>

                <!-- Leyenda Flotante en la esquina inferior izquierda del Mapa -->
                <div class="absolute bottom-3 left-3 z-[400] bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-xl border border-slate-200 text-[11px] font-semibold text-slate-600 flex items-center gap-3 shadow-sm">
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Óptimo</span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Precaución</span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-red-500"></span> Crítico</span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span> Sin señal</span>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: PANEL FICHA DE DETALLE DEL NODO SELECCIONADO -->
        <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <!-- Encabezado de la Ficha Lateral -->
            <div class="bg-[#002235] text-white p-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-xs font-bold text-emerald-300 uppercase tracking-wider" id="panel-online-tag">Nodo en línea</span>
                </div>
                <span class="text-[10px] text-slate-300 font-mono" id="panel-node-uid">AS-000</span>
            </div>

            <div class="p-5 space-y-4">
                <!-- Header con Badge de Estado y Categoría -->
                <div>
                    <div class="flex items-center justify-between gap-2 mb-1">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400" id="panel-node-category">Agrícola</span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700" id="panel-status-badge">● Crítico</span>
                    </div>
                    <h3 class="text-xl font-extrabold text-slate-800 m-0" id="panel-node-title">Hangar de ganadería</h3>
                    <p class="text-xs text-slate-500 m-0 mt-0.5" id="panel-node-location">Zona pecuaria · AS-001</p>
                </div>

                <!-- Bloque Lectura CO2 Principal -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-2">
                    <div class="flex justify-between items-baseline">
                        <span class="text-xs font-semibold text-slate-500">CO₂ actual</span>
                        <span class="text-[10px] font-bold text-slate-400">PPM</span>
                    </div>
                    <div class="flex items-baseline gap-1">
                        <span class="text-3xl font-black text-slate-900" id="panel-co2-val">1.248</span>
                        <span class="text-xs font-bold text-slate-500">ppm</span>
                    </div>

                    <!-- Barra de progreso de estado CO2 -->
                    <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                        <div id="panel-co2-bar" class="h-full bg-gradient-to-r from-emerald-500 via-amber-500 to-red-500 rounded-full transition-all duration-500" style="width: 85%;"></div>
                    </div>
                </div>

                <!-- Mini Grid de Temperatura y Humedad -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-200">
                        <span class="text-[11px] font-semibold text-slate-500 block">Temperatura</span>
                        <span class="text-base font-extrabold text-slate-800" id="panel-temp-val">29.4 °C</span>
                    </div>
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-200">
                        <span class="text-[11px] font-semibold text-slate-500 block">Humedad</span>
                        <span class="text-base font-extrabold text-slate-800" id="panel-hum-val">71%</span>
                    </div>
                </div>

                <!-- Caja de Recomendación EHS / SST -->
                <div class="bg-amber-50/80 p-4 rounded-2xl border border-amber-200/80 space-y-1">
                    <h5 class="text-xs font-bold text-amber-800 flex items-center gap-1.5 m-0">
                        <i class="fas fa-sparkles text-amber-600"></i> Recomendación EHS
                    </h5>
                    <p class="text-xs text-amber-900 m-0 leading-relaxed" id="panel-recommendation">
                        Abra puertas y ventanas. Active extracción mecánica y evalúe evacuación preventiva.
                    </p>
                </div>

                <!-- Enlace de acción -->
                <div class="pt-1 text-center">
                    <a href="{{ route('ehscefa.mapa.index') }}" class="text-xs font-bold text-[#39A900] hover:text-emerald-700 inline-flex items-center gap-1 transition-colors">
                        Ver análisis de 24 horas <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@section('js')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
    let map;
    let markersLayer = L.layerGroup();
    let variableActual = 'co2';
    let geojsonData = null;
    let nodoSeleccionado = null;

    document.addEventListener('DOMContentLoaded', function () {
        // Coordenadas centrales dinámicas
        const centerCoords = [{{ $centerLat ?? 2.612210 }}, {{ $centerLng ?? -75.361408 }}];
        map = L.map('mapa-dashboard-ehs').setView(centerCoords, 16);

        // Vista Satelital por defecto (Google Maps Hybrid para mejor cobertura)
        L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            attribution: '© Google Maps | AirSense CEFA'
        }).addTo(map);

        markersLayer.addTo(map);

        cargarNodosEnMapa();
        setInterval(cargarNodosEnMapa, 30000);
    });

    function cargarNodosEnMapa() {
        fetch("{{ route('ehscefa.mapa.geojson') }}")
            .then(res => res.json())
            .then(data => {
                geojsonData = data;
                actualizarKpisYAlertas();
                renderizarPuntosEnMapa();
            })
            .catch(err => console.error("Error cargando GeoJSON:", err));
    }

    function actualizarKpisYAlertas() {
        if (!geojsonData || !geojsonData.features) return;

        const points = geojsonData.features.filter(f => f.geometry.type === 'Point');
        const total = points.length;
        let optimos = 0;
        let precauciones = 0;
        let criticos = 0;
        let nodoCritico = null;

        points.forEach(f => {
            const p = f.properties;
            if (p.status_code === 'critico') {
                criticos++;
                if (!nodoCritico) nodoCritico = p;
            } else if (p.status_code === 'precaucion') {
                precauciones++;
            } else {
                optimos++;
            }
        });

        document.getElementById('kpi-total-nodos').innerText = total;
        document.getElementById('kpi-online-nodos').innerText = `${total} en línea`;
        document.getElementById('kpi-optimo-nodos').innerText = optimos;
        document.getElementById('kpi-optimo-porcentaje').innerText = `${total > 0 ? Math.round((optimos / total) * 100) : 0}% del total`;
        document.getElementById('kpi-precaucion-nodos').innerText = precauciones;
        document.getElementById('kpi-critico-nodos').innerText = criticos;

        // Banner de Alerta Crítica Activa
        const banner = document.getElementById('banner-alerta-critica');
        if (criticos > 0 && nodoCritico) {
            banner.classList.remove('hidden');
            document.getElementById('lbl-alerta-mensaje').innerText = 
                `${nodoCritico.ambiente} registra ${nodoCritico.co2} ppm de CO₂. Ventile el espacio de inmediato.`;
        } else {
            banner.classList.add('hidden');
        }

        // Si no se ha seleccionado nodo, seleccionar el primero o crítico por defecto
        if (!nodoSeleccionado && points.length > 0) {
            seleccionarNodo(nodoCritico || points[0].properties);
        }
    }

    function seleccionarNodo(props) {
        nodoSeleccionado = props;

        document.getElementById('panel-node-uid').innerText = props.device_uid || 'AS-001';
        document.getElementById('panel-online-tag').innerText = props.is_online ? 'Nodo en línea' : 'Sin señal';
        document.getElementById('panel-node-category').innerText = props.categoria || 'General';
        document.getElementById('panel-node-title').innerText = props.ambiente || props.name;
        document.getElementById('panel-node-location').innerText = `${props.name} · ${props.device_uid}`;

        document.getElementById('panel-co2-val').innerText = Math.round(props.co2);
        document.getElementById('panel-temp-val').innerText = `${props.temperatura} °C`;
        document.getElementById('panel-hum-val').innerText = `${props.humedad}%`;

        document.getElementById('panel-recommendation').innerText = props.recomendacion_ehs || 'Niveles ambientales en rango estable.';

        // Badge de estado
        const badge = document.getElementById('panel-status-badge');
        if (props.status_code === 'critico') {
            badge.className = 'px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700';
            badge.innerText = '● Crítico';
        } else if (props.status_code === 'precaucion') {
            badge.className = 'px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700';
            badge.innerText = '● Precaución';
        } else {
            badge.className = 'px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700';
            badge.innerText = '● Óptimo';
        }

        // Barra de CO2 (porcentaje relativo a 1600ppm max)
        const pct = Math.min(100, Math.round((props.co2 / 1600) * 100));
        document.getElementById('panel-co2-bar').style.width = `${pct}%`;
    }

    function filtrarVariable(variable) {
        // Filtros (si los tuvieras habilitados)
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
                        color: props.stroke || '#eab308', // Amarillo
                        weight: props['stroke-width'] || 4,
                        fillColor: props.fill || '#39A900', // Verde
                        fillOpacity: props['fill-opacity'] || 0.25,
                        dashArray: '5, 5'
                    }
                });
                polygonLayer.bindTooltip(`<b>${props.name}</b>`, { sticky: true });
                markersLayer.addLayer(polygonLayer);
                return;
            }

            if (geom.type === 'Point') {
                const coords = geom.coordinates;
                let pulseClass = "pulse-green";
                if (props.status_code === 'critico') pulseClass = "pulse-red";
                else if (props.status_code === 'precaucion') pulseClass = "pulse-yellow";

                const customIcon = L.divIcon({
                    className: 'custom-leaflet-icon',
                    html: `
                        <div onclick="seleccionarNodoDirecto(${props.id})" class="cursor-pointer" style="position:relative; width:30px; height:30px;">
                            <div class="marker-pin" style="background-color: ${props.color};"></div>
                            <div class="absolute -top-1 -right-1 w-3.5 h-3.5 rounded-full ${pulseClass}" style="background-color: ${props.color};"></div>
                        </div>
                    `,
                    iconSize: [30, 30],
                    iconAnchor: [15, 30] // La punta del alfiler apuntará exactamente a la coordenada
                });

                const marker = L.marker([coords[1], coords[0]], { icon: customIcon });

                marker.bindTooltip(`
                    <div class="text-center leading-tight">
                        <span class="text-[11px] font-extrabold text-slate-800">${props.ambiente}</span><br>
                        <span class="text-[9.5px] font-bold" style="color: ${props.color};">${props.status_text}</span>
                    </div>
                `, { 
                    permanent: true, 
                    direction: "top", 
                    offset: [0, -32],
                    className: 'custom-tooltip'
                });

                marker.on('click', function() {
                    seleccionarNodo(props);
                });

                markersLayer.addLayer(marker);
            }
        });

        if (markersLayer.getLayers().length > 0) {
            const bounds = L.featureGroup(markersLayer.getLayers()).getBounds();
            if (bounds.isValid()) {
                map.fitBounds(bounds, { padding: [40, 40] });
            }
        }
    }

    function seleccionarNodoDirecto(id) {
        if (!geojsonData || !geojsonData.features) return;
        const feature = geojsonData.features.find(f => f.properties.id === id);
        if (feature) seleccionarNodo(feature.properties);
    }
</script>
@endsection



