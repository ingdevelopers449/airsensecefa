# AirSense CEFA 🌿

Sistema de Monitoreo de Calidad del Aire e Internet de las Cosas (IoT) diseñado para el **Centro de Formación Agroindustrial La Angostura (CEFA)** — SENA Regional Huila. 🏫

---

## 📝 Descripción del Proyecto

**AirSense CEFA** monitorea y analiza en tiempo real las variables ambientales (concentración de CO₂, temperatura y humedad relativa) en los diferentes ambientes de formación del centro agroindustrial. 

La plataforma web permite a aprendices, instructores, personal EHS/SST y administradores visualizar datos en tiempo real, generar alertas tempranas ante situaciones de riesgo y aplicar protocolos de contingencia ambiental. 🌡️💨

---

## 🚀 Funcionalidades Principales

- 📈 **Monitoreo en Tiempo Real**: Visualización interactiva de niveles de CO₂ (ppm), Temperatura (°C) y Humedad (%).
- 🚦 **Semaforización Ambiental**: Indicadores visuales instantáneos basados en umbrales normativos de calidad del aire.
- 👥 **Gestión por Roles y Permisos**:
  - **Administrador CEFA**: Gestión global de usuarios, ambientes y configuraciones.
  - **Instructor CEFA**: Monitoreo de sus ambientes asignados y reportes.
  - **EHS / SST (Seguridad y Salud)**: Gestión de contingencias, alertas críticas e inspecciones.
- 🔔 **Alertas Tempranas**: Avisos automáticos visuales y por correo electrónico.
- 📊 **Reportes e Históricos**: Gráficas de tendencia y descargas de datos.
- 📱 **Diseño Responsive & SENA Midnight**: Interfaz moderna adaptable a móviles, tablets y monitores.

---

## 🛠️ Arquitectura y Tecnologías

- **Backend Framework**: [Laravel](https://laravel.com/) (PHP 8.x)
- **Frontend / UI**: Blade Templates, TailwindCSS, Bootstrap 5, Vite, Vanilla JavaScript.
- **Base de Datos**: MySQL / MariaDB (Laragon).
- **IoT & Telemetría**: Nodos ESP32, Sensores NDIR MH-Z19B (CO₂), DHT22 (Temp/Hum), Protocolo MQTT.
- **Autenticación**: Laravel Breeze / Middleware de Roles Integrado.

---

## 📚 Documentación del Proyecto

El proyecto cuenta con guías completas para el trabajo en equipo y control de versiones bajo Git:

- 🚀 **[`docs/GUIA_RAPIDA_GIT.md`](file:///c:/laragon/www/airsense-cefa/docs/GUIA_RAPIDA_GIT.md)**: Guía rápida paso a paso para desarrolladores junior.
- 📘 **[`docs/GUIA_TRABAJO_EQUIPO.md`](file:///c:/laragon/www/airsense-cefa/docs/GUIA_TRABAJO_EQUIPO.md)**: Manual maestro de roles, ramas y conventional commits.
- 🔄 **[`docs/GUIA_MIGRACION_DEVELOP_A_MAIN.md`](file:///c:/laragon/www/airsense-cefa/docs/GUIA_MIGRACION_DEVELOP_A_MAIN.md)**: Guía de sincronización y migración entre `develop` y `main`.
- 📝 **[`docs/CONVENTIONAL_COMMITS_GUIA.md`](file:///c:/laragon/www/airsense-cefa/docs/CONVENTIONAL_COMMITS_GUIA.md)**: Estándar de nomenclatura para commits.

---

## 📁 Estructura Principal del Repositorio

```text
airsense-cefa/
├── app/                    # Controladores, Modelos y Lógica Laravel
│   ├── Http/Controllers/   # Controladores por módulos (Admin, EHS, Sensor)
│   └── Models/             # Modelos Eloquent (User, Role, Sensor, etc.)
├── docs/                   # Documentación oficial y guías del equipo
├── public/                 # Assets públicos compilados (Vite / CSS / JS)
├── resources/              # Vistas Blade, estilos TailwindCSS y JS fuente
│   └── views/              # Vistas organizadas por roles y layouts
├── routes/                 # Rutas de la aplicación (web.php)
└── tailwind.config.js      # Configuración de paleta institucional SENA
```

---

## ⚡ Instalación y Ejecución Local

1. **Clonar el repositorio**:
   ```bash
   git clone https://github.com/ingdevelopers449/airsensecefa.git
   cd airsense-cefa
   ```

2. **Instalar dependencias de PHP y Node**:
   ```bash
   composer install
   npm install
   ```

3. **Configurar el archivo de entorno**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Ejecutar migraciones**:
   ```bash
   php artisan migrate --seed
   ```

5. **Iniciar el servidor local y compilar assets**:
   ```bash
   php artisan serve
   npm run build # o npm run dev
   ```

---
*AirSense CEFA • Centro de Formación Agroindustrial La Angostura • SENA Regional Huila* 🟢
