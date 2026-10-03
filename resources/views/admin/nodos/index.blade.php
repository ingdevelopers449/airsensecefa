@extends('layouts.sidebaradmincefa')

@section('tituloPagina', 'Nodos IoT')

@section('content')

<div class="contenedor-nodos" x-data="{ filtro: 'todos', busqueda: '' }">

    <!-- MENSAJE DE ÉXITO AL GUARDAR AMBIENTE -->
    @if (session('success'))
        <div class="alerta-exito">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- FILTROS Y BUSCADOR -->

    <div class="herramientas-nodos">

        <div class="filtros-nodos">

            <!-- TODOS -->
            <div class="filtro-nodo"
                 :class="{ 'activo': filtro === 'todos' }"
                 @click="filtro = 'todos'">
                Todos
                <span class="numero-filtro">
                    {{ $nodes->count() }}
                </span>
            </div>

            <!-- REGISTRADOS -->
            <div class="filtro-nodo"
                 :class="{ 'activo': filtro === 'registrados' }"
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

            <!-- CAMBIO DE UBICACIÓN / OFFLINE -->
            <div class="filtro-nodo"
                 :class="{ 'activo': filtro === 'cambio_ubicacion' }"
                 @click="filtro = 'cambio_ubicacion'">
                Cambio de ubicación
                <span class="numero-filtro">
                    {{ $nodes->where('connectivity_status', 'offline')->count() }}
                </span>
            </div>

        </div>


        <div class="buscador-nodo">

            <input
                type="text"
                x-model="busqueda"
                placeholder="🔍  Buscar nodo por UID o ambiente..."
            >

        </div>

    </div>


    <!-- TABLA DE NODOS -->

    <div class="tabla-nodos">

        <div class="tabla-contenedor">

            <table>

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>UID Dispositivo</th>
                        <th>Ambiente Asignado</th>
                        <th>Estado de Conectividad</th>
                        <th>Última Transmisión</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($nodes as $node)

                        <tr x-show="
                            (filtro === 'todos' ||
                             (filtro === 'registrados' && {{ $node->environment_id ? 'true' : 'false' }}) ||
                             (filtro === 'no_registrados' && {{ !$node->environment_id ? 'true' : 'false' }}) ||
                             (filtro === 'cambio_ubicacion' && {{ $node->connectivity_status === 'offline' ? 'true' : 'false' }}))
                            &&
                            (busqueda === '' ||
                             '{{ strtolower($node->device_uid) }}'.includes(busqueda.toLowerCase()) ||
                             '{{ strtolower($node->environment?->name ?? 'sin asignar') }}'.includes(busqueda.toLowerCase()))
                        ">

                            <!-- ID -->
                            <td>
                                <strong>
                                    #{{ $node->id }}
                                </strong>
                            </td>

                            <!-- DEVICE UID -->
                            <td>
                                <span class="device-uid">
                                    {{ $node->device_uid }}
                                </span>
                            </td>

                            <!-- AMBIENTE -->
                            <td>
                                <div class="ambiente-nodo">

                                    <i class="fas fa-building"></i>

                                    <span>
                                        {{ $node->environment?->name ?? 'Sin asignar' }}
                                    </span>

                                </div>
                            </td>

                            <!-- ESTADO -->
                            <td>
                                @if ($node->connectivity_status === 'online')

                                    <span class="badge-conectividad online">
                                        <span class="punto-estado"></span>
                                        Online
                                    </span>

                                @elseif ($node->connectivity_status === 'offline')

                                    <span class="badge-conectividad offline">
                                        <span class="punto-estado"></span>
                                        Offline
                                    </span>

                                @else

                                    <span class="badge-conectividad unknown">
                                        <span class="punto-estado"></span>
                                        Desconocido
                                    </span>

                                @endif
                            </td>

                            <!-- ÚLTIMA TRANSMISIÓN -->
                            <td>

                                @if ($node->last_seen_at)

                                    <div class="ultima-transmision">

                                        <i class="far fa-clock"></i>

                                        <span>
                                            {{ $node->last_seen_at->format('d/m/Y H:i') }}
                                        </span>

                                    </div>

                                @else

                                    <span class="sin-transmision">
                                        Nunca
                                    </span>

                                @endif

                            </td>

                            <!-- ACCIONES: BOTÓN MODAL ASIGNAR AMBIENTE -->
                            <td>
                                <button type="button" class="btn-asignar" data-bs-toggle="modal" data-bs-target="#modalAsignar{{ $node->id }}">
                                    <i class="fas fa-pen"></i>
                                    Asignar Ambiente
                                </button>
                            </td>

                        </tr>

                        <!-- MODAL DE ASIGNACIÓN DE AMBIENTE PARA ESTE NODO -->
                        <div class="modal fade" id="modalAsignar{{ $node->id }}" tabindex="-1" aria-labelledby="modalAsignarLabel{{ $node->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg rounded-4">
                                    <div class="modal-header bg-sena-dark text-white rounded-top-4 py-3">
                                        <h5 class="modal-title font-semibold fs-6" id="modalAsignarLabel{{ $node->id }}">
                                            <i class="fas fa-edit me-2"></i> Asignar Ambiente al Nodo #{{ $node->id }}
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>

                                    <form action="{{ route('admin.nodos.asignar-ambiente') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="node_id" value="{{ $node->id }}">

                                        <div class="modal-body p-4">
                                            <div class="mb-3 text-start">
                                                <label class="form-label font-semibold text-slate-700">UID del Dispositivo:</label>
                                                <input type="text" class="form-control bg-slate-100 font-mono text-indigo-600" value="{{ $node->device_uid }}" readonly>
                                            </div>

                                            <div class="mb-3 text-start">
                                                <label for="environment_id_{{ $node->id }}" class="form-label font-semibold text-slate-700">Seleccionar Ambiente / Aula:</label>
                                                <select name="environment_id" id="environment_id_{{ $node->id }}" class="form-select rounded-3" required>
                                                    <option value="" disabled {{ !$node->environment_id ? 'selected' : '' }}>-- Seleccione un ambiente --</option>
                                                    @foreach ($environments as $env)
                                                        <option value="{{ $env->id }}" {{ $node->environment_id == $env->id ? 'selected' : '' }}>
                                                            {{ $env->name }} {{ $env->code ? '('.$env->code.')' : '' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="modal-footer bg-slate-50 rounded-bottom-4">
                                            <button type="button" class="btn btn-secondary btn-sm px-3 rounded-3" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-sena btn-sm px-3 rounded-3">
                                                <i class="fas fa-save me-1"></i> Guardar Asignación
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    @empty

                        <tr>

                            <td colspan="6" class="tabla-vacia">

                                <i class="fas fa-microchip"></i>

                                <p>No hay nodos registrados.</p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection