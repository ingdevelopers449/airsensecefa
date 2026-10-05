# 📑 Módulo 7: Exportación de Reportes Protegidos (EHS CEFA)

## 1. Visión General y Propósito
El módulo de **Reportes Protegidos** permite a los especialistas de **EHS & SST** generar informes oficiales imprimibles (PDF) y hojas de cálculo (Excel). Los archivos PDF se emiten con firma digital hash de integridad y protección contra edición para fines de auditoría institucional ante el SENA o entes de salud pública.

---

## 2. Requerimientos Funcionales Relacionados (Elicitación)
* **RF-15 (Exportación de Reportes Protegidos):** 
  - Exportación en formatos **Excel (.xlsx)** y **PDF (.pdf)**.
  - Los archivos PDF deben ser generados **protegidos contra edición** (bloqueados para modificación).
  - Incluir un código de **verificación de integridad de datos (Hash SHA-256)** impreso en el pie de página para certificar que el reporte no fue alterado después de su generación.
* **RF-12 (Log de Auditoría Interna):** Registrar en el log de auditoría cada vez que un usuario exporta un reporte.

---

## 3. Historias de Usuario Asociadas
* **HU-025 (Generación de Informes PDF para Auditorías):** Como EHS, necesito exportar un reporte en PDF inmodificable con sello digital para presentar en las inspecciones de seguridad laboral.
* **HU-026 (Exportación Masiva a Excel):** Exportar las mediciones de un mes completo en Excel para análisis de datos estadísticos.

---

## 4. Estructura y Contenido del Reporte PDF
1. **Encabezado Institucional:** Logo SENA, nombre del Centro de Formación Agroindustrial "La Angostura", fecha de emisión y datos del funcionario EHS emisor.
2. **Resumen Ejecutivo:** Total de horas monitoreadas, porcentaje de tiempo en semáforo verde, amarillo y rojo.
3. **Tablas Estadísticas:** Máximos, mínimos y promedios por ambiente.
4. **Pie de Página de Seguridad (Hash de Integridad):**
   - **Hash SHA-256:** Cadena única hexadecimal de 64 caracteres.
   - **Mensaje de Validez:** *"Documento protegido generado por AirSense CEFA. Prohibida su alteración."*

---

## 5. Origen de Datos y Librerías Laravel
* **Generación PDF:** `barryvdh/laravel-dompdf`
* **Generación Excel:** `maatwebsite/excel`
* **Cálculo Hash:** `hash('sha256', $datosReporte)`
* **Vista:** `resources/views/ehscefa/reportes/index.blade.php`
