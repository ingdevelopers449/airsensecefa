# AirSense CEFA

<p align="center">
  <strong>Sistema de Monitoreo de Calidad del Aire e Internet de las Cosas (IoT)</strong>
</p>

<p align="center">
  <a href="https://github.com/ingdevelopers449/airsensecefa">
    <img src="https://img.shields.io/badge/GitHub-Repository-181717?style=for-the-badge&logo=github&logoColor=white" alt="GitHub Repository">
  </a>
  <a href="https://laravel.com">
    <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  </a>
  <a href="https://www.php.net">
    <img src="https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  </a>
  <a href="https://tailwindcss.com">
    <img src="https://img.shields.io/badge/TailwindCSS-3.x-38BDF8?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="TailwindCSS">
  </a>
  <a href="https://www.mysql.com">
    <img src="https://img.shields.io/badge/MySQL-8.x-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  </a>
  <a href="https://mqtt.org">
    <img src="https://img.shields.io/badge/MQTT-Protocol-660066?style=for-the-badge&logo=eclipse-mosquitto&logoColor=white" alt="MQTT">
  </a>
</p>

<p align="center">
  <em>Centro de Formación Agroindustrial La Angostura — SENA Regional Huila</em>
</p>

---

## Descripcion del Proyecto

**AirSense CEFA** es una plataforma integral de monitoreo ambiental diseñada para los ambientes de formacion del **Centro de Formacion Agroindustrial La Angostura (CEFA)**. El sistema captura, procesa y visualiza en tiempo real variables criticas de calidad del aire, permitiendo a instructores, aprendices y personal EHS/SST tomar decisiones informadas y oportunas.

### Variables Monitoreadas

| Variable | Sensor | Unidad | Rango Tipico |
|----------|--------|--------|--------------|
| Concentracion de CO2 | MH-Z19B (NDIR) | ppm | 400 - 5000 |
| Temperatura | DHT22 | grados C | -40 - 80 |
| Humedad Relativa | DHT22 | % HR | 0 - 100 |

---

## Funcionalidades Principales

- **Monitoreo en Tiempo Real**: Visualizacion interactiva de niveles de CO2, temperatura y humedad con actualizacion continua via MQTT.
- **Semaforizacion Ambiental**: Indicadores visuales instantaneos basados en umbrales normativos de calidad del aire (Verde / Amarillo / Rojo).
- **Gestion por Roles y Permisos**:
  - **Administrador CEFA**: Gestion global de usuarios, ambientes y configuraciones del sistema.
  - **Instructor CEFA**: Monitoreo de sus ambientes asignados y generacion de reportes.
  - **EHS / SST (Seguridad y Salud en el Trabajo)**: Gestion de contingencias, alertas criticas e inspecciones.
- **Alertas Tempranas**: Avisos automaticos visuales y por correo electronico ante condiciones de riesgo.
- **Reportes e Historicos**: Graficas de tendencia temporal y descarga de datos en formatos exportables.
- **Diseño Responsive**: Interfaz moderna adaptable a moviles, tablets y monitores de escritorio.

---

## Arquitectura y Tecnologias

### Stack Tecnologico

| Capa | Tecnologia | Version |
|------|-----------|---------|
| Backend Framework | Laravel | 11.x |
| Lenguaje | PHP | 8.2+ |
| Frontend / UI | Blade Templates + TailwindCSS + Bootstrap 5 | - |
| Compilador de Assets | Vite | 5.x |
| Base de Datos | MySQL / MariaDB | 8.x / 10.x |
| Protocolo IoT | MQTT (Mosquitto) | - |
| Microcontrolador | ESP32 | - |
| Autenticacion | Laravel Breeze + Middleware de Roles | - |

### Arquitectura IoT

```
[Sensores] --> [ESP32] --> [MQTT Broker] --> [Laravel Backend] --> [Base de Datos]
                                                              |
                                                              v
                                                        [Dashboard Web]
```

---

## Estructura del Repositorio

```text
airsense-cefa/
├── app/
│   ├── Http/
│   │   ├── Controllers/       # Controladores por modulos (Admin, EHS, Sensor)
│   │   └── Middleware/        # Middleware de roles y autenticacion
│   ├── Models/                # Modelos Eloquent (User, Role, Sensor, etc.)
│   └── Services/              # Logica de negocio y procesamiento MQTT
├── config/                    # Configuraciones de la aplicacion
├── database/
│   ├── migrations/            # Migraciones de base de datos
│   └── seeders/               # Datos semilla
├── docs/                      # Documentacion oficial y guias del equipo
├── public/                    # Assets publicos compilados (Vite / CSS / JS)
├── resources/
│   ├── views/                 # Vistas Blade organizadas por roles y layouts
│   ├── css/                   # Estilos TailwindCSS
│   └── js/                    # JavaScript fuente
├── routes/                    # Rutas de la aplicacion (web.php, api.php)
├── storage/                   # Almacenamiento de logs y cache
├── tailwind.config.js         # Configuracion de paleta institucional SENA
└── vite.config.js             # Configuracion de Vite
```

---

## Instalacion y Ejecucion Local

### Requisitos Previos

- PHP 8.2 o superior
- Composer 2.x
- Node.js 18.x o superior
- MySQL 8.x o MariaDB 10.x
- Laragon (recomendado para entorno local)

### Pasos de Instalacion

**1. Clonar el repositorio**

```bash
git clone https://github.com/ingdevelopers449/airsensecefa.git
cd airsense-cefa
```

**2. Instalar dependencias de PHP y Node**

```bash
composer install
npm install
```

**3. Configurar el archivo de entorno**

```bash
cp .env.example .env
php artisan key:generate
```

> **Nota**: Asegurate de configurar las credenciales de la base de datos y los parametros del broker MQTT en el archivo `.env`.

**4. Ejecutar migraciones y seeders**

```bash
php artisan migrate --seed
```

**5. Iniciar el servidor local y compilar assets**

```bash
php artisan serve
npm run build
```

Para desarrollo con recarga en caliente:

```bash
npm run dev
```

---

## Documentacion del Proyecto

El proyecto cuenta con guias completas para el trabajo en equipo y control de versiones bajo Git:

| Documento | Descripcion |
|-----------|-------------|
| [`docs/GUIA_RAPIDA_GIT.md`](docs/GUIA_RAPIDA_GIT.md) | Guia rapida paso a paso para desarrolladores junior |
| [`docs/GUIA_TRABAJO_EQUIPO.md`](docs/GUIA_TRABAJO_EQUIPO.md) | Manual maestro de roles, ramas y conventional commits |
| [`docs/GUIA_MIGRACION_DEVELOP_A_MAIN.md`](docs/GUIA_MIGRACION_DEVELOP_A_MAIN.md) | Guia de sincronizacion y migracion entre `develop` y `main` |
| [`docs/CONVENTIONAL_COMMITS_GUIA.md`](docs/CONVENTIONAL_COMMITS_GUIA.md) | Estandar de nomenclatura para commits |
| [`docs/INSTALACION_LIBRERIAS_LARAVEL.md`](docs/INSTALACION_LIBRERIAS_LARAVEL.md) | Instalacion de librerias y dependencias Laravel |

---

## Equipo de Desarrollo

<p align="center">
  <strong>Organizacion GitHub:</strong>
  <a href="https://github.com/ingdevelopers449">
    <img src="https://img.shields.io/badge/ingdevelopers449-Organization-181717?style=for-the-badge&logo=github&logoColor=white" alt="GitHub Organization">
  </a>
</p>

| Rol | Desarrollador | GitHub |
|-----|---------------|--------|
| Desarrolladora | Isabella | [@isabella](https://github.com/isabella) |
| Desarrolladora | Lizbeth | [@lizbeth](https://github.com/lizbeth) |
| Desarrollador | Michaell | [@michaell](https://github.com/michaell) |

---

## Licencia

Este proyecto es de uso institucional para el **SENA - Centro de Formacion Agroindustrial La Angostura**.

---

<p align="center">
  <strong>AirSense CEFA</strong> &bull; Centro de Formacion Agroindustrial La Angostura &bull; SENA Regional Huila
</p>
