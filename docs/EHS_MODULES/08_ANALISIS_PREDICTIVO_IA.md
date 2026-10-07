# 🧠 Módulo 8: Análisis Predictivo con Inteligencia Artificial (EHS CEFA)

## 1. Visión General y Propósito
El módulo de **Análisis Predictivo basado en IA** utiliza técnicas de regresión y análisis de series de tiempo para proyectar los niveles futuros de Dióxido de Carbono (CO₂) y Temperatura en los ambientes del CEFA. Permite al EHS anticiparse a situaciones de sofocación o acumulación de gas antes de que ocurran.

---

## 2. Requerimientos Funcionales Relacionados (Elicitación)
* **RF-22 (Módulo Predictivo con IA):** Ejecución de modelos matemáticos y analíticos de proyección.
* **RF-23 (Requisito de Mínimo de Datos - 100 Lecturas / 24 Horas):** Para publicar una predicción confiable, el ambiente debe acumular mínimo 100 lecturas históricas. Si no las alcanza, el sistema debe marcarlo como **"En Aprendizaje"** y abstenerse de mostrar resultados no confiables.
* **RF-24 & RF-25 (Predicción de CO₂ y Variables):** Proyección a 1, 2 y 4 horas del comportamiento del aire.
* **RF-26 & RF-27 (Detección de Riesgo y Alertas Predictivas):** Emitir notificaciones preventivas antes de que se supere el umbral crítico.
* **RF-28 (Visualización Gráfica):** Gráficos con bandas de confianza e indicadores numéricos.

---

## 3. Historias de Usuario Asociadas
* **HU-030 (Predicción de Calidad del Aire Futura):** Como EHS, necesito saber si el CO₂ alcanzará niveles peligrosos a las 3:00 PM en un aula llena para sugerir un descanso previo.
* **HU-031 (Validación de Estado "En Aprendizaje"):** Evitar decisiones erróneas basadas en nodos recién instalados sin suficiente historial.

---

## 4. Métricas e Interfaz de Usuario
1. **Tarjeta de Estado del Modelo:**
   - **Confianza de la Predicción:** Porcentaje de precisión del modelo (ej. `94.2%`).
   - **Estado del Ambiente:** 🟢 *Estable*, 🟡 *Riesgo Proyectado*, 🔵 *En Aprendizaje (< 100 lecturas)*.
2. **Gráfico de Proyección Futura (Línea de Tiempo):**
   - **Línea Sólida:** Datos reales históricos registrados.
   - **Línea Punteada (Área Sombreada):** Proyección IA a las próximas 1, 2 y 4 horas con intervalo de confianza.
3. **Alertas Tempranas:** Alerta preventiva (ej. *"Atención: Se prevé que el ambiente alcance 1050 ppm de CO₂ a las 15:30 hrs"*).

---

## 5. Origen de Datos y Modelos Laravel / IA
* **Modelos:** `App\Models\Prediction`, `App\Models\SensorReading`
* **Lógica Predictiva:** Regresión Lineal / Polinomial en PHP o microservicio Python API.
* **Vista:** `resources/views/ehscefa/predictivo/index.blade.php`
