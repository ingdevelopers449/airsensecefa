# 🛡️ Arquitectura y Documentación de Enrutamiento por Roles - AirSense CEFA

Este documento detalla la implementación y arquitectura del sistema de enrutamiento basado en roles de usuario para redirigir a cada perfil a su respectivo Dashboard.

---

## 📐 1. Estructura de la Base de Datos y Modelos

El sistema utiliza una relación de uno a muchos (**1:N**) entre la tabla `roles` y la tabla `users`:

* **Tabla `roles` (`App\Models\Role`):** Contiene los roles del sistema:
  - `id: 1` ➡️ **Administrador** (`ADMIN`)
  - `id: 2` ➡️ **Funcionario de SST** (`SST`)
  - `id: 3` ➡️ **Instructor** (`INSTRUCTOR`)
* **Tabla `users` (`App\Models\User`):** Almacena la llave foránea `role_id` vinculada a cada usuario.

### Modelo `User.php`:
Se habilitó el atributo `role_id` en el `$fillable`:
```php
#[Fillable([
    'role_id',
    'name',
    'email',
    'password',
    'is_active',
])]
```

---

## 🔀 2. Lógica de Enrutamiento (`routes/web.php`)

La ruta principal `/` inspecciona si existe una sesión activa utilizando la fachada `Auth`.

### Código Implementado:

```php
<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // 1. Verificar si el usuario ha iniciado sesión
    if (Auth::check()) {
        $role = Auth::user()->role_id;

        // Redirección condicional según el rol asignado
        if ($role == 1) {
            return view('admin.dashboard');
        } elseif ($role == 2) {
            return view('ehscefa.dashboard');
        } elseif ($role == 3) {
            return view('instructor.dashboard');
        }
    }

    // 2. Si es un visitante sin sesión, cargar la Landing Page pública
    return view('welcome');
});
```

---

## 💻 3. Vistas Blade por Rol

Se crearon los componentes de vistas individuales en la ruta `resources/views/`:

1. **Administrador:** `resources/views/admin/dashboard.blade.php`
2. **SST / EHS:** `resources/views/ehscefa/dashboard.blade.php`
3. **Instructor:** `resources/views/instructor/dashboard.blade.php`

---

## 🔒 4. Manejo de Errores y Seguridad

- **`Auth::check()`:** Previene el error `Attempt to read property "role_id" on null` asegurando que no se consulte la propiedad `role_id` si el usuario no se ha autenticado.
- **Flujo de Navegación:** Otorga una experiencia transparente enviando a los usuarios logueados directamente a su panel operativo y a los usuarios externos a la presentación del proyecto.
