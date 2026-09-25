# 🚀 Guía de Git Flow y Feature Branching - AirSense CEFA

¡Bienvenido al equipo de desarrollo de **AirSense CEFA**! Para mantener nuestro código limpio, organizado y sin errores en producción, utilizaremos la metodología de trabajo basada en **Ramas de Características (Feature Branching)**.

Esta guía explica paso a paso cómo trabajar en Git de la manera más sencilla.

---

## 📌 1. Estructura de nuestras Ramas

* **`main` (Principal):** Es la rama donde vive la versión final, estable y sin errores del proyecto. **Nadie sube código directamente a `main`**.
* **`develop` (Desarrollo):** Es la rama donde se unen todos los avances probados del equipo.
* **`feature/...` (Ramas de trabajo de cada uno):** Es la rama personal o por funcionalidad donde trabajas diariamente en tu computadora.

---

## 🛠️ 2. Flujo de Trabajo Paso a Paso

### 📥 Paso 1: Actualizar tu repositorio antes de empezar
Antes de iniciar a programar en el día, siempre asegúrate de tener la última versión del proyecto:

```bash
# Cambiar a la rama de desarrollo
git checkout develop

# Descargar las novedades que subieron tus compañeros
git pull origin develop
```

---

### 🌿 Paso 2: Crear tu propia rama de trabajo
Para cada nueva tarea o módulo que vayas a realizar, crea una rama con un nombre claro usando el prefijo `feature/` o `dev/`:

```bash
# Ejemplo si vas a crear el módulo de login o dashboard:
git checkout -b feature/login-usuario

# O si vas a trabajar en analítica:
git checkout -b feature/prediccion-co2
```

> **Nota:** El comando `-b` crea la rama y te cambia a ella automáticamente.

---

### 💻 Paso 3: Desarrollar y guardar tus cambios locales
Mientras trabajas en tu código, guarda tus avances regularmente con estos dos comandos:

```bash
# 1. Preparar los archivos modificados
git add .

# 2. Guardar una foto de tus avances con un mensaje descriptivo
git commit -m "Se crea la vista inicial del dashboard de sensores"
```

---

### ☁️ Paso 4: Subir tu rama a GitHub
Cuando hayas terminado tu tarea o quieras hacer un respaldo en la nube:

```bash
# Reemplaza 'feature/login-usuario' por el nombre de tu rama
git push -u origin feature/login-usuario
```

---

### 🔀 Paso 5: Solicitar la integración a `develop` (Pull Request en GitHub)

1. Entra al repositorio en GitHub: [https://github.com/ingdevelopers449/airsensecefa](https://github.com/ingdevelopers449/airsensecefa)
2. Verás un botón amarillo que dice **"Compare & pull request"**. Haz clic en él.
3. Asegúrate de seleccionar:
   - **Base:** `develop`
   - **Compare:** `tu-rama-de-trabajo`
4. Escribe un breve resumen de lo que construiste y dale clic en **"Create Pull Request"**.
5. El **Líder del Proyecto** revisará tu código y lo unirá a `develop`.

---

## 💡 Comandos de Emergencia / Resumen Rápido

| ¿Qué quiero hacer? | Comando Git |
| :--- | :--- |
| Ver en qué rama estoy | `git branch` |
| Ver qué archivos he cambiado | `git status` |
| Cambiarme a la rama `develop` | `git checkout develop` |
| Traer cambios de GitHub | `git pull origin develop` |
| Crear nueva rama | `git checkout -b feature/nombre-tarea` |

---

¡Siguiendo estos pasos garantizamos un proyecto profesional, organizado y libre de errores! 🟢 AirSense CEFA - SENA La Angostura
