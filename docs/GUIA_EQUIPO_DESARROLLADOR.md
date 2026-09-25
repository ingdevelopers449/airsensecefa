# 👥 Guía del Equipo Desarrollador - AirSense CEFA

Bienvenido al equipo de desarrollo de **AirSense CEFA** (Centro de Formación Agroindustrial La Angostura &bull; SENA Regional Huila).

Este documento especifica las normas de trabajo, la arquitectura del proyecto y los pasos exactos para desarrollar y enviar tu código correctamente.

---

## 🏗️ 1. Arquitectura del Proyecto

El proyecto está construido sobre **Laravel** (PHP) con **Bootstrap 5** y arquitectura de base de datos modular.

### Módulos Principales del Sistema:
1. **Frontend / UI:** Vistas en Blade (`resources/views/`), diseño institucional SENA con CSS personalizado (`resources/css/styles.css`).
2. **Backend & Autenticación:** Modelos Eloquent y Controladores bajo el estándar nativo de Laravel (`users`, `roles`, `permissions`).
3. **Telemetría & IoT:** Ingesta de lecturas de sensores (CO₂, Temperatura, Humedad) y publicaciones MQTT.
4. **SST & Contingencia:** Protocolos de Seguridad y Salud en el Trabajo, manuales de contingencia y alertas tempranas.
5. **Inteligencia Artificial:** Modelos predictivos de calidad del aire y curvas de tendencia.

---

## 🌿 2. Reglas del Control de Versiones (Git)

### Nombres de Ramas Obligatorios
Nadie debe subir código directo a `main` ni a `develop`. Cada desarrollador debe crear una rama con su nombre o la característica que le fue asignada:

* **Isabella (Frontend & UI):** `feature/ui-dashboard` o `dev/isabella`
* **Michael (IA & Analítica):** `feature/modelo-prediccion` o `dev/michael`
* **Lizbeth (Telemetría & MQTT):** `feature/sensores-mqtt` o `dev/lizbeth`
* **Líder / Backend:** `feature/modulo-backend` o `dev/inglozada`

---

## 🔄 3. Ciclo de Trabajo Diario (Paso a Paso)

### 1️⃣ Inicio del día (Sincronizar cambios)
Antes de escribir una sola línea de código, descarga los últimos avances del equipo:
```bash
git checkout develop
git pull origin develop
```

### 2️⃣ Crear o cambiarse a tu rama de trabajo
```bash
# Si es la primera vez que creas tu rama:
git checkout -b feature/tu-funcionalidad

# Si tu rama ya existe, solo cámbiate a ella:
git checkout feature/tu-funcionalidad
```

### 3️⃣ Programar y guardar avances locales
Guarda commits pequeños y descriptivos:
```bash
git add .
git commit -m "feat(ui): diseñar componentes de tarjetas de sensores"
```

### 4️⃣ Subir tu rama a GitHub
Sube únicamente los cambios a la rama específica sobre la que estás trabajando:
```bash
# Ejemplo si estás en la rama feature/login-usuario:
git push origin feature/login-usuario

# O si es la primera vez que subes esa rama a GitHub:
git push -u origin feature/login-usuario
```
> **Recuerda:** Reemplaza `feature/login-usuario` por el nombre exacto de la rama en la que estás trabajando (`git branch`).

### 5️⃣ Solicitar aprobación (Pull Request)
1. Ve a GitHub: [https://github.com/ingdevelopers449/airsensecefa](https://github.com/ingdevelopers449/airsensecefa)
2. Abre un **Pull Request** apuntando de tu rama `feature/...` hacia `develop`.
3. Notifica al Líder del Proyecto para revisión e integración.

---

## 👑 4. ¿Cómo Integrar `develop` hacia `main`? (Líder del Proyecto)

Cuando la rama `develop` tenga nuevas funcionalidades completadas y probadas sin errores, el Líder ejecutará desde su consola:

```bash
# 1. Cambiarse a main
git checkout main

# 2. Traer los cambios aprobados de develop
git merge develop

# 3. Publicar la nueva versión estable en GitHub
git push origin main

# 4. Volver a la rama de desarrollo
git checkout develop
```

---

## ⚡ 5. Comandos Útiles del Proyecto

```bash
# Iniciar el servidor local de Laravel
php artisan serve

# Reconstruir assets CSS/JS
npm run build

# Ejecutar o refrescar migraciones de base de datos
php artisan migrate:fresh --seed
```
