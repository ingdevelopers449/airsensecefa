@extends('layouts.sidebarinstructorcefa')

@section('css')
<style>
{!! file_get_contents(resource_path('css/instructor-dashboard.css')) !!}
</style>
@endsection

@section('content')

<div class="container-fluid py-3">

    {{-- ═══════════════════ ENCABEZADO ═══════════════════ --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4 bg-success bg-opacity-10 rounded-4">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-success text-white rounded-4 p-3">
                    <i class="bi bi-leaf-fill fs-2"></i>
                </div>

                <div class="flex-grow-1">
                    <h3 class="fw-bold text-success mb-1">
                        Semáforo Ambiental
                    </h3>
                    <p class="text-secondary mb-0">
                        Monitoreo visual del estado ambiental de la institución.
                    </p>
                </div>

                {{-- Indicador de última actualización --}}
                <div id="last-update-container" class="text-end d-none d-md-block">
                    <small class="text-secondary d-block">
                        <i class="bi bi-clock-history me-1"></i>Última lectura
                    </small>
                    <span id="last-update" class="fw-semibold text-success small">
                        {{ $measuredAt ? \Carbon\Carbon::parse($measuredAt)->diffForHumans() : 'Sin datos' }}
                    </span>
                </div>
            </div>
        </div>
    </div>


    {{-- ═══════════════════ TARJETAS DE MÉTRICAS ═══════════════════ --}}
    <div class="row g-3 mb-4">

        {{-- ── CO₂ con Gauge ── --}}
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 metric-card">
                <div id="co2-accent" class="metric-accent" style="background-color: {{ $gaugeColor }};"></div>
                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="text-secondary fw-medium small">CO₂ (PPM)</span>
                        <i class="bi bi-wind text-success opacity-50"></i>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        {{-- SVG Gauge --}}
                        <div class="gauge-wrapper">
                            <svg viewBox="0 0 120 120" class="gauge-svg">
                                <circle class="gauge-bg" cx="60" cy="60" r="50"/>
                                <circle id="co2-gauge-progress" class="gauge-progress" cx="60" cy="60" r="50"
                                        stroke-dasharray="{{ number_format($circumference, 2) }}"
                                        stroke-dashoffset="{{ number_format($gaugeOffset, 2) }}"
                                        stroke="{{ $gaugeColor }}"
                                        transform="rotate(-90 60 60)"/>
                                <text x="60" y="52" text-anchor="middle"
                                      class="gauge-label-text">Aula</text>
                                <text x="60" y="74" text-anchor="middle"
                                      class="gauge-number-text">{{ $aulaNumero }}</text>
                            </svg>
                        </div>

                        <div>
                            <div class="metric-value">
                                <span id="co2-value">{{ $co2 ?? '--' }}</span>
                                <span class="metric-unit">PPM</span>
                            </div>
                            <span id="co2-badge" class="metric-badge"
                                  style="background-color: {{ $co2Config['bg'] }};
                                         color: {{ $co2Config['color'] }};">
                                {{ $co2Config['label'] }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ── Temperatura ── --}}
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 metric-card">
                <div id="temp-accent" class="metric-accent" style="background-color: {{ $tempConfig['dot'] }};"></div>
                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="text-secondary fw-medium small">Temperatura</span>
                        <i class="bi bi-thermometer-half text-warning opacity-50"></i>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <div class="metric-icon-circle" style="background: rgba(255, 152, 0, 0.12);">
                            <i class="bi bi-thermometer-half fs-3" style="color: #ff9800;"></i>
                        </div>
                        <div>
                            <div class="metric-value">
                                <span id="temp-value">{{ $temperatura ?? '--' }}</span>
                                <span class="metric-unit">°C</span>
                            </div>
                            <span id="temp-badge" class="metric-badge"
                                  style="background-color: {{ $tempConfig['bg'] }};
                                         color: {{ $tempConfig['color'] }};">
                                {{ $tempConfig['label'] }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ── Humedad ── --}}
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 metric-card">
                <div id="hum-accent" class="metric-accent" style="background-color: {{ $humConfig['dot'] }};"></div>
                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="text-secondary fw-medium small">Humedad</span>
                        <i class="bi bi-droplet-half text-primary opacity-50"></i>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <div class="metric-icon-circle" style="background: rgba(33, 150, 243, 0.12);">
                            <i class="bi bi-droplet-half fs-3" style="color: #2196f3;"></i>
                        </div>
                        <div>
                            <div class="metric-value">
                                <span id="hum-value">{{ $humedad ?? '--' }}</span><span class="metric-unit">%</span>
                            </div>
                            <span id="hum-badge" class="metric-badge"
                                  style="background-color: {{ $humConfig['bg'] }};
                                         color: {{ $humConfig['color'] }};">
                                {{ $humConfig['label'] }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>


    {{-- ═══════════════════ SEMÁFORO + PANEL LATERAL ═══════════════════ --}}
    <div class="row g-4 mb-4">

        {{-- ── SEMÁFORO PRINCIPAL (reemplaza la gráfica) ── --}}
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-stoplights text-success me-2"></i>
                        Estado Ambiental
                    </h5>
                    <p class="text-secondary small mt-1 mb-0">
                        Estado general de las condiciones ambientales del aula
                    </p>
                </div>

                <div class="card-body px-4 pb-4">
                    <div class="row">

                        {{-- SEMÁFORO VISUAL --}}
                        <div class="col-md-5 d-flex align-items-center justify-content-center py-3">
                            <div class="semaforo-area">
                                <div class="semaforo-container">

                                    <div class="semaforo-body">
                                        {{-- LUZ ROJA --}}
                                        <div class="semaforo-light-wrapper">
                                            <div class="semaforo-visor"></div>
                                            <div id="semaforo-red" class="semaforo-light {{ $estado === 'rojo' ? 'active-red' : 'inactive' }}"></div>
                                        </div>

                                        {{-- LUZ AMARILLA --}}
                                        <div class="semaforo-light-wrapper">
                                            <div class="semaforo-visor"></div>
                                            <div id="semaforo-yellow" class="semaforo-light {{ $estado === 'amarillo' ? 'active-yellow' : 'inactive' }}"></div>
                                        </div>

                                        {{-- LUZ VERDE --}}
                                        <div class="semaforo-light-wrapper">
                                            <div class="semaforo-visor"></div>
                                            <div id="semaforo-green" class="semaforo-light {{ $estado === 'verde' ? 'active-green' : 'inactive' }}"></div>
                                        </div>
                                    </div>

                                    {{-- POSTE Y BASE --}}
                                    <div class="semaforo-pole"></div>
                                    <div class="semaforo-base"></div>
                                    <div class="semaforo-base-bottom"></div>

                                </div>
                            </div>
                        </div>

                        {{-- PANEL DE ESTADO E INDICADORES --}}
                        <div class="col-md-7">
                            <div class="estado-panel border-start-md">

                                <h5 class="fw-bold mb-3">Estado Ambiental General</h5>

                                {{-- Badge de estado principal --}}
                                <span id="estado-badge" class="estado-badge-large text-bg-{{ $colorEstado }}">
                                    <i id="estado-icon" class="{{ $iconoEstado }}"></i>
                                    <span id="estado-label">{{ $etiquetaEstado }}</span>
                                </span>

                                <p id="estado-message" class="text-secondary mt-3 mb-0" style="font-size: 0.875rem;">
                                    {{ $mensajeEstado }}
                                </p>

                                <div class="separator-line"></div>

                                {{-- INDICADORES --}}
                                <h6 class="indicadores-titulo">Indicadores</h6>

                                {{-- Indicador: Calidad del Aire (CO₂) --}}
                                <div id="indicator-co2" class="indicator-item">
                                    <div class="indicator-dot"
                                         style="background-color: {{ $co2Config['dot'] }};"></div>
                                    <div class="indicator-info">
                                        <strong>Calidad del aire</strong>
                                        <p>{{ $co2Desc }}</p>
                                    </div>
                                    <span class="indicator-badge"
                                          style="background-color: {{ $co2Config['bg'] }};
                                                 color: {{ $co2Config['color'] }};">
                                        {{ $co2Config['label'] }}
                                    </span>
                                </div>

                                {{-- Indicador: Temperatura --}}
                                <div id="indicator-temp" class="indicator-item">
                                    <div class="indicator-dot"
                                         style="background-color: {{ $tempConfig['dot'] }};"></div>
                                    <div class="indicator-info">
                                        <strong>Temperatura</strong>
                                        <p>{{ $tempDesc }}</p>
                                    </div>
                                    <span class="indicator-badge"
                                          style="background-color: {{ $tempConfig['bg'] }};
                                                 color: {{ $tempConfig['color'] }};">
                                        {{ $tempConfig['label'] }}
                                    </span>
                                </div>

                                {{-- Indicador: Humedad --}}
                                <div id="indicator-hum" class="indicator-item">
                                    <div class="indicator-dot"
                                         style="background-color: {{ $humConfig['dot'] }};"></div>
                                    <div class="indicator-info">
                                        <strong>Humedad relativa</strong>
                                        <p>{{ $humDesc }}</p>
                                    </div>
                                    <span class="indicator-badge"
                                          style="background-color: {{ $humConfig['bg'] }};
                                                 color: {{ $humConfig['color'] }};">
                                        {{ $humConfig['label'] }}
                                    </span>
                                </div>

                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        {{-- ── PANEL LATERAL DERECHO ── --}}
        <div class="col-12 col-lg-4">

            {{-- AMBIENTE ASIGNADO (reemplaza el selector de aula) --}}
            <x-environment-card :ambiente="$ambienteNombre" :estado="$estado" />

            {{-- REGISTRO DE OCUPACIÓN --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">
                        Registro de Ocupación del Ambiente
                    </h5>

                    <i class="bi bi-three-dots-vertical text-secondary fs-5"></i>
                </div>

                <div class="card-body px-4 pb-4">

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle p-3">
                            <i class="bi bi-people-fill fs-3"></i>
                        </div>

                        <div>
                            <span class="text-secondary d-block">
                                Aforo Actual:
                            </span>

                            <strong class="fs-4">
                                {{ $aforoActual }}
                            </strong>

                            <span class="text-secondary">
                                aprendices
                            </span>
                        </div>
                    </div>

                    <div class="progress rounded-pill" style="height: 12px;">
                        <div
                            class="progress-bar bg-success"
                            role="progressbar"
                            style="width: {{ $porcentajeAforo }}%;"
                            aria-valuenow="{{ $porcentajeAforo }}"
                            aria-valuemin="0"
                            aria-valuemax="100"
                        ></div>
                    </div>

                    <div class="d-flex justify-content-between mt-2 text-secondary small">
                        <span>0</span>
                        <span>Máximo: {{ $aforoMaximo }}</span>
                    </div>

                </div>
            </div>

        </div>

    </div>

</div>

@endsection

@section('js')
<script>
/**
 * ═══════════════════════════════════════════════════════════
 * AirSense CEFA – Dashboard Instructor: Actualización en Tiempo Real
 * Polling cada 15 segundos al endpoint /api/v1/nodes/latest-telemetry
 * ═══════════════════════════════════════════════════════════
 */
(function () {
    'use strict';

    const POLL_INTERVAL_MS = 15000;
    const API_URL = '/api/v1/nodes/latest-telemetry?limit=1';
    const GAUGE_RADIUS = 50;
    const GAUGE_MAX = 2000;
    const CIRCUMFERENCE = 2 * Math.PI * GAUGE_RADIUS; // ≈ 314.16

    // ── Configuración de umbrales (mismos que SensorThresholdService) ──
    const THRESHOLDS = {
        co2: { green: 800, yellow: 1200 },
        temperature: { greenMin: 18, greenMax: 26, yellowMin: 15, yellowMax: 30 },
        humidity: { greenMin: 40, greenMax: 60, yellowMin: 30, yellowMax: 70 },
    };

    const STATUS_CONFIG = {
        verde:     { label: 'Bueno',    bg: '#e8f5e9', color: '#2e7d32', dot: '#2e7d32' },
        amarillo:  { label: 'En alerta', bg: '#fff8e1', color: '#e65100', dot: '#f9a825' },
        rojo:      { label: 'Crítico',   bg: '#ffebee', color: '#c62828', dot: '#e63946' },
        sin_datos: { label: 'Sin datos', bg: '#f5f5f5', color: '#616161', dot: '#9e9e9e' },
    };

    const GAUGE_COLORS = {
        verde: '#39A900', amarillo: '#f9a825', rojo: '#e63946', sin_datos: '#6c757d'
    };

    const ESTADO_GENERAL = {
        verde:     { bsClass: 'text-bg-success',   icon: 'bi-check-circle-fill',           label: 'ÓPTIMO',    msg: '¡Excelente! Todas las condiciones ambientales son adecuadas.' },
        amarillo:  { bsClass: 'text-bg-warning',    icon: 'bi-exclamation-triangle-fill',   label: 'EN ALERTA', msg: 'Uno o más indicadores presentan niveles que requieren atención y seguimiento.' },
        rojo:      { bsClass: 'text-bg-danger',     icon: 'bi-exclamation-octagon-fill',    label: 'CRÍTICO',   msg: '¡Alerta! Se deben revisar las condiciones ambientales de inmediato.' },
        sin_datos: { bsClass: 'text-bg-secondary',  icon: 'bi-hourglass-split',             label: 'SIN DATOS', msg: 'Esperando información de los sensores.' },
    };

    const DESCRIPTIONS = {
        co2: {
            verde: 'Las condiciones del aire se mantienen adecuadas.',
            amarillo: 'Se recomienda mejorar la ventilación del espacio.',
            rojo: 'Se requiere atención inmediata en la ventilación.',
            sin_datos: 'Esperando datos del sensor de CO₂.',
        },
        temperature: {
            verde: 'La temperatura se encuentra en rango confortable.',
            amarillo: 'La temperatura requiere monitoreo continuo.',
            rojo: 'Temperatura fuera del rango aceptable.',
            sin_datos: 'Esperando datos del sensor de temperatura.',
        },
        humidity: {
            verde: 'La humedad relativa se mantiene adecuada.',
            amarillo: 'La humedad requiere seguimiento y atención.',
            rojo: 'Humedad fuera del rango aceptable.',
            sin_datos: 'Esperando datos del sensor de humedad.',
        },
    };

    // ── Funciones de evaluación de umbrales ──────────────────

    function evaluateCo2(value) {
        if (value === null || value === undefined) return 'sin_datos';
        if (value < THRESHOLDS.co2.green) return 'verde';
        if (value <= THRESHOLDS.co2.yellow) return 'amarillo';
        return 'rojo';
    }

    function evaluateTemperature(value) {
        if (value === null || value === undefined) return 'sin_datos';
        if (value >= THRESHOLDS.temperature.greenMin && value <= THRESHOLDS.temperature.greenMax) return 'verde';
        if ((value >= THRESHOLDS.temperature.yellowMin && value < THRESHOLDS.temperature.greenMin) ||
            (value > THRESHOLDS.temperature.greenMax && value <= THRESHOLDS.temperature.yellowMax)) return 'amarillo';
        return 'rojo';
    }

    function evaluateHumidity(value) {
        if (value === null || value === undefined) return 'sin_datos';
        if (value >= THRESHOLDS.humidity.greenMin && value <= THRESHOLDS.humidity.greenMax) return 'verde';
        if ((value >= THRESHOLDS.humidity.yellowMin && value < THRESHOLDS.humidity.greenMin) ||
            (value > THRESHOLDS.humidity.greenMax && value <= THRESHOLDS.humidity.yellowMax)) return 'amarillo';
        return 'rojo';
    }

    function evaluateOverall(co2Estado, tempEstado, humEstado) {
        const estados = [co2Estado, tempEstado, humEstado];
        if (estados.includes('rojo')) return 'rojo';
        if (estados.includes('amarillo')) return 'amarillo';
        if (estados.includes('verde')) return 'verde';
        return 'sin_datos';
    }

    // ── Utilidades de formato ────────────────────────────────

    function timeAgo(dateString) {
        if (!dateString) return 'Sin datos';
        const now = new Date();
        const then = new Date(dateString);
        const diffMs = now - then;
        const diffSec = Math.floor(diffMs / 1000);
        if (diffSec < 60) return 'hace ' + diffSec + ' seg';
        const diffMin = Math.floor(diffSec / 60);
        if (diffMin < 60) return 'hace ' + diffMin + ' min';
        const diffHr = Math.floor(diffMin / 60);
        if (diffHr < 24) return 'hace ' + diffHr + 'h';
        return 'hace ' + Math.floor(diffHr / 24) + 'd';
    }

    // ── Función principal de actualización del DOM ───────────

    function updateDashboard(data) {
        if (!data || !data.data || data.data.length === 0) return;

        const reading = data.data[0];
        const measurements = reading.measurements || [];

        // Extraer valores por variable_type
        let co2Val = null, tempVal = null, humVal = null;
        measurements.forEach(function (m) {
            const val = parseFloat(m.value);
            if (m.variable_type === 'co2') co2Val = val;
            else if (m.variable_type === 'temperature') tempVal = val;
            else if (m.variable_type === 'humidity') humVal = val;
        });

        // Evaluar estados
        const co2Estado  = evaluateCo2(co2Val);
        const tempEstado = evaluateTemperature(tempVal);
        const humEstado  = evaluateHumidity(humVal);
        const estado     = evaluateOverall(co2Estado, tempEstado, humEstado);

        const co2Cfg  = STATUS_CONFIG[co2Estado];
        const tempCfg = STATUS_CONFIG[tempEstado];
        const humCfg  = STATUS_CONFIG[humEstado];

        // ── Actualizar valores numéricos ──
        setText('co2-value', co2Val !== null ? co2Val : '--');
        setText('temp-value', tempVal !== null ? tempVal : '--');
        setText('hum-value', humVal !== null ? humVal : '--');

        // ── Actualizar badges de métricas ──
        updateBadge('co2-badge', co2Cfg);
        updateBadge('temp-badge', tempCfg);
        updateBadge('hum-badge', humCfg);

        // ── Actualizar accents (barra lateral izquierda de tarjetas) ──
        setStyle('co2-accent', 'backgroundColor', GAUGE_COLORS[co2Estado]);
        setStyle('temp-accent', 'backgroundColor', tempCfg.dot);
        setStyle('hum-accent', 'backgroundColor', humCfg.dot);

        // ── Actualizar Gauge SVG de CO₂ ──
        const gaugePercent = co2Val !== null ? Math.min(100, (co2Val / GAUGE_MAX) * 100) : 0;
        const gaugeOffset  = CIRCUMFERENCE - (CIRCUMFERENCE * gaugePercent / 100);
        const gaugeEl = document.getElementById('co2-gauge-progress');
        if (gaugeEl) {
            gaugeEl.setAttribute('stroke-dashoffset', gaugeOffset.toFixed(2));
            gaugeEl.setAttribute('stroke', GAUGE_COLORS[co2Estado]);
        }

        // ── Actualizar Semáforo ──
        updateSemaforo(estado);

        // ── Actualizar panel de estado general ──
        const estadoCfg = ESTADO_GENERAL[estado];
        const badgeEl = document.getElementById('estado-badge');
        if (badgeEl) {
            badgeEl.className = 'estado-badge-large ' + estadoCfg.bsClass;
        }
        setClass('estado-icon', estadoCfg.icon);
        setText('estado-label', estadoCfg.label);
        setText('estado-message', estadoCfg.msg);

        // ── Actualizar indicadores ──
        updateIndicator('indicator-co2', co2Cfg, DESCRIPTIONS.co2[co2Estado]);
        updateIndicator('indicator-temp', tempCfg, DESCRIPTIONS.temperature[tempEstado]);
        updateIndicator('indicator-hum', humCfg, DESCRIPTIONS.humidity[humEstado]);

        // ── Actualizar timestamp ──
        const measuredAt = reading.measured_at || reading.received_at || null;
        setText('last-update', timeAgo(measuredAt));
    }

    // ── Helpers DOM ──────────────────────────────────────────

    function setText(id, text) {
        const el = document.getElementById(id);
        if (el) el.textContent = text;
    }

    function setStyle(id, prop, value) {
        const el = document.getElementById(id);
        if (el) el.style[prop] = value;
    }

    function setClass(id, className) {
        const el = document.getElementById(id);
        if (el) el.className = className;
    }

    function updateBadge(id, cfg) {
        const el = document.getElementById(id);
        if (!el) return;
        el.textContent = cfg.label;
        el.style.backgroundColor = cfg.bg;
        el.style.color = cfg.color;
    }

    function updateSemaforo(estado) {
        const lights = {
            'semaforo-red':    { active: 'active-red',    on: estado === 'rojo' },
            'semaforo-yellow': { active: 'active-yellow', on: estado === 'amarillo' },
            'semaforo-green':  { active: 'active-green',  on: estado === 'verde' },
        };
        Object.entries(lights).forEach(function ([id, cfg]) {
            const el = document.getElementById(id);
            if (!el) return;
            el.classList.remove('active-red', 'active-yellow', 'active-green', 'inactive');
            el.classList.add(cfg.on ? cfg.active : 'inactive');
        });
    }

    function updateIndicator(id, cfg, description) {
        const el = document.getElementById(id);
        if (!el) return;

        const dot = el.querySelector('.indicator-dot');
        if (dot) dot.style.backgroundColor = cfg.dot;

        const desc = el.querySelector('.indicator-info p');
        if (desc) desc.textContent = description;

        const badge = el.querySelector('.indicator-badge');
        if (badge) {
            badge.textContent = cfg.label;
            badge.style.backgroundColor = cfg.bg;
            badge.style.color = cfg.color;
        }
    }

    // ── Polling ──────────────────────────────────────────────

    function fetchSensorData() {
        fetch(API_URL)
            .then(function (res) {
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.json();
            })
            .then(function (data) {
                updateDashboard(data);
            })
            .catch(function (err) {
                console.warn('[AirSense] Error al consultar sensores:', err.message);
            });
    }

    // Ejecutar primera consulta inmediata + intervalo periódico
    fetchSensorData();
    setInterval(fetchSensorData, POLL_INTERVAL_MS);

})();
</script>
@endsection