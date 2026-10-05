# 📈 Módulo 6: Historial de Aire y Registro Epidemiológico (EHS CEFA)

## 1. Visión General y Propósito
El módulo de **Historial de Aire** permite realizar consultas epidemiológicas y análisis retrospectivos de hasta **1 año de lecturas ambientales** almacenadas en el sistema. Es la herramienta clave para auditar tendencias de CO₂, picos de temperatura e investigar patrones de riesgo en los ambientes de formación.

---

## 2. Requerimientos Funcionales Relacionados (Elicitación)
* **RF-11 (Trazabilidad de Eventos Automáticos):** Reconstruir la secuencia temporal de lecturas, cambios de semáforo y picos de contaminación.
* **RF-14 (Almacenamiento Continuo en BD):** Persistencia ilimitada de mediciones de sensores en MySQL.
* **RF-20 (Anonimización del Historial de Aforo):** Desvincular nombres de instructores/estudiantes en las consultas históricas de ocupación para proteger la privacidad.

---

## 3. Historias de Usuario Asociadas
* **HU-020 (Consulta Histórica de Lecturas):** Como EHS, necesito consultar el comportamiento del CO₂ en un aula durante los últimos 6 meses para evaluar si se requiere instalar extractores de aire.
* **HU-021 (Comparativa entre Ambientes):** Comparar la calidad del aire de dos talleres o laboratorios en el mismo rango de fechas.

---

## 4. Funcionalidades e Interfaz de Usuario
1. **Filtros Avanzados de Consulta:**
   - **Rango de Fechas:** Seleccionar fecha inicio y fecha fin (hasta 365 días hacia atrás).
   - **Ambiente / Nodo:** Filtrar por un ambiente específico o consultar todos.
   - **Variable Ambiental:** Alternar entre CO₂ (ppm), Temperatura (°C), Humedad (%) o PM2.5.
2. **Gráficos Interactivos de Series de Tiempo (Chart.js / ApexCharts):**
   - Líneas de tendencia con zoom interactivo.
   - Resaltado visual de picos que superaron el umbral permisible.
3. **Tabla de Resultados Paginada:** Muestra fecha/hora exacta, nodo, lectura medida y semáforo resultante.

---

## 5. Origen de Datos y Modelos Laravel (MySQL)
* **Modelos:** `App\Models\SensorReading`, `App\Models\SensorMeasurement`, `App\Models\Node`
* **Vista:** `resources/views/ehscefa/historico/index.blade.php`
