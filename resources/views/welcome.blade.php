@php
    $telemetria = $telemetria ?? [
        'co2' => ['valor' => 540, 'unidad' => 'ppm', 'estado' => 'Óptimo'],
        'temperatura' => ['valor' => 24.5, 'unidad' => '°C'],
        'humedad' => ['valor' => 58, 'unidad' => '%'],
    ];

    $desarrolladores = $desarrolladores ?? [
        [
            'iniciales' => 'AD',
            'nombre' => 'Aprendiz ADSO 1',
            'rol' => 'Líder de Desarrollo IoT',
            'programa' => 'ADSO - Ficha 2670142',
            'especialidad' => 'Desarrollo Backend en Laravel y firmware ESP32 para sensores ambientales.',
            'github' => 'https://github.com/',
        ],
        [
            'iniciales' => 'FE',
            'nombre' => 'Aprendiz ADSO 2',
            'rol' => 'Desarrollador Frontend & UI',
            'programa' => 'ADSO - Ficha 2670142',
            'especialidad' => 'Diseño de interfaces web responsivas, componentes Bootstrap y experiencia de usuario.',
            'github' => 'https://github.com/',
        ],
        [
            'iniciales' => 'IA',
            'nombre' => 'Aprendiz ADSO 3',
            'rol' => 'Analítica de Datos & IA',
            'programa' => 'ADSO - Ficha 2670142',
            'especialidad' => 'Modelado de algoritmos predictivos de CO₂ y correlación climática.',
            'github' => 'https://github.com/',
        ],
        [
            'iniciales' => 'ST',
            'nombre' => 'Aprendiz ADSO 4',
            'rol' => 'Soporte Telemetría & Redes',
            'programa' => 'ADSO - Ficha 2670142',
            'especialidad' => 'Protocolos MQTT, seguridad de red y sincronización offline en aulas.',
            'github' => 'https://github.com/',
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
                        <a class="nav-link" href="#solucion">La Solución</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#funcionalidades">Funcionalidades</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#roles">Roles</a>
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
        <section id="hero" class="hero-section hero-fullscreen section-scroll">
            <div class="container d-flex flex-column justify-content-between flex-grow-1 pt-3 pb-3">
                
                <!-- Fila Superior: Contenido Principal y Diagrama -->
                <div class="row align-items-start align-items-lg-center g-4 mt-0 mt-lg-1 mb-auto">
                    
                    <!-- Columna Izquierda: Información Principal -->
                    <div class="col-lg-7 pt-lg-1">

                        <!-- Título Principal Institucional -->
                        <h1 class="display-2 fw-bold text-white mb-3">
                            Monitoreo inteligente <br>
                            <span class="text-sena">de calidad del aire</span>
                        </h1>

                        <!-- Subtítulo Aclaratorio -->
                        <p class="lead text-light opacity-90 mb-4" style="font-size: 1.15rem; max-width: 620px;">
                            Detectamos y predecimos en tiempo real la acumulación de <strong>CO₂</strong>, <strong>temperatura</strong> y <strong>humedad</strong> en las aulas y hangares del CEFA La Angostura para prevenir la fatiga y avisar con semáforos cuándo abrir las ventanas.
                        </p>
                    </div>

                    <!-- Columna Derecha: Diagrama Centrado con Ajuste Manual -->
                    <div class="col-lg-5 d-flex align-items-center justify-content-center text-center">
                        <div class="w-100" style="max-width: 480px;">
                            <img src="{{ asset('images/diagrama-sensores.svg') }}" 
                                 alt="Diagrama de Monitoreo: CO2, Temperatura y Humedad" 
                                 class="img-fluid mx-auto d-block"
                                 style="filter: drop-shadow(0 15px 30px rgba(0, 0, 0, 0.45));">
                        </div>
                    </div>

                </div>

                <!-- Fila Inferior Integrada: Tarjeta en 3 Pasos + Ticker + Scroll Down -->
                <div class="mt-4 pt-2">
                    
                    <!-- TARJETA EN 3 PASOS (Explicación Horizontal Inmediata) -->
                    <div class="card bg-white text-dark shadow-sm border-0 rounded-3 p-3 p-md-4 mb-3">
                        <div class="row g-3 text-center text-md-start align-items-center">
                            
                            <div class="col-md-4 d-flex align-items-center gap-3">
                                <span class="badge bg-sena rounded-circle p-2 fs-6 fw-bold" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">1</span>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Sensores IoT en aulas</h6>
                                    <small class="text-muted">Lecturas cada 60s en hangares y ambientes.</small>
                                </div>
                            </div>

                            <div class="col-md-4 d-flex align-items-center gap-3 border-start-md ps-md-3">
                                <span class="badge bg-sena rounded-circle p-2 fs-6 fw-bold" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">2</span>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Inteligencia Artificial</h6>
                                    <small class="text-muted">Anticipa picos de saturación 15 minutos antes.</small>
                                </div>
                            </div>

                            <div class="col-md-4 d-flex align-items-center gap-3 border-start-md ps-md-3">
                                <span class="badge bg-sena rounded-circle p-2 fs-6 fw-bold" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">3</span>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Semáforo & Alertas SST</h6>
                                    <small class="text-muted">Avisa el momento exacto para abrir ventanas.</small>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Ticker de Telemetría Inferior e Indicador de Scroll -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 text-white-50 small">
                        <div>
                            <i class="bi bi-activity text-sena me-1"></i> Promedio institucional CEFA:
                            <span class="text-white fw-bold ms-1">{{ $telemetria['co2']['valor'] ?? 540 }} {{ $telemetria['co2']['unidad'] ?? 'ppm' }}</span> <span class="text-sena">({{ $telemetria['co2']['estado'] ?? 'Óptimo' }})</span> &bull;
                            <span class="text-white fw-bold ms-1">{{ $telemetria['temperatura']['valor'] ?? 24.5 }} {{ $telemetria['temperatura']['unidad'] ?? '°C' }}</span> &bull;
                            <span class="text-white fw-bold ms-1">{{ $telemetria['humedad']['valor'] ?? 58 }} {{ $telemetria['humedad']['unidad'] ?? '%' }}</span>
                        </div>
                        <div>
                            <a href="#solucion" class="text-white-50 text-decoration-none d-inline-flex align-items-center gap-1 hover-white bounce-scroll">
                                <span>Desliza para explorar</span>
                                <i class="bi bi-chevron-down"></i>
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </section>

        <!-- =========================================================================
             2. SECCIÓN "LA SOLUCIÓN"
             ========================================================================= -->
        <section id="solucion" class="py-5 bg-light section-scroll border-bottom">
            <div class="container py-4">
                
                <div class="text-center mx-auto mb-5" style="max-width: 768px;">
                    <span class="text-uppercase fw-bold text-sena small tracking-wider d-block mb-1">
                        Nuestra Solución
                    </span>
                    <h2 class="display-6 fw-bold text-sena-dark mb-2">
                        Tecnología que cuida nuestra salud
                    </h2>
                    <p class="text-secondary small">Un ecosistema de hardware IoT, Inteligencia Artificial y panel web institucional.</p>
                </div>

                <div class="row g-4">
                    
                    <div class="col-md-4">
                        <div class="card card-sena h-100 border-0 shadow-sm p-4 bg-white">
                            <span class="badge bg-primary rounded-pill mb-3" style="width: fit-content;">Paso 01</span>
                            <h5 class="fw-bold text-dark mb-2">Mide</h5>
                            <p class="text-secondary small leading-relaxed mb-3">
                                Sensores de alta precisión capturan los niveles de aire, temperatura y humedad cada minuto de forma autónoma.
                            </p>
                            <small class="text-muted border-top pt-2 d-block">
                                <strong>Hardware:</strong> ESP32 &bull; Sensores MH-Z19B &bull; DHT22
                            </small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-sena h-100 border-0 shadow-sm p-4 bg-white">
                            <span class="badge bg-sena rounded-pill mb-3" style="width: fit-content;">Paso 02</span>
                            <h5 class="fw-bold text-dark mb-2">Analiza</h5>
                            <p class="text-secondary small leading-relaxed mb-3">
                                Nuestra plataforma procesa los datos y aplica Inteligencia Artificial para predecir cuándo el aire será dañino.
                            </p>
                            <small class="text-muted border-top pt-2 d-block">
                                <strong>Software:</strong> Backend Laravel &bull; Modelos IA
                            </small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-sena h-100 border-0 shadow-sm p-4 bg-white">
                            <span class="badge bg-danger rounded-pill mb-3" style="width: fit-content;">Paso 03</span>
                            <h5 class="fw-bold text-dark mb-2">Alerta</h5>
                            <p class="text-secondary small leading-relaxed mb-3">
                                Un sistema visual tipo semáforo y correos automáticos avisan al instante para tomar medidas como abrir ventanas.
                            </p>
                            <small class="text-muted border-top pt-2 d-block">
                                <strong>Prevención:</strong> Notificaciones SST &bull; Semáforo en vivo
                            </small>
                        </div>
                    </div>

                </div>

            </div>
        </section>

        <!-- =========================================================================
             3. SECCIÓN "FUNCIONALIDADES PRINCIPALES"
             ========================================================================= -->
        <section id="funcionalidades" class="py-5 bg-white section-scroll border-bottom">
            <div class="container py-4">
                
                <div class="text-center mx-auto mb-5" style="max-width: 768px;">
                    <span class="text-uppercase fw-bold text-sena small tracking-wider d-block mb-1">
                        Funcionalidades Principales
                    </span>
                    <h2 class="display-6 fw-bold text-sena-dark mb-2">
                        Todo lo que necesitas para un CEFA más saludable
                    </h2>
                    <p class="text-secondary small">Herramientas diseñadas para instructores, aprendices y seguridad ocupacional.</p>
                </div>

                <div class="row g-4">
                    
                    <!-- Card 1 -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 card-sena border shadow-sm p-4 bg-light">
                            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3 p-3 mb-3 fs-3" style="width: 52px; height: 52px;">
                                <i class="bi bi-map"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">Mapa interactivo</h5>
                            <p class="card-text text-secondary small leading-relaxed">
                                Visualiza el estado de cada ambiente de formación sobre un plano digital del CEFA con semáforo en tiempo real.
                            </p>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 card-sena border shadow-sm p-4 bg-light">
                            <div class="d-inline-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-3 p-3 mb-3 fs-3" style="width: 52px; height: 52px;">
                                <i class="bi bi-bell"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">Alertas duales</h5>
                            <p class="card-text text-secondary small leading-relaxed">
                                Notificaciones visuales en el panel y correo institucional automático cuando el CO₂ supera el umbral crítico.
                            </p>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 card-sena border shadow-sm p-4 bg-light">
                            <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-3 p-3 mb-3 fs-3" style="width: 52px; height: 52px;">
                                <i class="bi bi-cpu"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">Predicción con IA</h5>
                            <p class="card-text text-secondary small leading-relaxed">
                                El módulo predictivo anticipa hasta 15 minutos antes cuándo el aire se deteriorará, sugiriendo acciones preventivas.
                            </p>
                        </div>
                    </div>

                    <!-- Card 4 -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 card-sena border shadow-sm p-4 bg-light">
                            <div class="d-inline-flex align-items-center justify-content-center bg-info bg-opacity-10 text-info rounded-3 p-3 mb-3 fs-3" style="width: 52px; height: 52px;">
                                <i class="bi bi-file-earmark-bar-graph"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">Histórico exportable</h5>
                            <p class="card-text text-secondary small leading-relaxed">
                                Consulta registros de hasta un año académico y exporta reportes protegidos en Excel o PDF para auditorías.
                            </p>
                        </div>
                    </div>

                    <!-- Card 5 -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 card-sena border shadow-sm p-4 bg-light">
                            <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning rounded-3 p-3 mb-3 fs-3" style="width: 52px; height: 52px;">
                                <i class="bi bi-hdd-network"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">Sensores IoT ESP32</h5>
                            <p class="card-text text-secondary small leading-relaxed">
                                Nodos autónomos con modo offline que almacenan lecturas durante pérdidas de señal Wi-Fi y sincronizan al reconectarse.
                            </p>
                        </div>
                    </div>

                    <!-- Card 6 -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 card-sena border shadow-sm p-4 bg-light">
                            <div class="d-inline-flex align-items-center justify-content-center bg-sena-subtle text-sena rounded-3 p-3 mb-3 fs-3" style="width: 52px; height: 52px;">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">Seguridad y RBAC</h5>
                            <p class="card-text text-secondary small leading-relaxed">
                                Control de acceso por rol, log de auditoría completo y cierre de sesión automático por inactividad según el perfil.
                            </p>
                        </div>
                    </div>

                </div>

            </div>
        </section>

        <!-- =========================================================================
             4. SECCIÓN "ROLES DE USUARIO"
             ========================================================================= -->
        <section id="roles" class="py-5 bg-light section-scroll border-bottom">
            <div class="container py-4">
                
                <div class="text-center mx-auto mb-5" style="max-width: 768px;">
                    <span class="text-uppercase fw-bold text-sena small tracking-wider d-block mb-1">
                        Roles de Usuario
                    </span>
                    <h2 class="display-6 fw-bold text-sena-dark mb-2">
                        Diseñado para cada integrante de la comunidad
                    </h2>
                    <p class="text-secondary small">Perfiles personalizados para una gestión eficiente y oportuna.</p>
                </div>

                <div class="row g-4">
                    
                    <div class="col-md-4">
                        <div class="card card-sena h-100 p-4 border bg-white shadow-sm">
                            <div class="fs-2 text-primary mb-2"><i class="bi bi-gear-wide-connected"></i></div>
                            <h5 class="fw-bold text-dark mb-2">Administradores</h5>
                            <p class="text-secondary small leading-relaxed mb-3">
                                Control total del sistema, gestión de usuarios, definición de límites de alerta y visualización del mapa completo del CEFA.
                            </p>
                            <ul class="list-unstyled small text-muted border-top pt-2 mb-0">
                                <li><i class="bi bi-check2 text-sena me-1"></i> Control de acceso y roles</li>
                                <li><i class="bi bi-check2 text-sena me-1"></i> Configuración de nodos IoT</li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-sena h-100 p-4 border bg-white shadow-sm">
                            <div class="fs-2 text-sena mb-2"><i class="bi bi-shield-check"></i></div>
                            <h5 class="fw-bold text-dark mb-2">Funcionarios SST</h5>
                            <p class="text-secondary small leading-relaxed mb-3">
                                Herramienta preventiva y reactiva. Reciben alertas críticas y pueden observar el estado general para actuar rápido.
                            </p>
                            <ul class="list-unstyled small text-muted border-top pt-2 mb-0">
                                <li><i class="bi bi-check2 text-sena me-1"></i> Alertas críticas al correo</li>
                                <li><i class="bi bi-check2 text-sena me-1"></i> Reportes exportables para auditoría</li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-sena h-100 p-4 border bg-white shadow-sm">
                            <div class="fs-2 text-info mb-2"><i class="bi bi-person-workspace"></i></div>
                            <h5 class="fw-bold text-dark mb-2">Instructores</h5>
                            <p class="text-secondary small leading-relaxed mb-3">
                                Un panel sencillo para ver el estado de su salón, registrar el número de aprendices (aforo) y saber cuándo ventilar.
                            </p>
                            <ul class="list-unstyled small text-muted border-top pt-2 mb-0">
                                <li><i class="bi bi-check2 text-sena me-1"></i> Semáforo visual en el aula</li>
                                <li><i class="bi bi-check2 text-sena me-1"></i> Registro fácil de aforo</li>
                            </ul>
                        </div>
                    </div>

                </div>

            </div>
        </section>

        <!-- =========================================================================
             5. SECCIÓN "EQUIPO DESARROLLADOR" (ADSO SENA La Angostura)
             ========================================================================= -->
        <section id="equipo" class="py-5 bg-white section-scroll border-bottom">
            <div class="container py-4">
                
                <div class="text-center mx-auto mb-5" style="max-width: 768px;">
                    <span class="text-uppercase fw-bold text-sena small tracking-wider d-block mb-1">
                        Equipo Desarrollador
                    </span>
                    <h2 class="display-6 fw-bold text-sena-dark mb-2">
                        Creado por Aprendices del SENA
                    </h2>
                    <p class="text-secondary small">Centro de Formación Agroindustrial La Angostura &bull; Regional Huila</p>
                </div>

                <div class="row g-4">
                    @foreach ($desarrolladores as $dev)
                        @php
                            $devObj = (object) $dev;
                        @endphp
                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sena h-100 border shadow-sm p-4 bg-white text-center d-flex flex-column justify-content-between">
                                <div>
                                    <!-- Avatar Institucional con Iniciales -->
                                    <div class="rounded-circle bg-sena-dark text-white d-inline-flex align-items-center justify-content-center mx-auto mb-3 shadow-sm" style="width: 64px; height: 64px; font-size: 1.25rem; font-weight: bold;">
                                        {{ $devObj->iniciales ?? 'AD' }}
                                    </div>

                                    <!-- Nombre del Aprendiz -->
                                    <h6 class="fw-bold text-dark mb-1">{{ $devObj->nombre ?? 'Aprendiz' }}</h6>

                                    <!-- Rol en el Proyecto -->
                                    <span class="text-sena fw-semibold small d-block mb-2">{{ $devObj->rol ?? 'Desarrollador' }}</span>

                                    <!-- Badge del Programa Académico -->
                                    <div class="mb-3">
                                        <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.65rem;">
                                            {{ $devObj->programa ?? 'ADSO' }}
                                        </span>
                                    </div>

                                    <!-- Especialidad -->
                                    <p class="text-secondary small leading-relaxed mb-3" style="font-size: 0.8rem;">
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
         PIE DE PÁGINA INSTITUCIONAL SENA LA ANGOSTURA
         ========================================================================= -->
    <footer class="bg-sena-dark text-white py-5 mt-auto">
        <div class="container">
            <div class="row g-4 mb-4">
                
                <!-- Columna 1: Datos del Proyecto -->
                <div class="col-lg-5">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="fs-4 fw-bold text-white">AirSense CEFA</span>
                        <span class="badge bg-sena">SENA</span>
                    </div>
                    <p class="text-white-50 small pe-lg-4 leading-relaxed">
                        Sistema integral de monitoreo ambiental inteligente. Mide y predice en tiempo real la acumulación de dióxido de carbono (CO₂), temperatura y humedad para garantizar ambientes pedagógicos seguros y saludables.
                    </p>
                    <div class="small text-white-50 d-flex align-items-center gap-2">
                        <i class="bi bi-geo-alt-fill text-sena"></i>
                        <span>Centro de Formación Agroindustrial La Angostura &bull; SENA Regional Huila</span>
                    </div>
                </div> 

                <!-- Columna 2: Enlaces Rápidos -->
                <div class="col-6 col-lg-3">
                    <h6 class="text-uppercase fw-bold text-white small tracking-wider mb-3">Navegación</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="#hero" class="text-white-50 text-decoration-none">Inicio</a></li>
                        <li class="mb-2"><a href="#solucion" class="text-white-50 text-decoration-none">La Solución</a></li>
                        <li class="mb-2"><a href="#funcionalidades" class="text-white-50 text-decoration-none">Funcionalidades</a></li>
                        <li class="mb-2"><a href="#roles" class="text-white-50 text-decoration-none">Roles de Usuario</a></li>
                        <li class="mb-2"><a href="#equipo" class="text-white-50 text-decoration-none">Equipo Desarrollador</a></li>
                    </ul>
                </div>

                <!-- Columna 3: Información Formativa -->
                <div class="col-6 col-lg-4">
                    <h6 class="text-uppercase fw-bold text-white small tracking-wider mb-3">Programa Académico</h6>
                    <p class="small text-sena fw-bold mb-1">
                        TGO. Análisis y Desarrollo de Software (ADSO)
                    </p>
                    <p class="small text-white-50 mb-3">
                        Proyecto formativo institucional orientado a la salud ocupacional, prevención de riesgos ambientales y telemetría IoT.
                    </p>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-speedometer2 me-1"></i> Panel de Control
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-shield-lock me-1"></i> Panel Administrativo
                            </a>
                        @endauth
                    @endif
                </div>

            </div>

            <!-- Separador y Copyright -->
            <div class="border-top border-secondary border-opacity-25 pt-4 d-flex flex-column flex-md-row justify-content-between align-items-center small text-white-50">
                <p class="mb-2 mb-md-0">&copy; {{ date('Y') }} AirSense CEFA &bull; Servicio Nacional de Aprendizaje (SENA)</p>
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
