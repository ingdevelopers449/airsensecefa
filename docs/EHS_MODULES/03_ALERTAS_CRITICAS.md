# 🚨 Módulo 3: Gestión de Alertas Críticas (EHS CEFA)

## 1. Visión General y Propósito
El módulo de **Alertas Críticas** centraliza todas las notificaciones de eventos en los que los umbrales de bioseguridad ambiental fueron superados en cualquier nodo del CEFA. Permite al equipo EHS realizar acuso de recibo, seguimiento epidemiológico y verificar la atención inmediata de la emergencia.

---

## 2. Requerimientos Funcionales Relacionados (Elicitación)
* **RF-16 (Sistema de Notificación Dual):** Activación simultánea de dos canales: (1) alerta visual en la interfaz web, y (2) notificación por correo electrónico institucional.
* **RF-27 (Alertas Predictivas):** Recepción de notificaciones generadas con anticipación por el módulo de Inteligencia Artificial.
* **RF-29 (Visualización de Alertas por Ambiente):** Filtro rápido por nodo o ambiente de formación.

---

## 3. Historias de Usuario Asociadas
* **HU-010 (Recepción de Alertas por Correo y Web):** Como EHS, necesito ser notificado al instante cuando el CO₂ supere las 1000 ppm en un aula para intervenir oportunamente.
* **HU-011 (Seguridad y Registro de Alertas Atendidas):** Como Especialista de SST, necesito marcar como "atendida" una alerta y registrar la observación o medida tomada.

---

## 4. Estructura de la Interfaz y Filtros
1. **Consola de Registro de Alertas:**
   - **Ambiente / Nodo AFECTADO:** Nombre del área y UID del dispositivo.
   - **Variable que Disparó la Alerta:** CO₂, Temperatura o Humedad.
   - **Valor Medido vs. Umbral Permisible:** Ej. `1250 ppm` (Umbral: `1000 ppm`).
   - **Fecha y Hora Exacta:** Timestamp del evento.
   - **Canal de Notificación:** Estado de envío de correo y alerta en pantalla.
   - **Estado de Atención:** 🔴 *Pendiente*, 🟡 *En Proceso*, 🟢 *Resuelta*.
2. **Acciones del EHS:** Botón *"Marcar como Atendida"*, agregar nota de intervención SST.

---

## 5. Origen de Datos y Modelos Laravel (MySQL)
* **Modelo Principal:** `App\Models\Alert`
* **Campos Relacionados:** `id`, `node_id`, `variable_type`, `triggered_value`, `threshold_value`, `status` (`pending`, `in_progress`, `resolved`), `email_sent`, `resolved_by`, `resolved_at`, `notes`.
* **Notificaciones Laravel:** `App\Notifications\CriticalEnvironmentalAlert`
* **Vista:** `resources/views/ehscefa/alertas/index.blade.php`
