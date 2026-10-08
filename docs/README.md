# 📚 Documentación — AirSense CEFA

> **Centro de Formación Agroindustrial La Angostura · SENA Regional Huila**
>
> Este documento es el **índice oficial** de toda la documentación del proyecto. Aquí encontrarás una descripción clara de cada carpeta y de cada archivo, para que cualquier integrante del equipo pueda orientarse y encontrar lo que necesita rápidamente.

---

## 🗂️ Árbol General de la Carpeta `docs/`

```
docs/
│
├── README.md                        ← Este archivo (índice general)
├── database_schema.md               ← Esquema de base de datos (raíz)
│
├── guides/                          ← Guías de flujo de trabajo del equipo
│   ├── git-flow.md
│   ├── team-workflow.md
│   └── laravel-setup.md
│
├── schemas/                         ← Copia organizada del esquema de base de datos
│   └── database_schema.md
│
├── EHS_MODULES/                     ← Documentación técnica de cada módulo EHS/SST
│   ├── 01_DASHBOARD_MONITOREO.md
│   ├── 02_MANUAL_CONTINGENCIA.md
│   ├── 03_ALERTAS_CRITICAS.md
│   ├── 04_NODOS_IOT_MAPA.md
│   ├── 05_ESTADO_HARDWARE.md
│   ├── 06_HISTORIAL_AIRE.md
│   ├── 07_REPORTES_PROTEGIDOS.md
│   └── 08_ANALISIS_PREDICTIVO_IA.md
│
├── backend/                         ← Documentación de la capa de backend
│   ├── DOCUMENTACION_API_TELEMETRIA_IOT.md
│   ├── DOCUMENTACION_LARAVEL_TELESCOPE.md
│   └── ENRUTAMIENTO_ROLES.md
│
├── contexto/                        ← Contexto general e histórico del proyecto
│   └── contexto_airsense_cefa.md
│
├── proceso/                         ← Documentos de proceso y elicitación de requisitos
│   ├── elicitacionairsense.md
│   └── historiaarisense.md
│
├── databasesql/                     ← Scripts SQL de la base de datos
│   └── airsensecefa.sql
│
├── hardware/                        ← Firmware y librerías del hardware IoT
│   ├── FIRMWARE_ESP32_AIRSENSE/     ← Código fuente del firmware ESP32
│   ├── FIRMWARE_ESP32_AIRSENSE.rar  ← Versión comprimida del firmware
│   └── libraries/                   ← Librerías Arduino/ESP32 utilizadas
│
├── tareas/                          ← Manuales de tarea por integrante del equipo
│   ├── TAREA_LIDER_PROYECTO.md
│   ├── TAREA_ISABELLA.md
│   ├── TAREA_LIZBETH.md
│   └── TAREA_MICHAELL.md
│
├── tasks/                           ← Asignación global de tareas y ramas Git
│   └── asignacion-equipo.md
│
└── diagrams/                        ← Diagramas de arquitectura (por agregar)
```

---

## 📁 Descripción Detallada de Cada Carpeta y Archivo

---

### 📖 `guides/` — Guías de flujo de trabajo

Contiene las guías que todo el equipo debe conocer para trabajar correctamente con Git y Laravel.

| Archivo | Descripción |
|---------|-------------|
| [`git-flow.md`](guides/git-flow.md) | **Guía unificada de Git Flow.** Explica la estrategia de ramas (`main`, `develop`, `feature/...`), el flujo diario del desarrollador junior, cómo el líder aprueba Pull Requests, cómo migrar de `develop` a `main`, y la solución a errores comunes como `[rejected] (fetch first)`. |
| [`team-workflow.md`](guides/team-workflow.md) | **Roles del equipo y Conventional Commits.** Define quién trabaja en qué módulo del proyecto (con archivos típicos), la nomenclatura de ramas, y el estándar de mensajes de commit (`feat`, `fix`, `docs`, `style`, `refactor`, etc.) con ejemplos concretos para AirSense CEFA. |
| [`laravel-setup.md`](guides/laravel-setup.md) | **Instalación de dependencias.** Pasos para clonar el repositorio, instalar librerías PHP (`composer install`), instalar dependencias de frontend (`npm install && npm run build`), configurar el `.env`, ejecutar migraciones y activar Laravel Telescope. |

---

### 🗃️ `schemas/` — Esquema de base de datos

Contiene la fuente única de verdad del esquema de la base de datos. **No editar manualmente**; cualquier cambio estructural debe venir de una migración Laravel.

| Archivo | Descripción |
|---------|-------------|
| [`database_schema.md`](schemas/database_schema.md) | Describe todas las tablas del sistema: `users`, `roles`, `nodes`, `sensor_readings`, `ambientes`, `alertas`, etc. Incluye columnas, tipos de dato, relaciones (foreign keys) y propósito de cada tabla. |

---

### 🛡️ `EHS_MODULES/` — Módulos del Sistema EHS/SST

Documentación técnica de cada módulo visible para el rol **EHS / Seguridad y Salud en el Trabajo**. Cada archivo sigue la numeración del menú lateral de la aplicación.

| Archivo | Descripción |
|---------|-------------|
| [`01_DASHBOARD_MONITOREO.md`](EHS_MODULES/01_DASHBOARD_MONITOREO.md) | Dashboard principal del usuario EHS: mapa interactivo con nodos IoT, semáforo ambiental en tiempo real (CO₂, Temperatura, Humedad) y tarjeta de variables. |
| [`02_MANUAL_CONTINGENCIA.md`](EHS_MODULES/02_MANUAL_CONTINGENCIA.md) | Módulo de manuales de contingencia: listado de protocolos de actuación ante situaciones de riesgo ambiental. |
| [`03_ALERTAS_CRITICAS.md`](EHS_MODULES/03_ALERTAS_CRITICAS.md) | Sistema de alertas críticas: registro y visualización de eventos donde los sensores superan umbrales de seguridad establecidos por norma. |
| [`04_NODOS_IOT_MAPA.md`](EHS_MODULES/04_NODOS_IOT_MAPA.md) | Gestión y visualización de nodos IoT sobre el mapa del CEFA: ubicación geográfica, estado de conexión y datos más recientes de cada nodo. |
| [`05_ESTADO_HARDWARE.md`](EHS_MODULES/05_ESTADO_HARDWARE.md) | Estado de hardware: monitoreo del estado de cada dispositivo ESP32 (conectado/desconectado), última lectura y nivel de batería o señal. |
| [`06_HISTORIAL_AIRE.md`](EHS_MODULES/06_HISTORIAL_AIRE.md) | Historial de calidad del aire: gráficas de tendencia temporal por nodo y variable (CO₂, temperatura, humedad) con filtros por rango de fechas. |
| [`07_REPORTES_PROTEGIDOS.md`](EHS_MODULES/07_REPORTES_PROTEGIDOS.md) | Módulo de reportes: generación y descarga de informes en PDF protegidos, con resumen de condiciones ambientales por período. |
| [`08_ANALISIS_PREDICTIVO_IA.md`](EHS_MODULES/08_ANALISIS_PREDICTIVO_IA.md) | Análisis predictivo con IA: proyecciones de calidad del aire basadas en datos históricos, usando modelos de machine learning integrados vía API. |

---

### ⚙️ `backend/` — Documentación del Backend

Referencia técnica para los controladores, rutas y herramientas de monitoreo del servidor.

| Archivo | Descripción |
|---------|-------------|
| [`DOCUMENTACION_API_TELEMETRIA_IOT.md`](backend/DOCUMENTACION_API_TELEMETRIA_IOT.md) | Documenta los endpoints de la API REST que reciben datos de los sensores ESP32 vía HTTP/MQTT. Incluye estructura del payload JSON, headers requeridos, autenticación con API key y ejemplos de respuesta. |
| [`DOCUMENTACION_LARAVEL_TELESCOPE.md`](backend/DOCUMENTACION_LARAVEL_TELESCOPE.md) | Guía de uso de Laravel Telescope: cómo acceder al panel de monitoreo de peticiones, consultas SQL, logs y eventos del sistema en el entorno de desarrollo. |
| [`ENRUTAMIENTO_ROLES.md`](backend/ENRUTAMIENTO_ROLES.md) | Describe el sistema de rutas y middleware de roles: cómo están configuradas las rutas en `web.php` para los roles `admin`, `instructor`, `ehs` y `aprendiz`, y cómo añadir nuevas rutas protegidas. |

---

### 📋 `contexto/` — Contexto del Proyecto

| Archivo | Descripción |
|---------|-------------|
| [`contexto_airsense_cefa.md`](contexto/contexto_airsense_cefa.md) | **Documento maestro de contexto.** Describe en profundidad el problema que soluciona AirSense CEFA, los usuarios del sistema, el entorno del CEFA La Angostura, los requisitos funcionales y no funcionales, y las decisiones de arquitectura tomadas durante el desarrollo. |

---

### 📊 `proceso/` — Documentos de Proceso y Análisis

| Archivo | Descripción |
|---------|-------------|
| [`elicitacionairsense.md`](proceso/elicitacionairsense.md) | **Elicitación de requisitos.** Contiene las entrevistas, encuestas y técnicas de levantamiento de información realizadas para entender las necesidades del CEFA y de los usuarios del sistema. |
| [`historiaarisense.md`](proceso/historiaarisense.md) | **Historia del proyecto.** Documento detallado con el historial de desarrollo, decisiones de diseño, sprints realizados, problemas encontrados y soluciones aplicadas a lo largo del ciclo de vida del proyecto. |

---

### 🗄️ `databasesql/` — Scripts SQL

| Archivo | Descripción |
|---------|-------------|
| [`airsensecefa.sql`](databasesql/airsensecefa.sql) | **Backup completo de la base de datos.** Script SQL de exportación que incluye la estructura de todas las tablas y los datos semilla (usuarios, roles, nodos, ambientes, etc.). Útil para restaurar la base de datos en un entorno nuevo. |

---

### 🔧 `hardware/` — Firmware y Hardware IoT

| Archivo / Carpeta | Descripción |
|-------------------|-------------|
| [`FIRMWARE_ESP32_AIRSENSE/`](hardware/FIRMWARE_ESP32_AIRSENSE/) | **Código fuente del firmware.** Proyecto Arduino/ESP32 que se carga en los microcontroladores de los nodos IoT. Incluye la lógica de lectura de sensores (CO₂ MH-Z19B, DHT22), conexión WiFi y envío de datos al backend vía HTTP. |
| [`FIRMWARE_ESP32_AIRSENSE.rar`](hardware/FIRMWARE_ESP32_AIRSENSE.rar) | Versión comprimida del firmware para distribución y respaldo. |
| [`libraries/`](hardware/libraries/) | Librerías de Arduino/ESP32 necesarias para compilar el firmware (ej. `PubSubClient` para MQTT, `DHT` sensor library, driver MH-Z19B). |

---

### 📝 `tareas/` — Manuales de Tarea por Integrante

Cada archivo contiene las instrucciones específicas paso a paso para que cada desarrollador realice su asignación sin interferir con el trabajo de los demás.

| Archivo | Integrante | Descripción |
|---------|-----------|-------------|
| [`TAREA_LIDER_PROYECTO.md`](tareas/TAREA_LIDER_PROYECTO.md) | **Luis Felipe Lozada** | Tareas del líder: gestión del backend core, API de telemetría, seguridad y configuración global. |
| [`TAREA_ISABELLA.md`](tareas/TAREA_ISABELLA.md) | **Isabella Sifuentes** | Tareas de frontend: mejoras visuales en las vistas `welcome` y `login`, ajustes de diseño responsivo. |
| [`TAREA_LIZBETH.md`](tareas/TAREA_LIZBETH.md) | **Lizbeth Dayana Daza** | Tareas de gestión de nodos IoT: vistas de listado, creación y edición de nodos desde el panel de administración. |
| [`TAREA_MICHAELL.md`](tareas/TAREA_MICHAELL.md) | **Michael Gustavo Castaño** | Tareas de telemetría y analítica: implementación de gráficas de tendencia, semáforos de color y análisis predictivo. |

---

### 📌 `tasks/` — Asignación Global del Equipo

| Archivo | Descripción |
|---------|-------------|
| [`asignacion-equipo.md`](tasks/asignacion-equipo.md) | Tabla resumen con los 4 integrantes, su rol, la rama Git asignada y el enlace a su manual de tarea individual. Vista rápida para el líder. |

---

### 📐 `diagrams/` — Diagramas de Arquitectura *(en construcción)*

Carpeta destinada a almacenar diagramas de arquitectura del sistema, flujos de datos y diagramas de la base de datos en formato de imagen o Mermaid.

---

## 👥 Equipo de Desarrollo

| Integrante | Rol | Rama Git |
|-----------|-----|----------|
| **Luis Felipe Lozada Bastidas** | Líder de Desarrollo IoT | `develop` / `main` |
| **Isabella Sifuentes Perdomo** | Analítica de Datos | `feature/ui-welcome-login` |
| **Michael Gustavo Castaño Pareja** | Analítica de Datos & IA | `feature/telemetria-graficas` |
| **Lizbeth Dayana Daza Rogelis** | Soporte Telemetría & Redes | `feature/nodo-gestion-ui` |

---

## 🔗 Repositorio

GitHub: [github.com/ingdevelopers449/airsensecefa](https://github.com/ingdevelopers449/airsensecefa)

---

*AirSense CEFA · Documentación oficial del proyecto · SENA La Angostura*
