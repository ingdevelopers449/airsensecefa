# 📦 Guía de Instalación de Librerías y Dependencias — AirSense CEFA

Bienvenido a la guía oficial de instalación de paquetes y dependencias del proyecto **AirSense CEFA**. 

Dado que el proyecto utiliza herramientas como **Laravel Telescope** (para monitorear las peticiones IoT en vivo), **Laravel Breeze** y compiladores de assets, **todos los integrantes del equipo (Isabella, Lizbeth, Michaell)** deben ejecutar estos comandos en su portátil al clonar o actualizar el proyecto.

---

## 🚀 PASO A PASO: Instalación de Dependencias en tu Portátil

### 1. Clonar o Descargar los Cambios del Repositorio
Abre tu terminal en la carpeta de tus proyectos y ejecuta:
```bash
# Si es la primera vez que clonas:
git clone https://github.com/ingdevelopers449/airsensecefa.git
cd airsensecefa

# Si ya tenías el proyecto, pásate a la rama develop y actualiza:
git checkout develop
git pull origin develop
```

---

### 2. Instalar Librerías de PHP en Composer (Incluye Laravel Telescope)
Ejecuta el siguiente comando para descargar e instalar todas las dependencias de PHP registradas en `composer.json` (incluyendo Telescope y Breeze):

```bash
composer install
```

---

### 3. Instalar Dependencias de Frontend (NPM)
Para compilar los estilos CSS (Tailwind/Styles) e iconos del sistema:

```bash
npm install
npm run build
```

---

### 4. Configurar el Archivo de Entorno `.env`
Si es la primera vez que instalas el proyecto en tu máquina:

```bash
# 1. Copiar el archivo de ejemplo
cp .env.example .env

# 2. Generar la clave de encriptación de la aplicación
php artisan key:generate
```

---

### 5. Instalar y Migrar Laravel Telescope en MySQL
Para activar las tablas de monitoreo de peticiones IoT y Telescope en tu base de datos local:

```bash
# 1. Ejecutar las migraciones de tablas del sistema y Telescope
php artisan migrate

# 2. Publicar los assets e interfaz visual de Telescope
php artisan telescope:install
```

---

### 6. Verificar que Laravel Telescope Funciona en tu Navegador

Una vez completados los pasos anteriores, abre tu navegador e ingresa a:

👉 **`http://localhost/airsense-cefa/public/telescope`**  
*(O en el puerto 8000: `http://localhost:8000/telescope`)*

¡Listo! Ya tendrás instalado **Laravel Telescope** en tu portátil y podrás ver las peticiones de los sensores ESP32, los logs y las consultas a la base de datos en tiempo real.
