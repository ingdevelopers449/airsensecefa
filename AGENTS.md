# Reglas Globales del Agente - AirSense CEFA

## 🚨 REGLA CRÍTICA DE CONTROL DE VERSIONES

- **PROHIBIDO EJECUTAR `git push`:** El agente NUNCA debe ejecutar `git push` hacia ningún repositorio remoto (GitHub, GitLab, etc.).
- El agente puede editar archivos, hacer pruebas, ejecutar comandos de build/migración y hacer `git commit` locales si se solicita.
- Toda acción de subir código a GitHub debe ser realizada manualmente por el usuario/líder del proyecto.

## 🌿 REGLA DE TRABAJO EN RAMA (`feature/nodo-gestion-ui`)

- **Rama Autorizada:** El agente únicamente trabajará y basará sus cambios en la rama `feature/nodo-gestion-ui` (teniendo en cuenta que la rama principal de integración es `develop`).
- **Flujo de Salida Git:** Cada vez que el agente realice modificaciones a archivos en el proyecto, deberá entregar explícitamente los comandos Git sugeridos:
  1. `git add <archivos tocados>`
  2. `git commit -m "<mensaje descriptivo de la tarea realizada>"`
  3. `git push origin feature/nodo-gestion-ui`

