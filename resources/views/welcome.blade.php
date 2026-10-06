@php
    $telemetria = $telemetria ?? [
        'co2' => ['valor' => 540, 'unidad' => 'ppm', 'estado' => 'Óptimo'],
        'temperatura' => ['valor' => 24.5, 'unidad' => '°C'],
        'humedad' => ['valor' => 58, 'unidad' => '%'],
    ];

    $desarrolladores = $desarrolladores ?? [
        [
            'iniciales' => 'AD',
            'nombre' => 'Luis Felipe Lozada Bastidas',
            'rol' => 'Líder de Desarrollo IoT',
            'programa' => 'ADSO - Ficha 3312595',
            'especialidad' => 'Desarrollo Backend en Laravel y firmware ESP32 para sensores ambientales.',
            'github' => 'https://github.com/ingdevelopers449',
            'foto' => 'images/equipo/foto-adso-1.png',
        ],
        [
            'iniciales' => 'FE',
            'nombre' => 'Isabella Sifuentes Perdomo',
            'rol' => 'Analítica de Datos',
            'programa' => 'ADSO - Ficha 3312595',
            'especialidad' => 'Diseño de interfaces web responsivas, componentes Bootstrap y experiencia de usuario.',
            'github' => 'https://github.com/',
            'foto' => 'images/equipo/foto-adso-2.png',
        ],
        [
            'iniciales' => 'IA',
            'nombre' => 'Michael Gustavo Castaño Pareja',
            'rol' => 'Analítica de Datos & IA',
            'programa' => 'ADSO - Ficha 3312595',
            'especialidad' => 'Modelado de algoritmos predictivos de CO₂ y correlación climática.',
            'github' => 'https://github.com/',
            'foto' => 'images/equipo/foto-adso-3.png',
        ],
        [
            'iniciales' => 'ST',
            'nombre' => 'Lizbeth Dayana Daza Rogelis',
            'rol' => 'Soporte Telemetría & Redes',
            'programa' => 'ADSO - Ficha 3312595',
            'especialidad' => 'Protocolos MQTT, seguridad de red y sincronización offline en aulas.',
            'github' => 'https://github.com/',
            'foto' => 'images/equipo/foto-adso-4.png',
        ],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AirSense CEFA - Monitoreo Inteligente de Calidad del Aire | SENA</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Bootstrap 5.3.3 CSS Oficial -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons Oficial -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Fuentes de Google: Inter (La mejor para dashboards y tecnología) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Estilos Institucionales SENA -->
    @vite(['resources/css/styles.css', 'resources/css/funcionalidades.css'])
</head>
<body>

    <!-- =========================================================================
         BARRA DE NAVEGACIÓN BOOTSTRAP 5 CON SCROLLSPY
         ========================================================================= -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm sticky-top" id="mainNavbar">
        <div class="container">
            
            <!-- Logotipo Institucional AirSense CEFA -->
            <a class="navbar-brand d-flex align-items-center gap-2 text-decoration-none" href="#hero">
                <!-- 1. Altura del Isotipo (Logo) -->
                <img src="{{ asset('images/logo.png') }}" alt="AirSense CEFA Logo" style="height: 38px; width: auto;" class="d-inline-block">

                <!-- 2. Altura del Texto "AirSense CEFA" -->
                <img src="{{ asset('images/airsense-texto.svg') }}" alt="AirSense CEFA" style="height: 21px; width: auto; transform: translateY(0px);" class="d-inline-block">
            </a>

            <!-- Botón Hamburguesa Nativo de Bootstrap -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menú Central y Acceso -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-1">
                    <li class="nav-item">
                        <a class="nav-link" href="#hero">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#funcionalidades">Funcionalidades</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#equipo">Equipo Desarrollador</a>
                    </li>
                </ul>

                <!-- Botones Iniciar Sesión / Dashboard -->
                <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-sena px-4 py-2 rounded-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm">
                                <i class    ="bi bi-speedometer2"></i>
                                <span>Dashboard</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-sena px-3 py-2 rounded-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm">
                                <i class="bi bi-box-arrow-in-right"></i>
                                <span>Iniciar sesión</span>
                            </a>
                        @endauth
                    @endif
                </div>
            </div>

        </div>
    </nav>

    <!-- Contenido Principal -->
    <main>
        <!-- =========================================================================
             1. HERO SECTION (100vh Pantalla Completa + Bootstrap 5 + Fondo cefa.jpg)
             ========================================================================= -->
        <section id="hero" class="hero-section hero-fullscreen section-scroll" style="position: relative; overflow: hidden; background-image: linear-gradient(to right, rgba(0, 50, 77, 0.93) 0%, rgba(0, 50, 77, 0.84) 55%, rgba(0, 50, 77, 0.65) 100%), url('{{ asset('images/cefa.jpg') }}');">

            <!-- Imagen independiente: Centrada verticalmente en el lado derecho -->
            <!-- ↔ right: ajustado para más a la derecha -->
            <div class="d-none d-lg-block" style="position: absolute; right: 2%; top: 50%; transform: translateY(-45%); width: 45%; max-width: 480px; z-index: 1;">
                <img src="images/diagrama_sensores.png"
                     alt="Diagrama de Monitoreo: CO2, Temperatura y Humedad"
                     class="img-fluid w-100"
                     style="filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.55));">
            </div>

            <div class="container d-flex flex-column justify-content-between flex-grow-1 pt-3 pb-3" style="position: relative; z-index: 2;">

                <!-- Fila Superior: Solo contenido izquierdo (imagen ya es independiente) -->
                <div class="row mt-0 mt-lg-1 mb-auto">

                    <!-- Columna Izquierda: Información Principal (Agrandamos a col-lg-10 para dar espacio al texto gigante) -->
                    <div class="col-lg-10 pt-lg-1" style="position: relative; z-index: 3;">

                        <!-- Título Principal Institucional -->
                        <!-- Pantallas grandes -->
                        <h1 class="fw-bold text-white mb-5 d-none d-md-block" style="font-size: 5rem; line-height: 1.15; text-shadow: 0 4px 12px rgba(0,0,0,0.4);">
                            Monitoreo inteligente <br>
                            <span class="text-sena">de calidad del aire</span>
                        </h1>
                        <!-- Pantallas pequeñas (móviles) -->
                        <h1 class="fw-bold text-white mb-5 d-block d-md-none" style="font-size: 3rem; line-height: 1.15;">
                            Monitoreo inteligente <br>
                            <span class="text-sena">de calidad del aire</span>
                        </h1>

                        <!-- Subtítulo Aclaratorio -->
                        <p class="lead text-light opacity-90 mb-5" style="font-size: 1.1rem; max-width: 650px; line-height: 1.6; margin-top: 6rem;">
                            Detectamos y predecimos en tiempo real la acumulación de <strong>CO₂</strong>, <strong>temperatura</strong> y <strong>humedad</strong> en las aulas y hangares del CEFA La Angostura para prevenir la fatiga y avisar con semáforos cuándo abrir las ventanas.
                        </p>

                        <!-- Píldoras (Badges) de Variables Medidas -->
                        <div class="d-flex flex-wrap gap-3 mt-4 mb-4">
                            <span class="badge bg-white text-dark rounded-pill px-3 py-2 shadow-sm" style="font-size: 0.95rem;">
                                <i class="bi bi-wind text-info me-1"></i> Dióxido de carbono (CO₂)
                            </span>
                            <span class="badge bg-white text-dark rounded-pill px-3 py-2 shadow-sm" style="font-size: 0.95rem;">
                                <i class="bi bi-thermometer-half text-danger me-1"></i> Temperatura
                            </span>
                            <span class="badge bg-white text-dark rounded-pill px-3 py-2 shadow-sm" style="font-size: 0.95rem;">
                                <i class="bi bi-droplet-fill text-primary me-1"></i> Humedad
                            </span>
                        </div>

                        <!-- Imagen visible solo en móvil (reemplaza la absoluta) -->
                        <div class="d-block d-lg-none mb-8 text-center">
                            <img src="images/diagrama_sensores.png"
                                 alt="Diagrama de Monitoreo: CO2, Temperatura y Humedad"
                                 class="img-fluid mx-auto"
                                 style="max-width: 320px; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.45));">
                        </div>
                    </div>

                </div>

                <!-- Fila Inferior Integrada: Tarjeta en 3 Pasos + Ticker + Scroll Down -->
                <div class="mt-4 pt-2">
                    <!-- TARJETAS EN 3 PASOS - Alineación horizontal -->
                    <div class="row g-3">

                        <!-- TARJETA 1 -->
                        <div class="col-md-4">
                            <div class="card text-dark shadow-sm border-0 rounded-3 p-3 h-100" style="background-color: rgba(255, 255, 255, 0.85); backdrop-filter: blur(8px);">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge bg-sena rounded-circle p-2 fs-6 fw-bold flex-shrink-0" style="width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center;">1</span>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1" style="font-size: 1.05rem;">Sensores IoT en aulas</h6>
                                        <div class="text-muted" style="font-size: 0.85rem; line-height: 1.3;">Lecturas cada 60s en hangares y ambientes.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TARJETA 2 -->
                        <div class="col-md-4">
                            <div class="card text-dark shadow-sm border-0 rounded-3 p-3 h-100" style="background-color: rgba(255, 255, 255, 0.85); backdrop-filter: blur(8px);">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge bg-sena rounded-circle p-2 fs-6 fw-bold flex-shrink-0" style="width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center;">2</span>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1" style="font-size: 1.05rem;">Inteligencia Artificial</h6>
                                        <div class="text-muted" style="font-size: 0.85rem; line-height: 1.3;">Anticipa picos de saturación 15 minutos antes.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TARJETA 3 -->
                        <div class="col-md-4">
                            <div class="card text-dark shadow-sm border-0 rounded-3 p-3 h-100" style="background-color: rgba(255, 255, 255, 0.85); backdrop-filter: blur(8px);">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge bg-sena rounded-circle p-2 fs-6 fw-bold flex-shrink-0" style="width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center;">3</span>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1" style="font-size: 1.05rem;">Semáforo & Alertas SST</h6>
                                        <div class="text-muted" style="font-size: 0.85rem; line-height: 1.3;">Avisa el momento exacto para abrir ventanas.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Ticker de Telemetría Inferior e Indicador de Scroll -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 text-white-50 small mt-3">
                        <div>
                            <i class="bi bi-activity text-sena me-1"></i> Promedio institucional CEFA:
                            <span class="text-white fw-bold ms-1">{{ $telemetria['co2']['valor'] ?? 540 }} {{ $telemetria['co2']['unidad'] ?? 'ppm' }}</span> <span class="text-sena">({{ $telemetria['co2']['estado'] ?? 'Óptimo' }})</span> &bull;
                            <span class="text-white fw-bold ms-1">{{ $telemetria['temperatura']['valor'] ?? 24.5 }} {{ $telemetria['temperatura']['unidad'] ?? '°C' }}</span> &bull;
                            <span class="text-white fw-bold ms-1">{{ $telemetria['humedad']['valor'] ?? 58 }} {{ $telemetria['humedad']['unidad'] ?? '%' }}</span>
                        </div>
                        <div>
                            <a href="#funcionalidades" class="text-white-50 text-decoration-none d-inline-flex align-items-center gap-1 hover-white bounce-scroll">
                                <span>Desliza para explorar</span>
                                <i class="bi bi-chevron-down"></i>
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </section>

        <!-- =========================================================================
             2. SECCIÓN "FUNCIONALIDADES PRINCIPALES"
             ========================================================================= -->
        <section id="funcionalidades" class="section-scroll">
            
            <!-- Banner Superior con Parallax -->
            <div class="funcionalidades-hero" style="background-image: linear-gradient(to right, rgba(0, 50, 77, 0.95) 0%, rgba(0, 50, 77, 0.8) 100%), url('{{ asset('images/cefa.jpg') }}');">
                <div class="container" style="position: relative; z-index: 2;">
                    <span class="text-sena fw-bold tracking-wider mb-2 d-block small" style="letter-spacing: 2px; text-transform: uppercase;">Funcionalidades</span>
                    <h2 class="fw-bold text-white mb-2" style="font-size: 2.2rem; max-width: 800px; line-height: 1.2;">
                        Todo lo que necesitas para un CEFA <span class="text-sena">más saludable</span>
                    </h2>
                    <p class="text-light opacity-75 mb-0" style="max-width: 600px; font-size: 0.95rem;">
                        Herramientas diseñadas para monitorear, analizar y gestionar la calidad del aire en los ambientes de formación del CEFA La Angostura.
                    </p>
                </div>
            </div>

            <!-- Contenido de las Funcionalidades -->
            <div class="bg-white py-5" style="position: relative; overflow: hidden;">
                <!-- Decoración de fondo suave circular (tipo filigrana o anillos) -->
                <div style="position: absolute; top: -10%; left: -5%; width: 300px; height: 300px; border-radius: 50%; border: 2px solid rgba(0, 50, 77, 0.03); z-index: 0;"></div>
                <div style="position: absolute; top: 5%; left: -10%; width: 500px; height: 500px; border-radius: 50%; border: 2px solid rgba(0, 50, 77, 0.03); z-index: 0;"></div>
                <div style="position: absolute; bottom: -10%; right: -5%; width: 400px; height: 400px; border-radius: 50%; border: 2px solid rgba(0, 50, 77, 0.03); z-index: 0;"></div>

                <div class="container" style="position: relative; z-index: 1;">
                    <!-- Cabecera del Grid -->
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
                        <div class="d-flex align-items-center mb-3 mb-md-0" style="flex: 1;">
                            <span class="text-sena fw-bold tracking-wider me-3" style="letter-spacing: 1px; text-transform: uppercase; font-size: 0.9rem;">Funcionalidades Principales</span>
                            <div class="flex-grow-1" style="height: 1px; background-color: #e9ecef;"></div>
                        </div>
                        <div class="ms-md-4 text-muted text-start text-md-end" style="max-width: 350px; font-size: 0.8rem;">
                            Explora las herramientas clave que ofrece AirSense CEFA para un monitoreo ambiental eficiente.
                        </div>
                    </div>

                    <!-- Grid de Tarjetas (2/3 columnas) -->
                    <div class="row g-3">
                        
                        <!-- Card 1 -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card bg-white h-100 p-4 card-funcionalidad border-left-green d-flex flex-row align-items-center shadow-sm">
                                <div class="icon-circle bg-sena-subtle text-sena me-3">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-dark mb-1" style="font-size: 1.05rem;">Mapa interactivo</h5>
                                    <p class="text-secondary mb-0" style="font-size: 0.85rem; line-height: 1.4;">
                                        Visualiza el estado de cada ambiente de formación en un plano digital del CEFA en tiempo real.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card bg-white h-100 p-4 card-funcionalidad border-left-red d-flex flex-row align-items-center shadow-sm">
                                <div class="icon-circle bg-danger bg-opacity-10 text-danger me-3">
                                    <i class="bi bi-bell"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-dark mb-1" style="font-size: 1.05rem;">Alertas duales</h5>
                                    <p class="text-secondary mb-0" style="font-size: 0.85rem; line-height: 1.4;">
                                        Recibe notificaciones visuales en la plataforma y por correo institucional cuando se superan los umbrales críticos.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card bg-white h-100 p-4 card-funcionalidad border-left-purple d-flex flex-row align-items-center shadow-sm">
                                <div class="icon-circle bg-opacity-10 me-3" style="color: #6f42c1; background-color: rgba(111, 66, 193, 0.1);">
                                    <i class="bi bi-graph-up-arrow"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-dark mb-1" style="font-size: 1.05rem;">Predicción con IA</h5>
                                    <p class="text-secondary mb-0" style="font-size: 0.85rem; line-height: 1.4;">
                                        Anticipa hasta 15 minutos antes posibles deterioros de la calidad del aire, sugiriendo acciones preventivas.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Card 4 -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card bg-white h-100 p-4 card-funcionalidad border-left-blue d-flex flex-row align-items-center shadow-sm">
                                <div class="icon-circle bg-primary bg-opacity-10 text-primary me-3">
                                    <i class="bi bi-bar-chart"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-dark mb-1" style="font-size: 1.05rem;">Histórico exportable</h5>
                                    <p class="text-secondary mb-0" style="font-size: 0.85rem; line-height: 1.4;">
                                        Consulta registros de hasta un año académico y exporta reportes en Excel o PDF para análisis y auditorías.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Card 5 -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card bg-white h-100 p-4 card-funcionalidad border-left-green d-flex flex-row align-items-center shadow-sm">
                                <div class="icon-circle bg-sena-subtle text-sena me-3">
                                    <i class="bi bi-router"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-dark mb-1" style="font-size: 1.05rem;">Sensores IoT ESP32</h5>
                                    <p class="text-secondary mb-0" style="font-size: 0.85rem; line-height: 1.4;">
                                        Nodos autónomos que monitorean CO₂, temperatura y humedad, con modo offline y sincronización automática.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Card 6 -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card bg-white h-100 p-4 card-funcionalidad border-left-yellow d-flex flex-row align-items-center shadow-sm">
                                <div class="icon-circle bg-warning bg-opacity-10 text-warning me-3" style="color: #d39e00 !important;">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-dark mb-1" style="font-size: 1.05rem;">Seguridad y control</h5>
                                    <p class="text-secondary mb-0" style="font-size: 0.85rem; line-height: 1.4;">
                                        Acceso por rol, registro de auditoría completo y cierre de sesión automático por inactividad.
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <!-- =========================================================================
             5. SECCIÓN "EQUIPO DESARROLLADOR" (ADSO SENA La Angostura)
             ========================================================================= -->
        <section id="equipo" class="bg-white section-scroll d-flex align-items-center py-2" style="min-height: calc(100vh - 74px - 78px);">
            <div class="container w-100">
                
                <div class="text-center mx-auto mb-3" style="max-width: 768px;">
                    <span class="text-uppercase fw-bold text-sena small tracking-wider d-block mb-1">
                        Equipo Desarrollador
                    </span>
                    <h2 class="h3 fw-bold text-sena-dark mb-1">
                        Creado por Aprendices del SENA
                    </h2>
                    <p class="text-secondary small mb-0">Centro de Formación Agroindustrial La Angostura &bull; Regional Huila</p>
                </div>

                <div class="row g-4 justify-content-center">
                    @foreach ($desarrolladores as $dev)
                        @php
                            $devObj = (object) $dev;
                        @endphp
                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sena h-100 border shadow-sm p-4 bg-white text-center d-flex flex-column justify-content-between">
                                <div>
                                    <!-- Avatar Institucional con Iniciales o Foto (Aumentado a 140px) -->
                                    <div class="rounded-circle overflow-hidden bg-sena-dark text-white d-inline-flex align-items-center justify-content-center mx-auto mb-3 shadow position-relative" style="width: 140px; height: 140px; font-size: 2.5rem; font-weight: bold;">
                                        @if(isset($devObj->foto) && $devObj->foto)
                                            <!-- Si no se encuentra la imagen en public/, se mostrarán las iniciales automáticamente gracias a onerror -->
                                            <img src="{{ asset($devObj->foto) }}" alt="{{ $devObj->nombre }}" class="w-100 h-100" style="object-fit: cover;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="w-100 h-100 bg-sena-dark text-white align-items-center justify-content-center position-absolute top-0 start-0" style="display: none; font-size: 2.5rem; font-weight: bold;">
                                                {{ $devObj->iniciales ?? 'AD' }}
                                            </div>
                                        @else
                                            {{ $devObj->iniciales ?? 'AD' }}
                                        @endif
                                    </div>

                                    <!-- Nombre del Aprendiz -->
                                    <h5 class="fw-bold text-dark mb-1" style="font-size: 1.05rem;">{{ $devObj->nombre ?? 'Aprendiz' }}</h5>

                                    <!-- Rol en el Proyecto -->
                                    <span class="text-sena fw-semibold small d-block mb-2">{{ $devObj->rol ?? 'Desarrollador' }}</span>

                                    <!-- Badge del Programa Académico -->
                                    <div class="mb-2">
                                        <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.7rem;">
                                            {{ $devObj->programa ?? 'ADSO' }}
                                        </span>
                                    </div>

                                    <!-- Especialidad -->
                                    <p class="text-secondary small leading-relaxed mb-3" style="font-size: 0.825rem;">
                                        {{ $devObj->especialidad ?? '' }}
                                    </p>
                                </div>

                                <!-- Botón Perfil GitHub -->
                                <div class="border-top pt-3">
                                    <a href="{{ $devObj->github ?? '#' }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-dark btn-sm rounded-pill w-100">
                                        <i class="bi bi-github me-1"></i> Perfil GitHub
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </main>

    <!-- =========================================================================
         PIE DE PÁGINA (MINI FOOTER INSTITUCIONAL)
         ========================================================================= -->
    <footer class="bg-sena-dark text-white py-3 mt-auto shadow-sm" style="position: relative; z-index: 10;">
        <div class="container">
            <div class="row align-items-center gap-3 gap-md-0">
                <!-- Logos Izquierda -->
                <div class="col-md-4 d-flex justify-content-center justify-content-md-start align-items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 36px; width: auto;">
                    <img src="{{ asset('images/airsense-texto-blanco.svg') }}" alt="AirSense CEFA" style="height: 20px; width: auto; transform: translateY(2px);">
                </div>
                
                <!-- Derechos y Contacto -->
                <div class="col-md-4 text-center text-white-50 small fw-medium" style="font-size: 0.8rem; line-height: 1.4;">
                    &copy; {{ date('Y') }} <span class="text-white fw-bold">AirSense CEFA</span>. Todos los derechos reservados.<br>
                    Centro de Formación Agroindustrial La Angostura &bull; SENA Regional Huila
                </div>
                
                <!-- Logo SENA Derecha -->
                <div class="col-md-4 d-flex justify-content-center justify-content-md-end">
                    <img src="{{ asset('images/log-sena.svg') }}" alt="SENA" style="height: 42px; width: auto;">
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3.3 JavaScript Bundle Oficial -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script de Navegación Activa y Ubicación en Tiempo Real -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const navLinks = document.querySelectorAll('#navbarContent .nav-link[href^="#"]');
            const sections = [];
            
            navLinks.forEach(link => {
                const targetId = link.getAttribute('href');
                if (targetId && targetId !== '#') {
                    const section = document.querySelector(targetId);
                    if (section) {
                        sections.push({ id: targetId, section: section, link: link });
                    }
                }
            });

            function setActiveLink(activeLink) {
                navLinks.forEach(link => link.classList.remove('active'));
                if (activeLink) {
                    activeLink.classList.add('active');
                }
            }

            function updateActiveNav() {
                const scrollPos = window.scrollY + 130; // Altura de navbar + compensación de detección

                // Si el usuario llega al final de la página, activa la última sección
                if ((window.innerHeight + window.scrollY) >= (document.documentElement.scrollHeight - 50)) {
                    if (sections.length > 0) {
                        setActiveLink(sections[sections.length - 1].link);
                        return;
                    }
                }

                let current = null;
                for (let i = 0; i < sections.length; i++) {
                    const top = sections[i].section.offsetTop;
                    if (scrollPos >= top) {
                        current = sections[i].link;
                    }
                }

                // Si está arriba del todo, activar la primera (Inicio)
                if (!current && sections.length > 0) {
                    current = sections[0].link;
                }

                setActiveLink(current);
            }

            // Clics directos en los enlaces
            navLinks.forEach(link => {
                link.addEventListener('click', function () {
                    setActiveLink(this);
                    
                    // Cerrar automáticamente el menú hamburguesa en pantallas pequeñas
                    const navbarContent = document.getElementById('navbarContent');
                    if (navbarContent && navbarContent.classList.contains('show')) {
                        const bsCollapse = bootstrap.Collapse.getInstance(navbarContent);
                        if (bsCollapse) {
                            bsCollapse.hide();
                        }
                    }
                });
            });

            // Enlaces de llamada a la acción en el Hero
            document.querySelectorAll('a[href^="#"]:not(.nav-link)').forEach(ctaLink => {
                ctaLink.addEventListener('click', function () {
                    const targetHref = this.getAttribute('href');
                    const matchedNavLink = Array.from(navLinks).find(l => l.getAttribute('href') === targetHref);
                    if (matchedNavLink) {
                        setActiveLink(matchedNavLink);
                    }
                });
            });

            // Actualizar al cargar y escuchar scroll
            updateActiveNav();
            window.addEventListener('scroll', updateActiveNav, { passive: true });
            window.addEventListener('resize', updateActiveNav, { passive: true });
        });
    </script>
</body>
</html>
