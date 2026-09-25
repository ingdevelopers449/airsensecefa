# 📖 Guía Visual de GitHub: Pull Requests y Solución de Conflictos

Esta guía te enseñará paso a paso cómo mover tus cambios desde una rama de característica (`feature/...`) hacia `develop` y luego hacia `main` usando la interfaz web de GitHub, además de solucionar el error común de desincronización.

---

## 🚀 Parte 1: Integrar cambios visualmente en la Web de GitHub

### Paso 1: Pasar de `feature/login-usuario` 👉 a `develop`

1. Entra a tu proyecto en GitHub: [https://github.com/ingdevelopers449/airsensecefa](https://github.com/ingdevelopers449/airsensecefa).
2. Ve a la pestaña **Pull Requests** (en el menú superior).
3. Haz clic en el botón verde **"New pull request"**.
4. Verás dos desplegables en la parte superior:
   - **`base:`** Selecciona `develop` *(A dónde quieres llevar el código)*.
   - **`compare:`** Selecciona `feature/login-usuario` *(De dónde viene tu código)*.
5. Haz clic en **"Create pull request"**.
6. Escribe un título o descripción breve y vuelve a hacer clic en **"Create pull request"**.
7. Finalmente, presiona el botón verde **"Merge pull request"** y luego **"Confirm merge"**.

¡Listo! Tu código de la rama `feature` ahora forma parte de `develop`.

---

### Paso 2: Pasar de `develop` 👉 a `main` (Publicar versión estable)

Cuando la rama `develop` tenga todas las características listas y probadas:

1. Ve nuevamente a **Pull Requests** ➡️ **"New pull request"**.
2. Configura las ramas así:
   - **`base:`** `main`
   - **`compare:`** `develop`
3. Haz clic en **"Create pull request"**.
4. Presiona **"Merge pull request"** y confirma.

---

## 🛑 Parte 2: Solución de Errores de Desincronización (`rejected - fetch first`)

### ¿Por qué ocurre este error?
Ocurre cuando hiciste cambios directamente en la web de GitHub (o un compañero tuyo subió código) y tu computadora local no tiene esos cambios al momento de hacer `git push`.

### 🛠️ Solución Paso a Paso desde la Consola

Si al hacer `git push` te sale el mensaje de rechazo:

```text
! [rejected]        main -> main (fetch first)
error: failed to push some refs...
```

**Ejecuta estos 3 comandos simples en tu terminal:**

```bash
# 1. Descargar y combinar los cambios de GitHub con tu PC
git pull origin nombre-de-tu-rama

# 2. Si te pide confirmar el mensaje de unión, presiona Enter o guarda el archivo
# 3. Vuelve a subir tus cambios a GitHub (ahora sí te dejará)
git push origin nombre-de-tu-rama
```

#### Ejemplo práctico si estás en `develop`:
```bash
git pull origin develop
git push origin develop
```

---

## 🎯 Ejercicio Práctico Sugerido

Para ponerlo en práctica ahora mismo sin temor a dañar nada:

1. Haz un `git status` en tu terminal para ver que este archivo [`docs/SOLUCION_GIT_DESINCRONIZACION.md`](file:///c:/laragon/www/airsense-cefa/docs/SOLUCION_GIT_DESINCRONIZACION.md) está listo para guardarse.
2. Agrega y guarda el cambio localmente:
   ```bash
   git add .
   git commit -m "docs: agregar guia de solucion de desincronizacion git y pull requests"
   ```
3. Sube este cambio a tu rama en GitHub:
   ```bash
   git push origin feature/login-usuario
   ```
4. Entra a GitHub y realiza el **Paso 1** (crear el Pull Request hacia `develop`).
