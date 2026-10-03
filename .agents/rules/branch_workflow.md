# Reglas de Trabajo en Rama y Comandos Git - AirSense CEFA

## 🌿 Rama de Trabajo Exclusiva (`feature/nodo-gestion-ui`)

1. **Restricción de Rama:**
   - El agente debe trabajar exclusivamente en la rama de desarrollo **`feature/nodo-gestion-ui`**.
   - Se debe tener presente que la rama principal de integración del proyecto es **`develop`**.
   - Toda modificación de código, controladores, vistas o rutas se asumirá y orientará hacia la rama `feature/nodo-gestion-ui`.

## 📋 Salida Obligatoria de Comandos Git tras Realizar Cambios

Al finalizar o completar cualquier tarea que toque o modifique archivos del proyecto, el agente **DEBERÁ SIEMPRE** proporcionar al usuario la lista formateada de los comandos Git correspondientes:

1. **Preparación de archivos (`git add`):**
   - Especificar exactamente los archivos que fueron creados o editados.
   - Ejemplo: `git add app/Http/Controllers/Admin/NodoController.php routes/web.php`

2. **Confirmación local (`git commit`):**
   - Proponer un mensaje de commit claro, profesional y estructurado con relación a los cambios realizados.
   - Ejemplo: `git commit -m "feat(nodo-gestion): implementar vistas y rutas para la gestión de nodos"`

3. **Publicación en la rama (`git push`):**
   - Indicar el comando exacto para subir los cambios a la rama activa del usuario hacia el remoto:
   - Ejemplo: `git push origin feature/nodo-gestion-ui`

> ⚠️ *Nota: El comando `git push` siempre se entregará como sugerencia en texto para que el usuario o líder del proyecto lo ejecute manualmente, respetando la prohibición de ejecución automática de push.*
