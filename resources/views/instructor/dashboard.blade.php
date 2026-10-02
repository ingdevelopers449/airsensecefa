@extends('layouts.sidebarinstructorcefa')

@section('css')
<style>
{!! file_get_contents(resource_path('css/instructor-dashboard.css')) !!}
</style>
@endsection

@section('content')

@php
    // ═══════════════════════════════════════════════════════════
    // Variables de sensores con fallbacks (datos dummy por ahora).
    // Cuando se cree el InstructorDashboardController, estas
    // variables serán pasadas desde el controlador con datos
    // reales de SensorMeasurement.
    // ═══════════════════════════════════════════════════════════

    $co2Value  = isset($co2) && is_numeric($co2) ? (float) $co2 : null;
    $tempValue = isset($temperatura) && is_numeric($temperatura) ? (float) $temperatura : null;
    $humValue  = isset($humedad) && is_numeric($humedad) ? (float) $humedad : null;

    // ── Umbrales de CO₂ ──
    // 🟢 < 800 PPM  │  🟡 800 – 1200 PPM  │  🔴 > 1200 PPM
    $co2Estado = match (true) {
        $co2Value === null        => 'sin_datos',
        $co2Value < 800           => 'verde',
        $co2Value <= 1200         => 'amarillo',
        default                   => 'rojo',
    };

    // ── Umbrales de Temperatura (18 – 26 °C óptimo) ──
    $tempEstado = match (true) {
        $tempValue === null                                                     => 'sin_datos',
        $tempValue >= 18 && $tempValue <= 26                                    => 'verde',
        ($tempValue >= 15 && $tempValue < 18) || ($tempValue > 26 && $tempValue <= 30) => 'amarillo',
        default                                                                 => 'rojo',
    };

    // ── Umbrales de Humedad (40 – 60 % óptimo) ──
    $humEstado = match (true) {
        $humValue === null                                                    => 'sin_datos',
        $humValue >= 40 && $humValue <= 60                                    => 'verde',
        ($humValue >= 30 && $humValue < 40) || ($humValue > 60 && $humValue <= 70) => 'amarillo',
        default                                                               => 'rojo',
    };

    // ── Estado general del semáforo = peor indicador ──
    $todosEstados = [$co2Estado, $tempEstado, $humEstado];
    if (in_array('rojo', $todosEstados)) {
        $estado = 'rojo';
    } elseif (in_array('amarillo', $todosEstados)) {
        $estado = 'amarillo';
    } elseif (in_array('verde', $todosEstados)) {
        $estado = 'verde';
    } else {
        $estado = 'sin_datos';
    }

    // ── Configuración visual del estado general ──
    $colorEstado = match ($estado) {
        'verde'    => 'success',
        'amarillo' => 'warning',
        'rojo'     => 'danger',
        default    => 'secondary',
    };

    $etiquetaEstado = match ($estado) {
        'verde'    => 'ÓPTIMO',
        'amarillo' => 'EN ALERTA',
        'rojo'     => 'CRÍTICO',
        default    => 'SIN DATOS',
    };

    $iconoEstado = match ($estado) {
        'verde'    => 'bi-check-circle-fill',
        'amarillo' => 'bi-exclamation-triangle-fill',
        'rojo'     => 'bi-exclamation-octagon-fill',
        default    => 'bi-hourglass-split',
    };

    $mensajeEstado = match ($estado) {
        'verde'    => '¡Excelente! Todas las condiciones ambientales son adecuadas.',
        'amarillo' => 'Uno o más indicadores presentan niveles que requieren atención y seguimiento.',
        'rojo'     => '¡Alerta! Se deben revisar las condiciones ambientales de inmediato.',
        default    => 'Esperando información de los sensores.',
    };

    // ── Mapeo de estado → estilos por variable ──
    $statusConfig = [
        'verde'     => ['label' => 'Bueno',    'bg' => '#e8f5e9', 'color' => '#2e7d32', 'dot' => '#2e7d32'],
        'amarillo'  => ['label' => 'En alerta', 'bg' => '#fff8e1', 'color' => '#e65100', 'dot' => '#f9a825'],
        'rojo'      => ['label' => 'Crítico',   'bg' => '#ffebee', 'color' => '#c62828', 'dot' => '#e63946'],
        'sin_datos' => ['label' => 'Sin datos', 'bg' => '#f5f5f5', 'color' => '#616161', 'dot' => '#9e9e9e'],
    ];

    $co2Config  = $statusConfig[$co2Estado];
    $tempConfig = $statusConfig[$tempEstado];
    $humConfig  = $statusConfig[$humEstado];

    // ── Descripciones de indicadores ──
    $co2Desc = match ($co2Estado) {
        'verde'    => 'Las condiciones del aire se mantienen adecuadas.',
        'amarillo' => 'Se recomienda mejorar la ventilación del espacio.',
        'rojo'     => 'Se requiere atención inmediata en la ventilación.',
        default    => 'Esperando datos del sensor de CO₂.',
    };

    $tempDesc = match ($tempEstado) {
        'verde'    => 'La temperatura se encuentra en rango confortable.',
        'amarillo' => 'La temperatura requiere monitoreo continuo.',
        'rojo'     => 'Temperatura fuera del rango aceptable.',
        default    => 'Esperando datos del sensor de temperatura.',
    };

    $humDesc = match ($humEstado) {
        'verde'    => 'La humedad relativa se mantiene adecuada.',
        'amarillo' => 'La humedad requiere seguimiento y atención.',
        'rojo'     => 'Humedad fuera del rango aceptable.',
        default    => 'Esperando datos del sensor de humedad.',
    };

    // ── Gauge circular de CO₂ ──
    $gaugeMax     = 2000;
    $gaugePercent = $co2Value !== null ? min(100, ($co2Value / $gaugeMax) * 100) : 0;
    $circumference = 2 * M_PI * 50; // ≈ 314.16
    $gaugeOffset   = $circumference - ($circumference * $gaugePercent / 100);
    $gaugeColor = match ($co2Estado) {
        'verde'    => '#39A900',
        'amarillo' => '#f9a825',
        'rojo'     => '#e63946',
        default    => '#6c757d',
    };

    // ── Datos de ocupación ──
    $aforoActual   = $aforoActual ?? 0;
    $aforoMaximo   = $aforoMaximo ?? 30;
    $porcentajeAforo = $aforoMaximo > 0
        ? min(100, round(($aforoActual / $aforoMaximo) * 100))
        : 0;

    // ── Ambiente asignado ──
    $ambienteNombre = $ambienteNombre ?? 'Sin Ambiente Asignado';
    $aulaNumero     = $aulaNumero ?? '0';
@endphp

<div class="container-fluid py-3">

    {{-- ═══════════════════ ENCABEZADO ═══════════════════ --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4 bg-success bg-opacity-10 rounded-4">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-success text-white rounded-4 p-3">
                    <i class="bi bi-leaf-fill fs-2"></i>
                </div>

                <div>
                    <h3 class="fw-bold text-success mb-1">
                        Semáforo Ambiental
                    </h3>
                    <p class="text-secondary mb-0">
                        Monitoreo visual del estado ambiental de la institución.
                    </p>
                </div>
            </div>
        </div>
    </div>


    {{-- ═══════════════════ TARJETAS DE MÉTRICAS ═══════════════════ --}}
    <div class="row g-3 mb-4">

        {{-- ── CO₂ con Gauge ── --}}
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 metric-card">
                <div class="metric-accent" style="background-color: {{ $gaugeColor }};"></div>
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
                                <circle class="gauge-progress" cx="60" cy="60" r="50"
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
                                {{ $co2 ?? '--' }}
                                <span class="metric-unit">PPM</span>
                            </div>
                            <span class="metric-badge"
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
                <div class="metric-accent" style="background-color: {{ $tempConfig['dot'] }};"></div>
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
                                {{ $temperatura ?? '--' }}
                                <span class="metric-unit">°C</span>
                            </div>
                            <span class="metric-badge"
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
                <div class="metric-accent" style="background-color: {{ $humConfig['dot'] }};"></div>
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
                                {{ $humedad ?? '--' }}<span class="metric-unit">%</span>
                            </div>
                            <span class="metric-badge"
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
                                            <div class="semaforo-light {{ $estado === 'rojo' ? 'active-red' : 'inactive' }}"></div>
                                        </div>

                                        {{-- LUZ AMARILLA --}}
                                        <div class="semaforo-light-wrapper">
                                            <div class="semaforo-visor"></div>
                                            <div class="semaforo-light {{ $estado === 'amarillo' ? 'active-yellow' : 'inactive' }}"></div>
                                        </div>

                                        {{-- LUZ VERDE --}}
                                        <div class="semaforo-light-wrapper">
                                            <div class="semaforo-visor"></div>
                                            <div class="semaforo-light {{ $estado === 'verde' ? 'active-green' : 'inactive' }}"></div>
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
                                <span class="estado-badge-large text-bg-{{ $colorEstado }}">
                                    <i class="{{ $iconoEstado }}"></i>
                                    {{ $etiquetaEstado }}
                                </span>

                                <p class="text-secondary mt-3 mb-0" style="font-size: 0.875rem;">
                                    {{ $mensajeEstado }}
                                </p>

                                <div class="separator-line"></div>

                                {{-- INDICADORES --}}
                                <h6 class="indicadores-titulo">Indicadores</h6>

                                {{-- Indicador: Calidad del Aire (CO₂) --}}
                                <div class="indicator-item">
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
                                <div class="indicator-item">
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
                                <div class="indicator-item">
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