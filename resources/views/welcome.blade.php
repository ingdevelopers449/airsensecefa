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
            'rol' => 'Desarrollador Frontend & UI',
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

    <!-- Bootstrap 5.3.3 CSS Oficial -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons Oficial -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Fuentes de Google: Inter (La mejor para dashboards y tecnología) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Estilos Institucionales SENA -->
    @vite(['resources/css/styles.css'])
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

                <!-- Botones Iniciar Sesión / Registro / Dashboard -->
                <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-sena px-4 py-2 rounded-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm">
                                <i class="bi bi-speedometer2"></i>
                                <span>Dashboard</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-sena px-3 py-2 rounded-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm">
                                <i class="bi bi-box-arrow-in-right"></i>
                                <span>Iniciar sesión</span>
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-outline-secondary px-3 py-2 rounded-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm">
                                    <i class="bi bi-person-plus"></i>
                                    <span>Registro</span>
                                </a>
                            @endif
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
        <section id="hero" class="hero-section hero-fullscreen section-scroll" style="position: relative; overflow: hidden;">

            <!-- Imagen independiente: Centrada verticalmente en el lado derecho -->
            <!-- ↔ right:  4%=muy derecha | 12%=centro-derecha | 25%=más al centro -->
            <!-- ↕ top:   40%=más arriba  | 50%=centrada       | 60%=más abajo    -->
            <div class="d-none d-lg-block" style="position: absolute; right: 8%; top: 50%; transform: translateY(-45%); width: 60%; max-width: 600px; z-index: 1;">
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
                        <!-- Pantallas grandes: 6rem (ahora cabe en 2 renglones porque la columna es más ancha) -->
                        <h1 class="fw-bold text-white mb-5 d-none d-md-block" style="font-size: 6rem; line-height: 1.1; text-shadow: 0 4px 12px rgba(0,0,0,0.4);">
                            Monitoreo inteligente <br>
                            <span class="text-sena">de calidad del aire</span>
                        </h1>
                        <!-- Pantallas pequeñas (móviles) -->
                        <h1 class="fw-bold text-white mb-4 d-block d-md-none" style="font-size: 3rem; line-height: 1.15;">
                            Monitoreo inteligente <br>
                            <span class="text-sena">de calidad del aire</span>
                        </h1>

                        <!-- Subtítulo Aclaratorio -->
                        <p class="lead text-light opacity-90 mb-4 mt-10 pt-3" style="font-size: 1.25rem; max-width: 650px; line-height: 1.6;">
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
                            <div class="card bg-white text-dark shadow-sm border-0 rounded-3 p-3 p-md-4 h-100">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge bg-sena rounded-circle p-2 fs-5 fw-bold flex-shrink-0" style="width: 44px; height: 44px; display: inline-flex; align-items: center; justify-content: center;">1</span>
                                    <div>
                                        <h5 class="fw-bold text-dark mb-1" style="font-size: 1.15rem;">Sensores IoT en aulas</h5>
                                        <div class="text-muted" style="font-size: 0.95rem; line-height: 1.4;">Lecturas cada 60s en hangares y ambientes.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TARJETA 2 -->
                        <div class="col-md-4">
                            <div class="card bg-white text-dark shadow-sm border-0 rounded-3 p-3 p-md-4 h-100">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge bg-sena rounded-circle p-2 fs-5 fw-bold flex-shrink-0" style="width: 44px; height: 44px; display: inline-flex; align-items: center; justify-content: center;">2</span>
                                    <div>
                                        <h5 class="fw-bold text-dark mb-1" style="font-size: 1.15rem;">Inteligencia Artificial</h5>
                                        <div class="text-muted" style="font-size: 0.95rem; line-height: 1.4;">Anticipa picos de saturación 15 minutos antes.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TARJETA 3 -->
                        <div class="col-md-4">
                            <div class="card bg-white text-dark shadow-sm border-0 rounded-3 p-3 p-md-4 h-100">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge bg-sena rounded-circle p-2 fs-5 fw-bold flex-shrink-0" style="width: 44px; height: 44px; display: inline-flex; align-items: center; justify-content: center;">3</span>
                                    <div>
                                        <h5 class="fw-bold text-dark mb-1" style="font-size: 1.15rem;">Semáforo & Alertas SST</h5>
                                        <div class="text-muted" style="font-size: 0.95rem; line-height: 1.4;">Avisa el momento exacto para abrir ventanas.</div>
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
        <section id="funcionalidades" class="bg-white section-scroll border-bottom d-flex align-items-center py-3" style="min-height: calc(100vh - 74px);">
            <div class="container w-100">
                
                <div class="text-center mx-auto mb-4 mt-2" style="max-width: 768px;">
                    <span class="text-uppercase fw-bold text-sena small tracking-wider d-block mb-1">
                        Funcionalidades Principales
                    </span>
                    <h2 class="display-6 fw-bold text-sena-dark mb-2">
                        Todo lo que necesitas para un CEFA más saludable
                    </h2>
                    <p class="text-secondary small">Herramientas diseñadas para instructores, aprendices y seguridad ocupacional.</p>
                </div>

                <div class="row g-3">
                    
                    <!-- Card 1 -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm p-3 bg-white d-flex flex-column" style="border-radius: 1rem; border: 1px solid rgba(0,0,0,0.05) !important;">
                            <!-- Icono estilo 3D SENA -->
                            <div class="d-inline-flex align-items-center justify-content-center rounded-3 mb-2" style="width: 44px; height: 44px; background-color: #dcfce7;">
                                <span style="font-size: 24px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.15));">🗺️</span>
                            </div>
                            <h5 class="fw-bold mb-2 fs-6" style="color: #00324b; line-height: 1.4;">Mapa interactivo</h5>
                            <p class="card-text text-secondary mb-3" style="font-size: 0.85rem; line-height: 1.35;">
                                Visualiza el estado de cada ambiente de formación sobre un plano digital del CEFA con semáforo en tiempo real.
                            </p>
                            <div class="mt-auto text-end">
                                <a href="#funcionalidades" class="btn btn-sena rounded-circle d-inline-flex align-items-center justify-content-center p-0 hover-lift" style="width: 28px; height: 28px;">
                                    <i class="bi bi-arrow-right text-white"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm p-3 bg-white d-flex flex-column" style="border-radius: 1rem; border: 1px solid rgba(0,0,0,0.05) !important;">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-3 mb-2" style="width: 44px; height: 44px; background-color: #dcfce7;">
                                <span style="font-size: 24px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.15));">🚨</span>
                            </div>
                            <h5 class="fw-bold mb-2 fs-6" style="color: #00324b; line-height: 1.4;">Alertas duales</h5>
                            <p class="card-text text-secondary mb-3" style="font-size: 0.85rem; line-height: 1.35;">
                                Notificaciones visuales en el panel y correo institucional automático cuando el CO₂ supera el umbral crítico.
                            </p>
                            <div class="mt-auto text-end">
                                <a href="#funcionalidades" class="btn btn-sena rounded-circle d-inline-flex align-items-center justify-content-center p-0 hover-lift" style="width: 28px; height: 28px;">
                                    <i class="bi bi-arrow-right text-white"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm p-3 bg-white d-flex flex-column" style="border-radius: 1rem; border: 1px solid rgba(0,0,0,0.05) !important;">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-3 mb-2" style="width: 44px; height: 44px; background-color: #dcfce7;">
                                <span style="font-size: 24px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.15));">🧠</span>
                            </div>
                            <h5 class="fw-bold mb-2 fs-6" style="color: #00324b; line-height: 1.4;">Predicción con IA</h5>
                            <p class="card-text text-secondary mb-3" style="font-size: 0.85rem; line-height: 1.35;">
                                El módulo predictivo anticipa hasta 15 minutos antes cuándo el aire se deteriorará, sugiriendo acciones preventivas.
                            </p>
                            <div class="mt-auto text-end">
                                <a href="#funcionalidades" class="btn btn-sena rounded-circle d-inline-flex align-items-center justify-content-center p-0 hover-lift" style="width: 28px; height: 28px;">
                                    <i class="bi bi-arrow-right text-white"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4 -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm p-3 bg-white d-flex flex-column" style="border-radius: 1rem; border: 1px solid rgba(0,0,0,0.05) !important;">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-3 mb-2" style="width: 44px; height: 44px; background-color: #dcfce7;">
                                <span style="font-size: 24px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.15));">📊</span>
                            </div>
                            <h5 class="fw-bold mb-2 fs-6" style="color: #00324b; line-height: 1.4;">Histórico exportable</h5>
                            <p class="card-text text-secondary mb-3" style="font-size: 0.85rem; line-height: 1.35;">
                                Consulta registros de hasta un año académico y exporta reportes protegidos en Excel o PDF para auditorías.
                            </p>
                            <div class="mt-auto text-end">
                                <a href="#funcionalidades" class="btn btn-sena rounded-circle d-inline-flex align-items-center justify-content-center p-0 hover-lift" style="width: 28px; height: 28px;">
                                    <i class="bi bi-arrow-right text-white"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Card 5 -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm p-3 bg-white d-flex flex-column" style="border-radius: 1rem; border: 1px solid rgba(0,0,0,0.05) !important;">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-3 mb-2" style="width: 44px; height: 44px; background-color: #dcfce7;">
                                <span style="font-size: 24px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.15));">📡</span>
                            </div>
                            <h5 class="fw-bold mb-2 fs-6" style="color: #00324b; line-height: 1.4;">Sensores IoT ESP32</h5>
                            <p class="card-text text-secondary mb-3" style="font-size: 0.85rem; line-height: 1.35;">
                                Nodos autónomos con modo offline que almacenan lecturas durante pérdidas de señal Wi-Fi y sincronizan al reconectarse.
                            </p>
                            <div class="mt-auto text-end">
                                <a href="#funcionalidades" class="btn btn-sena rounded-circle d-inline-flex align-items-center justify-content-center p-0 hover-lift" style="width: 28px; height: 28px;">
                                    <i class="bi bi-arrow-right text-white"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Card 6 -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm p-3 bg-white d-flex flex-column" style="border-radius: 1rem; border: 1px solid rgba(0,0,0,0.05) !important;">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-3 mb-2" style="width: 44px; height: 44px; background-color: #dcfce7;">
                                <span style="font-size: 24px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.15));">🔐</span>
                            </div>
                            <h5 class="fw-bold mb-2 fs-6" style="color: #00324b; line-height: 1.4;">Seguridad y RBAC</h5>
                            <p class="card-text text-secondary mb-3" style="font-size: 0.85rem; line-height: 1.35;">
                                Control de acceso por rol, log de auditoría completo y cierre de sesión automático por inactividad según el perfil.
                            </p>
                            <div class="mt-auto text-end">
                                <a href="#funcionalidades" class="btn btn-sena rounded-circle d-inline-flex align-items-center justify-content-center p-0 hover-lift" style="width: 28px; height: 28px;">
                                    <i class="bi bi-arrow-right text-white"></i>
                                </a>
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
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <!-- Logos Izquierda -->
            <div class="d-flex align-items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 36px; width: auto;">
                <img src="{{ asset('images/airsense-texto-blanco.svg') }}" alt="AirSense CEFA" style="height: 20px; width: auto; transform: translateY(2px);">
            </div>
            
            <!-- Derechos y Contacto -->
            <div class="text-center text-white-50 small fw-medium" style="font-size: 0.8rem; line-height: 1.4;">
                &copy; {{ date('Y') }} <span class="text-white fw-bold">AirSense CEFA</span>. Todos los derechos reservados.<br>
                Centro de Formación Agroindustrial La Angostura &bull; SENA Regional Huila
            </div>
            
            <!-- Logo SENA Derecha -->
            <div>
                <img src="{{ asset('images/log-sena.svg') }}" alt="SENA" style="height: 42px; width: auto;">
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
