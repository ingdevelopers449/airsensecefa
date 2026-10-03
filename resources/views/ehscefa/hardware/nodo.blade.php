@extends('layouts.sidebarehscefa')

@section('tituloPagina', 'Estado de Hardware & Conectividad IoT')

@section('content')
<div class="container-fluid px-0">

    <!-- 1. Encabezado y Descripción del Módulo -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 pb-2 border-bottom">
        <div>
            <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                <i class="bi bi-cpu text-success"></i> Diagnóstico de Hardware IoT
            </h4>
            <p class="text-muted small mb-0">Monitoreo técnico de latido (*Keep-Alive*), señal GPS (NEO-6M) y estado de los dispositivos ESP32 en el CEFA.</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <a href="{{ route('ehscefa.dashboard') }}" class="btn btn-outline-secondary btn-sm px-3 rounded-pill">
                <i class="bi bi-arrow-left me-1"></i> Volver al Dashboard
            </a>
            <button type="button" onclick="window.location.reload();" class="btn btn-success btn-sm px-3 rounded-pill">
                <i class="bi bi-arrow-repeat me-1"></i> Actualizar Señal
            </button>
        </div>
    </div>

    <!-- 2. Tarjetas KPI de Estado de Conectividad -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Total Nodos -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold d-block mb-1">Total Dispositivos</span>
                        <h3 class="fw-bold text-dark mb-0">{{ $totalNodos ?? count($nodos ?? []) }}</h3>
                        <small class="text-secondary" style="font-size: 11px;">Hardware registrado en la red</small>
                    </div>
                    <div class="rounded-3 bg-primary-subtle text-primary p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-router fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Nodos En Línea -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white border-start border-4 border-success">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold d-block mb-1">En Línea (Keep-Alive)</span>
                        <h3 class="fw-bold text-success mb-0">{{ $nodosOnline ?? 0 }}</h3>
                        <small class="text-success fw-medium" style="font-size: 11px;">
                            <i class="bi bi-check-circle-fill me-1"></i>Transmitiendo (≤ 5 min)
                        </small>
                    </div>
                    <div class="rounded-3 bg-success-subtle text-success p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-wifi fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Nodos Fuera de Línea -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white border-start border-4 border-danger">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold d-block mb-1">Fuera de Línea</span>
                        <h3 class="fw-bold text-danger mb-0">{{ $nodosOffline ?? 0 }}</h3>
                        <small class="text-danger fw-medium" style="font-size: 11px;">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>Sin señal (> 5 min)
                        </small>
                    </div>
                    <div class="rounded-3 bg-danger-subtle text-danger p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-wifi-off fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Coordenadas GPS -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold d-block mb-1">Módulos GPS NEO-6M</span>
                        <h3 class="fw-bold text-dark mb-0">100%</h3>
                        <small class="text-muted" style="font-size: 11px;">Geolocalización activa</small>
                    </div>
                    <div class="rounded-3 bg-info-subtle text-info p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-geo-alt-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Tabla Principal de Diagnóstico de Hardware -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2">
                <h6 class="fw-bold text-dark m-0">Nodos IoT en la Red CEFA</h6>
                <span class="badge bg-secondary-subtle text-secondary rounded-pill fw-semibold">{{ count($nodos ?? []) }} Dispositivos</span>
            </div>
            <div class="d-flex gap-2">
                <input type="text" id="buscarNodo" class="form-control form-control-sm rounded-pill px-3" placeholder="Buscar por UID o ambiente..." style="max-width: 250px;">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablaNodos">
                <thead class="table-light">
                    <tr class="text-secondary small text-uppercase">
                        <th class="ps-4">Dispositivo / UID</th>
                        <th>Ubicación / Ambiente</th>
                        <th>Estado Keep-Alive</th>
                        <th>Coordenadas GPS</th>
                        <th>Última Transmisión</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nodos ?? [] as $nodo)
                        @php
                            $ultimaLectura = $nodo->readings->first();
                            $minutosDiferencia = $ultimaLectura ? $ultimaLectura->created_at->diffInMinutes(now()) : 999;
                            $enLinea = $minutosDiferencia <= 5;
                            $lat = $nodo->latitude ?? ($ultimaLectura->reported_latitude ?? null);
                            $lng = $nodo->longitude ?? ($ultimaLectura->reported_longitude ?? null);
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-2 bg-light border p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                        <i class="bi bi-cpu-fill text-secondary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 13.5px;">{{ $nodo->device_uid ?? 'ESP32_NODO' }}</div>
                                        <small class="text-muted" style="font-size: 11px;">Token: {{ Str::limit($nodo->device_token ?? 'Token_Default', 12) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark" style="font-size: 13px;">{{ $nodo->name ?? 'Ambiente CEFA' }}</div>
                                <span class="badge bg-light text-secondary border font-medium" style="font-size: 10px;">{{ $nodo->location_category ?? 'Académica' }}</span>
                            </td>
                            <td>
                                @if($enLinea)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 rounded-pill fw-semibold d-inline-flex align-items-center gap-1">
                                        <span class="spinner-grow spinner-grow-sm text-success" style="width: 6px; height: 6px;" role="status"></span>
                                        En Línea ({{ $minutosDiferencia }}m)
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1.5 rounded-pill fw-semibold d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-x-circle-fill"></i> Fuera de Línea
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($lat && $lng)
                                    <a href="https://maps.google.com/?q={{ $lat }},{{ $lng }}" target="_blank" class="text-decoration-none text-primary fw-medium small">
                                        <i class="bi bi-geo-alt me-1"></i>{{ number_format($lat, 5) }}, {{ number_format($lng, 5) }}
                                    </a>
                                @else
                                    <span class="text-muted small">Sin señal GPS</span>
                                @endif
                            </td>
                            <td>
                                @if($ultimaLectura)
                                    <div class="small fw-semibold text-dark">{{ $ultimaLectura->created_at->format('d/m/Y H:i') }}</div>
                                    <small class="text-muted" style="font-size: 11px;">{{ $ultimaLectura->created_at->diffForHumans() }}</small>
                                @else
                                    <span class="text-muted small">Sin registros</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="verDetallesNodo('{{ $nodo->device_uid }}', '{{ $nodo->name }}', '{{ $lat }}', '{{ $lng }}')">
                                    <i class="bi bi-info-circle me-1"></i> Detalle
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                <p class="fw-semibold mb-1">No hay nodos registradas en la base de datos.</p>
                                <small>Ejecuta la sincronización <code>php artisan sync:telemetry</code> para descargar lecturas reales.</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Script de Búsqueda Dinámica -->
<script>
    document.getElementById('buscarNodo').addEventListener('keyup', function() {
        let filtro = this.value.toLowerCase();
        let filas = document.querySelectorAll('#tablaNodos tbody tr');
        filas.forEach(function(fila) {
            let texto = fila.innerText.toLowerCase();
            fila.style.display = texto.includes(filtro) ? '' : 'none';
        });
    });

    function verDetallesNodo(uid, nombre, lat, lng) {
        Swal.fire({
            title: 'Diagnóstico de Dispositivo',
            html: `
                <div class="text-start p-2">
                    <p class="mb-1"><strong>UID Hardware:</strong> <code>${uid}</code></p>
                    <p class="mb-1"><strong>Ambiente:</strong> ${nombre}</p>
                    <p class="mb-1"><strong>Latitud GPS:</strong> ${lat || 'N/A'}</p>
                    <p class="mb-1"><strong>Longitud GPS:</strong> ${lng || 'N/A'}</p>
                    <p class="mb-0 text-muted small mt-2">Firmware compatible con HTTPS SSL WiFiClientSecure y buffering LittleFS.</p>
                </div>
            `,
            icon: 'info',
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#39A900',
            customClass: {
                popup: 'rounded-4 border-0 shadow-lg'
            }
        });
    }
</script>
@endsection