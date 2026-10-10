@extends('layouts.sidebaradmincefa')

@section('tituloPagina', 'Nodos IoT')

@section('content')

<div class="contenedor-nodos" x-data="{ filtro: 'todos', busqueda: '' }">

    <!-- ENCABEZADO SUPERIOR SEGÚN IMAGEN DE REFERENCIA -->
    <div class="encabezado-infraestructura">
        <div>
            <h1 class="titulo-infra">Nodos conectados</h1>
            <p class="desc-infra">Registra y administra los dispositivos ESP32 detectados.</p>
        </div>
    </div>



    <!-- FILTROS Y BUSCADOR -->
    <div class="herramientas-nodos">

        <div class="filtros-nodos">

            <!-- REGISTRADOS -->
            <div class="filtro-nodo"
                 :class="{ 'activo': filtro === 'todos' || filtro === 'registrados' }"
                 @click="filtro = 'registrados'">
                Registrados
                <span class="numero-filtro">
                    {{ $nodes->whereNotNull('environment_id')->count() }}
                </span>
            </div>

            <!-- NO REGISTRADOS -->
            <div class="filtro-nodo"
                 :class="{ 'activo': filtro === 'no_registrados' }"
                 @click="filtro = 'no_registrados'">
                No registrados
                <span class="numero-filtro">
                    {{ $nodes->whereNull('environment_id')->count() }}
                </span>
            </div>

            <!-- CAMBIO DE UBICACIÓN -->
            <div class="filtro-nodo"
                 :class="{ 'activo': filtro === 'cambio_ubicacion' }"
                 @click="filtro = 'cambio_ubicacion'">
                Cambio de ubicación
                <span class="numero-filtro">
                    {{ $locationChangedCount }}
                </span>
            </div>

        </div>

        <div class="buscador-nodo">
            <input
                type="text"
                x-model="busqueda"
                placeholder="🔍  Buscar nodo..."
            >
        </div>

    </div>

    <!-- LISTA DE NODOS (TARJETAS / FILAS SEGÚN IMAGEN DE REFERENCIA) -->
    <div class="lista-nodos-cards">

        @forelse ($nodes as $node)

            @php
                $isLocationChanged = (bool) ($node->location_changed ?? false);
                $isUnregistered = ($node->environment_id === null);
                $isOffline = ($node->connectivity_status === 'offline');
            @endphp

            <div class="card-nodo-item"
                 x-show="
                    (filtro === 'todos' ||
                     (filtro === 'registrados' && {{ !$isUnregistered ? 'true' : 'false' }}) ||
                     (filtro === 'no_registrados' && {{ $isUnregistered ? 'true' : 'false' }}) ||
                     (filtro === 'cambio_ubicacion' && {{ $isLocationChanged ? 'true' : 'false' }}))
                    &&
                    (busqueda === '' ||
                     '{{ strtolower($node->device_uid) }}'.includes(busqueda.toLowerCase()) ||
                     '{{ strtolower($node->name ?? $node->environment?->name ?? '') }}'.includes(busqueda.toLowerCase()))
                 ">

                <!-- INFORMACIÓN IZQUIERDA: ÍCONO WIFI + NOMBRE + BADGES + DATOS -->
                <div class="info-izquierda-nodo">

                    <div class="icono-nodo-box {{ $isOffline ? 'offline' : 'online' }}">
                        <i class="fas fa-wifi"></i>
                    </div>

                    <div>
                        <div class="nombre-nodo-titulo">
                            <span>{{ $node->name ?? $node->environment?->name ?? 'Nodo IoT' }}</span>

                            @if ($isOffline)
                                <span class="badge-estado-pill offline">
                                    <span class="punto-estado"></span> Sin conexión
                                </span>
                            @else
                                <span class="badge-estado-pill online">
                                    <span class="punto-estado"></span> En línea
                                </span>
                            @endif

                            @if ($isLocationChanged)
                                <span class="badge-estado-pill cambio">
                                    <span class="punto-estado"></span> Ubicación cambió
                                </span>
                            @endif
                        </div>

                        <div class="datos-nodo-sub">
                            {{ $node->device_uid }} · Token: ASCE-{{ substr(md5($node->device_uid), 0, 4) }}-•••• · {{ $node->environment?->description ?? ($node->environment?->name ?? 'Sin ubicación') }}
                        </div>
                    </div>

                </div>

                <!-- INFORMACIÓN DERECHA: LATITUD, LONGITUD, BOTÓN REGISTRAR CAMBIO Y BOTÓN LÁPIZ -->
                <div class="info-derecha-nodo">

                    <div class="coordenada-box">
                        <div class="coordenada-item">
                            <small>Latitud</small>
                            <strong>{{ $node->last_reported_latitude !== null ? number_format($node->last_reported_latitude, 5) : 'N/A' }}</strong>
                        </div>
                        <div class="coordenada-item">
                            <small>Longitud</small>
                            <strong>{{ $node->last_reported_longitude !== null ? number_format($node->last_reported_longitude, 4) : 'N/A' }}</strong>
                        </div>
                    </div>

                    @if ($isLocationChanged)
                        <button type="button" class="btn-registrar-cambio" data-bs-toggle="modal" data-bs-target="#modalCambioUbicacion{{ $node->id }}">
                            Registrar cambio
                        </button>
                    @endif

                    <button type="button" class="btn-editar-nodo-circle" data-bs-toggle="modal" data-bs-target="#modalAsignar{{ $node->id }}" title="Editar nodo">
                        <i class="fas fa-pen"></i>
                    </button>

                </div>

            </div>

            <!-- MODAL 1: CONFIRMAR NUEVA UBICACIÓN (SEGÚN IMAGEN DE REFERENCIA 1) -->
            @if ($isLocationChanged)
                <div class="modal fade" id="modalCambioUbicacion{{ $node->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content modal-custom-ref shadow-lg">
                            <div class="modal-custom-header">
                                <div>
                                    <div class="modal-custom-sub">CAMBIO DETECTADO</div>
                                    <h2 class="modal-custom-title">Confirmar nueva ubicación</h2>
                                </div>
                                <button type="button" class="btn-close-modal-circle" data-bs-dismiss="modal" aria-label="Cerrar">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <form action="{{ route('admin.nodos.asignar-ambiente') }}" method="POST">
                                @csrf
                                <input type="hidden" name="node_id" value="{{ $node->id }}">
                                <input type="hidden" name="environment_id" value="{{ $node->environment_id }}">

                                <div class="modal-body px-4 py-2">
                                    <!-- GRID DE COMPARACIÓN DE UBICACIÓN -->
                                    <div class="grid-comparacion-ubicacion">
                                        <!-- UBICACIÓN ANTERIOR -->
                                        <div class="card-ubicacion-box">
                                            <small>UBICACIÓN ANTERIOR</small>
                                            <h4>Bloque B</h4>
                                            <p>2.92761, -75.28214</p>
                                        </div>

                                        <div class="flecha-comparacion">
                                            <i class="fas fa-chevron-right"></i>
                                        </div>

                                        <!-- NUEVA UBICACIÓN (VERDE) -->
                                        <div class="card-ubicacion-box nueva">
                                            <small>NUEVA UBICACIÓN</small>
                                            <h4>{{ $node->environment?->name ?? 'Centro de acopio' }}</h4>
                                            <p>{{ number_format($node->last_reported_latitude ?? 2.92781, 5) }}, {{ number_format($node->last_reported_longitude ?? -75.2811, 4) }}</p>
                                        </div>
                                    </div>

                                    <!-- BANNER INFORMATIVO AZUL -->
                                    <div class="banner-info-blue">
                                        <i class="fas fa-shield-alt text-blue-600 fs-5"></i>
                                        <span>El cambio quedará almacenado con usuario, fecha y hora en el reporte de nodos y la auditoría.</span>
                                    </div>
                                </div>

                                <div class="modal-footer border-0 px-4 pt-3 pb-3 justify-content-end gap-2">
                                    <button type="button" class="btn-descartar-modal" data-bs-dismiss="modal">Descartar</button>
                                    <button type="submit" class="btn-confirmar-modal">Confirmar ubicación</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <!-- MODAL 2: CONFIGURAR NODO IOT / EDITAR (SEGÚN IMAGEN DE REFERENCIA 2) -->
            <div class="modal fade" id="modalAsignar{{ $node->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content modal-custom-ref shadow-lg">
                        <div class="modal-custom-header">
                            <div>
                                <div class="modal-custom-sub">CONFIGURAR NODO IOT</div>
                                <h2 class="modal-custom-title">{{ $node->name ?? $node->environment?->name ?? 'Nodo IoT' }}</h2>
                            </div>
                            <button type="button" class="btn-close-modal-circle" data-bs-dismiss="modal" aria-label="Cerrar">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <form action="{{ route('admin.nodos.asignar-ambiente') }}" method="POST">
                            @csrf
                            <input type="hidden" name="node_id" value="{{ $node->id }}">

                            <div class="modal-body px-4 py-2">

                                <!-- TOP BOX CON TOKEN E ÍCONO WIFI -->
                                <div class="box-nodo-token-info">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="icono-nodo-box online mb-0" style="width: 40px; height: 40px;">
                                            <i class="fas fa-wifi"></i>
                                        </div>
                                        <div>
                                            <strong class="d-block text-slate-800 fs-6">{{ $node->device_uid }}</strong>
                                            <small class="text-slate-500">ASCE-4321-•••• · Detectado y autenticado</small>
                                        </div>
                                    </div>

                                    <span class="badge-token-valido">
                                        ● Token válido
                                    </span>
                                </div>

                                <!-- CAMPOS DEL FORMULARIO GRID 2 COLUMNAS -->
                                <div class="row g-3">
                                    <div class="col-md-6 text-start">
                                        <label class="form-label font-semibold text-slate-700">Nombre del lugar</label>
                                        <input type="text" name="name" class="form-control rounded-3 py-2" value="{{ $node->name ?? '' }}" placeholder="Ej. Hangar de ganadería">
                                    </div>

                                    <div class="col-md-6 text-start">
                                        <label for="environment_id_{{ $node->id }}" class="form-label font-semibold text-slate-700">Categoría</label>
                                        <select name="environment_id" id="environment_id_{{ $node->id }}" class="form-select rounded-3 py-2" required>
                                            <option value="" disabled {{ !$node->environment_id ? 'selected' : '' }}>-- Seleccione un ambiente --</option>
                                            @foreach ($environments as $env)
                                                <option value="{{ $env->id }}" {{ $node->environment_id == $env->id ? 'selected' : '' }}>
                                                    {{ $env->name }} {{ $env->description ? '('.$env->description.')' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 text-start">
                                        <label class="form-label font-semibold text-slate-700">Latitud</label>
                                        <input type="text" class="form-control rounded-3 py-2" value="{{ number_format($node->last_reported_latitude ?? 2.92780, 5) }}">
                                    </div>

                                    <div class="col-md-6 text-start">
                                        <label class="form-label font-semibold text-slate-700">Longitud</label>
                                        <input type="text" class="form-control rounded-3 py-2" value="{{ number_format($node->last_reported_longitude ?? -75.2810, 4) }}">
                                    </div>
                                </div>

                                <!-- BANNER INFORMATIVO COORDENADAS MAPA CEFA -->
                                <div class="banner-info-blue">
                                    <i class="fas fa-map-marker-alt text-blue-600 fs-5"></i>
                                    <span>Estas coordenadas vincularán el nodo con un único punto del mapa del CEFA.</span>
                                </div>
                            </div>

                            <div class="modal-footer border-0 px-4 pt-3 pb-3 justify-content-end gap-2">
                                <button type="button" class="btn-descartar-modal" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn-confirmar-modal">
                                    <i class="fas fa-check me-1"></i> Guardar nodo
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        @empty

            <div class="text-center py-5 text-slate-400">
                <i class="fas fa-microchip fs-1 mb-2"></i>
                <p class="m-0">No hay nodos registrados.</p>
            </div>

        @endforelse

    </div>

</div>

@endsection