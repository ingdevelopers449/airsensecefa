<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Oficial EHS - AirSense CEFA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #1e293b; background: #fff; }
        .watermark { position: absolute; top: 35%; left: 15%; opacity: 0.04; font-size: 5rem; font-weight: 900; transform: rotate(-30deg); pointer-events: none; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; margin: 0; }
        }
    </style>
</head>
<body class="p-4 position-relative">

    <!-- Botón Flotante para Imprimir -->
    <div class="no-print position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <button onclick="window.print();" class="btn btn-danger btn-lg rounded-pill shadow-lg px-4 fw-bold">
            <i class="bi bi-printer-fill me-2"></i> Imprimir / Guardar en PDF
        </button>
    </div>

    <!-- Sello de Agua -->
    <div class="watermark text-uppercase">AIRSENSE CEFA OFFICIAL REPORT</div>

    <!-- Encabezado Institucional SENA -->
    <div class="row align-items-center pb-3 mb-4 border-bottom border-2 border-dark">
        <div class="col-8">
            <div class="d-flex align-items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 50px;">
                <div>
                    <h5 class="fw-bold text-dark mb-0 text-uppercase">SENA — Centro de Formación Agroindustrial CEFA</h5>
                    <span class="text-secondary small fw-semibold">Sistema de Monitoreo Inteligente de Calidad del Aire (AirSense)</span>
                </div>
            </div>
        </div>
        <div class="col-4 text-end">
            <span class="badge bg-dark text-white px-3 py-2 text-uppercase fw-bold" style="font-size: 0.75rem;">
                Informe de Auditoría EHS
            </span>
            <div class="text-muted small mt-1" style="font-size: 11px;">
                Emisión: {{ date('d/m/Y H:i:s') }}
            </div>
        </div>
    </div>

    <!-- Resumen del Informe -->
    <div class="row g-3 mb-4">
        <div class="col-4">
            <div class="p-3 bg-light rounded-3 border">
                <span class="text-muted small d-block mb-1">Variable Evaluada</span>
                <strong class="text-dark fs-6 text-uppercase">{{ $variableType }}</strong>
            </div>
        </div>
        <div class="col-4">
            <div class="p-3 bg-light rounded-3 border">
                <span class="text-muted small d-block mb-1">Ambiente / Nodo</span>
                <strong class="text-dark fs-6">{{ $environmentObj ? $environmentObj->name : 'Todos los Ambientes' }}</strong>
            </div>
        </div>
        <div class="col-4">
            <div class="p-3 bg-light rounded-3 border">
                <span class="text-muted small d-block mb-1">Periodo Consultado</span>
                <strong class="text-dark fs-6">{{ $startDate }} a {{ $endDate }}</strong>
            </div>
        </div>
    </div>

    <!-- Tabla de Estadísticas -->
    <table class="table table-bordered align-middle mb-4" style="font-size: 0.85rem;">
        <thead class="table-dark">
            <tr>
                <th>Total Muestras Analizadas</th>
                <th>Promedio Calculado</th>
                <th>Pico Máximo (Peligro)</th>
                <th>Registro Mínimo</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="fw-bold">{{ number_format($totalReadings) }}</td>
                <td class="fw-bold text-primary">{{ number_format($avgValue, 2) }}</td>
                <td class="fw-bold text-danger">{{ number_format($maxValue, 2) }}</td>
                <td class="fw-bold text-success">{{ number_format($minValue, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Muestreo de Lecturas -->
    <h6 class="fw-bold text-dark mb-2">Detalle de Medición de Telemetría (Primeras 500 lecturas)</h6>
    <table class="table table-striped table-sm border align-middle mb-4" style="font-size: 0.8rem;">
        <thead>
            <tr>
                <th>#</th>
                <th>Fecha y Hora</th>
                <th>Ambiente</th>
                <th>Nodo IoT</th>
                <th>Valor Medido</th>
                <th>Estado EHS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($measurements as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->created_at ? $item->created_at->format('d/m/Y H:i:s') : 'N/A' }}</td>
                    <td>{{ $item->reading && $item->reading->environment ? $item->reading->environment->name : 'CEFA' }}</td>
                    <td>{{ $item->reading && $item->reading->node ? $item->reading->node->name : 'ESP32' }}</td>
                    <td class="fw-bold">{{ number_format($item->value, 2) }} {{ $item->unit }}</td>
                    <td>
                        @if($item->variable_type === 'co2')
                            {{ $item->value > 1000 ? 'CRÍTICO' : ($item->value > 700 ? 'ADVERTENCIA' : 'NORMAL') }}
                        @else
                            {{ $item->value > 30 ? 'PELIGRO' : 'ÓPTIMO' }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pie de Página de Seguridad SHA-256 -->
    <div class="mt-5 pt-3 border-top border-2 border-dark">
        <div class="row align-items-center">
            <div class="col-8">
                <p class="fw-bold text-dark mb-0 small">Sello Digital de Integridad y Firma de Auditoría (SHA-256):</p>
                <div class="font-monospace text-muted small text-break fw-semibold" style="font-size: 10px;">
                    {{ $hashSignature }}
                </div>
                <p class="text-secondary mt-1 mb-0" style="font-size: 10px;">
                    * Documento oficial inalterable generado por AirSense CEFA. Prohibida su modificación sin autorización.
                </p>
            </div>
            <div class="col-4 text-end">
                <div class="border-top border-dark pt-1 d-inline-block px-4">
                    <span class="d-block fw-bold small text-dark">Firma Especialista EHS / SST</span>
                    <span class="d-block text-muted" style="font-size: 10px;">CEFA SENA</span>
                </div>
            </div>
        </div>
    </div>

</body>
</html>