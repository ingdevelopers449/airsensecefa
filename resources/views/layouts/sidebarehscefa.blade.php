<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>AirSense CEFA - Panel de EHS</title>

    <!-- Bootstrap 5.3.3 CSS Oficial & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Google Fonts: Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome & SweetAlert2 -->
    <script src="https://kit.fontawesome.com/dcb1bbced2.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Scripts / Styles -->
    @vite(['resources/css/app.css', 'resources/css/styles.css', 'resources/js/app.js'])
    
    <!-- AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @yield('css')
</head>

<body class="h-full antialiased text-slate-800 bg-slate-50 selection:bg-sena-green selection:text-white" x-data="{ sidebarOpen: false }">

    <div class="min-h-screen flex flex-col lg:flex-row bg-slate-50">
        <!-- Sidebar Navigation Executive SENA Midnight -->
        <aside class="w-full lg:w-64 bg-[#002235] text-slate-100 flex-shrink-0 flex flex-col justify-between border-r border-[#003B5C]/60 lg:fixed lg:inset-y-0 lg:left-0 lg:z-40 shadow-xl">
            <div>
                <!-- Brand Header -->
                <div class="h-16 px-4 flex items-center justify-between bg-[#001522] border-b border-[#003B5C]/70">
                    <a href="{{ route('ehscefa.dashboard') }}" class="flex items-center gap-2.5 text-decoration-none">
                        <!-- Isotipo AirSense -->
                        <img src="{{ asset('images/logo.png') }}" alt="Logo AirSense" class="h-8 w-auto object-contain">
                        <div class="flex flex-col">
                            <!-- Logo de Texto Blanco -->
                            <img src="{{ asset('images/airsense-texto-blanco.svg') }}" alt="AirSense CEFA" class="h-3.5 w-auto object-contain">
                            <span class="text-[8.5px] text-[#39A900] font-bold tracking-wider uppercase mt-0.5">PANEL EHS</span>
                        </div>
                    </a>
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-slate-300 hover:text-white focus:outline-none p-1.5 rounded-lg hover:bg-white/10">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                </div>

                <!-- Nav Menu -->
                <div class="px-3 py-4 space-y-5 overflow-y-auto max-h-[calc(100vh-128px)]" :class="{ 'block': sidebarOpen, 'hidden lg:block': !sidebarOpen }">
                    
                    <!-- Dashboard -->
                    <div>
                        <a href="{{ route('ehscefa.dashboard') }}" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 text-decoration-none {{ request()->routeIs('ehscefa.dashboard') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <i class="fas fa-chart-pie text-base w-4 text-center text-[#39A900]"></i>
                            <span>Dashboard Monitoreo</span>
                        </a>
                    </div>

                    <!-- GESTIÓN DE CONTINGENCIAS Y PROTOCOLOS SST -->
                    <div class="space-y-1">
                        <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Seguridad & Salud (SST)</p>

                        <a href="{{ route('ehscefa.contingencias.index') }}" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-[13.5px] font-semibold text-decoration-none transition-all duration-150 {{ request()->routeIs('ehscefa.contingencias.*') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <i class="fas fa-book-medical w-4 text-center text-[#39A900]"></i>
                            <span>Manual de Contingencia</span>
                        </a>

                        <a href="" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-[13.5px] font-semibold text-decoration-none transition-all duration-150 {{ request()->routeIs('ehscefa.alertas.*') ? 'bg-rose-400/20 text-rose-400 font-bold border-l-4 border-rose-400' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <span class="flex items-center gap-2.5">
                                <i class="fas fa-bell text-rose-400 w-4 text-center"></i>
                                <span>Alertas Críticas</span>
                            </span>
                        </a>
                    </div>

                    <!-- GESTIÓN DE NODOS IoT & UBIACIONES -->
                    <div class="space-y-1">
                        <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Infraestructura & Mapa</p>

                        <a href="" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-[13.5px] font-semibold text-decoration-none transition-all duration-150 {{ request()->routeIs('ehscefa.nodos.*') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <i class="fas fa-map-marked-alt w-4 text-center text-[#39A900]"></i>
                            <span>Nodos IoT en Mapa</span>
                        </a>

                        <a href="{{ route('ehscefa.hardware.nodo') }}" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-[13.5px] font-semibold text-decoration-none transition-all duration-150 {{ request()->routeIs('ehscefa.hardware.*') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <i class="fas fa-wifi w-4 text-center text-[#39A900]"></i>
                            <span>Estado de Hardware</span>
                        </a>
                    </div>

                    <!-- HISTORIALES Y REPORTES EPIDEMIOLÓGICOS -->
                    <div class="space-y-1">
                        <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Auditoría Epidemiológica</p>

                        <a href="{{ route('ehscefa.historico.index') }}" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-[13.5px] font-semibold text-decoration-none transition-all duration-150 {{ request()->routeIs('ehscefa.historico.*') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <i class="fas fa-calendar-alt w-4 text-center text-[#39A900]"></i>
                            <span>Historial de Aire (1 Año)</span>
                        </a>

                        <a href="{{ route('ehscefa.reportes.index') }}" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-[13.5px] font-semibold text-decoration-none transition-all duration-150 {{ request()->routeIs('ehscefa.reportes.*') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <i class="fas fa-file-export w-4 text-center text-[#39A900]"></i>
                            <span>Reportes Protegidos</span>
                        </a>
                    </div>

                    <!-- ANALÍTICA PREDICTIVA -->
                    <div class="space-y-1">
                        <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Modelación & Predicción</p>

                        <a href="{{ route('ehscefa.predictivo.index') }}" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-[13.5px] font-semibold text-decoration-none transition-all duration-150 {{ request()->routeIs('ehscefa.predictivo.*') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <i class="fas fa-brain w-4 text-center text-[#39A900]"></i>
                            <span>Análisis Predictivo (IA)</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer User Badge - Bootstrap 5 Clean -->
            <div class="p-3 border-top border-secondary-subtle" style="background-color: #001522;">
                <div class="d-flex align-items-center justify-content-between p-2 rounded-3 border" style="background-color: #002237; border-color: rgba(255,255,255,0.1) !important;">
                    <div class="d-flex align-items-center gap-2 overflow-hidden">
                        <div class="position-relative flex-shrink-0">
                            <div class="rounded-3 text-white d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 36px; height: 36px; background-color: #39A900; font-size: 14px;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-dark rounded-circle" title="Sesión activa">
                                <span class="visually-hidden">En línea</span>
                            </span>
                        </div>
                        <div class="lh-sm overflow-hidden">
                            <div class="text-white fw-bold text-truncate" style="font-size: 13px;">{{ Auth::user()->name }}</div>
                            <div class="fw-semibold text-truncate" style="font-size: 11px; color: #86efac;">Especialista EHS & SST</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-1 ms-1">
                        <a href="{{ route('profile.edit') }}" class="btn btn-sm text-white-50 hover-text-white p-1" title="Configurar Perfil">
                            <i class="bi bi-gear-fill" style="font-size: 14px;"></i>
                        </a>
                        <button type="button" onclick="confirmarCierreSesion()" class="btn btn-sm text-white-50 hover-text-danger p-1" title="Cerrar Sesión">
                            <i class="bi bi-box-arrow-right text-rose-400" style="font-size: 14px;"></i>
                        </button>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Canvas -->
        <div class="flex-1 flex flex-col min-w-0 lg:ml-64 w-full bg-slate-50">
            <!-- Header Navbar Sticky -->
            <header class="h-16 bg-white border-b border-slate-200 px-4 px-lg-6 flex items-center justify-between sticky top-0 z-30 shadow-sm">
                <div class="flex items-center gap-3">
                    <h1 class="text-lg font-bold text-slate-800 font-heading hidden sm:block m-0">@yield('tituloPagina', 'Dashboard Principal')</h1>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 rounded-pill font-semibold">
                        <i class="bi bi-circle-fill text-success me-1" style="font-size: 8px;"></i>
                        Sistema En Línea
                    </span>
                </div>

                <!-- User Profile Dropdown (Bootstrap 5 Clean) -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" type="button" class="btn btn-light bg-white border border-secondary-subtle rounded-pill px-3 py-1.5 d-flex align-items-center gap-2 shadow-sm focus-ring">
                        <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold" style="width: 30px; height: 30px; background-color: #003B5C; font-size: 12px;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="text-start d-none d-sm-block lh-1">
                            <div class="fw-bold text-dark" style="font-size: 13px;">{{ Auth::user()->name }}</div>
                            <small class="text-muted" style="font-size: 10.5px;">EHS & Salud Ocupacional</small>
                        </div>
                        <i class="bi bi-chevron-down text-muted ms-1" style="font-size: 10px;"></i>
                    </button>

                    <div x-show="open" @click.away="open = false" x-cloak
                         class="position-absolute end-0 mt-2 bg-white rounded-3 shadow-lg border border-secondary-subtle p-2 z-30" style="width: 250px;">
                        
                        <div class="p-2.5 bg-light rounded-2 mb-1 border border-secondary-subtle">
                            <div class="fw-bold text-dark text-truncate" style="font-size: 13px;">{{ Auth::user()->name }}</div>
                            <div class="text-muted text-truncate" style="font-size: 11.5px;">{{ Auth::user()->email }}</div>
                            <span class="badge bg-success-subtle text-success border border-success-subtle mt-1.5 fw-semibold">
                                <i class="bi bi-shield-check me-1"></i> Auditor EHS Autorizado
                            </span>
                        </div>

                        <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-2.5 rounded-2 text-secondary fw-semibold" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person-gear text-secondary"></i> Mi Perfil
                        </a>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-2.5 rounded-2 text-secondary fw-semibold" href="{{ url('/') }}" target="_blank">
                            <i class="bi bi-box-arrow-up-right text-secondary"></i> Ver Sitio Web
                        </a>

                        <div class="dropdown-divider my-1"></div>

                        <button type="button" onclick="confirmarCierreSesion()" class="dropdown-item d-flex align-items-center gap-2 py-2 px-2.5 rounded-2 text-danger fw-bold bg-transparent border-0">
                            <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                        </button>
                    </div>
                </div>
            </header>

            <!-- Formulario oculto de Cierre de Sesión -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>

            <!-- Alert Toast Notifications & Content Canvas -->
            <main class="flex-1 p-4 p-lg-6 max-w-7xl w-full mx-auto">
                @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center justify-content-between rounded-4 shadow-sm mb-4" role="alert">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-center p-2" style="width: 32px; height: 32px;">
                                <i class="fas fa-check text-xs"></i>
                            </div>
                            <span class="fw-medium small">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger d-flex align-items-center justify-content-between rounded-4 shadow-sm mb-4" role="alert">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center p-2" style="width: 32px; height: 32px;">
                                <i class="fas fa-exclamation-triangle text-xs"></i>
                            </div>
                            <span class="fw-medium small">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script de Confirmación SweetAlert2 para Cierre de Sesión -->
    <script>
        function confirmarCierreSesion() {
            Swal.fire({
                title: '¿Cerrar sesión en AirSense?',
                text: 'Se cerrará tu sesión activa y tendrás que volver a autenticarte.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-box-arrow-right me-1"></i> Sí, cerrar sesión',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-4 shadow-lg border-0',
                    confirmButton: 'btn btn-danger px-3 py-2 font-semibold',
                    cancelButton: 'btn btn-secondary px-3 py-2 font-semibold me-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>

    @yield('js')
</body>
</html>

