# 📘 Guía Completa de Sustentación: Asignación de Ambientes a Nodos IoT (AirSense CEFA)

Esta guía contiene la explicación detallada, paso a paso y sin tecnicismos complejos, de todo lo que se construyó en el módulo de **Gestión de Nodos IoT**. Sirve como documento de consulta personal para entender las conexiones entre archivos, cómo viajan los datos y cómo explicar el proyecto en una presentación.

---

## 🎯 1. ¿Qué fue lo que se construyó y creó?

Se implementó el flujo completo de **Gestión y Asignación de Ambientes para Dispositivos IoT** (ESP32):

1. **Tabla Principal de Nodos (`index.blade.php`)**:
   - Muestra todos los nodos registrados en el sistema.
   - Muestra el estado de conectividad en tiempo real (insignia verde `Online`, roja `Offline` o gris `Desconocido`).
   - Muestra el ambiente de formación asignado (con ícono 🏢 o el texto *"Sin asignar"*).
   - Incluye la fecha de última transmisión formateada (`d/m/Y H:i`).

2. **Ventana Emergente (Modal) con Formulario**:
   - Al presionar el botón morado **"Asignar Ambiente"**, se despliega una ventana flotante específica para ese nodo.
   - Contiene un menú desplegable (`<select>`) cargado dinámicamente con todas las aulas de formación activas en la base de datos.
   - Permite al usuario administrador seleccionar una nueva aula y presionar **"Guardar Asignación"**.

3. **Mensaje de Confirmación (Flash Message)**:
   - Al guardar, la página se recarga mostrando un aviso verde en la parte superior (*"Ambiente asignado correctamente"*).

4. **Organización de Estilos CSS (`styles.css`)**:
   - Todos los estilos de la tabla, los botones, las insignias de conectividad y la ventana emergente fueron centralizados en el archivo general `resources/css/styles.css` sin necesidad de código CSS dentro de la vista Blade.

---

## 🧭 2. El Mapa de Conexión entre Archivos (¿Cómo viajan los datos?)

```
[ NAVEGADOR ]
     │
     │  1. El usuario entra a http://127.0.0.1:8000/admin/nodos
     ▼
[ RUTAS: routes/web.php ]
     │  Route::get('/nodos', [NodoController::class, 'index'])->name('nodos');
     ▼
[ CONTROLADOR: app/Http/Controllers/Admin/NodoController.php ]
     │  Ejecuta la función index()
     │
     ├─► Consulta 1: Node::with('environment')->get()  ──► (Trae los nodos)
     ├─► Consulta 2: Environment::where('is_active', true)->get() ──► (Trae las aulas)
     │
     ▼  Envía $nodes y $environments mediante compact()
[ VISTA BLADE: resources/views/admin/nodos/index.blade.php ]
     │
     │  Renderiza la tabla y construye un <form> dentro de un modal por cada nodo
     │
     │  2. El usuario elige un aula en el <select> y presiona "Guardar"
     ▼
[ RUTAS: routes/web.php ]
     │  Route::post('/nodos/asignar-ambiente', [NodoController::class, 'asignarAmbiente']);
     ▼
[ CONTROLADOR: NodoController@asignarAmbiente ]
     │
     ├─► 1. Valida que el nodo y el ambiente existan ($request->validate)
     ├─► 2. Busca el nodo por su ID (Node::findOrFail)
     ├─► 3. Actualiza la columna environment_id
     ├─► 4. Guarda físicamente en la base de datos ($node->save())
     │
     ▼  Redirige a 'admin.nodos' con un mensaje de éxito (.with('success'))
[ NAVEGADOR ]
     │  Muestra la tabla actualizada + El aviso verde de confirmación arriba.
```

---

## 📄 3. Explicación Archivo por Archivo

### 1. El Controlador (`app/Http/Controllers/Admin/NodoController.php`)
- **`index()`**: Se encarga de consultar la base de datos y preparar las variables. Usa `with('environment')` para realizar una consulta optimizada ("Eager Loading") que une la tabla de nodos con la de ambientes sin sobrecargar el servidor.
- **`asignarAmbiente()`**: Recibe los datos enviados por el usuario desde el formulario del modal.
  ```php
  $node = Node::findOrFail($request->node_id); // Encuentra el nodo
  $node->environment_id = $request->environment_id; // Cambia el aula
  $node->save(); // Guarda en MySQL
  ```

### 2. Las Rutas (`routes/web.php`)
Están protegidas dentro del grupo con autenticación y prefijo `admin`:
- `GET /admin/nodos`: Apunta a `NodoController@index` (Carga la página).
- `POST /admin/nodos/asignar-ambiente`: Apunta a `NodoController@asignarAmbiente` (Guarda el formulario).
- `Route::redirect('/nodos', '/admin/nodos')`: Redirección de cortesía para evitar errores 404 si el usuario escribe la URL sin el prefijo `/admin`.

### 3. La Vista (`resources/views/admin/nodos/index.blade.php`)
- Utiliza `@forelse ($nodes as $node)` para recorrer la lista de nodos.
- Para el desplegable `<select>`, utiliza un `@foreach ($environments as $env)` para llenar las opciones con los nombres de las aulas.
- La expresión `{{ $node->environment_id == $env->id ? 'selected' : '' }}` se asegura de dejar marcada como seleccionada el aula que el nodo tiene asignada actualmente.
- Incluye la directiva `@csrf` para la protección de seguridad en formularios de Laravel.

### 4. La Hoja de Estilos (`resources/css/styles.css`)
Contiene las clases CSS limpias del sistema:
- `.tabla-nodos` y `.tabla-contenedor`: Estructura responsive con bordes redondeados y sombra suave.
- `.badge-conectividad`: Píldora de color verde (`online`), rojo (`offline`) o gris (`unknown`).
- `.btn-asignar`: Botón interactivo para abrir la ventana emergente.
- `.alerta-exito`: Banner verde con ícono de check para notificar al usuario.

---

## 🧪 4. Datos de Prueba para Demostración (`database/seeders/InitialDataSeeder.php`)

Para la demostración en vivo se crearon los siguientes registros:

**Aulas creadas:**
1. `Aula de Formación 101 - CEFA`
2. `Laboratorio de Procesamiento de Alimentos`
3. `Hangar de Ganadería y Bovinos`
4. `Centro de Acopio Agroindustrial`
5. `Taller de Maquinaria Agrícola`

**Nodos creados:**
- **Nodo #1 (`ESP32_XX5R69`)**: Asignado a *Aula 101* (Online).
- **Nodo #2 (`ESP32_LAB_002`)**: Asignado a *Laboratorio de Alimentos* (Online).
- **Nodo #3 (`ESP32_AGRO_003`)**: **Sin asignar** (Offline) ➔ *Ideal para mostrar la asignación en vivo.*
- **Nodo #4 (`ESP32_HAN_004`)**: Asignado a *Hangar de Ganadería* (Online).

---

## ❓ 5. Preguntas Frecuentes para Sustentar

1. **¿Cómo sabe el servidor qué nodo se está editando?**
   - El formulario dentro del modal incluye un campo oculto: `<input type="hidden" name="node_id" value="{{ $node->id }}">`. Al enviar el formulario, el controlador recibe ese `node_id` y sabe exactamente cuál registro modificar.

2. **¿Qué es `@csrf` y por qué es obligatorio?**
   - Es una directiva de seguridad de Laravel contra ataques CSRF (*Cross-Site Request Forgery*). Genera un token único para validar que la solicitud viene del formulario legítimo de nuestra aplicación.

3. **¿Por qué se usa `compact('nodes', 'environments')` en el controlador?**
   - Es una función nativa de PHP que toma las variables `$nodes` y `$environments` y las empaqueta en un arreglo asociativo para que la vista Blade pueda acceder a ellas directamente.

---

## 🛠️ 6. ¿Cómo reemplazar los datos de prueba cuando el sistema pase a PRODUCCIÓN?

Para la fase de pruebas y sustentación, las pestañas superiores de filtrado muestran contadores dinámicos:
- **Registrados**: `{{ $nodes->count() }}` (Calcula en tiempo real la cantidad total de nodos en la BD).
- **No registrados**: `$unregisteredCount = 1;` (Muestra 1 para demostración de pruebas).
- **Cambio de ubicación**: `$locationChangedCount = 1;` (Muestra 1 para demostración de pruebas).

### Pasos para conectar datos 100% reales en producción:

1. **En el Controlador (`app/Http/Controllers/Admin/NodoController.php`)**:
   Buscar las líneas donde se definen las variables `$unregisteredCount` y `$locationChangedCount` y reemplazar los números fijos por la consulta deseada a la base de datos:

   ```php
   // REEMPLAZAR ESTO:
   // $unregisteredCount = 1;
   // $locationChangedCount = 1;

   // POR CONSULTAS REALES (Ejemplo):
   $unregisteredCount = Node::where('environment_id', null)->count();
   $locationChangedCount = Node::where('connectivity_status', 'offline')->count();
   ```

2. **En la Vista (`resources/views/admin/nodos/index.blade.php`)**:
   No requieres realizar ningún cambio visual, ya que las etiquetas `{{ $unregisteredCount }}` y `{{ $locationChangedCount }}` recibirán el número exacto que calcule la base de datos.

