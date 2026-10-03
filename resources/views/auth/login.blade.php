<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Iniciar sesión - {{ config('app.name', 'AirSense CEFA') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Bootstrap 5.3.3 CSS Oficial -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/css/styles.css', 'resources/css/login.css', 'resources/js/app.js'])
</head>
<body class="login-page">

<div class="container-fluid p-0 m-0 login-container">
    <div class="row g-0 m-0 h-100">
        
        <!-- Lado Izquierdo (Imagen del mapa) -->
        <div class="col-lg-6 d-none d-lg-block h-100">
            <div style="background-image: url('{{ asset('images/Mapa.Sena.png') }}'); background-size: cover; background-position: center; width: 100%; height: 100%;"></div>
        </div>

        <!-- Lado Derecho (Formulario blanco) -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center login-right-panel p-4 p-md-5 h-100" style="background-color: #ffffff; position: relative; overflow: hidden;">
            <div class="deco-circle-fill"></div>
            <div class="deco-circle-line-1"></div>
            <div class="deco-circle-line-2"></div>
            
            <div class="deco-leaf-fill"></div>
            <div class="deco-leaf-line"></div>
            <!-- Botón Volver -->
            <a href="/" class="btn bg-white shadow-sm position-absolute d-flex align-items-center justify-content-center" style="top: 2rem; left: 2rem; border-radius: 10px; font-weight: 600; color: #0a2a43; font-size: 0.9rem; padding: 0.5rem 1rem; border: 1px solid #eef7f0; z-index: 10;">
                <i class="bi bi-arrow-left me-2"></i> Volver
            </a>
            
            <div style="width: 100%; max-width: 440px; position: relative; z-index: 1;">
                
                <!-- Logo -->
                <div class="text-center mb-5">
                    <div class="d-inline-flex flex-column align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ asset('images/logo.png') }}" alt="AirSense CEFA" style="height: 60px;">
                            <img src="{{ asset('images/airsense-texto.svg') }}" alt="AirSense CEFA Text" style="height: 32px;">
                        </div>
                        <div class="d-flex align-items-center justify-content-center mt-2" style="width: 100%;">
                            <div style="flex: 1; height: 1px; background-color: #0b3b32; opacity: 0.3;"></div>
                            <span class="px-2 text-uppercase" style="font-size: 0.65rem; color: #0b3b32; font-weight: 700; letter-spacing: 0.5px;">Monitoreo inteligente de calidad del aire</span>
                            <div style="flex: 1; height: 1px; background-color: #0b3b32; opacity: 0.3;"></div>
                        </div>
                    </div>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success mb-4 text-center small rounded-3">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Títulos -->
                <div class="text-center mb-4 pb-2">
                    <h2 class="fw-bold mb-2" style="color: #0a2a43; font-size: 1.85rem; letter-spacing: -0.5px;">Bienvenida de nuevo</h2>
                    <p style="color: #6b7280; font-size: 0.95rem;">Qué bueno tenerte aquí.</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Correo electrónico -->
                    <div class="mb-4 position-relative">
                        <i class="bi bi-envelope input-icon-left"></i>
                        <input id="email" class="form-control form-control-icon @error('email') is-invalid @enderror" 
                               type="email" name="email" value="{{ old('email') }}" 
                               placeholder="Correo electrónico" required autofocus autocomplete="username">
                        @error('email')
                            <div class="invalid-feedback mt-2" style="padding-left: 0.5rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Contraseña -->
                    <div class="mb-4 position-relative">
                        <i class="bi bi-lock input-icon-left"></i>
                        <input id="password" class="form-control form-control-icon @error('password') is-invalid @enderror" 
                               type="password" name="password" 
                               placeholder="Contraseña" required autocomplete="current-password">
                        <i class="bi bi-eye input-icon-right" id="togglePassword"></i>
                        @error('password')
                            <div class="invalid-feedback mt-2" style="padding-left: 0.5rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Recordarme y Olvidó Contraseña -->
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2">
                        <div class="form-check m-0">
                            <input class="form-check-input shadow-sm" type="checkbox" name="remember" id="remember_me">
                            <label class="form-check-label text-secondary" for="remember_me" style="font-size: 0.9rem;">
                                Recordarme
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-login-link" style="font-size: 0.9rem;">
                                ¿Olvidaste tu contraseña?
                            </a>
                        @endif
                    </div>

                    <!-- Botón Iniciar Sesión -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-login shadow-sm">
                            Iniciar sesión
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
    // Script para alternar la visibilidad de la contraseña
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        if (togglePassword && password) {
            togglePassword.addEventListener('click', function () {
                // Alternar el tipo de input
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                
                // Alternar el icono
                this.classList.toggle('bi-eye');
                this.classList.toggle('bi-eye-slash');
            });
        }
    });
</script>

</body>
</html>
