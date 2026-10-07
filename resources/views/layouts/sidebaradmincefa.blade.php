<!DOCTYPE html>
<html lang="es" class="h-full bg-light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>AirSense CEFA - Panel de Administración</title>

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
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 text-decoration-none">
                        <!-- Isotipo AirSense -->
                        <img src="{{ asset('images/logo.png') }}" alt="Logo AirSense" class="h-8 w-auto object-contain">
                        <div class="flex flex-col">
                            <!-- Logo de Texto Blanco -->
                            <img src="{{ asset('images/airsense-texto-blanco.svg') }}" alt="AirSense CEFA" class="h-3.5 w-auto object-contain">
                            <span class="text-[8.5px] text-[#39A900] font-bold tracking-wider uppercase mt-0.5">PANEL ADMINISTRADOR</span>
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
<<<<<<< HEAD
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 text-decoration-none {{ request()->routeIs('admin.dashboard', 'dashboard') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <i class="fas fa-chart-pie text-base w-4 text-center text-[#39A900]"></i>
                            <span>Dashboard Principal</span>
                        </a>
                    </div>

                    <!-- GESTIÓN DE USUARIOS -->
                    <div x-data="{ open: {{ request()->routeIs('admin.usuarios.*') ? 'true' : 'false' }} }" class="space-y-1">
                        <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Personal</p>

                        <button @click="open = !open" type="button" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-[13.5px] font-semibold transition-all duration-200 {{ request()->routeIs('admin.usuarios.*') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }} focus:outline-none">
=======
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 text-decoration-none {{ request()->routeIs('admin.dashboard', 'dashboard') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <i class="fas fa-chart-pie text-sm w-4 text-center text-[#39A900]"></i>
                            <span class="text-sm">Dashboard Principal</span>
                        </a>
                    </div>

                    <!-- CATÁLOGOS -->
                    <div x-data="{ open: {{ request()->routeIs('usuarios.*', 'gusuarios.*') ? 'true' : 'false' }} }" class="space-y-1">
                        <p class="px-3 text-[10.5px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5">Gestión y Catálogos</p>

                        <button @click="open = !open" type="button" class="w-full flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('usuarios.*', 'gusuarios.*') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }} focus:outline-none">
>>>>>>> origin/feature/login-usuario
                            <div class="flex items-center gap-2.5">
                                <i class="fas fa-users-cog w-4 text-center text-[#39A900]"></i>
                                <span>Gestión de Usuarios</span>
                            </div>
                            <i class="fas fa-chevron-down text-[10px] transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                        </button>
                        <div x-show="open" x-collapse x-cloak class="pl-6 pr-2 py-1 space-y-1 border-l-2 border-[#003B5C] ml-4 mt-1">
                            <a href="#" class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-[13px] font-medium text-decoration-none transition-all duration-150 {{ request()->routeIs('usuarios.create') ? 'bg-[#39A900] text-white font-bold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-white/10' }}">
                                <i class="fas fa-user-plus text-[11px] text-[#39A900]"></i>
                                <span>Registrar Usuario</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-[13px] font-medium text-decoration-none transition-all duration-150 {{ request()->routeIs('gusuarios.listausuario') ? 'bg-[#39A900] text-white font-bold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-white/10' }}">
                                <i class="fas fa-list text-[11px] text-[#39A900]"></i>
                                <span>Listado de Usuarios</span>
                            </a>
                        </div>

                        <a href="{{ route('admin.asignaciones.index') }}" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-[13.5px] font-semibold text-decoration-none transition-all duration-150 {{ request()->routeIs('admin.asignaciones.*') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <i class="fas fa-chalkboard-teacher w-4 text-center text-[#39A900]"></i>
                            <span>Asignar Ambientes</span>
                        </a>
                    </div>

                    <!-- CONFIGURACIÓN GLOBAL & HARDWARE IoT -->
                    <div class="space-y-1">
                        <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Configuración & IoT</p>

                        <a href="#" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-[13.5px] font-semibold text-decoration-none transition-all duration-150 {{ request()->routeIs('umbrales.*') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <i class="fas fa-sliders-h w-4 text-center text-[#39A900]"></i>
                            <span>Umbrales de Alerta</span>
                        </a>

                        <a href="{{ route('admin.nodos')}}" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-[13.5px] font-semibold text-decoration-none transition-all duration-150 {{ request()->routeIs('nodos.*') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <i class="fas fa-microchip w-4 text-center text-[#39A900]"></i>
                            <span>Nodos IoT (ESP32)</span>
                        </a>

                        <a href="#" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-[13.5px] font-semibold text-decoration-none transition-all duration-150 {{ request()->routeIs('conectividad.*') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <i class="fas fa-wifi w-4 text-center text-[#39A900]"></i>
                            <span>Monitor Conectividad</span>
                        </a>

                        <a href="#" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-[13.5px] font-semibold text-decoration-none transition-all duration-150 {{ request()->routeIs('ubicaciones.*') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <i class="fas fa-map-marker-alt w-4 text-center text-[#39A900]"></i>
                            <span>Ubicación de Nodos</span>
                        </a>
                    </div>

                    <!-- AUDITORÍA Y REPORTES -->
                    <div class="space-y-1">
                        <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Auditoría & Seguridad</p>

                        <a href="{{ route('admin.historico.index') }}" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-[13.5px] font-semibold text-decoration-none transition-all duration-150 {{ request()->routeIs('admin.historico.*') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <i class="fas fa-history w-4 text-center text-[#39A900]"></i>
                            <span>Log de Auditoría</span>
                        </a>

                        <a href="{{ route('admin.reportes.index') }}" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-[13.5px] font-semibold text-decoration-none transition-all duration-150 {{ request()->routeIs('admin.reportes.*') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <i class="fas fa-file-pdf w-4 text-center text-[#39A900]"></i>
                            <span>Reportes Protegidos</span>
                        </a>
                    </div>

                    <!-- ANALÍTICA E INTELIGENCIA ARTIFICIAL -->
                    <div class="space-y-1">
                        <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Inteligencia Artificial</p>

                        <a href="{{ route('admin.predictivo.index') }}" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-[13.5px] font-semibold text-decoration-none transition-all duration-150 {{ request()->routeIs('admin.predictivo.*') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <i class="fas fa-brain w-4 text-center text-[#39A900]"></i>
                            <span>Módulo Predictivo</span>
=======
                        <p class="px-3 text-[10.5px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5">Operaciones Comercial</p>

                        <a href="" class="flex items-center gap-2.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-200 hover:text-white hover:bg-white/10 text-decoration-none">
                            <i class="fas fa-cart-plus text-[#39A900] w-4 text-center"></i>
                            <span>Nueva Compra Entrada</span>
                        </a>

                        <a href="" class="flex items-center gap-2.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-decoration-none transition-all duration-150 {{ request()->routeIs('compras.index') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <i class="fas fa-file-invoice-dollar w-4 text-center text-[#39A900]"></i>
                            <span>Historial Compras</span>
                        </a>

                        <a href="" class="flex items-center gap-2.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-amber-300 hover:text-amber-200 hover:bg-white/10 text-decoration-none">
                            <i class="fas fa-cash-register w-4 text-center"></i>
                            <span>Nueva Venta (POS)</span>
                        </a>

                        <a href="" class="flex items-center gap-2.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-decoration-none transition-all duration-150 {{ request()->routeIs('ventas.index') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <i class="fas fa-shopping-basket w-4 text-center text-[#39A900]"></i>
                            <span>Historial Ventas</span>
                        </a>
                    </div>

                    <!-- REPORTES & POST-VENTA -->
                    <div class="space-y-1">
                        <p class="px-3 text-[10.5px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5">Reportes & Post-Venta</p>

                        <a href="" class="flex items-center gap-2.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-decoration-none transition-all duration-150 {{ request()->routeIs('reportes.ventas') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <i class="fas fa-chart-line w-4 text-center text-[#39A900]"></i>
                            <span>Reporte de Ventas</span>
                        </a>

                        <a href="" class="flex items-center gap-2.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-decoration-none transition-all duration-150 {{ request()->routeIs('reportes.ganancias') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <i class="fas fa-calculator w-4 text-center text-[#39A900]"></i>
                            <span>Resumen Ganancias</span>
                        </a>

                        <a href="" class="flex items-center gap-2.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-decoration-none transition-all duration-150 {{ request()->routeIs('devoluciones.*') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <i class="fas fa-undo w-4 text-center text-[#39A900]"></i>
                            <span>Devoluciones</span>
                        </a>

                        <a href="" class="flex items-center gap-2.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-decoration-none transition-all duration-150 {{ request()->routeIs('garantias.*') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <i class="fas fa-shield-alt w-4 text-center text-[#39A900]"></i>
                            <span>Garantías</span>
                        </a>

                        <a href="" class="flex items-center gap-2.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-decoration-none transition-all duration-150 {{ request()->routeIs('configuracion.bitacora') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <i class="fas fa-history w-4 text-center text-[#39A900]"></i>
                            <span>Bitácora & Backup</span>
>>>>>>> origin/feature/login-usuario
                        </a>
                    </div>
                </div>
            </div>

<<<<<<< HEAD
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
                            <div class="fw-semibold text-truncate" style="font-size: 11px; color: #86efac;">Administrador General</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-1 ms-1">
                        <a href="{{ route('profile.edit') }}" class="btn btn-sm text-white-50 hover-text-white p-1" title="Configurar Perfil">
                            <i class="bi bi-gear-fill" style="font-size: 14px;"></i>
                        </a>
                        <button type="button" onclick="confirmarCierreSesion()" class="btn btn-sm text-white-50 hover-text-danger p-1" title="Cerrar Sesión">
                            <i class="bi bi-box-arrow-right text-rose-400" style="font-size: 14px;"></i>
                        </button>
=======
            <!-- Footer User Badge -->
            <div class="p-3.5 bg-[#001522] border-t border-[#003B5C]/70">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-[#39A900] text-white flex items-center justify-center font-bold text-base shadow-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-white truncate m-0">{{ Auth::user()->name }}</p>
                        <p class="text-[10.5px] text-[#39A900] font-medium m-0">Administrador General</p>
>>>>>>> origin/feature/login-usuario
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Canvas -->
        <div class="flex-1 flex flex-col min-w-0 lg:ml-64 w-full bg-slate-50">
            <!-- Header Navbar Sticky -->
<<<<<<< HEAD
            <header class="h-16 bg-white border-b border-slate-200 px-4 px-lg-6 flex items-center justify-between sticky top-0 z-30 shadow-sm">
                <div class="flex items-center gap-3">
                    <h1 class="text-lg font-bold text-slate-800 font-heading hidden sm:block m-0">@yield('tituloPagina', 'Dashboard Principal')</h1>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 rounded-pill font-semibold">
                        <i class="bi bi-circle-fill text-success me-1" style="font-size: 8px;"></i>
=======
            <header class="h-16 bg-white/95 backdrop-blur-md border-b border-slate-200/80 px-4 px-lg-6 flex items-center justify-between sticky top-0 z-30 shadow-xs">
                <div class="flex items-center gap-3">
                    <h1 class="text-lg font-bold text-slate-800 font-heading hidden sm:block m-0">@yield('tituloPagina', 'Dashboard Principal')</h1>
                    <span class="inline-flex items-center gap-2 text-xs font-semibold px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                        <span class="w-2 h-2 rounded-full bg-[#39A900] animate-pulse"></span>
>>>>>>> origin/feature/login-usuario
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
                            <small class="text-muted" style="font-size: 10.5px;">Administrador de Sistema</small>
                        </div>
                        <i class="bi bi-chevron-down text-muted ms-1" style="font-size: 10px;"></i>
                    </button>

                    <div x-show="open" @click.away="open = false" x-cloak
                         class="position-absolute end-0 mt-2 bg-white rounded-3 shadow-lg border border-secondary-subtle p-2 z-30" style="width: 250px;">
                        
                        <div class="p-2.5 bg-light rounded-2 mb-1 border border-secondary-subtle">
                            <div class="fw-bold text-dark text-truncate" style="font-size: 13px;">{{ Auth::user()->name }}</div>
                            <div class="text-muted text-truncate" style="font-size: 11.5px;">{{ Auth::user()->email }}</div>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle mt-1.5 fw-semibold">
                                <i class="bi bi-shield-lock me-1"></i> Administrador General
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

<<<<<<< HEAD
            <!-- Formulario oculto de Cierre de Sesión -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>

            <!-- Alert Toast Notifications & Content Canvas -->
            <main class="flex-1 p-4 p-lg-6 max-w-7xl w-full mx-auto">
                @if(session('success'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: '{{ session('success') }}',
                                showConfirmButton: false,
                                timer: 3500,
                                timerProgressBar: true
                            });
                        });
                    </script>
=======
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
>>>>>>> origin/feature/login-usuario
                @endif

                @if(session('error'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'error',
                                title: '{{ session('error') }}',
                                showConfirmButton: false,
                                timer: 4000,
                                timerProgressBar: true
                            });
                        });
                    </script>
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
