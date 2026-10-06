@extends('layouts.sidebarehscefa')

@section('tituloPagina', 'Análisis Predictivo con Inteligencia Artificial')

@section('content')
<div class="container-fluid px-0">

    <!-- 1. Encabezado e Insignia DeepSeek AI -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 pb-2 border-bottom">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h4 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-cpu-fill text-primary"></i> Predicción de Calidad del Aire con IA
                </h4>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-bold" style="font-size: 0.75rem;">
                    <i class="bi bi-stars me-1"></i> {{ $aiEngine }}
                </span>
            </div>
            <p class="text-muted small mb-0">Anticipación inteligente a problemas de ventilación y acumulación de gases en los ambientes del CEFA.</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <a href="{{ route('ehscefa.dashboard') }}" class="btn btn-outline-secondary btn-sm px-3 rounded-pill">
                <i class="bi bi-arrow-left me-1"></i> Dashboard EHS
            </a>
            <button type="button" onclick="window.location.reload();" class="btn btn-primary btn-sm px-3 rounded-pill shadow-sm">
                <i class="bi bi-arrow-repeat me-1"></i> Recalcular Proyección
            </button>
        </div>
    </div>

    <!-- 2. Filtros de Ambiente y Variable con Auto-Submit -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('ehscefa.predictivo.index') }}" class="row g-3 align-items-center">
                
                <div class="col-12 col-sm-6 col-md-5">
                    <label class="form-label small fw-semibold text-muted mb-1">Seleccionar Ambiente / Aula</label>
                    <select name="environment_id" class="form-select form-select-sm rounded-3" onchange="this.form.submit()">
                        <option value="">Todos los Ambientes (Vista General CEFA)</option>
                        @foreach($environments as $env)
                            <option value="{{ $env->id }}" {{ $environmentId == $env->id ? 'selected' : '' }}>
                                {{ $env->name }} ({{ $env->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-sm-6 col-md-4">
                    <label class="form-label small fw-semibold text-muted mb-1">Variable a Proyectar</label>
                    <select name="variable_type" class="form-select form-select-sm rounded-3" onchange="this.form.submit()">
                        <option value="co2" {{ $variableType === 'co2' ? 'selected' : '' }}>CO2 (Concentración de Gas Monóxido)</option>
                        <option value="temperature" {{ $variableType === 'temperature' ? 'selected' : '' }}>Temperatura (°C)</option>
                        <option value="humidity" {{ $variableType === 'humidity' ? 'selected' : '' }}>Humedad Relativa (%)</option>
                    </select>
                </div>

                <div class="col-12 col-md-3 text-md-end mt-3 mt-md-4">
                    <span class="badge bg-light text-secondary border rounded-pill px-3 py-2 small">
                        <i class="bi bi-database-check text-success me-1"></i> Lecturas: <strong>{{ number_format($totalReadings) }}</strong>
                    </span>
                </div>

            </form>
        </div>
    </div>

    @if($isLearningPhase)
        <!-- CUMPLIMIENTO RF-23: ESTADO EN APRENDIZAJE (< 100 LECTURAS) -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white border-start border-4 border-info">
            <div class="card-body p-4 text-center">
                <div class="d-inline-flex p-3 rounded-circle bg-info-subtle text-info mb-3">
                    <i class="bi bi-hourglass-split display-5"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Ambiente en Fase de Aprendizaje (RF-23)</h5>
                <p class="text-muted small mb-3 max-w-2xl mx-auto" style="max-width: 600px;">
                    El sistema requiere un mínimo de <strong>100 lecturas históricas</strong> para alimentar los modelos analíticos de DeepSeek AI sin generar falsas alarmas.
                </p>
                <div class="d-inline-block p-3 bg-light rounded-3 border text-start mb-3" style="max-width: 500px;">
                    <div class="d-flex justify-content-between text-muted small fw-semibold mb-1">
                        <span>Progreso de Muestreo</span>
                        <span class="text-primary">{{ $totalReadings }} / 100 lecturas</span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ min(100, ($totalReadings / 100) * 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    @else

        <!-- 3. BARRA DE TENDENCIA INSTANTÁNEA -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
            <div class="card-body p-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 d-flex align-items-center justify-content-center {{ $trendDirection === 'up' ? 'bg-warning-subtle text-warning' : ($trendDirection === 'down' ? 'bg-success-subtle text-success' : 'bg-primary-subtle text-primary') }}" style="width: 48px; height: 48px;">
                        @if($trendDirection === 'up')
                            <i class="bi bi-graph-up-arrow fs-4"></i>
                        @elseif($trendDirection === 'down')
                            <i class="bi bi-graph-down-arrow fs-4"></i>
                        @else
                            <i class="bi bi-dash-lg fs-4"></i>
                        @endif
                    </div>
                    <div>
                        <span class="text-muted small d-block mb-1">Comportamiento Proyectado a 4 Horas:</span>
                        <h6 class="fw-bold text-dark mb-0">
                            @if($trendDirection === 'up')
                                <span class="text-warning"><i class="bi bi-arrow-up-right me-1"></i>En Aumento Progresivo (+{{ $trendPercentage }}%)</span>
                            @elseif($trendDirection === 'down')
                                <span class="text-success"><i class="bi bi-arrow-down-right me-1"></i>Descendiendo / Normalizándose (-{{ $trendPercentage }}%)</span>
                            @else
                                <span class="text-primary"><i class="bi bi-check-circle me-1"></i>Estable y Seguro</span>
                            @endif
                        </h6>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 bg-light p-2 px-3 rounded-pill border">
                    <i class="bi bi-shield-check text-primary fs-5"></i>
                    <span class="small fw-semibold text-secondary">
                        Precisión del Modelo: <strong class="text-dark">{{ number_format($confidenceScore, 1) }}%</strong>
                    </span>
                </div>
            </div>
        </div>

        <!-- 4. LÍNEA DE TIEMPO FUTURA (ESTÁNDAR LIMPIO AIRSENSE CEFA) -->
        <h6 class="fw-bold text-dark mb-3 ps-1">
            <i class="bi bi-clock-history text-primary me-2"></i> Horario de Proyección Futura
        </h6>

        <div class="row g-3 mb-4">
            @php
                $borderClasses = [
                    0 => 'border-primary',
                    1 => 'border-info',
                    2 => 'border-warning',
                    3 => 'border-danger',
                ];
                $bgSubtleClasses = [
                    0 => 'bg-primary-subtle text-primary',
                    1 => 'bg-info-subtle text-info',
                    2 => 'bg-warning-subtle text-warning',
                    3 => 'bg-danger-subtle text-danger',
                ];
                $textColors = [
                    0 => 'text-primary',
                    1 => 'text-info',
                    2 => 'text-warning',
                    3 => 'text-danger',
                ];
            @endphp

            @foreach($timeline as $index => $step)
                @php
                    $bClass = $borderClasses[$index] ?? 'border-primary';
                    $subtleClass = $bgSubtleClasses[$index] ?? 'bg-primary-subtle text-primary';
                    $textColor = $textColors[$index] ?? 'text-primary';
                @endphp
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 bg-white border-start border-4 {{ $bClass }}">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1 small fw-semibold">
                                    {{ $step['title'] }}
                                </span>
                                <span class="fw-bold font-monospace text-muted" style="font-size: 0.85rem;">
                                    <i class="bi bi-clock me-1"></i>{{ $step['time'] }}
                                </span>
                            </div>

                            <div class="my-2 d-flex align-items-center justify-content-between">
                                <div>
                                    <h3 class="fw-bold mb-0 text-dark" style="letter-spacing: -0.5px;">
                                        {{ number_format($step['value'], 1) }}
                                    </h3>
                                    <small class="text-muted" style="font-size: 11px;">
                                        {{ $variableType === 'co2' ? 'PPM CO2' : ($variableType === 'temperature' ? '°C Temp' : '% Humedad') }}
                                    </small>
                                </div>
                                <div class="rounded-3 p-3 d-flex align-items-center justify-content-center {{ $subtleClass }}" style="width: 44px; height: 44px;">
                                    <i class="bi {{ $index === 0 ? 'bi-geo-alt' : 'bi-hourglass-split' }} fs-4"></i>
                                </div>
                            </div>

                            <div class="pt-2 border-top d-flex justify-content-between align-items-center" style="font-size: 11px;">
                                <span class="text-secondary">{{ $step['badge'] }}</span>
                                @if($step['status'] === 'danger')
                                    <span class="badge bg-danger text-white rounded-pill px-2 py-1">Peligro Crítico</span>
                                @elseif($step['status'] === 'warning')
                                    <span class="badge bg-warning text-dark rounded-pill px-2 py-1">Advertencia</span>
                                @else
                                    <span class="badge bg-success text-white rounded-pill px-2 py-1">Nivel Seguro</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- 5. CONCLUSIÓN DE DEEPSEEK AI EN LENGUAJE SENCILLO -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white border-start border-4 border-primary">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="rounded-3 bg-primary-subtle text-primary p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-stars fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0">Diagnóstico Interpretativo — DeepSeek AI</h6>
                        <small class="text-muted" style="font-size: 11px;">Análisis predictivo en lenguaje claro</small>
                    </div>
                </div>
                <div class="p-3 bg-light rounded-3 border mt-2">
                    <p class="text-dark small mb-0" style="line-height: 1.6; text-align: justify; font-size: 0.95rem;">
                        {{ $aiDiagnosis }}
                    </p>
                </div>
            </div>
        </div>

        <!-- 6. GRÁFICA DE TENDENCIA CON LÍNEA LÍMITE DE SEGURIDAD -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
            <div class="card-header bg-transparent border-0 pt-3 px-4 pb-0 d-flex align-items-center justify-content-between">
                <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-activity text-primary"></i> Curva de Calidad del Aire (Histórico Real vs Proyección IA)
                </h6>
                <div class="d-flex align-items-center gap-3" style="font-size: 11px;">
                    <span><span class="badge bg-primary me-1">——</span> Histórico Real</span>
                    <span><span class="badge bg-danger me-1">-- --</span> Proyección IA</span>
                    <span><span class="badge bg-secondary me-1">...</span> Límite Seguro ({{ $thresholdLimit }})</span>
                </div>
            </div>
            <div class="card-body p-4">
                <div style="height: 320px; width: 100%;">
                    <canvas id="chartPredictivoIntuitivo"></canvas>
                </div>
            </div>
        </div>

        <!-- 7. RECOMENDACIONES PREVENTIVAS EHS -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-4">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-shield-check text-success fs-4"></i>
                <h6 class="fw-bold text-dark mb-0">Recomendaciones Preventivas EHS Sugeridas</h6>
            </div>
            <div class="row g-3">
                @foreach($recommendedActions as $action)
                    <div class="col-12 col-md-6">
                        <div class="p-3 rounded-3 bg-light border d-flex align-items-start gap-3">
                            <div class="rounded-3 bg-success-subtle text-success p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="bi bi-check-lg fs-6"></i>
                            </div>
                            <div>
                                <span class="fw-bold text-dark d-block mb-1 small">Medida Sugerida</span>
                                <span class="text-secondary small">{{ $action }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    @endif

</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@if(!$isLearningPhase)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const historical = @json($chartHistorical);
        const forecast = @json($chartForecast);
        const limitValue = {{ $thresholdLimit }};

        const labels = [
            ...historical.map(i => i.label),
            ...forecast.map(i => i.label)
        ];

        const dataHistorical = [
            ...historical.map(i => i.value),
            ...forecast.map(() => null)
        ];

        const dataForecast = [
            ...historical.map((i, index) => (index === historical.length - 1) ? i.value : null),
            ...forecast.map(i => i.value)
        ];

        const dataLimit = labels.map(() => limitValue);

        const ctx = document.getElementById('chartPredictivoIntuitivo').getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Límite Seguro (Permisible)',
                        data: dataLimit,
                        borderColor: 'rgba(220, 53, 69, 0.4)',
                        borderWidth: 1.5,
                        borderDash: [4, 4],
                        pointRadius: 0,
                        fill: false
                    },
                    {
                        label: 'Histórico Real',
                        data: dataHistorical,
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.04)',
                        borderWidth: 2,
                        pointRadius: 2,
                        fill: true,
                        tension: 0.2
                    },
                    {
                        label: 'Proyección DeepSeek IA',
                        data: dataForecast,
                        borderColor: '#dc3545',
                        borderDash: [6, 6],
                        backgroundColor: 'rgba(220, 53, 69, 0.08)',
                        borderWidth: 3,
                        pointRadius: 5,
                        pointBackgroundColor: '#dc3545',
                        fill: true,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 } }
                    },
                    y: {
                        beginAtZero: false,
                        grid: { color: 'rgba(0, 0, 0, 0.05)' }
                    }
                }
            }
        });
    });
</script>
@endif

@endsection
