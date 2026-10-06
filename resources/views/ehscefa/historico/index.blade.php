@extends('layouts.sidebarehscefa')

@section('tituloPagina', 'Historial de Aire (1 Año)')

@section('content')
<div class="container-fluid px-0">

    <!-- 1. Encabezado y Acciones -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 pb-2 border-bottom">
        <div>
            <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                <i class="bi bi-clock-history text-success"></i> Historial de Aire & Registro Epidemiológico
            </h4>
            <p class="text-muted small mb-0">Análisis retrospectivo y trazabilidad de lecturas ambientales hasta de 1 año en los ambientes del CEFA.</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <a href="{{ route('ehscefa.dashboard') }}" class="btn btn-outline-secondary btn-sm px-3 rounded-pill">
                <i class="bi bi-arrow-left me-1"></i> Dashboard EHS
            </a>
            <button type="button" onclick="window.print();" class="btn btn-success btn-sm px-3 rounded-pill shadow-sm">
                <i class="bi bi-printer me-1"></i> Imprimir Reporte
            </button>
        </div>
    </div>

    <!-- 2. Barra de Filtros Avanzados -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('ehscefa.historico.index') }}" class="row g-3 align-items-end">
                
                <!-- Variable Ambiental -->
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Variable Ambiental</label>
                    <select name="variable_type" class="form-select form-select-sm rounded-3" onchange="this.form.submit()">
                        <option value="co2" {{ $variableType === 'co2' ? 'selected' : '' }}>CO2 (Gas Monóxido / PPM)</option>
                        <option value="temperature" {{ $variableType === 'temperature' ? 'selected' : '' }}>Temperatura (°C)</option>
                        <option value="humidity" {{ $variableType === 'humidity' ? 'selected' : '' }}>Humedad Relativa (%)</option>
                    </select>
                </div>

                <!-- Ambiente / Nodo -->
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Ambiente / Aula</label>
                    <select name="environment_id" class="form-select form-select-sm rounded-3" onchange="this.form.submit()">
                        <option value="">Todos los Ambientes</option>
                        @foreach($environments as $env)
                            <option value="{{ $env->id }}" {{ $environmentId == $env->id ? 'selected' : '' }}>
                                {{ $env->name }} ({{ $env->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Fecha Inicio -->
                <div class="col-6 col-md-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Fecha Desde</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="form-control form-control-sm rounded-3" onchange="this.form.submit()">
                </div>

                <!-- Fecha Fin -->
                <div class="col-6 col-md-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Fecha Hasta</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="form-control form-control-sm rounded-3" onchange="this.form.submit()">
                </div>

            </form>
        </div>
    </div>

    <!-- 3. Tarjetas KPI Epidemiológicas -->
    <div class="row g-3 mb-4">
        <!-- Promedio -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold d-block mb-1">Promedio en el Rango</span>
                        <h3 class="fw-bold text-dark mb-0">{{ number_format($avgValue, 1) }}</h3>
                        <small class="text-secondary" style="font-size: 11px;">
                            {{ $variableType === 'co2' ? 'PPM' : ($variableType === 'temperature' ? '°C' : '%') }}
                        </small>
                    </div>
                    <div class="rounded-3 bg-primary-subtle text-primary p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-calculator fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pico Máximo -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white border-start border-4 border-danger">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold d-block mb-1">Pico Máximo (Peligro)</span>
                        <h3 class="fw-bold text-danger mb-0">{{ number_format($maxValue, 1) }}</h3>
                        <small class="text-danger fw-medium" style="font-size: 11px;">Lectura máxima registrada</small>
                    </div>
                    <div class="rounded-3 bg-danger-subtle text-danger p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-graph-up-arrow fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mínimo -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white border-start border-4 border-success">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold d-block mb-1">Valor Mínimo</span>
                        <h3 class="fw-bold text-success mb-0">{{ number_format($minValue, 1) }}</h3>
                        <small class="text-success fw-medium" style="font-size: 11px;">Muestreo más bajo</small>
                    </div>
                    <div class="rounded-3 bg-success-subtle text-success p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-graph-down-arrow fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Lecturas -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold d-block mb-1">Total Muestras</span>
                        <h3 class="fw-bold text-dark mb-0">{{ number_format($totalReadings) }}</h3>
                        <small class="text-secondary" style="font-size: 11px;">Registros procesados</small>
                    </div>
                    <div class="rounded-3 bg-info-subtle text-info p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-database-check fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Gráfica de Serie Temporal (Chart.js) -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
        <div class="card-header bg-transparent border-0 pt-3 px-4 pb-0 d-flex align-items-center justify-content-between">
            <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-activity text-primary"></i> Comportamiento Histórico de {{ strtoupper($variableType) }}
            </h6>
            <span class="badge bg-light text-secondary border px-2 py-1 rounded-pill" style="font-size: 11px;">
                Muestras cronológicas (Max 100 puntos)
            </span>
        </div>
        <div class="card-body p-4">
            <div style="height: 320px; width: 100%;">
                <canvas id="chartHistorial"></canvas>
            </div>
        </div>
    </div>

    <!-- 5. Tabla Paginada de Registros Históricos -->
    <div class="card border-0 shadow-sm rounded-4 bg-white">
        <div class="card-header bg-transparent border-0 pt-3 px-4 pb-2">
            <h6 class="fw-bold text-dark mb-0">Detalle de Registros Medidos</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Fecha & Hora</th>
                        <th>Ambiente / Aula</th>
                        <th>Nodo IoT</th>
                        <th>Lectura Medida</th>
                        <th>Unidad</th>
                        <th>Semáforo EHS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($measurements as $item)
                        <tr>
                            <td class="ps-4 fw-semibold text-dark">
                                <i class="bi bi-calendar-event me-1 text-muted"></i>
                                {{ $item->created_at ? $item->created_at->format('d/m/Y H:i:s') : 'N/A' }}
                            </td>
                            <td>
                                <span class="fw-medium text-dark">
                                    {{ $item->reading && $item->reading->environment ? $item->reading->environment->name : 'CEFA General' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border font-monospace">
                                    {{ $item->reading && $item->reading->node ? $item->reading->node->name : 'ESP32' }}
                                </span>
                            </td>
                            <td class="fw-bold fs-6 text-dark">
                                {{ number_format($item->value, 2) }}
                            </td>
                            <td>
                                <span class="text-muted small">{{ $item->unit }}</span>
                            </td>
                            <td>
                                @if($item->variable_type === 'co2')
                                    @if($item->value > 1000)
                                        <span class="badge bg-danger text-white rounded-pill px-2 py-1">
                                            <i class="bi bi-exclamation-octagon-fill me-1"></i> Crítico (> 1000 PPM)
                                        </span>
                                    @elseif($item->value > 700)
                                        <span class="badge bg-warning text-dark rounded-pill px-2 py-1">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Advertencia (700-1000)
                                        </span>
                                    @else
                                        <span class="badge bg-success text-white rounded-pill px-2 py-1">
                                            <i class="bi bi-check-circle-fill me-1"></i> Normal (< 700 PPM)
                                        </span>
                                    @endif
                                @elseif($item->variable_type === 'temperature')
                                    @if($item->value > 30)
                                        <span class="badge bg-danger text-white rounded-pill px-2 py-1">Peligro (> 30°C)</span>
                                    @else
                                        <span class="badge bg-success text-white rounded-pill px-2 py-1">Óptimo</span>
                                    @endif
                                @else
                                    <span class="badge bg-info text-white rounded-pill px-2 py-1">Registrado</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-search display-6 d-block mb-2"></i>
                                No se encontraron registros para los filtros seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($measurements->hasPages())
            <div class="card-footer bg-transparent border-0 p-3 d-flex justify-content-end">
                {{ $measurements->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const rawData = @json($chartData);
        
        const labels = rawData.map(item => item.label);
        const values = rawData.map(item => item.value);

        const ctx = document.getElementById('chartHistorial').getContext('2d');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Lectura {{ strtoupper($variableType) }}',
                    data: values,
                    borderColor: '{{ $variableType === "co2" ? "#dc3545" : ($variableType === "temperature" ? "#fd7e14" : "#0d6efd") }}',
                    backgroundColor: 'rgba(13, 110, 253, 0.05)',
                    borderWidth: 2,
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.3
                }]
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
