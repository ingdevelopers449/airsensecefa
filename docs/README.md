# 📚 Documentación — AirSense CEFA

> Centro de Formación Agroindustrial La Angostura · SENA Regional Huila

---

## 📂 Estructura de la Carpeta `docs/`

```
docs/
├── guides/               # Guías de flujo de trabajo y buenas prácticas
│   ├── git-flow.md        → Flujo Git completo (ramas, push, merge, errores)
│   ├── team-workflow.md   → Roles del equipo y commits convencionales
│   └── laravel-setup.md   → Instalación de librerías y dependencias Laravel
│
├── schemas/              # Esquema de base de datos (única fuente de verdad)
│   └── database_schema.md
│
├── diagrams/             # Diagramas de arquitectura y flujos del sistema
│
├── modules/              # Documentación de módulos específicos
│   └── ehs_modules/      → Módulos EHS/SST
│
├── tasks/                # Tareas y asignaciones del equipo
│   ├── asignacion-equipo.md → Resumen de roles y ramas asignadas
│   └── tareas/              → Manuales individuales por desarrollador
│
├── hardware/             # Documentación de hardware IoT (ESP32, sensores)
└── databasesql/          # Scripts SQL (creación, seeders, backups)
```

---

## 📋 Índice de Guías

| Documento | Descripción |
|-----------|-------------|
| [`guides/git-flow.md`](guides/git-flow.md) | Flujo Git completo: ramas, commits, push, merge y solución de errores |
| [`guides/team-workflow.md`](guides/team-workflow.md) | Roles del equipo, asignación de módulos y convención de commits |
| [`guides/laravel-setup.md`](guides/laravel-setup.md) | Instalación de Composer, NPM, Telescope y migraciones |
| [`schemas/database_schema.md`](schemas/database_schema.md) | Esquema completo de la base de datos del sistema |
| [`tasks/asignacion-equipo.md`](tasks/asignacion-equipo.md) | Tabla de tareas y ramas Git por integrante |

---

## 👥 Equipo de Desarrollo

| Integrante | Rol | Rama |
|-----------|-----|------|
| **Luis Felipe Lozada Bastidas** | Líder de Desarrollo IoT | `develop` / `main` |
| **Isabella Sifuentes Perdomo** | Analítica de Datos | `feature/ui-welcome-login` |
| **Michael Gustavo Castaño Pareja** | Analítica de Datos & IA | `feature/telemetria-graficas` |
| **Lizbeth Dayana Daza Rogelis** | Soporte Telemetría & Redes | `feature/nodo-gestion-ui` |

---
*AirSense CEFA · Documentación oficial del proyecto*
