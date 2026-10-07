# 📊 Módulo 1: Dashboard de Monitoreo Ambiental (EHS CEFA)

## 1. Visión General y Propósito
El **Dashboard de Monitoreo Ambiental** es el panel principal de control para el rol de **EHS & SST (Seguridad y Salud en el Trabajo)**. Proporciona una vista ejecutiva e interactiva en tiempo real sobre la calidad del aire de todos los ambientes de formación y zonas del Centro de Formación Agroindustrial "La Angostura" (CEFA).

---

## 2. Requerimientos Funcionales Relacionados (Elicitación)
* **RF-01 (Visualización en Tiempo Real):** El sistema debe permitir visualizar en tiempo real los valores de CO₂, Temperatura y Humedad reportados por los nodos IoT.
* **RF-02 (Indicadores de Semáforo Ambiental):** Clasificación por colores según umbrales predefinidos (🟢 *Normal*, 🟡 *Precaución*, 🔴 *Alerta Crítica*).
* **RF-03 (Consola de Resumen Global):** Mostrar el estado consolidado de la calidad del aire del CEFA.
* **RF-04 (Ciclo de Actualización):** Actualización continua de mediciones cada 1 minuto (RNF-04).
* **RF-06 (Alertas Destacadas):** Tarjetas de acceso directo a zonas o ambientes con lecturas fuera de rango.
* **RF-29 (Visualización de Variables en Mapa):** Acceso rápido al mapa digital interactivo.

---

## 3. Historias de Usuario Asociadas
* **HU-001 (Monitoreo General del Centro):** Como Funcionario de SST, necesito ver el estado global de la calidad del aire para saber si hay algún riesgo inminente en las instalaciones.
* **HU-002 (Visualización de Semáforos Ambientales):** Como EHS, necesito identificar de un vistazo las áreas en estado crítico mediante colores intuitivos.
* **HU-003 (Métricas Promedio e Históricas del Día):** Consultar los máximos y mínimos de CO₂ y temperatura durante la jornada.

---

## 4. Información y Métricas a Desplegar en la Interfaz
1. **Tarjetas KPI Globales:**
   - **Calidad Global del Aire:** Estado general del CEFA (*Bueno*, *Moderado*, *Peligroso*).
   - **Promedio de CO₂ (ppm):** Valor medio global (ej. `435 ppm`).
   - **Promedio de Temperatura (°C):** Valor medio del centro (ej. `24.5 °C`).
   - **Promedio de Humedad (%):** Porcentaje medio (ej. `65%`).
   - **Nodos Activos en Transmisión:** Contabilidad de hardware funcionando (`X / Total`).
2. **Gráfica de Tendencia en Vivo:** Línea temporal del día mostrando la evolución del CO₂ y la temperatura.
3. **Grilla de Ambientes Monitoreados:** Tarjetas individuales por ambiente de formación mostrando su semáforo ambiental, valor actual y badge de estado.

---

## 5. Origen de Datos y Modelos Laravel (MySQL)
* **Modelo Principal:** `App\Models\Node`
* **Lecturas:** `App\Models\SensorReading` (vía `readings()`)
* **Mediciones:** `App\Models\SensorMeasurement` (`variable_type`, `value`, `unit`)
* **Controlador:** `App\Http\Controllers\Ehs\EHSController@index` (o `dashboard`)
* **Vista:** `resources/views/ehscefa/dashboard.blade.php`
