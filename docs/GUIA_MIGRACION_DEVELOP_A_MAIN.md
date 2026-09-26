# 🔄 Guía Definitiva: Sincronización y Migración entre `develop` y `main`

Esta guía te explica paso a paso la forma **correcta, segura y sin errores** para pasar cambios entre la rama de desarrollo (`develop`) y la rama de producción (`main`).

---

## ❓ ¿Por qué ocurrió el error `[rejected] (fetch first)`?

El error ocurre cuando la rama en GitHub (`origin/develop`) tiene algún commit o cambio que tu computadora local **no ha descargado aún**. 

Si intentas hacer `git push` sin haber descargado primero lo nuevo de GitHub, Git rechaza el envío para no sobrescribir nada.

---

## 🛠️ PASO A PASO CORRECTO DE MIGRACIÓN Y SINCRONIZACIÓN

### 1️⃣ Paso 1: Resolver el envío rechazado a `develop` (Solución Inmediata)

Estando en la rama `develop`:

```bash
# 1. Traer y fusionar los cambios de GitHub con tu local
git pull origin develop --no-rebase

# 2. Ahora sí subir los cambios unidos a GitHub
git push origin develop
```

---

### 👑 2️⃣ Paso 2: Flujo Estándar de Migración de `develop` 👉 a `main` (Producción)

Cada vez que quieras pasar los avances probados de `develop` a la rama estable `main`, sigue este orden exacto:

```bash
# 1. Cambiarse a develop y asegurarse de tener TODO lo de GitHub
git checkout develop
git pull origin develop

# 2. Cambiarse a main y actualizar main desde GitHub
git checkout main
git pull origin main

# 3. Traer todo lo de develop hacia main
git merge develop

# 4. Subir la nueva versión estable a main en GitHub
git push origin main

# 5. Volver a sincronizar develop con main (para mantener ambas ramas 100% idénticas)
git checkout develop
git merge main
git push origin develop
```

---

## ⚡ RESUMEN DE COMANDOS EN UNA SOLA LÍNEA (Copiar y Pegar)

Para migrar de `develop` a `main` y dejar ambas ramas perfectamente sincronizadas:

```bash
git checkout develop ; git pull origin develop ; git checkout main ; git pull origin main ; git merge develop ; git push origin main ; git checkout develop ; git merge main ; git push origin develop
```

---

## 📋 Reglas Rápidas de Diagnóstico

| Síntoma / Mensaje | Causa | Solución |
| :--- | :--- | :--- |
| `[rejected] develop -> develop (fetch first)` | GitHub tiene cambios que tu PC no ha descargado. | Ejecuta `git pull origin develop` y luego `git push origin develop`. |
| `Already up to date` | No hay nada nuevo por fusionar. | Todo está sincronizado correctamente. |
| `Automatic merge failed` | Conflicto en un archivo editado en dos partes distintas. | Abre VS Code, acepta los cambios correctos, ejecuta `git add .`, `git commit` y `git push`. |

---
*Documento oficial de control de versiones • AirSense CEFA*
