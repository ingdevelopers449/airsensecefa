# 🗺️ Módulo 4: Nodos IoT en Mapa Digital Interactivo (EHS CEFA)

## 1. Visión General y Propósito
El módulo de **Nodos IoT en Mapa** ofrece una representación espacial georreferenciada del Centro de Formación Agroindustrial "La Angostura" (CEFA). Permite al EHS ubicar geográficamente cada dispositivo, consultar el estado del semáforo ambiental en tiempo real y categorizar las áreas (Agrícola, Académica, Administrativa).

---

## 2. Requerimientos Funcionales Relacionados (Elicitación)
* **RF-29 (Visualización en Mapa Digital):** Ver el valor de cada variable ambiental en el punto exacto del mapa.
* **RF-34 (Registro de Nodos por Coordenadas GPS):** Graficar los puntos en el mapa utilizando latitud y longitud reportadas por el hardware ESP32 (módulo GPS NEO-6M).
* **RF-35 (Gestión de Categorías de Ubicación):** Clasificar los puntos del mapa (por ejemplo, Agrícola, Administrativo o Académico).
* **RF-36 (Relación Unívoca Mapa-ESP32):** Vincular cada marcador del mapa a un único nodo IoT mediante su UID.

---

## 3. Historias de Usuario Asociadas
* **HU-004 (Visualización Espacial del CEFA):** Como EHS, necesito ver un mapa del centro de formación para identificar qué bloque o galpón presenta problemas de calidad de aire.
* **HU-005 (Selección de Variables en Mapa):** Como Auditor de SST, necesito alternar entre ver el CO₂, la temperatura o la humedad en los marcadores del mapa.
* **HU-038 (Edición de Categoría y Nombre del Nodo):** Actualizar la información del punto sin alterar la identidad del hardware.

---

## 4. Funcionalidades e Interacción en el Mapa
1. **Mapa Interactivo (Leaflet.js / OpenStreetMap):**
   - Capa personalizada con el plano/mapa del CEFA "La Angostura".
   - Marcadores de colores dinámicos segun el semáforo (🟢 Normal, 🟡 Advertencia, 🔴 Peligro).
   - Tooltip emergente al hacer clic: muestra el nombre del ambiente, valores de sensores y fecha de lectura.
2. **Selector de Variable:** Botones superiores para cambiar el mapa a modo *Dióxido de Carbono (CO₂)*, *Temperatura (°C)* o *Humedad (%)*.
3. **Filtro por Categorías:** Ocultar o mostrar nodos de zonas Agrícolas, Aulas Académicas o Secciones Administrativas.

---

## 5. Origen de Datos y Modelos Laravel (MySQL)
* **Modelo Principal:** `App\Models\Node` (`latitude`, `longitude`, `name`, `location_category`)
* **Librería Frontend:** Leaflet.js
* **Vista:** `resources/views/ehscefa/nodos/mapa.blade.php`
