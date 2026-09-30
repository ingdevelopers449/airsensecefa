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

    <div class="lista-nodos">


        <!-- NODO 1 -->

        <div class="nodo">

            <div class="icono-nodo">
                <i class="fas fa-wifi"></i>
            </div>


            <div class="informacion-nodo">

                <div class="nombre-nodo">

                    Hangar de ganadería

                    <span class="estado-nodo online">
                        ● En línea
                    </span>

                </div>

                <div class="datos-nodo">
                    ESP-001 · Token: ASCE-4321-•••• · Agropecuaria
                </div>

            </div>


            <div class="ubicacion-nodo">

                <div>
                    <small>Latitud</small>
                    <strong>2.92780</strong>
                </div>

                <div>
                    <small>Longitud</small>
                    <strong>-75.2810</strong>
                </div>

            </div>


            <button class="editar-nodo" title="Editar nodo">
                <i class="fas fa-pen"></i>
            </button>

        </div>


        <!-- NODO 2 -->

        <div class="nodo">

            <div class="icono-nodo">
                <i class="fas fa-wifi"></i>
            </div>


            <div class="informacion-nodo">

                <div class="nombre-nodo">

                    Centro de acopio

                    <span class="estado-nodo online">
                        ● En línea
                    </span>

                    <span class="estado-nodo cambio">
                        ● Ubicación cambió
                    </span>

                </div>

                <div class="datos-nodo">
                    ESP-002 · Token: ASCE-4322-•••• · Agroindustrial
                </div>

            </div>


            <div class="ubicacion-nodo">

                <div>
                    <small>Latitud</small>
                    <strong>2.92781</strong>
                </div>

                <div>
                    <small>Longitud</small>
                    <strong>-75.2811</strong>
                </div>

            </div>


            <button class="editar-nodo" title="Editar nodo">
                <i class="fas fa-pen"></i>
            </button>

        </div>


        <!-- NODO 3 -->

        <div class="nodo">

            <div class="icono-nodo">
                <i class="fas fa-wifi"></i>
            </div>


            <div class="informacion-nodo">

                <div class="nombre-nodo">

                    Ambiente 204

                    <span class="estado-nodo online">
                        ● En línea
                    </span>

                </div>

                <div class="datos-nodo">
                    ESP-003 · Token: ASCE-4323-•••• · Académica
                </div>

            </div>


            <div class="ubicacion-nodo">

                <div>
                    <small>Latitud</small>
                    <strong>2.92782</strong>
                </div>

                <div>
                    <small>Longitud</small>
                    <strong>-75.2812</strong>
                </div>

            </div>


            <button class="editar-nodo" title="Editar nodo">
                <i class="fas fa-pen"></i>
            </button>

        </div>


        <!-- NODO 4 -->

        <div class="nodo">

            <div class="icono-nodo">
                <i class="fas fa-wifi"></i>
            </div>


            <div class="informacion-nodo">

                <div class="nombre-nodo">

                    Laboratorio de alimentos

                    <span class="estado-nodo online">
                        ● En línea
                    </span>

                </div>

                <div class="datos-nodo">
                    ESP-004 · Token: ASCE-4324-•••• · Laboratorio
                </div>

            </div>


            <div class="ubicacion-nodo">

                <div>
                    <small>Latitud</small>
                    <strong>2.92783</strong>
                </div>

                <div>
                    <small>Longitud</small>
                    <strong>-75.2813</strong>
                </div>

            </div>


            <button class="editar-nodo" title="Editar nodo">
                <i class="fas fa-pen"></i>
            </button>

        </div>


        <!-- NODO 5 -->

        <div class="nodo">

            <div class="icono-nodo sin-conexion">
                <i class="fas fa-wifi"></i>
            </div>


            <div class="informacion-nodo">

                <div class="nombre-nodo">

                    Bloque administrativo

                    <span class="estado-nodo offline">
                        ● Sin conexión
                    </span>

                </div>

                <div class="datos-nodo">
                    ESP-005 · Token: ASCE-4325-•••• · Administrativa
                </div>

            </div>


            <div class="ubicacion-nodo">

                <div>
                    <small>Latitud</small>
                    <strong>2.92784</strong>
                </div>

                <div>
                    <small>Longitud</small>
                    <strong>-75.2814</strong>
                </div>

            </div>


            <button class="editar-nodo" title="Editar nodo">
                <i class="fas fa-pen"></i>
            </button>

        </div>


    </div>

</div>


@endsection