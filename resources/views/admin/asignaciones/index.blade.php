@extends('layouts.sidebaradmincefa')

@section('tituloPagina', 'Asignación de Ambientes')

@section('content')

<div class="contenedor-nodos" x-data="{ filtro: 'todos', busqueda: '' }">

    <!-- ENCABEZADO SUPERIOR -->
    <div class="encabezado-infraestructura">
        <div>
            <h1 class="titulo-infra">Asignación de Ambientes</h1>
        </div>
    </div>



    <!-- FILTROS Y BUSCADOR -->
    <div class="herramientas-nodos">

        <div class="filtros-nodos">

            <!-- TODOS -->
            <div class="filtro-nodo"
                 :class="{ 'activo': filtro === 'todos' }"
                 @click="filtro = 'todos'">
                Todos los instructores
                <span class="numero-filtro">
                    {{ $instructors->count() }}
                </span>
            </div>

            <!-- CON AMBIENTE -->
            <div class="filtro-nodo"
                 :class="{ 'activo': filtro === 'asignados' }"
                 @click="filtro = 'asignados'">
                Con ambiente asignado
                <span class="numero-filtro">
                    {{ $instructorAssignments->count() }}
                </span>
            </div>

            <!-- SIN AMBIENTE -->
            <div class="filtro-nodo"
                 :class="{ 'activo': filtro === 'sin_asignar' }"
                 @click="filtro = 'sin_asignar'">
                Sin ambiente asignado
                <span class="numero-filtro">
                    {{ $instructors->count() - $instructorAssignments->count() }}
                </span>
            </div>

        </div>

        <div class="buscador-nodo">
            <input
                type="text"
                x-model="busqueda"
                placeholder="🔍  Buscar instructor..."
            >
        </div>

    </div>

    <!-- LISTA DE TARJETAS DE INSTRUCTORES Y SUS ASIGNACIONES -->
    <div class="lista-nodos-cards">

        @forelse ($instructors as $instructor)

            @php
                $assignment = $instructorAssignments->get($instructor->id);
                $hasEnvironment = !is_null($assignment);
            @endphp

            <div class="card-nodo-item"
                 x-show="
                    (filtro === 'todos' ||
                     (filtro === 'asignados' && {{ $hasEnvironment ? 'true' : 'false' }}) ||
                     (filtro === 'sin_asignar' && {{ !$hasEnvironment ? 'true' : 'false' }}))
                    &&
                    (busqueda === '' ||
                     '{{ strtolower($instructor->name) }}'.includes(busqueda.toLowerCase()) ||
                     '{{ strtolower($instructor->email) }}'.includes(busqueda.toLowerCase()) ||
                     '{{ strtolower($assignment?->environment?->name ?? '') }}'.includes(busqueda.toLowerCase()))
                 ">

                <!-- INFORMACIÓN IZQUIERDA: AVATAR DE INSTRUCTOR + NOMBRE + AMBIENTE -->
                <div class="info-izquierda-nodo">

                    <div class="icono-nodo-box {{ $hasEnvironment ? 'online' : 'offline' }}">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>

                    <div>
                        <div class="nombre-nodo-titulo">
                            <span>{{ $instructor->name }}</span>

                            @if ($hasEnvironment)
                                <span class="badge-estado-pill online">
                                    <span class="punto-estado"></span> {{ $assignment->environment?->name }}
                                </span>
                            @else
                                <span class="badge-estado-pill offline">
                                    <span class="punto-estado"></span> Sin ambiente asignado
                                </span>
                            @endif
                        </div>

                        <div class="datos-nodo-sub">
                            <i class="far fa-envelope me-1"></i> {{ $instructor->email }} · {{ $assignment->environment?->description ?? ($instructor->role?->name ?? 'Instructor') }}
                        </div>
                    </div>

                </div>

                <!-- INFORMACIÓN DERECHA: DETALLES DE FECHA Y BOTÓN ASIGNAR -->
                <div class="info-derecha-nodo">

                    <div class="coordenada-box">
                        <div class="coordenada-item">
                            <small>Asignado el</small>
                            <strong>{{ $assignment ? $assignment->assigned_at->format('d/m/Y') : 'N/A' }}</strong>
                        </div>
                        <div class="coordenada-item">
                            <small>Asignado por</small>
                            <strong>{{ $assignment?->assignedBy?->name ?? 'Administración' }}</strong>
                        </div>
                    </div>

                    @if ($hasEnvironment)
                        <button type="button" class="btn-registrar-cambio text-danger border-danger" data-bs-toggle="modal" data-bs-target="#modalDesvincular{{ $assignment->id }}" title="Finalizar asignación">
                            Desvincular
                        </button>
                    @endif

                    <button type="button" class="btn-editar-nodo-circle" data-bs-toggle="modal" data-bs-target="#modalAsignarInstructor{{ $instructor->id }}" title="Asignar / Cambiar Ambiente">
                        <i class="fas fa-pen"></i>
                    </button>

                </div>

            </div>

            <!-- MODAL: ASIGNAR AMBIENTE A INSTRUCTOR -->
            <div class="modal fade" id="modalAsignarInstructor{{ $instructor->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content modal-custom-ref shadow-lg">
                        <div class="modal-custom-header">
                            <div>
                                <div class="modal-custom-sub">ASIGNACIÓN DE AMBIENTE</div>
                                <h2 class="modal-custom-title">{{ $instructor->name }}</h2>
                            </div>
                            <button type="button" class="btn-close-modal-circle" data-bs-dismiss="modal" aria-label="Cerrar">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <form action="{{ route('admin.asignaciones.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="instructor_user_id" value="{{ $instructor->id }}">

                            <div class="modal-body px-4 py-3">

                                <!-- CAJA INFORMATIVA DEL INSTRUCTOR -->
                                <div class="box-nodo-token-info mb-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="icono-nodo-box online mb-0" style="width: 40px; height: 40px;">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <div>
                                            <strong class="d-block text-slate-800 fs-6">{{ $instructor->name }}</strong>
                                            <small class="text-slate-500">{{ $instructor->email }} · {{ $instructor->role?->name ?? 'Instructor CEFA' }}</small>
                                        </div>
                                    </div>

                                    <span class="badge-token-valido">
                                        ● Estado Activo
                                    </span>
                                </div>

                                <!-- SELECCIÓN DEL AMBIENTE DE FORMACIÓN -->
                                <div class="mb-3 text-start">
                                    <label for="environment_id_{{ $instructor->id }}" class="form-label font-semibold text-slate-700">Seleccionar Ambiente de Formación</label>
                                    <select name="environment_id" id="environment_id_{{ $instructor->id }}" class="form-select rounded-3 py-2.5 fs-6" required>
                                        <option value="" disabled {{ !$hasEnvironment ? 'selected' : '' }}>-- Seleccione un ambiente de formación --</option>
                                        @foreach ($environments as $env)
                                            <option value="{{ $env->id }}" {{ $assignment?->environment_id == $env->id ? 'selected' : '' }}>
                                                🏢 {{ $env->name }} {{ $env->description ? '('.$env->description.')' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- BANNER INFORMATIVO -->
                                <div class="banner-info-blue">
                                    <i class="fas fa-info-circle text-blue-600 fs-5"></i>
                                    <span>Al asignar un ambiente, el instructor visualizará automáticamente la calidad del aire de esta aula al ingresar al sistema.</span>
                                </div>
                            </div>

                            <div class="modal-footer border-0 px-4 pt-3 pb-3 justify-content-end gap-2">
                                <button type="button" class="btn-descartar-modal" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn-confirmar-modal">
                                    <i class="fas fa-check me-1"></i> Guardar Asignación
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- MODAL BOOTSTRAP 5: CONFIRMAR DESVINCULACIÓN -->
            @if ($hasEnvironment)
                <div class="modal fade" id="modalDesvincular{{ $assignment->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content modal-custom-ref shadow-lg">
                            <div class="modal-custom-header bg-rose-50">
                                <div>
                                    <div class="modal-custom-sub text-rose-600">CONFIRMAR DESVINCULACIÓN</div>
                                    <h2 class="modal-custom-title text-slate-800">{{ $instructor->name }}</h2>
                                </div>
                                <button type="button" class="btn-close-modal-circle" data-bs-dismiss="modal" aria-label="Cerrar">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <form action="{{ route('admin.asignaciones.destroy', $assignment->id) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <div class="modal-body px-4 py-3 text-start">
                                    <div class="d-flex align-items-center gap-3 p-3 bg-rose-50/60 rounded-3 border border-rose-100 mb-3">
                                        <div class="rounded-circle bg-rose-100 text-rose-600 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                                            <i class="fas fa-exclamation-triangle fs-5"></i>
                                        </div>
                                        <div>
                                            <strong class="d-block text-slate-800 fs-6">¿Desvincular ambiente de formación?</strong>
                                            <p class="text-slate-600 small m-0">El instructor dejará de tener asignada el aula <strong>{{ $assignment->environment?->name }}</strong>.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer border-0 px-4 pt-2 pb-3 justify-content-end gap-2">
                                    <button type="button" class="btn-descartar-modal" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn-confirmar-modal bg-rose-600 hover:bg-rose-700 text-white">
                                        <i class="fas fa-unlink me-1"></i> Sí, desvincular
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

        @empty

            <div class="text-center py-5 text-slate-400">
                <i class="fas fa-users-slash fs-1 mb-2"></i>
                <p class="m-0">No se encontraron instructores registrados en el sistema.</p>
            </div>

        @endforelse

    </div>

</div>

@endsection
