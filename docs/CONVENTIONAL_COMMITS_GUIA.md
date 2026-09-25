# 📝 Guía de Commits Convencionales (Conventional Commits) - AirSense CEFA

Para mantener un historial claro, limpio y profesional en nuestro repositorio de Git, utilizaremos la convención estándar **Conventional Commits**.

---

## 📌 Estructura General de un Commit

```text
tipo(ámbito): descripción breve en minúsculas y modo imperativo
```

### Ejemplo:
- `feat(auth): agregar formulario de inicio de sesión con validaciones`
- `fix(ui): corregir desbordamiento del mapa en dispositivos móviles`

---

## 📋 Lista Completa de Prefijos Estándar

| Prefijo | Significado | ¿Cuándo usarlo? | Ejemplo |
| :--- | :--- | :--- | :--- |
| **`feat`** | **Feature (Característica)** | Al agregar una nueva funcionalidad o módulo al sistema. | `feat(sensors): implementar recepción de datos MQTT` |
| **`fix`** | **Bug Fix (Corrección)** | Al solucionar un error o falla en el código existente. | `fix(dashboard): corregir cálculo de promedio de CO2` |
| **`docs`** | **Documentation (Documentación)** | Al agregar o modificar archivos de documentación (`.md`, comentarios). | `docs: agregar guía de prefijos para el equipo` |
| **`style`** | **Estilos / Formato** | Cambios que no afectan la lógica (espacios, CSS, diseño, comillas). | `style(landing): ajustar colores y fuentes institucionales SENA` |
| **`refactor`** | **Refactorización** | Reescribir código para mejorarlo sin cambiar su comportamiento externo. | `refactor(models): optimizar relaciones Eloquent en User` |
| **`perf`** | **Performance (Rendimiento)** | Cambios que mejoran la velocidad o consumo de recursos de la app. | `perf(queries): indexar tabla sensor_readings` |
| **`test`** | **Pruebas** | Al agregar o modificar pruebas unitarias o de integración. | `test(auth): agregar pruebas de inicio de sesión` |
| **`chore`** | **Tareas / Mantenimiento** | Tareas de mantenimiento general, actualización de dependencias, etc. | `chore(deps): actualizar versión de Bootstrap` |
| **`build`** | **Sistema de Build** | Cambios en herramientas de compilación o empaquetado (Vite, npm, webpack). | `build(vite): configurar compilación de assets para producción` |
| **`ci`** | **Integración Continua** | Cambios en scripts o flujos de integración continua (GitHub Actions). | `ci: agregar workflow para ejecución de pruebas automáticas` |
| **`revert`** | **Reversión** | Al revertir un commit anterior que causó problemas. | `revert: "feat(auth): agregar formulario..."` |

---

## 🔍 Ámbitos (Scopes) Recomendados para AirSense CEFA

El **ámbito** ayuda a identificar rápidamente el módulo afectado. Ejemplos para nuestro proyecto:

- `(auth)`: Login, registros, roles y permisos.
- `(sensors)`: Sensores IoT, MQTT, lecturas.
- `(ui)`: Vistas Blade, Bootstrap, estilos CSS.
- `(db)` o `(seeders)`: Migraciones, esquemas SQL, seeders de base de datos.
- `(sst)`: Alertas, protocolos de seguridad y manuales.
- `(ai)`: Modelos predictivos y análisis de datos.

---

## ✅ Buenas Prácticas al Escribir Commits

1. **Usa el tiempo presente / imperativo:** Escribe `agregar` o `add` en vez de `agregado` o `added`.
2. **Sin punto al final:** `feat(ui): ajustar botón de inicio` (sin punto final).
3. **Commits pequeños y frecuentes:** Es preferible hacer 5 commits pequeños y claros que 1 commit gigante con 20 cambios distintos.
