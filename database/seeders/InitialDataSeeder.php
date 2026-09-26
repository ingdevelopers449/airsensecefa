<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Roles
        $roles = [
            ['name' => 'Administrador', 'code' => 'ADMIN', 'description' => 'Gestión completa de la plataforma.'],
            ['name' => 'Funcionario de SST', 'code' => 'SST', 'description' => 'Supervisión de Seguridad y Salud en el Trabajo.'],
            ['name' => 'Instructor', 'code' => 'INSTRUCTOR', 'description' => 'Consulta de monitoreo, ambiente asignado y registro de aforo.'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(['code' => $role['code']], $role);
        }

        // 2. Permisos
        $permissions = [
            ['code' => 'dashboard.view', 'name' => 'Ver Dashboard', 'module' => 'dashboard', 'description' => 'Consultar el dashboard y mapa de monitoreo.'],
            ['code' => 'users.manage', 'name' => 'Gestionar usuarios', 'module' => 'users', 'description' => 'Crear, consultar, actualizar y desactivar usuarios.'],
            ['code' => 'audit.view', 'name' => 'Consultar auditoría', 'module' => 'audit', 'description' => 'Consultar auditoría y autenticaciones fallidas.'],
            ['code' => 'thresholds.manage', 'name' => 'Gestionar umbrales', 'module' => 'configuration', 'description' => 'Gestionar umbrales ambientales.'],
            ['code' => 'nodes.view', 'name' => 'Consultar nodos', 'module' => 'nodes', 'description' => 'Consultar nodos registrados y no registrados.'],
            ['code' => 'nodes.manage', 'name' => 'Gestionar nodos', 'module' => 'nodes', 'description' => 'Registrar y editar nodos IoT.'],
            ['code' => 'map.manage', 'name' => 'Gestionar mapa', 'module' => 'map', 'description' => 'Gestionar puntos y ubicaciones de nodos.'],
            ['code' => 'environments.manage', 'name' => 'Gestionar ambientes', 'module' => 'configuration', 'description' => 'Gestionar ambientes de formación.'],
            ['code' => 'assignments.manage', 'name' => 'Gestionar asignaciones', 'module' => 'configuration', 'description' => 'Asignar instructores a ambientes.'],
            ['code' => 'monitoring.view', 'name' => 'Consultar monitoreo', 'module' => 'monitoring', 'description' => 'Consultar variables ambientales.'],
            ['code' => 'alerts.view', 'name' => 'Consultar alertas', 'module' => 'alerts', 'description' => 'Consultar alertas críticas y predictivas.'],
            ['code' => 'history.view', 'name' => 'Consultar históricos', 'module' => 'history', 'description' => 'Consultar históricos ambientales.'],
            ['code' => 'history.export', 'name' => 'Exportar históricos', 'module' => 'history', 'description' => 'Exportar históricos a Excel/PDF.'],
            ['code' => 'occupancy.create', 'name' => 'Registrar aforo', 'module' => 'occupancy', 'description' => 'Registrar cantidad anónima de ocupantes.'],
            ['code' => 'protocols.view', 'name' => 'Consultar protocolos', 'module' => 'sst', 'description' => 'Consultar recomendaciones institucionales.'],
            ['code' => 'protocols.manage', 'name' => 'Gestionar protocolos', 'module' => 'sst', 'description' => 'Gestionar recomendaciones/protocolos SST.'],
            ['code' => 'manual.view', 'name' => 'Consultar manual', 'module' => 'sst', 'description' => 'Consultar manual de contingencia.'],
            ['code' => 'manual.export', 'name' => 'Exportar manual', 'module' => 'sst', 'description' => 'Exportar manual a PDF.'],
            ['code' => 'manual.manage', 'name' => 'Gestionar manual', 'module' => 'sst', 'description' => 'Gestionar contenido del manual.'],
            ['code' => 'predictions.view', 'name' => 'Consultar predicciones', 'module' => 'ai', 'description' => 'Consultar predicciones y curvas.'],
            ['code' => 'system_events.view', 'name' => 'Consultar eventos automáticos', 'module' => 'audit', 'description' => 'Consultar trazabilidad automática.'],
            ['code' => 'report.nodes.view', 'name' => 'Consultar reporte de nodos', 'module' => 'reports', 'description' => 'Consultar reporte de nodos.'],
            ['code' => 'report.nodes.export', 'name' => 'Exportar reporte de nodos', 'module' => 'reports', 'description' => 'Exportar reporte de nodos.'],
            ['code' => 'maintenance.manage', 'name' => 'Gestionar mantenimiento', 'module' => 'maintenance', 'description' => 'Programar mantenimiento.'],
            ['code' => 'maintenance.view', 'name' => 'Consultar mantenimiento', 'module' => 'maintenance', 'description' => 'Consultar avisos de mantenimiento.'],
            ['code' => 'performance.view', 'name' => 'Consultar rendimiento', 'module' => 'performance', 'description' => 'Consultar capacidad y rendimiento.'],
        ];

        foreach ($permissions as $perm) {
            DB::table('permissions')->updateOrInsert(['code' => $perm['code']], $perm);
        }

        // 3. Asignación Rol -> Permisos
        $adminRole = DB::table('roles')->where('code', 'ADMIN')->first();
        if ($adminRole) {
            $allPerms = DB::table('permissions')->pluck('id');
            foreach ($allPerms as $permId) {
                DB::table('role_permissions')->insertOrIgnore([
                    'role_id' => $adminRole->id,
                    'permission_id' => $permId,
                ]);
            }
        }

        // 4. Umbrales Ambientales Iniciales
        $thresholds = [
            ['variable_type' => 'co2', 'operational_min' => 400.0, 'operational_max' => 5000.0, 'warning_max' => 800.0, 'critical_max' => 1000.0],
            ['variable_type' => 'temperature', 'operational_min' => null, 'operational_max' => null, 'warning_max' => null, 'critical_max' => null],
            ['variable_type' => 'humidity', 'operational_min' => null, 'operational_max' => null, 'warning_max' => null, 'critical_max' => null],
        ];

        foreach ($thresholds as $t) {
            DB::table('environmental_thresholds')->updateOrInsert(['variable_type' => $t['variable_type']], $t);
        }

        // 5. Categorías de Riesgo SST
        $categories = [
            ['code' => 'AIR_QUALITY', 'name' => 'Calidad de aire', 'description' => 'Protocolos relacionados con gases y calidad del aire.'],
            ['code' => 'CLIMATE', 'name' => 'Clima', 'description' => 'Protocolos relacionados con temperatura y humedad.'],
            ['code' => 'OCCUPANCY', 'name' => 'Aforo', 'description' => 'Protocolos relacionados con ocupación de ambientes.'],
        ];

        foreach ($categories as $cat) {
            DB::table('risk_categories')->updateOrInsert(['code' => $cat['code']], $cat);
        }

        // 6. Configuración Inicial del Sistema
        $settings = [
            ['setting_key' => 'dashboard_refresh_seconds', 'setting_value' => '60', 'value_type' => 'integer', 'description' => 'Intervalo máximo de actualización del dashboard.'],
            ['setting_key' => 'critical_alert_max_latency_ms', 'setting_value' => '2000', 'value_type' => 'integer', 'description' => 'Tiempo máximo esperado para reflejar alertas críticas.'],
            ['setting_key' => 'sensor_offline_after_seconds', 'setting_value' => '120', 'value_type' => 'integer', 'description' => 'Tiempo sin señal tras el cual un nodo puede considerarse fuera de línea.'],
        ];

        foreach ($settings as $s) {
            DB::table('system_settings')->updateOrInsert(['setting_key' => $s['setting_key']], $s);
        }

        // 7. Ambientes y Nodos Iniciales (Ejemplo de prueba)
        DB::table('environments')->updateOrInsert(
            ['code' => 'AULA_101'],
            [
                'name' => 'Aula de Formación 101 - CEFA',
                'description' => 'Ambiente de formación principal',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $envId = DB::table('environments')->where('code', 'AULA_101')->value('id');

        DB::table('nodes')->updateOrInsert(
            ['device_uid' => 'ESP32_XX5R69'],
            [
                'environment_id' => $envId,
                'name' => 'Nodo ESP32 La Angostura',
                'device_token_hash' => Hash::make('secret_token_abc123'),
                'token_version' => 1,
                'is_active' => true,
                'connectivity_status' => 'online',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
