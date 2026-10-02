@extends('layouts.sidebaradmincefa')

@section('tituloPagina', 'Nodos IoT')

@section('content')

<div class="contenedor-nodos">


    <!-- FILTROS Y BUSCADOR -->

    <div class="herramientas-nodos">

        <div class="filtros-nodos">

            <div class="filtro-nodo activo">
                Registrados

                <span class="numero-filtro">
                    5
                </span>
            </div>

            <div class="filtro-nodo">
                No registrados

                <span class="numero-filtro">
                    1
                </span>
            </div>

            <div class="filtro-nodo">
                Cambio de ubicación

                <span class="numero-filtro">
                    1
                </span>
            </div>

        </div>


        <div class="buscador-nodo">

            <input
                type="text"
                placeholder="🔍  Buscar nodo..."
            >

        </div>

    </div>


    <!-- LISTA DE NODOS -->

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
                </tr>
            </thead>

            <tbody>

                @forelse ($nodes as $node)

                    <tr>

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

                    </tr>

                @empty

                    <tr>

                        <td colspan="5" class="tabla-vacia">

                            <i class="fas fa-microchip"></i>

                            <p>No hay nodos registrados.</p>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection