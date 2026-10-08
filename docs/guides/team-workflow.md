# 📘 Guía de Trabajo en Equipo y Commits Convencionales — AirSense CEFA

> Documento unificado que consolida `GUIA_TRABAJO_EQUIPO.md` y `CONVENTIONAL_COMMITS_GUIA.md`.

---

## 👥 Estructura de Roles y Módulos

Para evitar conflictos de edición en Git, el proyecto se divide por **módulos independientes**:

| Integrante | Rol | Área de Trabajo | Archivos Típicos |
|-----------|-----|----------------|-----------------|
| **Líder — Luis Felipe** | Líder Técnico / Backend Core | Backend global, seguridad API, deploys | `routes/web.php`, `app/Http/Controllers/Admin/*`, `app/Models/*` |
| **Isabella** | Analítica de Datos / Frontend | Vistas welcome, login, ajustes UI | `resources/views/layouts/*`, `resources/views/admin/*`, `resources/css/*` |
| **Michael** | Analítica de Datos & IA | Sensores IoT, MQTT, gráficas predictivas | `app/Http/Controllers/SensorController.php`, `app/Models/SensorReading.php`, JS de gráficas |
| **Lizbeth** | Soporte Telemetría & Redes | Backend EHS/SST, permisos, alertas, nodos | `app/Http/Controllers/EhsController.php`, `resources/views/ehscefa/*`, migraciones |

> ⚠️ **Regla crítica:** Nunca asignes a dos desarrolladores cambios simultáneos sobre el mismo archivo Blade o Controller.

---

## 🌿 Estrategia de Ramas

```
main (producción, solo el líder)
 └── develop (integración de avances)
      ├── feature/isabella-ui-welcome-login
      ├── feature/michael-telemetria-graficas
      ├── feature/lizbeth-nodo-gestion-ui
      └── feature/mapa (rama actual del líder)
```

---

## 🔄 Flujo de Trabajo Completo

> Para el detalle de comandos Git día a día, consulta [`guides/git-flow.md`](./git-flow.md).

### Para desarrolladores junior:
1. `git checkout develop && git pull origin develop`
2. `git checkout -b feature/mi-tarea`
3. Programar → `git add .` → `git commit -m "feat(...): ..."`
4. `git push -u origin feature/mi-tarea`
5. Crear Pull Request hacia `develop` en GitHub.

### Para el Líder / Administrador:
- Revisar PR en GitHub → Approve → Merge.
- Migrar `develop` → `main` cuando el sprint esté listo.

---

## 📝 Convención de Commits (Conventional Commits)

### Estructura general
```text
tipo(ámbito): descripción breve en imperativo y minúsculas
```

### Ejemplos:
- `feat(auth): agregar formulario de inicio de sesión con validaciones`
- `fix(ui): corregir desbordamiento del mapa en dispositivos móviles`
- `docs: actualizar manual del equipo con nuevos roles`

### Prefijos permitidos

| Prefijo | Significado | Cuándo usarlo | Ejemplo |
|---------|-------------|--------------|---------|
| **`feat`** | Feature / Característica | Nueva funcionalidad | `feat(sensors): implementar recepción MQTT` |
| **`fix`** | Bug Fix / Corrección | Solucionar un error | `fix(dashboard): corregir cálculo de promedio CO2` |
| **`docs`** | Documentación | Modificar archivos `.md` o comentarios | `docs: agregar guía de prefijos` |
| **`style`** | Estilo / Formato | CSS, espacios, no afecta lógica | `style(sidebar): ajustar colores institucionales` |
| **`refactor`** | Refactorización | Mejorar código sin cambiar comportamiento | `refactor(models): optimizar relaciones Eloquent` |
| **`perf`** | Rendimiento | Mejoras de velocidad | `perf(queries): indexar tabla sensor_readings` |
| **`test`** | Pruebas | Agregar/modificar tests | `test(auth): agregar pruebas de login` |
| **`chore`** | Mantenimiento | Actualización de dependencias, CI | `chore(deps): actualizar versión de Bootstrap` |
| **`build`** | Sistema de Build | Cambios en Vite, npm | `build(vite): configurar compilación para producción` |
| **`ci`** | Integración continua | GitHub Actions, pipelines | `ci: agregar workflow de pruebas automáticas` |
| **`revert`** | Reversión | Revertir un commit anterior | `revert: "feat(auth): agregar formulario..."` |

### Ámbitos recomendados para AirSense CEFA

| Ámbito | Módulo |
|--------|--------|
| `(auth)` | Login, registros, roles y permisos |
| `(sensors)` | Sensores IoT, MQTT, lecturas |
| `(ui)` | Vistas Blade, Bootstrap, estilos CSS |
| `(db)` / `(seeders)` | Migraciones, esquemas SQL, seeders |
| `(sst)` | Alertas, protocolos de seguridad, manuales |
| `(ai)` | Modelos predictivos y análisis de datos |
| `(map)` | Mapa interactivo y geolocalización |

### Buenas prácticas

1. **Tiempo presente / imperativo:** Escribe `agregar`, no `agregado`.
2. **Sin punto al final:** `feat(ui): ajustar botón de inicio` ✅
3. **Commits pequeños y frecuentes:** Mejor 5 commits claros que 1 gigante.

---
*Manual oficial de desarrollo · AirSense CEFA — SENA La Angostura*
