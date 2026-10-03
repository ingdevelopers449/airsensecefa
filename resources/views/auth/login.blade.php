<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Iniciar sesión - {{ config('app.name', 'AirSense CEFA') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Bootstrap 5.3.3 CSS Oficial -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/css/styles.css', 'resources/css/login.css', 'resources/js/app.js'])

    <style>
        /* Estilos y Micro-animaciones para el Login */
        .login-fade-in {
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            background-color: #22c55e;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
            animation: pulse-green 2s infinite;
        }

        @keyframes pulse-green {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
            }
            70% {
                transform: scale(1);
                box-shadow: 0 0 0 8px rgba(34, 197, 94, 0);
            }
            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
            }
        }

        /* Input Focus Enhancements */
        .form-control-icon:focus {
            border-color: #39a900 !important;
            box-shadow: 0 0 0 0.25rem rgba(57, 169, 0, 0.15) !important;
        }

        .spin-icon {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="login-page">

<div class="container-fluid p-0 m-0 login-container">
    <div class="row g-0 m-0 h-100">
        
        <!-- Lado Izquierdo (Imagen del mapa con Overlay Informativo) -->
        <div class="col-lg-6 d-none d-lg-block h-100 position-relative">
            <div style="background-image: url('{{ asset('images/Mapa.Sena.png') }}'); background-size: cover; background-position: center; width: 100%; height: 100%; filter: brightness(0.92);"></div>
            
            <!-- Píldora Flotante Limpia e Institucional -->
            <div class="position-absolute bottom-0 start-0 p-4 p-xl-5">
                <div class="bg-white shadow-sm border rounded-pill px-3 py-2 d-inline-flex align-items-center gap-2">
                    <span class="pulse-dot"></span>
                    <span class="fw-semibold text-dark small" style="font-size: 0.85rem;">
                        Red de Monitoreo Ambiental CEFA
                    </span>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                        <i class="bi bi-wifi me-1"></i>En línea
                    </span>
                </div>
            </div>
        </div>

        <!-- Lado Derecho (Formulario blanco con animación de entrada) -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center login-right-panel p-4 p-md-5 h-100" style="background-color: #ffffff; position: relative; overflow: hidden;">
            <div class="deco-circle-fill"></div>
            <div class="deco-circle-line-1"></div>
            <div class="deco-circle-line-2"></div>
            
            <div class="deco-leaf-fill"></div>
            <div class="deco-leaf-line"></div>

            <!-- Botón Volver -->
            <a href="/" class="btn bg-white shadow-sm position-absolute d-flex align-items-center justify-content-center" style="top: 2rem; left: 2rem; border-radius: 10px; font-weight: 600; color: #0a2a43; font-size: 0.9rem; padding: 0.5rem 1rem; border: 1px solid #eef7f0; z-index: 10;">
                <i class="bi bi-arrow-left me-2"></i> Volver al Inicio
            </a>
            
            <div class="login-fade-in" style="width: 100%; max-width: 440px; position: relative; z-index: 1;">
                
                <!-- Logo -->
                <div class="text-center mb-4">
                    <div class="d-inline-flex flex-column align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ asset('images/logo.png') }}" alt="AirSense CEFA" style="height: 60px;">
                            <img src="{{ asset('images/airsense-texto.svg') }}" alt="AirSense CEFA Text" style="height: 32px;">
                        </div>
                        <div class="d-flex align-items-center justify-content-center mt-2" style="width: 100%;">
                            <div style="flex: 1; height: 1px; background-color: #0b3b32; opacity: 0.3;"></div>
                            <span class="px-2 text-uppercase" style="font-size: 0.65rem; color: #0b3b32; font-weight: 700; letter-spacing: 0.5px;">Plataforma de Control Ambiental</span>
                            <div style="flex: 1; height: 1px; background-color: #0b3b32; opacity: 0.3;"></div>
                        </div>
                    </div>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success mb-4 text-center small rounded-3 shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> {{ session('status') }}
                    </div>
                @endif

                <!-- Títulos Institucionales -->
                <div class="text-center mb-4 pb-1">
                    <h2 class="fw-bold mb-1" style="color: #0a2a43; font-size: 1.75rem; letter-spacing: -0.5px;">Bienvenido a AirSense CEFA</h2>
                    <p style="color: #6b7280; font-size: 0.9rem;">Ingresa tus credenciales para acceder al sistema.</p>
                </div>

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    <!-- Correo electrónico -->
                    <div class="mb-4 position-relative">
                        <i class="bi bi-envelope input-icon-left"></i>
                        <input id="email" class="form-control form-control-icon @error('email') is-invalid @enderror" 
                               type="email" name="email" value="{{ old('email') }}" 
                               placeholder="Correo electrónico institucional" required autofocus autocomplete="username">
                        @error('email')
                            <div class="invalid-feedback mt-2" style="padding-left: 0.5rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Contraseña -->
                    <div class="mb-3 position-relative">
                        <i class="bi bi-lock input-icon-left"></i>
                        <input id="password" class="form-control form-control-icon @error('password') is-invalid @enderror" 
                               type="password" name="password" 
                               placeholder="Contraseña" required autocomplete="current-password">
                        <i class="bi bi-eye input-icon-right" id="togglePassword"></i>
                        @error('password')
                            <div class="invalid-feedback mt-2" style="padding-left: 0.5rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Alerta de Bloq Mayús (Caps Lock Warning) -->
                    <div id="capsWarning" class="alert alert-warning py-1 px-2 mb-3 d-none rounded-3 align-items-center gap-2" style="font-size: 0.8rem;">
                        <i class="bi bi-capslock-fill text-warning fs-6"></i>
                        <span><strong>Bloq Mayús activado</strong></span>
                    </div>

                    <!-- Recordarme y Olvidó Contraseña -->
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2">
                        <div class="form-check m-0">
                            <input class="form-check-input shadow-sm" type="checkbox" name="remember" id="remember_me">
                            <label class="form-check-label text-secondary" for="remember_me" style="font-size: 0.88rem;">
                                Recordarme
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-login-link" style="font-size: 0.88rem;">
                                ¿Olvidaste tu contraseña?
                            </a>
                        @endif
                    </div>

                    <!-- Botón Iniciar Sesión con Estado de Carga -->
                    <div class="d-grid">
                        <button type="submit" id="btnSubmitLogin" class="btn btn-login shadow-sm d-flex align-items-center justify-content-center gap-2">
                            <span>Iniciar sesión</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Mostrar/Ocultar Contraseña
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#password');

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.classList.toggle('bi-eye');
                this.classList.toggle('bi-eye-slash');
            });
        }

        // 2. Detección de Bloq Mayús (Caps Lock)
        const capsWarning = document.querySelector('#capsWarning');

        if (passwordInput && capsWarning) {
            passwordInput.addEventListener('keyup', function (event) {
                if (event.getModifierState && event.getModifierState('CapsLock')) {
                    capsWarning.classList.remove('d-none');
                    capsWarning.classList.add('d-flex');
                } else {
                    capsWarning.classList.add('d-none');
                    capsWarning.classList.remove('d-flex');
                }
            });
        }

        // 3. Estado de Carga en Botón de Submit
        const loginForm = document.querySelector('#loginForm');
        const btnSubmit = document.querySelector('#btnSubmitLogin');

        if (loginForm && btnSubmit) {
            loginForm.addEventListener('submit', function () {
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = '<i class="bi bi-arrow-repeat spin-icon"></i> <span>Iniciando sesión...</span>';
            });
        }
    });
</script>

</body>
</html>
