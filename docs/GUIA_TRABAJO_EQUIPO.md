# 📘 Manual de Trabajo en Equipo y Control de Versiones - AirSense CEFA

Bienvenido al manual maestro de flujo de trabajo para el desarrollo de **AirSense CEFA** (Centro de Formación Agroindustrial La Angostura • SENA Regional Huila).

Este documento unifica las guías de Git Flow, Conventional Commits, prevención de conflictos y los roles de trabajo entre el **Administrador del Proyecto (Líder)** y los **Desarrolladores Junior**.

---

## 👥 1. Estructura de Roles y Asignación de Tareas

Para evitar conflictos de edición en Git y mantener un flujo de trabajo ordenado, el proyecto se divide por **módulos independientes**.

### 👑 Rol: Administrador / Líder del Proyecto (Tú)
* **Responsabilidades**:
  - Asignar tareas claras a cada desarrollador.
  - Gestionar las ramas principales (`main` y `develop`).
  - Revisar y aprobar los **Pull Requests (PR)** en GitHub.
  - Realizar despliegues y fusiones a producción.
  - Resolver conflictos mayores cuando ocurran.

### 💻 Roles: Desarrolladores Junior (3 Integrantes)
Cada desarrollador trabaja de forma aislada en su respectiva área asignada:

1. **Desarrollador 1 - Frontend & Vistas (`resources/views/`, Tailwind, Bootstrap)**:
   - *Área de trabajo*: Interfaces gráficas, Blade layouts, tableros y diseño responsivo.
   - *Archivos típicos*: `resources/views/layouts/*`, `resources/views/admin/*`, `resources/css/*`.

2. **Desarrollador 2 - Telemetría, IoT & IA (`sensors`, MQTT, Analítica)**:
   - *Área de trabajo*: Recepción de datos de sensores, modelos predictivos y gráficos.
   - *Archivos típicos*: `app/Http/Controllers/SensorController.php`, `app/Models/SensorReading.php`, scripts JS de gráficas.

3. **Desarrollador 3 - Backend, Autenticación & SST (`auth`, Roles, Protocolos EHS)**:
   - *Área de trabajo*: Lógica de usuarios, permisos, alertas de seguridad y módulos EHS/SST.
   - *Archivos típicos*: `app/Http/Controllers/EhsController.php`, `resources/views/ehscefa/*`, migraciones.

> ⚠️ **REGLA DE ASIGNACIÓN**: Nunca asignes a dos desarrolladores cambios simultáneos sobre el mismo archivo Blade o Controller.

---

## 🌿 2. Estrategia de Ramas (Git Flow Simplificado)

El repositorio cuenta con la siguiente jerarquía de ramas:

* **`main` (Producción & Versión Estable)**:
  - Contiene solo código funcional, probado y aprobado.
  - **PROHIBIDO hacer commit o push directo a `main`** (especialmente los junior).
* **`develop` (Integración de Avances)**:
  - Rama intermedia donde se unen todos los Pull Requests antes de pasar a `main`.
* **Ramas de Características (`feature/...` o `fix/...`)**:
  - Ramas individuales temporales creadas por cada desarrollador para realizar su tarea.

### Nomenclatura de Ramas Individuales:
* `feature/nombre-desarrollador-tarea` (Ejemplo: `feature/isabella-ui-dashboard`)
* `feature/modulo-telemetria` (Ejemplo: `feature/michael-sensores-mqtt`)
* `fix/corregir-login` (Ejemplo: `fix/lizbeth-error-rutas`)

---

## 🔄 3. Flujo Paso a Paso para los Desarrolladores Junior

Cada desarrollador debe seguir religiosamente estos 5 pasos para realizar cualquier tarea:

### 1️⃣ Inicio del día (Sincronizarse con los avances del equipo)
Antes de escribir una sola línea de código, debe actualizar su local con lo que se ha unido a `develop` o `main`:
```bash
git checkout develop
git pull origin develop
```

### 2️⃣ Crear su rama de trabajo individual
```bash
git checkout -b feature/mi-tarea-asignada
```

### 3️⃣ Trabajar y realizar commits frecuentes (Conventional Commits)
Mientras programa, debe guardar fotos locales de sus avances con mensajes estandarizados:
```bash
git add .
git commit -m "feat(ui): implementar tarjetas de medicion de co2"
```

### 4️⃣ Subir la rama a GitHub
Al finalizar la jornada o terminar la tarea:
```bash
git push -u origin feature/mi-tarea-asignada
```

### 5️⃣ Abrir Pull Request (PR) en GitHub
1. Ir a GitHub: [https://github.com/ingdevelopers449/airsensecefa](https://github.com/ingdevelopers449/airsensecefa).
2. Hacer clic en **New Pull Request**.
3. Seleccionar:
   - **Base**: `develop` (o `main`)
   - **Compare**: `feature/mi-tarea-asignada`
4. Asignar al **Administrador (Tú)** como revisor y hacer clic en **Create Pull Request**.

---

## 🛡️ 4. Flujo Paso a Paso para el Administrador (Tú)

Como Administrador, tu rol es supervisar, integrar y publicar el código de forma segura:

### 1️⃣ Asignación de Tareas
- Define tareas en un tablero (Trello, GitHub Projects o WhatsApp).
- Especifica claramente qué carpeta/archivo puede tocar cada desarrollador.

### 2️⃣ Revisión del Pull Request (Code Review)
- Entra a la pestaña **Pull Requests** en GitHub.
- Revisa la pestaña **Files changed** (archivos modificados).
- Verifica que el código no tenga errores ni rompa otras secciones.

### 3️⃣ Aprobación y Merge
- Si todo está correcto, haz clic en **Approve** y luego **Merge Pull Request**.
- Si detectas errores, deja un comentario en el PR pidiendo los cambios al desarrollador junior.

### 4️⃣ Publicar a la Rama Principal (`main`)
Cuando la rama `develop` esté completa y probada:
```bash
git checkout main
git pull origin main
git merge develop
git push origin main
```

---

## 🛑 5. Solución de Desincronizaciones y Conflictos

### Error Frecuente: `[rejected] (fetch first)`
Ocurre cuando el repositorio remoto en GitHub tiene commits que la PC local del desarrollador no tiene.

**Solución para el desarrollador**:
```bash
# Estando en su rama:
git pull origin develop
git push origin feature/mi-tarea-asignada
```

### Resolver Conflictos de Merge
Si GitHub avisa que hay un conflicto al intentar unir un PR:
1. El desarrollador se pasa a su rama en su PC: `git checkout feature/mi-tarea-asignada`.
2. Ejecuta `git pull origin develop`.
3. Abre los archivos marcados en conflicto en VS Code.
4. Selecciona **Accept Current Change** o **Accept Incoming Change**.
5. Guarda el archivo, ejecuta:
   ```bash
   git add .
   git commit -m "fix(merge): resolver conflicto con rama develop"
   git push origin feature/mi-tarea-asignada
   ```

---

## 📝 6. Convención de Commits (Conventional Commits)

Todos los desarrolladores deben formatear los mensajes de commit de la siguiente manera:

```text
tipo(ámbito): descripción breve en imperativo y minúsculas
```

### Prefijos Permitidos:
* **`feat`**: Nueva funcionalidad (Ej: `feat(sensors): agregar vista de mapa de sensores`).
* **`fix`**: Corrección de un fallo (Ej: `fix(auth): corregir redireccion despues de logout`).
* **`style`**: Cambios visuales o CSS que no alteran lógica (Ej: `style(sidebar): ajustar colores del menu`).
* **`docs`**: Cambios en documentación (Ej: `docs: actualizar manual del equipo`).
* **`refactor`**: Reestructuración de código (Ej: `refactor(controllers): simplificar logica de filtros`).
* **`chore`**: Tareas de mantenimiento o dependencias (Ej: `chore: actualizar librerias de composer`).

---

## ⚡ 7. Comandos de Referencia Rápida

| Acción | Comando |
| :--- | :--- |
| Ver estado de mis archivos | `git status` |
| Ver en qué rama me encuentro | `git branch` |
| Cambiar a otra rama | `git checkout nombre-rama` |
| Crear y cambiar a rama nueva | `git checkout -b feature/nombre-tarea` |
| Actualizar con cambios de GitHub | `git pull origin develop` |
| Iniciar servidor local Laravel | `php artisan serve` |
| Compilar estilos Tailwind / JS | `npm run build` |

---
*Manual oficial de desarrollo • AirSense CEFA - SENA La Angostura*
