@extends('layouts.sidebarehscefa')

@section('tituloPagina', 'Análisis Predictivo con Inteligencia Artificial')

@section('content')
<div class="container-fluid px-0">

    <!-- 1. Encabezado e Insignia DeepSeek AI -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 pb-2 border-bottom">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h4 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-cpu-fill text-primary"></i> Análisis Predictivo con IA
                </h4>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-bold" style="font-size: 0.75rem;">
                    <i class="bi bi-stars me-1"></i> {{ $aiEngine }}
                </span>
            </div>
            <p class="text-muted small mb-0">Modelación de series de tiempo y proyección preventiva de CO₂ y Temperatura a las +1h, +2h y +4h.</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <a href="{{ route('ehscefa.dashboard') }}" class="btn btn-outline-secondary btn-sm px-3 rounded-pill">
                <i class="bi bi-arrow-left me-1"></i> Dashboard EHS
            </a>
            <button type="button" onclick="window.location.reload();" class="btn btn-primary btn-sm px-3 rounded-pill shadow-sm">
                <i class="bi bi-arrow-repeat me-1"></i> Recalcular IA
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
                        <option value="">Todos los Ambientes (Vista General)</option>
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
                        <option value="co2" {{ $variableType === 'co2' ? 'selected' : '' }}>CO2 (Gas Monóxido / PPM)</option>
                        <option value="temperature" {{ $variableType === 'temperature' ? 'selected' : '' }}>Temperatura (°C)</option>
                        <option value="humidity" {{ $variableType === 'humidity' ? 'selected' : '' }}>Humedad Relativa (%)</option>
                    </select>
                </div>

                <div class="col-12 col-md-3 text-md-end mt-3 mt-md-4">
                    <span class="badge bg-light text-secondary border rounded-pill px-3 py-2 small">
                        <i class="bi bi-database-check text-success me-1"></i> Muestras: <strong>{{ number_format($totalReadings) }}</strong>
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
                    El sistema requiere un mínimo de <strong>100 lecturas históricas</strong> registradas por los nodos IoT para alimentar los modelos predictivos de DeepSeek AI sin generar falsos positivos.
                </p>
                <div class="d-inline-block p-3 bg-light rounded-3 border text-start mb-3" style="max-width: 500px;">
                    <div class="d-flex justify-content-between text-muted small fw-semibold mb-1">
                        <span>Progreso de Recolección de Datos</span>
                        <span class="text-primary">{{ $totalReadings }} / 100 lecturas</span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ min(100, ($totalReadings / 100) * 100) }}%"></div>
                    </div>
                </div>
                <div>
                    <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3 py-2">
                        <i class="bi bi-info-circle me-1"></i> Las predicciones automáticas se activarán una vez completado el umbral.
                    </span>
                </div>
            </div>
        </div>
    @else
        <!-- TARJETAS KPI DE PROYECCIÓN FUTURA DE IA -->
        <div class="row g-3 mb-4">
            <!-- Proyección +1 Hora -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold d-block mb-1">Proyección a +1 Hora</span>
                            <h3 class="fw-bold text-dark mb-0">{{ number_format($prediction1h, 1) }}</h3>
                            <small class="text-secondary" style="font-size: 11px;">
                                {{ $variableType === 'co2' ? 'PPM' : ($variableType === 'temperature' ? '°C' : '%') }}
                            </small>
                        </div>
                        <div class="rounded-3 bg-primary-subtle text-primary p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-clock-history fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Proyección +2 Horas -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold d-block mb-1">Proyección a +2 Horas</span>
                            <h3 class="fw-bold text-dark mb-0">{{ number_format($prediction2h, 1) }}</h3>
                            <small class="text-secondary" style="font-size: 11px;">
                                {{ $variableType === 'co2' ? 'PPM' : ($variableType === 'temperature' ? '°C' : '%') }}
                            </small>
                        </div>
                        <div class="rounded-3 bg-info-subtle text-info p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-graph-up-arrow fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Proyección +4 Horas -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white border-start border-4 {{ $riskStatus === 'danger' ? 'border-danger' : ($riskStatus === 'warning' ? 'border-warning' : 'border-success') }}">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold d-block mb-1">Proyección a +4 Horas</span>
                            <h3 class="fw-bold mb-0 {{ $riskStatus === 'danger' ? 'text-danger' : ($riskStatus === 'warning' ? 'text-warning' : 'text-success') }}">
                                {{ number_format($prediction4h, 1) }}
                            </h3>
                            <small class="fw-medium text-capitalize" style="font-size: 11px;">
                                Riesgo: {{ $riskStatus }}
                            </small>
                        </div>
                        <div class="rounded-3 p-3 d-flex align-items-center justify-content-center {{ $riskStatus === 'danger' ? 'bg-danger-subtle text-danger' : ($riskStatus === 'warning' ? 'bg-warning-subtle text-warning' : 'bg-success-subtle text-success') }}" style="width: 48px; height: 48px;">
                            <i class="bi bi-exclamation-triangle fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nivel de Confianza de la IA -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold d-block mb-1">Precisión del Modelo IA</span>
                            <h3 class="fw-bold text-primary mb-0">{{ number_format($confidenceScore, 1) }}%</h3>
                            <small class="text-secondary" style="font-size: 11px;">Coeficiente de Confianza R²</small>
                        </div>
                        <div class="rounded-3 bg-purple-subtle text-primary p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: #f0f0ff;">
                            <i class="bi bi-stars fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- GRÁFICA DE PRORECCIÓN TEMPORAL (CHART.JS) -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
            <div class="card-header bg-transparent border-0 pt-3 px-4 pb-0 d-flex align-items-center justify-content-between">
                <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-activity text-primary"></i> Tendencia Histórica vs Proyección DeepSeek AI
                </h6>
                <div class="d-flex align-items-center gap-3" style="font-size: 11px;">
                    <span><span class="badge bg-primary me-1">——</span> Histórico Real</span>
                    <span><span class="badge bg-danger me-1">-- --</span> Proyección IA</span>
                </div>
            </div>
            <div class="card-body p-4">
                <div style="height: 320px; width: 100%;">
                    <canvas id="chartPredictivo"></canvas>
                </div>
            </div>
        </div>

        <!-- DIAGNÓSTICO Y RECOMENDACIONES DE DEEPSEEK AI -->
        <div class="row g-4">
            <!-- Diagnóstico Cualitativo -->
            <div class="col-12 col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white p-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-chat-quote-fill text-primary fs-4"></i>
                        <h6 class="fw-bold text-dark mb-0">Diagnóstico Interpretativo — DeepSeek AI</h6>
                    </div>
                    <div class="p-3 rounded-3 bg-light border">
                        <p class="text-dark small mb-0" style="line-height: 1.6; text-align: justify;">
                            {{ $aiDiagnosis }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Recomendaciones Preventivas EHS -->
            <div class="col-12 col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white p-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-shield-check text-success fs-4"></i>
                        <h6 class="fw-bold text-dark mb-0">Acciones Preventivas EHS Sugeridas</h6>
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach($recommendedActions as $action)
                            <li class="list-group-item bg-transparent px-0 py-2 border-0 d-flex align-items-start gap-2 small text-secondary">
                                <i class="bi bi-check2-circle text-success fs-6 mt-1"></i>
                                <span>{{ $action }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
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

        const labels = [
            ...historical.map(i => i.label),
            ...forecast.map(i => i.label)
        ];

        // Datos reales con valores nulos en el tramo de predicción
        const dataHistorical = [
            ...historical.map(i => i.value),
            ...forecast.map(() => null)
        ];

        // Datos de predicción conectando el último punto real
        const lastRealValue = historical.length > 0 ? historical[historical.length - 1].value : null;
        const dataForecast = [
            ...historical.map((i, index) => (index === historical.length - 1) ? i.value : null),
            ...forecast.map(i => i.value)
        ];

        const ctx = document.getElementById('chartPredictivo').getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
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
                        backgroundColor: 'rgba(220, 53, 69, 0.05)',
                        borderWidth: 2.5,
                        pointRadius: 4,
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
