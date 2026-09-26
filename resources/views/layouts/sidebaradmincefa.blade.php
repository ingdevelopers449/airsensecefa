<!DOCTYPE html>
<html lang="es" class="h-full bg-light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('images/logo.png')}}" type="image/x-icon">
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

    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- Sidebar Navigation Executive SENA -->
        <aside class="w-full lg:w-72 bg-sena-dark text-slate-200 flex-shrink-0 flex flex-col justify-between border-r border-slate-800/60 lg:fixed lg:inset-y-0 lg:left-0 lg:z-40 shadow-xl">
            <div>
                <!-- Brand Header -->
                <div class="h-20 px-5 flex items-center justify-between bg-sena-header border-b border-slate-800/80">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 text-decoration-none">
                        <!-- Isotipo AirSense -->
                        <img src="{{ asset('images/logo.png') }}" alt="Logo AirSense" class="h-9 w-auto object-contain">
                        <div class="flex flex-col">
                            <!-- Logo de Texto Blanco -->
                            <img src="{{ asset('images/airsense-texto-blanco.svg') }}" alt="AirSense CEFA" class="h-4 w-auto object-contain">
                            <span class="text-[9px] text-sena-green font-bold tracking-widest uppercase mt-0.5">ADMINISTRACIÓN SST</span>
                        </div>
                    </a>
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-slate-400 hover:text-white focus:outline-none p-2 rounded-lg hover:bg-slate-800">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>

                <!-- Nav Menu -->
                <div class="px-3 py-5 space-y-6 overflow-y-auto max-h-[calc(100vh-140px)]" :class="{ 'block': sidebarOpen, 'hidden lg:block': !sidebarOpen }">
                    
                    <!-- Dashboard -->
                    <div>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-200 text-decoration-none {{ request()->routeIs('admin.dashboard', 'dashboard') ? 'bg-sena-green text-white shadow-lg shadow-sena-green/20' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                            <i class="fas fa-chart-pie text-lg w-5 text-center"></i>
                            <span>Dashboard Principal</span>
                        </a>
                    </div>

                    <!-- CATÁLOGOS -->
                    <div x-data="{ open: {{ request()->routeIs('usuarios.*', 'gusuarios.*') ? 'true' : 'false' }} }" class="space-y-1">
                        <p class="px-4 text-[11px] font-extrabold text-slate-400/80 uppercase tracking-wider mb-2">Gestión y Catálogos</p>

                        <button @click="open = !open" type="button" class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('usuarios.*', 'gusuarios.*') ? 'bg-slate-800/80 text-sena-green font-semibold border-l-4 border-sena-green' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }} focus:outline-none">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-users-cog w-5 text-center text-sena-green"></i>
                                <span>Gestión de Usuarios</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                        </button>
                        <div x-show="open" x-collapse x-cloak class="pl-8 pr-2 py-1 space-y-1">
                            <a href="" class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium text-decoration-none transition-all duration-150 {{ request()->routeIs('usuarios.create') ? 'bg-sena-green text-white font-semibold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800/40' }}">
                                <i class="fas fa-user-plus text-[10px] text-sena-green"></i>
                                <span>Registrar Usuario</span>
                            </a>
                            <a href="" class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium text-decoration-none transition-all duration-150 {{ request()->routeIs('gusuarios.listausuario') ? 'bg-sena-green text-white font-semibold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800/40' }}">
                                <i class="fas fa-list text-[10px] text-sena-green"></i>
                                <span>Listado de Usuarios</span>
                            </a>
                        </div>

                        <a href="" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-decoration-none transition-all duration-150 {{ request()->routeIs('proveedores.*') ? 'bg-slate-800/80 text-sena-green border-l-4 border-sena-green' : 'text-slate-300 hover:text-white hover:bg-slate-800/40' }}">
                            <i class="fas fa-building w-5 text-center"></i>
                            <span>Proveedores</span>
                        </a>

                        <a href="" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-decoration-none transition-all duration-150 {{ request()->routeIs('categorias.*') ? 'bg-slate-800/80 text-sena-green border-l-4 border-sena-green' : 'text-slate-300 hover:text-white hover:bg-slate-800/40' }}">
                            <i class="fas fa-tags w-5 text-center"></i>
                            <span>Categorías</span>
                        </a>

                        <a href="" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-decoration-none transition-all duration-150 {{ request()->routeIs('productos.*') ? 'bg-slate-800/80 text-sena-green border-l-4 border-sena-green' : 'text-slate-300 hover:text-white hover:bg-slate-800/40' }}">
                            <i class="fas fa-boxes w-5 text-center"></i>
                            <span>Productos e Insumos</span>
                        </a>
                    </div>

                    <!-- INVENTARIO -->
                    <div class="space-y-1">
                        <p class="px-4 text-[11px] font-extrabold text-slate-400/80 uppercase tracking-wider mb-2">Control de Inventario</p>

                        <a href="" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-decoration-none transition-all duration-150 {{ request()->routeIs('inventario.stock') ? 'bg-slate-800/80 text-sena-green border-l-4 border-sena-green' : 'text-slate-300 hover:text-white hover:bg-slate-800/40' }}">
                            <i class="fas fa-warehouse w-5 text-center"></i>
                            <span>Consultar Stock</span>
                        </a>

                        <a href="" class="flex items-center justify-between px-4 py-2.5 rounded-xl text-sm font-medium text-decoration-none transition-all duration-150 {{ request()->routeIs('inventario.stock-minimo') ? 'bg-slate-800/80 text-amber-400 border-l-4 border-amber-500' : 'text-slate-300 hover:text-white hover:bg-slate-800/40' }}">
                            <span class="flex items-center gap-3">
                                <i class="fas fa-exclamation-triangle text-amber-400 w-5 text-center"></i>
                                <span>Stock Mínimo</span>
                            </span>
                        </a>

                        <a href="" class="flex items-center justify-between px-4 py-2.5 rounded-xl text-sm font-medium text-decoration-none transition-all duration-150 {{ request()->routeIs('inventario.agotados') ? 'bg-slate-800/80 text-rose-400 border-l-4 border-rose-500' : 'text-slate-300 hover:text-white hover:bg-slate-800/40' }}">
                            <span class="flex items-center gap-3">
                                <i class="fas fa-times-circle text-rose-400 w-5 text-center"></i>
                                <span>Agotados</span>
                            </span>
                        </a>

                        <a href="" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-decoration-none transition-all duration-150 {{ request()->routeIs('inventario.ajustes') ? 'bg-slate-800/80 text-sena-green border-l-4 border-sena-green' : 'text-slate-300 hover:text-white hover:bg-slate-800/40' }}">
                            <i class="fas fa-tools w-5 text-center"></i>
                            <span>Ajustes de Stock</span>
                        </a>

                        <a href="" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-decoration-none transition-all duration-150 {{ request()->routeIs('inventario.movimientos') ? 'bg-slate-800/80 text-sena-green border-l-4 border-sena-green' : 'text-slate-300 hover:text-white hover:bg-slate-800/40' }}">
                            <i class="fas fa-exchange-alt w-5 text-center"></i>
                            <span>Movimientos Auditados</span>
                        </a>
                    </div>

                    <!-- COMPRAS Y VENTAS -->
                    <div class="space-y-1">
                        <p class="px-4 text-[11px] font-extrabold text-slate-400/80 uppercase tracking-wider mb-2">Operaciones Comercial</p>

                        <a href="" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:text-white hover:bg-slate-800/40 text-decoration-none">
                            <i class="fas fa-cart-plus text-sena-green w-5 text-center"></i>
                            <span>Nueva Compra Entrada</span>
                        </a>

                        <a href="" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-decoration-none transition-all duration-150 {{ request()->routeIs('compras.index') ? 'bg-slate-800/80 text-sena-green border-l-4 border-sena-green' : 'text-slate-300 hover:text-white hover:bg-slate-800/40' }}">
                            <i class="fas fa-file-invoice-dollar w-5 text-center"></i>
                            <span>Historial Compras</span>
                        </a>

                        <a href="" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-amber-300 hover:text-amber-200 hover:bg-slate-800/40 text-decoration-none">
                            <i class="fas fa-cash-register w-5 text-center"></i>
                            <span>Nueva Venta (POS)</span>
                        </a>

                        <a href="" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-decoration-none transition-all duration-150 {{ request()->routeIs('ventas.index') ? 'bg-slate-800/80 text-sena-green border-l-4 border-sena-green' : 'text-slate-300 hover:text-white hover:bg-slate-800/40' }}">
                            <i class="fas fa-shopping-basket w-5 text-center"></i>
                            <span>Historial Ventas</span>
                        </a>
                    </div>

                    <!-- REPORTES & POST-VENTA -->
                    <div class="space-y-1">
                        <p class="px-4 text-[11px] font-extrabold text-slate-400/80 uppercase tracking-wider mb-2">Reportes & Post-Venta</p>

                        <a href="" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-decoration-none transition-all duration-150 {{ request()->routeIs('reportes.ventas') ? 'bg-slate-800/80 text-sena-green border-l-4 border-sena-green' : 'text-slate-300 hover:text-white hover:bg-slate-800/40' }}">
                            <i class="fas fa-chart-line w-5 text-center"></i>
                            <span>Reporte de Ventas</span>
                        </a>

                        <a href="" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-decoration-none transition-all duration-150 {{ request()->routeIs('reportes.ganancias') ? 'bg-slate-800/80 text-sena-green border-l-4 border-sena-green' : 'text-slate-300 hover:text-white hover:bg-slate-800/40' }}">
                            <i class="fas fa-calculator w-5 text-center"></i>
                            <span>Resumen Ganancias</span>
                        </a>

                        <a href="" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-decoration-none transition-all duration-150 {{ request()->routeIs('devoluciones.*') ? 'bg-slate-800/80 text-sena-green border-l-4 border-sena-green' : 'text-slate-300 hover:text-white hover:bg-slate-800/40' }}">
                            <i class="fas fa-undo w-5 text-center"></i>
                            <span>Devoluciones</span>
                        </a>

                        <a href="" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-decoration-none transition-all duration-150 {{ request()->routeIs('garantias.*') ? 'bg-slate-800/80 text-sena-green border-l-4 border-sena-green' : 'text-slate-300 hover:text-white hover:bg-slate-800/40' }}">
                            <i class="fas fa-shield-alt w-5 text-center"></i>
                            <span>Garantías</span>
                        </a>

                        <a href="" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-decoration-none transition-all duration-150 {{ request()->routeIs('configuracion.bitacora') ? 'bg-slate-800/80 text-sena-green border-l-4 border-sena-green' : 'text-slate-300 hover:text-white hover:bg-slate-800/40' }}">
                            <i class="fas fa-history w-5 text-center"></i>
                            <span>Bitácora & Backup</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer User Badge -->
            <div class="p-4 bg-sena-header border-t border-slate-800/80">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-sena-green text-white flex items-center justify-center font-bold text-lg shadow-md">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-white truncate m-0">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-sena-green font-medium m-0">Administrador ERP</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Canvas -->
        <div class="flex-1 flex flex-col min-w-0 lg:ml-72 w-full">
            <!-- Header Navbar Sticky -->
            <header class="h-20 bg-white border-bottom border-slate-200 px-4 px-lg-5 flex items-center justify-between sticky top-0 z-30 shadow-sm">
                <div class="flex items-center gap-3">
                    <h1 class="text-lg font-bold text-slate-800 font-heading hidden sm:block m-0">@yield('tituloPagina', 'Dashboard Principal')</h1>
                    <span class="inline-flex items-center gap-2 text-xs font-semibold px-3 py-1.5 rounded-full bg-sena-light text-sena-green border border-sena-green/30">
                        <span class="w-2 h-2 rounded-full bg-sena-green animate-pulse"></span>
                        Sistema En Línea
                    </span>
                </div>

                <!-- User Dropdown Menu -->
                <div class="flex items-center gap-4" x-data="{ open: false }">
                    <div class="relative">
                        <button @click="open = !open" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-100 transition-colors focus:outline-none">
                            <span class="text-sm font-semibold text-slate-700">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                        </button>

                        <div x-show="open" @click.away="open = false" x-cloak
                             class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 transition-all">
                            <div class="px-4 py-3 border-b border-slate-100">
                                <p class="text-xs text-slate-400 font-medium m-0">Sesión activa</p>
                                <p class="text-sm font-bold text-slate-800 truncate m-0">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors text-decoration-none">
                                <i class="fas fa-user-cog text-slate-400"></i> Mi Perfil
                            </a>
                            <a href="{{ url('/') }}" target="_blank" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors text-decoration-none">
                                <i class="fas fa-external-link-alt text-slate-400"></i> Ver Sitio Web
                            </a>
                            <div class="border-t border-slate-100 my-1"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 transition-colors font-medium border-0 bg-transparent">
                                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Alert Toast Notifications -->
            <main class="flex-1 p-4 p-lg-5 max-w-7xl w-full mx-auto">
                @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center justify-content-between rounded-4 shadow-sm mb-4" role="alert">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center p-2" style="width: 32px; height: 32px;">
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
    @yield('js')
</body>
</html>
