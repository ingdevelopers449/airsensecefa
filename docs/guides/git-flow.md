# 🚀 Guía de Flujo Git — AirSense CEFA

> Documento unificado que consolida las guías anteriores: `GUIA_RAPIDA_GIT.md`, `GIT_FLOW_GUIA.md` y `GUIA_MIGRACION_DEVELOP_A_MAIN.md`.

---

## 🛑 Las 3 Reglas de Oro (Obligatorias)

1. **`main` es sagrada**: Nadie (excepto el Líder) sube cambios directo a `main`.
2. **1 Tarea = 1 Rama**: Para cada tarea nueva que te asignen, crea una rama nueva.
3. **No toques archivos de otros**: Si tu compañero está trabajando en un módulo, no edites los mismos archivos.

---

## 📌 Estructura de Ramas

| Rama | Propósito |
|------|-----------|
| `main` | Versión final estable. **Prohibido hacer push directo.** |
| `develop` | Rama intermedia donde se unen todos los avances probados. |
| `feature/...` | Ramas individuales por tarea o funcionalidad. |

**Nomenclatura de ramas de trabajo:**
- `feature/nombre-desarrollador-tarea` → Ej: `feature/isabella-ui-dashboard`
- `fix/descripcion-error` → Ej: `fix/lizbeth-error-rutas`

---

## 💻 Flujo del Desarrollador Junior — Día a Día

### 1️⃣ Sincronizarse antes de empezar
```bash
git checkout develop
git pull origin develop
```

### 2️⃣ Crear la rama para tu tarea
```bash
git checkout -b feature/mi-tarea-asignada
```

### 3️⃣ Trabajar y guardar avances frecuentes
```bash
git add .
git commit -m "feat(ui): implementar tarjeta de medicion de co2"
```

### 4️⃣ Subir la rama a GitHub
```bash
git push -u origin feature/mi-tarea-asignada
```

### 5️⃣ Abrir Pull Request en GitHub
1. Ir a [github.com/ingdevelopers449/airsensecefa](https://github.com/ingdevelopers449/airsensecefa)
2. Clic en **"Compare & pull request"** (botón amarillo)
3. Configurar:
   - **Base:** `develop`
   - **Compare:** `feature/mi-tarea-asignada`
4. Crear el PR y notificar al Líder.

---

## 👑 Flujo del Administrador / Líder

### Revisar y aprobar un Pull Request
1. Ir a **Pull Requests** en GitHub.
2. Revisar **Files changed**.
3. Si está correcto: **Approve** → **Merge Pull Request**.
4. Si hay errores: dejar comentario pidiendo correcciones.

### Publicar versión estable a `main`
```bash
# 1. Asegurarse de tener develop actualizado
git checkout develop
git pull origin develop

# 2. Pasar a main y actualizarla
git checkout main
git pull origin main

# 3. Fusionar develop en main
git merge develop
git push origin main

# 4. Re-sincronizar develop con main
git checkout develop
git merge main
git push origin develop
```

**Versión resumida (una sola línea):**
```bash
git checkout develop ; git pull origin develop ; git checkout main ; git pull origin main ; git merge develop ; git push origin main ; git checkout develop ; git merge main ; git push origin develop
```

---

## ⚡ Cheatsheet de Comandos

| ¿Qué quiero hacer? | Comando |
|---------------------|---------|
| Ver en qué rama estoy | `git branch` |
| Ver archivos modificados | `git status` |
| Actualizar antes de empezar | `git checkout develop` → `git pull origin develop` |
| Crear rama nueva | `git checkout -b feature/nombre-tarea` |
| Guardar y subir cambios | `git add .` → `git commit -m "..."` → `git push -u origin mi-rama` |
| Iniciar servidor Laravel | `php artisan serve` |
| Compilar assets | `npm run build` |

---

## 🛑 Solución a Errores Comunes

### Error: `[rejected] (fetch first)`
**Causa:** GitHub tiene cambios que tu PC no tiene.  
**Solución:**
```bash
git pull origin nombre-de-tu-rama
git push origin nombre-de-tu-rama
```

### Error al resolver conflictos de Merge
1. Ir a la rama con conflicto: `git checkout feature/mi-tarea`
2. Traer cambios de develop: `git pull origin develop`
3. Abrir VS Code → aceptar los cambios correctos en cada archivo marcado.
4. Guardar y ejecutar:
```bash
git add .
git commit -m "fix(merge): resolver conflicto con rama develop"
git push origin feature/mi-tarea
```

### Error: `[rejected] main -> main (fetch first)` (tabla de diagnóstico)

| Síntoma | Causa | Solución |
|---------|-------|----------|
| `[rejected] (fetch first)` | GitHub tiene commits más recientes | `git pull origin develop` → `git push` |
| `Already up to date` | Todo está sincronizado | No se necesita acción |
| `Automatic merge failed` | Conflicto en un mismo archivo | Abrir VS Code, aceptar cambios, `git add .`, `git commit`, `git push` |

---
*Guía oficial de control de versiones · AirSense CEFA — SENA La Angostura*
