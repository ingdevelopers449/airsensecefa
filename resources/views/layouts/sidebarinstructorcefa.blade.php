<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('images/logo.png')}}" type="image/x-icon">
    <title>AirSense CEFA - Panel de Instructor</title>

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
                    <a href="{{ route('instructor.dashboard') }}" class="flex items-center gap-2.5 text-decoration-none">
                        <!-- Isotipo AirSense -->
                        <img src="{{ asset('images/logo.png') }}" alt="Logo AirSense" class="h-8 w-auto object-contain">
                        <div class="flex flex-col">
                            <!-- Logo de Texto Blanco -->
                            <img src="{{ asset('images/airsense-texto-blanco.svg') }}" alt="AirSense CEFA" class="h-3.5 w-auto object-contain">
                            <span class="text-[8.5px] text-[#39A900] font-bold tracking-wider uppercase mt-0.5">PANEL INSTRUCTOR</span>
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
                        <a href="{{ route('instructor.dashboard') }}" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 text-decoration-none {{ request()->routeIs('instructor.dashboard') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <i class="fas fa-desktop text-base w-4 text-center text-[#39A900]"></i>
                            <span>Mi Ambiente de Formación</span>
                        </a>
                    </div>

                    <!-- MI AULA & AFORO -->
                    <div class="space-y-1">
                        <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Control de Aula & Aforo</p>

                        <a href="" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-[13.5px] font-semibold text-decoration-none transition-all duration-150 {{ request()->routeIs('instructor.estadía.*') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <i class="fas fa-check-circle w-4 text-center text-[#39A900]"></i>
                            <span>Estadía y Aforo Diario</span>
                        </a>

                        <a href="" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-[13.5px] font-semibold text-decoration-none transition-all duration-150 {{ request()->routeIs('instructor.cambio-ambiente.*') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <i class="fas fa-exchange-alt w-4 text-center text-[#39A900]"></i>
                            <span>Cambiar de Ambiente</span>
                        </a>
                    </div>

                    <!-- PROTOCOLOS & RECOMENDACIONES -->
                    <div class="space-y-1">
                        <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Protocolos de Seguridad</p>

                        <a href="" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-[13.5px] font-semibold text-decoration-none transition-all duration-150 {{ request()->routeIs('instructor.manual.*') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <i class="fas fa-notes-medical w-4 text-center text-[#39A900]"></i>
                            <span>Manual de Contingencia</span>
                        </a>

                        <a href="" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-[13.5px] font-semibold text-decoration-none transition-all duration-150 {{ request()->routeIs('instructor.alertas-preventivas.*') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <i class="fas fa-exclamation-circle w-4 text-center text-[#39A900]"></i>
                            <span>Alertas Preventivas</span>
                        </a>
                    </div>

                    <!-- ANALÍTICA PREDICTIVA DE AULA -->
                    <div class="space-y-1">
                        <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Pronóstico Ambiental</p>

                        <a href="" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-[13.5px] font-semibold text-decoration-none transition-all duration-150 {{ request()->routeIs('instructor.predictivo.*') ? 'bg-[#39A900]/20 text-[#39A900] font-bold border-l-4 border-[#39A900]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <i class="fas fa-chart-line w-4 text-center text-[#39A900]"></i>
                            <span>Tendencia de Aire (IA)</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer User Badge -->
            <div class="p-3.5 bg-[#001522] border-t border-[#003B5C]/70">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-[#39A900] text-white flex items-center justify-center font-bold text-base shadow-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-white truncate m-0">{{ Auth::user()->name }}</p>
                        <p class="text-[10.5px] text-[#39A900] font-medium m-0">Administrador General</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Canvas -->
        <div class="flex-1 flex flex-col min-w-0 lg:ml-64 w-full bg-slate-50">
            <!-- Header Navbar Sticky -->
            <header class="h-16 bg-white/95 backdrop-blur-md border-b border-slate-200/80 px-4 px-lg-6 flex items-center justify-between sticky top-0 z-30 shadow-xs">
                <div class="flex items-center gap-3">
                    <h1 class="text-lg font-bold text-slate-800 font-heading hidden sm:block m-0">@yield('tituloPagina', 'Dashboard Principal')</h1>
                    <span class="inline-flex items-center gap-2 text-xs font-semibold px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                        <span class="w-2 h-2 rounded-full bg-[#39A900] animate-pulse"></span>
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
    @yield('js')
</body>
</html>