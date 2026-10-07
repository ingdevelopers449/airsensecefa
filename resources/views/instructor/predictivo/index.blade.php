@extends('layouts.sidebarinstructorcefa')

@section('tituloPagina', 'Tendencia de Aire & Analítica Predictiva IA')

@section('content')
<div class="container-fluid px-0">

    <!-- 1. Encabezado e Insignia IA -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 pb-2 border-bottom">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h4 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-cpu-fill text-success"></i> Pronóstico de Calidad del Aire con IA
                </h4>
                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 fw-bold" style="font-size: 0.75rem;">
                    <i class="bi bi-stars me-1"></i> {{ $aiEngine }}
                </span>
            </div>
            <p class="text-muted small mb-0">Anticipación inteligente a la acumulación de gases y fatiga cognitiva en ambientes de formación del SENA CEFA.</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <a href="{{ route('instructor.dashboard') }}" class="btn btn-outline-secondary btn-sm px-3 rounded-pill shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Mi Ambiente
            </a>
            <button type="button" onclick="window.location.reload();" class="btn btn-success btn-sm px-3 rounded-pill shadow-sm" style="background-color: #39A900; border-color: #39A900;">
                <i class="bi bi-arrow-repeat me-1"></i> Recalcular Proyección
            </button>
        </div>
    </div>

    <!-- 2. Información del Ambiente Asignado y Filtro de Variable -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('instructor.predictivo.index') }}" class="row g-3 align-items-center">
                
                {{-- Ambiente Asignado por el Administrador (Fijo / Sin selector) --}}
                <div class="col-12 col-sm-6 col-md-6">
                    <label class="form-label small fw-semibold text-muted mb-1 d-flex align-items-center gap-1.5">
                        <i class="bi bi-geo-alt-fill text-success"></i> Ambiente de Formación Asignado
                        @if($hasAssignment ?? false)
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0.5 fw-semibold" style="font-size: 0.65rem;">
                                <i class="bi bi-shield-check me-1"></i> Asignado por Administrador
                            </span>
                        @else
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-0.5 fw-semibold" style="font-size: 0.65rem;">
                                <i class="bi bi-info-circle me-1"></i> Aula Predeterminada CEFA
                            </span>
                        @endif
                    </label>
                    <div class="d-flex align-items-center gap-2 p-2 px-3 rounded-3 bg-light border border-secondary-subtle">
                        <div class="rounded-2 p-1.5 bg-white text-success border shadow-2xs d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
                            <i class="bi bi-door-open-fill fs-6"></i>
                        </div>
                        <div class="overflow-hidden">
                            <div class="fw-bold text-dark text-truncate small">
                                {{ $selectedEnvironment->name ?? 'Sin Ambiente Asignado' }}
                            </div>
                            <div class="text-muted text-truncate" style="font-size: 0.72rem;">
                                Código: <strong class="text-secondary">{{ $selectedEnvironment->code ?? 'N/A' }}</strong> &bull; Espacio exclusivo de tu jornada formativa
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Variable a Proyectar --}}
                <div class="col-12 col-sm-6 col-md-4">
                    <label class="form-label small fw-semibold text-muted mb-1">Variable a Proyectar con IA</label>
                    <select name="variable_type" class="form-select form-select-sm rounded-3 shadow-none border-secondary-subtle" onchange="this.form.submit()">
                        <option value="co2" {{ $variableType === 'co2' ? 'selected' : '' }}>CO2 (Dióxido de Carbono - PPM)</option>
                        <option value="temperature" {{ $variableType === 'temperature' ? 'selected' : '' }}>Temperatura Ambiental (°C)</option>
                        <option value="humidity" {{ $variableType === 'humidity' ? 'selected' : '' }}>Humedad Relativa (%)</option>
                    </select>
                </div>

                {{-- Contador de Muestras del Ambiente --}}
                <div class="col-12 col-md-2 text-md-end mt-3 mt-md-4">
                    <span class="badge bg-light text-secondary border rounded-pill px-3 py-2 small w-100 text-center">
                        <i class="bi bi-database-check text-success me-1"></i> <strong>{{ number_format($totalReadings) }}</strong> lecturas
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
                <p class="text-muted small mb-3 mx-auto" style="max-width: 620px;">
                    Para garantizar proyecciones fiables y prevenir falsas alarmas en el aula de clase, el modelo requiere acumular al menos <strong>100 lecturas históricas continuas</strong> antes de publicar la curva de predicción oficial.
                </p>
                <div class="d-inline-block p-3 bg-light rounded-3 border text-start mb-3 w-100" style="max-width: 500px;">
                    <div class="d-flex justify-content-between text-muted small fw-semibold mb-1">
                        <span>Progreso de Muestreo</span>
                        <span class="text-primary fw-bold">{{ $totalReadings }} / 100 lecturas</span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ min(100, ($totalReadings / 100) * 100) }}%; background-color: #39A900 !important;"></div>
                    </div>
                </div>

                <div class="alert alert-light border rounded-3 p-3 text-start mx-auto mt-2" style="max-width: 600px;">
                    <div class="d-flex align-items-center gap-2 text-secondary fw-semibold small mb-2">
                        <i class="bi bi-info-circle-fill text-info"></i> Acciones preventivas durante la fase de aprendizaje:
                    </div>
                    <ul class="text-muted small mb-0 ps-3">
                        @foreach($recommendedActions as $action)
                            <li>{{ $action }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        @if(count($chartHistorical) > 0)
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-graph-up text-secondary"></i> Primeras Lecturas Recopiladas (Línea Base)
                    </h6>
                    <div style="height: 260px; position: relative;">
                        <canvas id="chartPredictivoInstructor"></canvas>
                    </div>
                </div>
            </div>
        @endif

    @else

        <!-- 3. BARRA DE TENDENCIA INSTANTÁNEA & KPIS -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
            <div class="card-body p-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 d-flex align-items-center justify-content-center {{ $trendDirection === 'up' ? 'bg-warning-subtle text-warning' : ($trendDirection === 'down' ? 'bg-success-subtle text-success' : 'bg-primary-subtle text-primary') }}" style="width: 48px; height: 48px;">
                        @if($trendDirection === 'up')
                            <i class="bi bi-graph-up-arrow fs-4 text-warning"></i>
                        @elseif($trendDirection === 'down')
                            <i class="bi bi-graph-down-arrow fs-4 text-success"></i>
                        @else
                            <i class="bi bi-arrow-right fs-4 text-primary"></i>
                        @endif
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="fw-bold mb-0 text-dark">
                                Comportamiento Proyectado: 
                                <span class="{{ $trendDirection === 'up' ? 'text-warning' : ($trendDirection === 'down' ? 'text-success' : 'text-primary') }}">
                                    {{ $trendDirection === 'up' ? 'Tendencia Ascendente (+'.$trendPercentage.'%)' : ($trendDirection === 'down' ? 'Tendencia Favorable (-'.$trendPercentage.'%)' : 'Tendencia Estable') }}
                                </span>
                            </h5>
                        </div>
                        <p class="text-muted small mb-0">Lectura actual: <strong>{{ number_format($currentValue, 1) }} {{ $unitLabel }}</strong> | Umbral seguro permisible: <strong>{{ $thresholdLimit }} {{ $unitLabel }}</strong></p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="badge {{ $riskStatus === 'danger' ? 'bg-danger text-white' : ($riskStatus === 'warning' ? 'bg-warning text-dark' : 'bg-success text-white') }} px-3 py-2 rounded-pill font-semibold">
                        @if($riskStatus === 'danger')
                            <i class="bi bi-exclamation-octagon-fill me-1"></i> Riesgo Futuro Previsto
                        @elseif($riskStatus === 'warning')
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Precaución Preventiva
                        @else
                            <i class="bi bi-check-circle-fill me-1"></i> Condiciones Óptimas
                        @endif
                    </span>
                    <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill small">
                        <i class="bi bi-shield-check text-success me-1"></i> Confianza IA: <strong>{{ $confidenceScore }}%</strong>
                    </span>
                </div>
            </div>
        </div>

        <!-- 4. LÍNEA DE TIEMPO FUTURA (TIMELINE - 4 COLUMNAS) -->
        <div class="row g-3 mb-4">
            @foreach($timeline as $item)
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm rounded-4 bg-white position-relative overflow-hidden border-top border-4 {{ $item['status'] === 'danger' ? 'border-danger' : ($item['status'] === 'warning' ? 'border-warning' : 'border-success') }}">
                        @if($item['is_current'])
                            <div class="position-absolute top-0 end-0 bg-primary text-white px-2 py-0.5 rounded-bottom-start small fw-bold" style="font-size: 0.65rem;">
                                EN VIVO
                            </div>
                        @endif
                        <div class="card-body p-3 text-center">
                            <span class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.5px;">{{ $item['title'] }}</span>
                            <div class="display-6 fw-bold text-dark my-1">{{ number_format($item['value'], 1) }}</div>
                            <div class="text-muted small mb-2">{{ $unitLabel }} &bull; {{ $item['time'] }}</div>
                            
                            <span class="badge {{ $item['status'] === 'danger' ? 'bg-danger-subtle text-danger border border-danger-subtle' : ($item['status'] === 'warning' ? 'bg-warning-subtle text-warning border border-warning-subtle' : 'bg-success-subtle text-success border border-success-subtle') }} rounded-pill px-2.5 py-1 small fw-semibold">
                                {{ $item['badge'] }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- 5. GRÁFICA COMPARATIVA: HISTÓRICO REAL VS PROYECCIÓN IA -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
                <div>
                    <h5 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                        <i class="bi bi-graph-up text-success"></i> Curva Proyectada y Serie Temporal
                    </h5>
                    <p class="text-muted small mb-0">Evolución de lecturas históricas conectadas con la proyección futura calculada por el motor de IA.</p>
                </div>
                <div class="d-flex align-items-center gap-3 small text-muted flex-wrap">
                    <span class="d-flex align-items-center gap-1">
                        <span class="d-inline-block rounded-circle" style="width: 10px; height: 10px; background-color: #0d6efd;"></span> Histórico Real
                    </span>
                    <span class="d-flex align-items-center gap-1">
                        <span class="d-inline-block rounded-circle" style="width: 10px; height: 10px; background-color: #39A900;"></span> Proyección IA
                    </span>
                    <span class="d-flex align-items-center gap-1">
                        <span class="d-inline-block rounded-circle" style="width: 10px; height: 10px; background-color: #dc3545;"></span> Límite Seguro Permisible ({{ $thresholdLimit }} {{ $unitLabel }})
                    </span>
                </div>
            </div>
            <div class="card-body p-4">
                <div style="height: 320px; position: relative;">
                    <canvas id="chartPredictivoInstructor"></canvas>
                </div>
            </div>
        </div>

        <!-- 6. PANEL PEDAGÓGICO: DIAGNÓSTICO DEL AULA Y RECOMENDACIONES PREVENTIVAS -->
        <div class="row g-4 mb-4">
            
            <!-- Diagnóstico IA en Lenguaje Claro -->
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="rounded-circle p-2 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                <i class="bi bi-chat-quote-fill"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-0">Diagnóstico Predictivo del Aula</h6>
                                <small class="text-muted">Interpretación algorítmica para el ambiente de formación</small>
                            </div>
                        </div>

                        <div class="p-3 rounded-3 bg-light border border-secondary-subtle mb-3">
                            <p class="text-secondary mb-0 small lh-base">
                                {{ $aiDiagnosis }}
                            </p>
                        </div>

                        <div class="d-flex align-items-center justify-content-between text-muted small pt-2 border-top">
                            <span><i class="bi bi-clock me-1"></i> Proyección actualizada cada 5 minutos</span>
                            <span class="badge bg-secondary-subtle text-secondary rounded-pill">SENA CEFA SST</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones Sugeridas para el Instructor -->
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="rounded-circle p-2 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                <i class="bi bi-list-check"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-0">Medidas Preventivas para el Instructor</h6>
                                <small class="text-muted">Acciones recomendadas para optimizar el confort y atención</small>
                            </div>
                        </div>

                        <div class="space-y-2">
                            @forelse($recommendedActions as $index => $action)
                                <div class="d-flex align-items-start gap-2.5 p-2.5 rounded-3 bg-light border border-light-subtle mb-2">
                                    <div class="rounded-circle bg-white text-success shadow-sm d-flex align-items-center justify-content-center fw-bold flex-shrink-0 mt-0.5" style="width: 22px; height: 22px; font-size: 11px;">
                                        {{ $index + 1 }}
                                    </div>
                                    <span class="text-dark small">{{ $action }}</span>
                                </div>
                            @empty
                                <div class="text-muted small p-3 bg-light rounded-3 text-center">
                                    No se requieren acciones correctivas en este momento. Las condiciones ambientales son adecuadas.
                                </div>
                            @endforelse
                        </div>

                        <div class="mt-3 pt-2 border-top d-flex align-items-center justify-content-between">
                            <span class="text-muted small"><i class="bi bi-shield-check text-success me-1"></i> Protocolos Institucionales CEFA</span>
                            <a href="{{ route('instructor.dashboard') }}" class="btn btn-link btn-sm text-decoration-none p-0 fw-semibold text-success">
                                Ver semáforo en vivo &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    @endif

</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const historical = @json($chartHistorical ?? []);
        const forecast = @json($chartForecast ?? []);
        const limitValue = {{ $thresholdLimit }};
        const isLearning = {{ $isLearningPhase ? 'true' : 'false' }};

        const ctx = document.getElementById('chartPredictivoInstructor');
        if (!ctx) return;

        if (isLearning) {
            // Gráfica de fase de aprendizaje
            const labels = historical.map(i => i.label);
            const dataHistorical = historical.map(i => i.value);

            new Chart(ctx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Lecturas Iniciales',
                            data: dataHistorical,
                            borderColor: '#39A900',
                            backgroundColor: 'rgba(57, 169, 0, 0.08)',
                            borderWidth: 2,
                            pointRadius: 2,
                            fill: true,
                            tension: 0.2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                        y: { beginAtZero: false, grid: { color: 'rgba(0,0,0,0.05)' } }
                    }
                }
            });
            return;
        }

        // Gráfica completa: Histórico + Proyección IA + Límite Permisible
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

        new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Límite Seguro (Permisible)',
                        data: dataLimit,
                        borderColor: 'rgba(220, 53, 69, 0.45)',
                        borderWidth: 1.5,
                        borderDash: [5, 5],
                        pointRadius: 0,
                        fill: false
                    },
                    {
                        label: 'Histórico Real',
                        data: dataHistorical,
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.05)',
                        borderWidth: 2,
                        pointRadius: 2,
                        fill: true,
                        tension: 0.2
                    },
                    {
                        label: 'Proyección IA (Tendencia)',
                        data: dataForecast,
                        borderColor: '#39A900',
                        borderDash: [6, 6],
                        backgroundColor: 'rgba(57, 169, 0, 0.10)',
                        borderWidth: 3,
                        pointRadius: 5,
                        pointBackgroundColor: '#39A900',
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
@endsection