@extends('layouts.sidebaradmincefa')

@section('tituloPagina', 'Reportes Protegidos & Exportación Oficial')

@section('content')
<div class="container-fluid px-0">

    <!-- Encabezado -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 pb-2 border-bottom">
        <div>
            <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                <i class="bi bi-file-earmark-lock text-success"></i> Generador de Reportes Protegidos EHS
            </h4>
            <p class="text-muted small mb-0">Emisión de informes ambientales oficiales con firma digital de integridad SHA-256 para auditorías institucionales.</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm px-3 rounded-pill">
                <i class="bi bi-arrow-left me-1"></i> Dashboard EHS
            </a>
        </div>
    </div>

    <!-- Filtros de Configuración del Reporte con Auto-Submit -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-sliders me-2 text-primary"></i> Configurar Parámetros del Reporte</h6>
                <span class="badge bg-light text-secondary border rounded-pill px-2 py-1 small">
                    <i class="bi bi-lightning-charge-fill text-warning me-1"></i> Actualización automática
                </span>
            </div>

            <form method="GET" action="{{ route('admin.reportes.index') }}" class="row g-3 align-items-end">
                
                <!-- Variable Ambiental -->
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Variable Ambiental</label>
                    <select name="variable_type" class="form-select rounded-3" onchange="this.form.submit()">
                        <option value="co2" {{ $variableType === 'co2' ? 'selected' : '' }}>CO2 (Concentración de Gas)</option>
                        <option value="temperature" {{ $variableType === 'temperature' ? 'selected' : '' }}>Temperatura Térmica</option>
                        <option value="humidity" {{ $variableType === 'humidity' ? 'selected' : '' }}>Humedad Relativa</option>
                    </select>
                </div>

                <!-- Ambiente / Aula -->
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Ambiente / Aula</label>
                    <select name="environment_id" class="form-select rounded-3" onchange="this.form.submit()">
                        <option value="">Todos los Ambientes del CEFA</option>
                        @foreach($environments as $env)
                            <option value="{{ $env->id }}" {{ $environmentId == $env->id ? 'selected' : '' }}>
                                {{ $env->name }} ({{ $env->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Fecha Inicial -->
                <div class="col-6 col-md-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Fecha Inicial</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="form-control rounded-3" onchange="this.form.submit()">
                </div>

                <!-- Fecha Final -->
                <div class="col-6 col-md-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Fecha Final</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="form-control rounded-3" onchange="this.form.submit()">
                </div>

            </form>
        </div>
    </div>

    <!-- Resumen del Informe a Exportar -->
    <div class="row g-4 mb-4">
        <!-- Card 1: Acciones de Descarga y Vista Previa -->
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-success text-white rounded-pill px-3 py-1">
                            <i class="bi bi-shield-check me-1"></i> Listo para Emisión
                        </span>
                        <span class="badge bg-light text-dark border rounded-pill px-2 py-1 small">SHA-256 Protegido</span>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Opciones de Exportación Oficial</h5>
                    <p class="text-muted small mb-4">Visualiza la vista previa emergente del reporte oficial inalterable o descarga los datos en formato hoja de cálculo.</p>
                </div>

                <div class="d-flex flex-wrap gap-3">
                    <!-- Botón Abre Modal Emergente -->
                    <button type="button" class="btn btn-danger btn-lg rounded-3 px-4 d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalVistaPreviaPdf">
                        <i class="bi bi-file-earmark-pdf-fill fs-5"></i>
                        <div class="text-start">
                            <span class="d-block fw-bold" style="font-size: 0.95rem;">Ver PDF Protegido</span>
                            <small class="d-block text-white-50" style="font-size: 11px;">Vista Previa e Impresión</small>
                        </div>
                    </button>

                    <!-- Botón Descargar CSV -->
                    <a href="{{ route('admin.reportes.csv', request()->all()) }}" class="btn btn-success btn-lg rounded-3 px-4 d-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-file-earmark-excel-fill fs-5"></i>
                        <div class="text-start">
                            <span class="d-block fw-bold" style="font-size: 0.95rem;">Exportar Excel (.CSV)</span>
                            <small class="d-block text-white-50" style="font-size: 11px;">Datos para Estadística</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Card 2: Firma de Integridad Digital -->
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-light p-4 border border-1">
                <h6 class="fw-bold text-dark mb-2 d-flex align-items-center gap-2">
                    <i class="bi bi-qr-code text-primary fs-5"></i> Sello de Integridad SHA-256
                </h6>
                <p class="text-secondary small mb-3">Este código de hash único garantiza que el documento no sea alterado posteriormente:</p>
                <div class="p-3 bg-white rounded-3 border text-break font-monospace text-primary fw-bold mb-3" style="font-size: 11px; letter-spacing: 0.5px;">
                    {{ $hashSignature }}
                </div>
                <div class="d-flex align-items-center gap-2 text-muted" style="font-size: 11px;">
                    <i class="bi bi-lock-fill text-success fs-6"></i>
                    <span>Documento bloqueado para edición. Cumplimiento de norma EHS.</span>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- MODAL EMERGENTE DE VISTA PREVIA PDF -->
<div class="modal fade" id="modalVistaPreviaPdf" tabindex="-1" aria-labelledby="modalPdfLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" style="height: 92vh;">
        <div class="modal-content border-0 shadow-lg rounded-4 h-100">
            <!-- Modal Header -->
            <div class="modal-header bg-dark text-white rounded-top-4 py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-pdf-fill text-danger fs-4"></i>
                    <div>
                        <h6 class="modal-title fw-bold text-white mb-0" id="modalPdfLabel">
                            Vista Previa de Documento Oficial Protegido (SHA-256)
                        </h6>
                        <small class="text-white-50" style="font-size: 11px;">SENA CEFA — AirSense EHS Audit Report</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body con iframe -->
            <div class="modal-body p-0 bg-secondary-subtle">
                <iframe id="iframePdfPreview" 
                        src="{{ route('admin.reportes.pdf', request()->all()) }}" 
                        style="width: 100%; height: 100%; min-height: 75vh; border: none;">
                </iframe>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer bg-light border-0 rounded-bottom-4 py-2 px-4 justify-content-between">
                <div class="d-flex align-items-center gap-2 text-muted" style="font-size: 12px;">
                    <i class="bi bi-shield-lock-fill text-success fs-6"></i>
                    <span>Documento oficial firmado con hash SHA-256</span>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">
                        Cerrar
                    </button>
                    <button type="button" onclick="document.getElementById('iframePdfPreview').contentWindow.print();" class="btn btn-sm btn-danger rounded-pill px-4 shadow-sm">
                        <i class="bi bi-printer-fill me-1"></i> Imprimir / Guardar PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
