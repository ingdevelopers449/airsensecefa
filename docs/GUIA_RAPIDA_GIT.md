# 🚀 Guía Súper Fácil de Git: ¿Cómo Trabajar en Equipo Sin Conflictos?

¡Bienvenido a la guía rápida de desarrollo de **AirSense CEFA**! 🌿

Esta guía está diseñada para que **cualquier desarrollador la entienda en 2 minutos** y pueda trabajar sin romper nada ni causar errores.

---

## 🛑 Las 3 Reglas de Oro (Obligatorias)

1. **`main` es sagrada**: Nadie (excepto el Líder) sube cambios directo a la rama `main`.
2. **1 Tarea = 1 Rama**: Para cada tarea nueva que te asignen, creas una rama nueva.
3. **No toques archivos de otros**: Si tu compañero está haciendo el Login, tú no edites el Login.

---

## 💻 GUÍA PARA EL DESARROLLADOR JUNIOR (Día a Día)

Imagina que te asignaron la tarea: **"Crear la tabla de sensores"**. Sigue estos **4 pasos**:

### 📥 PASO 1: Descargar lo último que hizo el equipo
Abre tu terminal y escribe:
```bash
git checkout develop
git pull origin develop
```
*(Esto actualiza tu PC con la versión más reciente).*

---

### 🌿 PASO 2: Crear tu rama para LA TAREA
Crea una rama con el nombre de tu tarea:
```bash
git checkout -b feature/tabla-sensores
```
*(El comando `-b` crea la rama y te pasa a ella inmediatamente. ¡Ya estás listo para programar!)*

---

### 💾 PASO 3: Programar y guardar tus avances
Abre VS Code y programa tu tarea. Cuando termines o quieras guardar tu avance:

```bash
# 1. Preparar archivos
git add .

# 2. Guardar foto de tu trabajo (mensaje corto explicando qué hiciste)
git commit -m "feat(ui): crear tabla visual de sensores"

# 3. Subir tu rama a GitHub
git push -u origin feature/tabla-sensores
```

---

### 🔀 PASO 4: Pedir al Líder que apruebe tu tarea (Pull Request)
1. Entra a GitHub: [https://github.com/ingdevelopers449/airsensecefa](https://github.com/ingdevelopers449/airsensecefa).
2. Verás un botón amarillo que dice **"Compare & pull request"**. Haz clic en él.
3. En **Base**, asegúrate de que diga `develop`.
4. Haz clic en **Create Pull Request**.
5. ¡Listo! Avísale a tu Líder por WhatsApp/Chat que ya terminaste para que lo revise.

---

## 👑 GUÍA PARA EL ADMINISTRADOR / LÍDER (Tú)

Tu trabajo es revisar y juntar las tareas terminadas:

### 1️⃣ Aprobar la tarea en GitHub:
1. Entra a GitHub ➡️ pestaña **Pull Requests**.
2. Haz clic en la tarea del desarrollador.
3. Revisa los archivos cambiados en la pestaña **Files changed**.
4. Si todo está bien, presiona el botón verde **Approve** y luego **Merge Pull Request**.

### 2️⃣ Publicar la versión final a `main` (Cuando el sprint esté listo):
Desde tu consola ejecuta:
```bash
# 1. Pasarte a main
git checkout main

# 2. Descargar main actualizado
git pull origin main

# 3. Unir los avances de develop a main
git merge develop

# 4. Subir la versión estable a GitHub
git push origin main
```

---

## ⚡ RESUMEN SUPER RÁPIDO (Machete / Cheatsheet)

| ¿Qué quiero hacer? | Comando exacto que debo copiar |
| :--- | :--- |
| **Actualizar mi PC antes de empezar** | `git checkout develop` <br> `git pull origin develop` |
| **Crear mi rama para una tarea nueva** | `git checkout -b feature/nombre-de-mi-tarea` |
| **Ver en qué rama estoy parado** | `git branch` |
| **Ver qué archivos he modificado** | `git status` |
| **Guardar y subir mis cambios** | `git add .` <br> `git commit -m "feat: mi avance"` <br> `git push -u origin mi-rama` |
| **Iniciar servidor de Laravel** | `php artisan serve` |
| **Compilar estilos/Tailwind/JS** | `npm run build` |

---

### 🚨 ¿Qué hacer en caso de error?
Si al hacer `git push` te sale un mensaje de error en rojo:
1. No entres en pánico.
2. Escribe en tu consola:
   ```bash
   git pull origin develop
   ```
3. Vuelve a intentar hacer `git push`. ¡Casi siempre eso soluciona todo!
