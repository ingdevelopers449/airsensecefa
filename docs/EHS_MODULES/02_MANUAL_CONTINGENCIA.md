# 🩺 Módulo 2: Manual de Contingencia y Protocolos SST (EHS CEFA)

## 1. Visión General y Propósito
El **Manual de Contingencia** es la guía oficial de actuación preventiva y correctiva ante eventos de riesgo ambiental en el CEFA. Permite al rol de **EHS & SST** gestionar los protocolos, recomendaciones y acciones inmediatas que deben ejecutarse cuando se detectan niveles peligrosos de CO₂, altas temperaturas o anomalías ambientales.

---

## 2. Requerimientos Funcionales Relacionados (Elicitación)
* **RF-17 (Despliegue de Recomendaciones):** El sistema debe desplegar recomendaciones automáticas según las condiciones ambientales detectadas.
* **RF-18 (Gestión del Manual de Contingencia par el EHS):** Operaciones CRUD (Crear, Consultar, Editar, Eliminar) exclusivas del rol EHS para gestionar las recomendaciones organizadas por categoría de riesgo (exceso de CO₂, calor elevado, humedad alta).
* **RNF-08 (Seguridad de Protocolos):** Solo usuarios con rol SST/EHS pueden modificar o estructurar las directivas de contingencia.

---

## 3. Historias de Usuario Asociadas
* **HU-012 (Gestión de Protocolos de Contingencia):** Como Especialista de SST, necesito redactar y actualizar los protocolos de emergencia para que los instructores sepan cómo evacuar o ventilar un aula.
* **HU-013 (Consulta de Directivas de Evacuación/Ventilación):** Como EHS, necesito categorizar las recomendaciones para que se activen automáticamente según la gravedad del riesgo.

---

## 4. Funcionalidades e Información de la Interfaz
1. **Gestión de Protocolos (CRUD):**
   - **Categoría de Riesgo:** Exceso de Dióxido de Carbono (CO₂), Estrés Térmico (Alta Temperatura), Humedad Extrema.
   - **Nivel de Gravedad:** Nivel 1 (Preventivo/Amarillo), Nivel 2 (Crítico/Rojo).
   - **Título de la Recomendación:** Texto descriptivo breve (ej. *"Ventilación Cruzada e Inmediata"*).
   - **Descripción y Pasos de Actuación:** Instrucciones detalladas en texto plano para el instructor/aprendices.
2. **Despliegue Dinámico:** Visualización emergente en el dashboard e historial ante alertas críticas.

---

## 5. Origen de Datos y Modelos Laravel (MySQL)
* **Tabla Base de Datos:** `contingency_protocols` / `contingency_rules`
* **Campos:** `id`, `category` (`co2`, `temperature`, `humidity`), `risk_level` (`warning`, `danger`), `title`, `description`, `action_steps`, `created_by`, `updated_at`.
* **Ruta Sugerida:** `Route::resource('/ehscefa/contingencias', ContingenciaController::class)`
* **Vista:** `resources/views/ehscefa/contingencias/index.blade.php`
