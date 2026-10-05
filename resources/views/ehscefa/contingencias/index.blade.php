@extends('layouts.sidebarehscefa')

@section('tituloPagina', 'Manual & Protocolos de Contingencia EHS')

@section('content')
<div class="container-fluid px-0">

    <!-- 1. Encabezado y Acciones -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 pb-2 border-bottom">
        <div>
            <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                <i class="bi bi-shield-exclamation text-danger"></i> Manual & Protocolos de Contingencia EHS
            </h4>
            <p class="text-muted small mb-0">Procedimientos estandarizados y planes de respuesta rápida ante contingencias de calidad de aire en el CEFA.</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <a href="{{ route('ehscefa.dashboard') }}" class="btn btn-outline-secondary btn-sm px-3 rounded-pill">
                <i class="bi bi-arrow-left me-1"></i> Dashboard EHS
            </a>
            <button type="button" class="btn btn-danger btn-sm px-3 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCrearProtocolo">
                <i class="bi bi-plus-lg me-1"></i> Nuevo Protocolo
            </button>
        </div>
    </div>

    <!-- Alertas Flash -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Por favor verifica los campos ingresados.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- 2. Tarjetas KPI de Resumen -->
    <div class="row g-3 mb-4">
        <!-- Card Total -->
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold d-block mb-1">Total Protocolos</span>
                        <h3 class="fw-bold text-dark mb-0">{{ $protocolos->count() }}</h3>
                        <small class="text-secondary" style="font-size: 11px;">Manuales de acción activos</small>
                    </div>
                    <div class="rounded-3 bg-primary-subtle text-primary p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-journal-text fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Danger -->
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white border-start border-4 border-danger">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold d-block mb-1">Nivel Crítico (Peligro)</span>
                        <h3 class="fw-bold text-danger mb-0">{{ $protocolos->where('risk_level', 'danger')->count() }}</h3>
                        <small class="text-danger fw-medium" style="font-size: 11px;">Acción de respuesta inmediata</small>
                    </div>
                    <div class="rounded-3 bg-danger-subtle text-danger p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-exclamation-octagon fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Warning -->
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white border-start border-4 border-warning">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold d-block mb-1">Nivel Advertencia</span>
                        <h3 class="fw-bold text-warning mb-0">{{ $protocolos->where('risk_level', 'warning')->count() }}</h3>
                        <small class="text-warning fw-medium" style="font-size: 11px;">Medidas preventivas EHS</small>
                    </div>
                    <div class="rounded-3 bg-warning-subtle text-warning p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-exclamation-triangle fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Grid de Protocolos de Contingencia -->
    <div class="row g-4">
        @forelse($protocolos as $protocolo)
            <div class="col-12 col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white d-flex flex-column justify-content-between">
                    <div>
                        <!-- Header de la tarjeta -->
                        <div class="card-header bg-transparent border-0 pt-3 px-3 pb-0 d-flex align-items-center justify-content-between">
                            <!-- Badge de Categoría -->
                            @if($protocolo->category === 'co2')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">
                                    <i class="bi bi-cloud-haze2 me-1"></i> CO2 / Gas
                                </span>
                            @elseif($protocolo->category === 'temperature')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill">
                                    <i class="bi bi-thermometer-half me-1"></i> Temperatura
                                </span>
                            @else
                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 rounded-pill">
                                    <i class="bi bi-droplet-half me-1"></i> Humedad
                                </span>
                            @endif

                            <!-- Badge de Riesgo -->
                            @if($protocolo->risk_level === 'danger')
                                <span class="badge bg-danger text-white px-2 py-1 rounded-pill">
                                    <i class="bi bi-exclamation-octagon-fill me-1"></i> Peligro
                                </span>
                            @else
                                <span class="badge bg-warning text-dark px-2 py-1 rounded-pill">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Advertencia
                                </span>
                            @endif
                        </div>

                        <!-- Cuerpo de la tarjeta -->
                        <div class="card-body p-3">
                            <h5 class="fw-bold text-dark mb-2">{{ $protocolo->title }}</h5>
                            <p class="text-muted small mb-3" style="text-align: justify;">{{ $protocolo->description }}</p>

                            <!-- Pasos de Acción -->
                            <div class="p-3 bg-light rounded-3 border border-1">
                                <span class="fw-bold text-dark d-block mb-1 small text-uppercase tracking-wider">
                                    <i class="bi bi-list-check text-primary me-1"></i> Pasos de Acción Inmediata:
                                </span>
                                <div class="text-secondary small whitespace-pre-line" style="line-height: 1.5; text-align: justify;">
                                    {!! nl2br(e($protocolo->action_steps)) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer de la tarjeta -->
                    <div class="card-footer bg-transparent border-top-0 p-3 pt-0 d-flex align-items-center justify-content-between">
                        <div class="text-muted" style="font-size: 11px;">
                            <i class="bi bi-person me-1"></i> {{ $protocolo->creator ? $protocolo->creator->name : 'Sistema EHS' }}
                            <span class="mx-1">•</span>
                            <i class="bi bi-clock me-1"></i> {{ $protocolo->created_at ? $protocolo->created_at->format('d/m/Y') : 'N/A' }}
                        </div>
                        <div class="d-flex gap-1">
                            <button type="button" class="btn btn-sm btn-light border rounded-circle" data-bs-toggle="modal" data-bs-target="#modalEditarProtocolo{{ $protocolo->id }}" title="Editar Protocolo">
                                <i class="bi bi-pencil text-secondary"></i>
                            </button>
                            <form action="{{ route('ehscefa.contingencias.destroy', $protocolo->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este protocolo de contingencia?');" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border rounded-circle" title="Eliminar Protocolo">
                                    <i class="bi bi-trash text-danger"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODAL EDITAR PROTOCOLO -->
            <div class="modal fade" id="modalEditarProtocolo{{ $protocolo->id }}" tabindex="-1" aria-labelledby="modalEditarLabel{{ $protocolo->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <div class="modal-header bg-light rounded-top-4">
                            <h5 class="modal-header-title fw-bold text-dark mb-0 fs-6" id="modalEditarLabel{{ $protocolo->id }}">
                                <i class="bi bi-pencil-square text-primary me-2"></i> Editar Protocolo de Contingencia
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('ehscefa.contingencias.update', $protocolo->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body p-4">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label class="form-label fw-semibold small text-muted">Categoría</label>
                                        <select name="category" class="form-select form-select-sm rounded-3" required>
                                            <option value="co2" {{ $protocolo->category === 'co2' ? 'selected' : '' }}>CO2 / Gas Monóxido</option>
                                            <option value="temperature" {{ $protocolo->category === 'temperature' ? 'selected' : '' }}>Temperatura</option>
                                            <option value="humidity" {{ $protocolo->category === 'humidity' ? 'selected' : '' }}>Humedad Relativa</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-semibold small text-muted">Nivel de Riesgo</label>
                                        <select name="risk_level" class="form-select form-select-sm rounded-3" required>
                                            <option value="warning" {{ $protocolo->risk_level === 'warning' ? 'selected' : '' }}>Advertencia (Warning)</option>
                                            <option value="danger" {{ $protocolo->risk_level === 'danger' ? 'selected' : '' }}>Peligro Crítico (Danger)</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold small text-muted">Título del Protocolo</label>
                                        <input type="text" name="title" value="{{ $protocolo->title }}" class="form-control form-control-sm rounded-3" required placeholder="Ej: Protocolo Evacuación por CO2 Elevado">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold small text-muted">Descripción del Escenario</label>
                                        <textarea name="description" rows="3" class="form-control form-control-sm rounded-3" required placeholder="Describa el umbral y escenario de riesgo...">{{ $protocolo->description }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold small text-muted">Pasos de Acción Recomendados</label>
                                        <textarea name="action_steps" rows="4" class="form-control form-control-sm rounded-3" required placeholder="Paso 1: Evacuar área...&#10;Paso 2: Activar extractores...">{{ $protocolo->action_steps }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer bg-light rounded-bottom-4 border-0">
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-sm btn-primary rounded-pill px-4">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                    <div class="mb-3">
                        <i class="bi bi-shield-check text-muted display-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark">No hay protocolos de contingencia registrados</h5>
                    <p class="text-muted small mb-3">Registra los procedimientos estandarizados de acción ante alertas de calidad de aire en el CEFA.</p>
                    <div>
                        <button type="button" class="btn btn-danger btn-sm px-4 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCrearProtocolo">
                            <i class="bi bi-plus-lg me-1"></i> Crear Primer Protocolo
                        </button>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- MODAL CREAR PROTOCOLO -->
<div class="modal fade" id="modalCrearProtocolo" tabindex="-1" aria-labelledby="modalCrearLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-light rounded-top-4">
                <h5 class="modal-header-title fw-bold text-dark mb-0 fs-6" id="modalCrearLabel">
                    <i class="bi bi-shield-plus text-danger me-2"></i> Registrar Nuevo Protocolo de Contingencia
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('ehscefa.contingencias.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold small text-muted">Categoría</label>
                            <select name="category" class="form-select form-select-sm rounded-3" required>
                                <option value="co2" selected>CO2 / Gas Monóxido</option>
                                <option value="temperature">Temperatura</option>
                                <option value="humidity">Humedad Relativa</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small text-muted">Nivel de Riesgo</label>
                            <select name="risk_level" class="form-select form-select-sm rounded-3" required>
                                <option value="warning">Advertencia (Warning)</option>
                                <option value="danger" selected>Peligro Crítico (Danger)</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small text-muted">Título del Protocolo</label>
                            <input type="text" name="title" class="form-control form-control-sm rounded-3" required placeholder="Ej: Protocolo de Respuesta por Concentración Alta de CO2">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small text-muted">Descripción del Escenario</label>
                            <textarea name="description" rows="3" class="form-control form-control-sm rounded-3" required placeholder="Ej: Este protocolo aplica cuando las lecturas de los sensores superen los 1000 PPM de CO2..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small text-muted">Pasos de Acción Recomendados</label>
                            <textarea name="action_steps" rows="4" class="form-control form-control-sm rounded-3" required placeholder="Paso 1: Notificar a SST y al instructor del ambiente.&#10;Paso 2: Abrir inmediatamente puertas y ventanas para ventilación natural.&#10;Paso 3: Activar extractores de aire."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4 border-0">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-sm btn-danger rounded-pill px-4">Guardar Protocolo</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection