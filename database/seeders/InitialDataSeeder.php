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

<<<<<<< HEAD

        // 9. Coordenadas Iniciales y Delimitaciones CEFA
        $coordinatesData = [
            [
                'length' => '-75.361408',
                'latitude' => '2.612210',
                'description' => 'Centro CEFA La Angostura',
            ],
            // DELIMITACIONES CEFA
            ['latitude' => '2.616929', 'length' => '-75.360114', 'description' => 'Delimitación CEFA - Punto 1'],
            ['latitude' => '2.613478', 'length' => '-75.363858', 'description' => 'Delimitación CEFA - Punto 2'],
            ['latitude' => '2.606083', 'length' => '-75.363333', 'description' => 'Delimitación CEFA - Punto 3'],
            ['latitude' => '2.611187', 'length' => '-75.360993', 'description' => 'Delimitación CEFA - Punto 4'],
            ['latitude' => '2.611007', 'length' => '-75.360389', 'description' => 'Delimitación CEFA - Punto 5'],
            ['latitude' => '2.611638', 'length' => '-75.359757', 'description' => 'Delimitación CEFA - Punto 6'],
            ['latitude' => '2.610385', 'length' => '-75.357881', 'description' => 'Delimitación CEFA - Punto 7'],
            ['latitude' => '2.611971', 'length' => '-75.357673', 'description' => 'Delimitación CEFA - Punto 8'],
            ['latitude' => '2.614663', 'length' => '-75.358752', 'description' => 'Delimitación CEFA - Punto 9'],
            ['latitude' => '2.614821', 'length' => '-75.358625', 'description' => 'Delimitación CEFA - Punto 10'],
        ];

        foreach ($coordinatesData as $coord) {
            DB::table('coordinates')->insert(
                array_merge($coord, ['created_at' => now(), 'updated_at' => now()])
=======
        // 7. Ambientes de prueba
        $environmentsData = [
            ['code' => 'HAN_GAN', 'name' => 'Hangar de ganadería', 'description' => 'Agropecuaria', 'is_active' => true],
            ['code' => 'ACOPIO', 'name' => 'Centro de acopio', 'description' => 'Agroindustrial', 'is_active' => true],
            ['code' => 'AMB_204', 'name' => 'Ambiente 204', 'description' => 'Académica', 'is_active' => true],
            ['code' => 'LAB_FOOD', 'name' => 'Laboratorio de alimentos', 'description' => 'Laboratorio', 'is_active' => true],
            ['code' => 'ADMIN_BLK', 'name' => 'Bloque administrativo', 'description' => 'Administrativa', 'is_active' => true],
        ];

        foreach ($environmentsData as $envData) {
            DB::table('environments')->updateOrInsert(
                ['code' => $envData['code']],
                array_merge($envData, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        $hanGanId  = DB::table('environments')->where('code', 'HAN_GAN')->value('id');
        $acopioId  = DB::table('environments')->where('code', 'ACOPIO')->value('id');
        $amb204Id  = DB::table('environments')->where('code', 'AMB_204')->value('id');
        $labFoodId = DB::table('environments')->where('code', 'LAB_FOOD')->value('id');

        // 8. Nodos IoT de prueba con coordenadas exactas de la referencia
        $nodesData = [
            [
                'device_uid' => 'ESP-001',
                'environment_id' => $hanGanId,
                'name' => 'Hangar de ganadería',
                'device_token_hash' => Hash::make('secret_token_001'),
                'token_version' => 1,
                'is_active' => true,
                'connectivity_status' => 'online',
                'last_reported_latitude' => 2.92780,
                'last_reported_longitude' => -75.2810,
                'last_seen_at' => now(),
            ],
            [
                'device_uid' => 'ESP-002',
                'environment_id' => $acopioId,
                'name' => 'Centro de acopio',
                'device_token_hash' => Hash::make('secret_token_002'),
                'token_version' => 1,
                'is_active' => true,
                'connectivity_status' => 'online',
                'last_reported_latitude' => 2.92781,
                'last_reported_longitude' => -75.2811,
                'last_seen_at' => now()->subMinutes(5),
            ],
            [
                'device_uid' => 'ESP-003',
                'environment_id' => $amb204Id,
                'name' => 'Ambiente 204',
                'device_token_hash' => Hash::make('secret_token_003'),
                'token_version' => 1,
                'is_active' => true,
                'connectivity_status' => 'online',
                'last_reported_latitude' => 2.92782,
                'last_reported_longitude' => -75.2812,
                'last_seen_at' => now()->subMinutes(10),
            ],
            [
                'device_uid' => 'ESP-004',
                'environment_id' => $labFoodId,
                'name' => 'Laboratorio de alimentos',
                'device_token_hash' => Hash::make('secret_token_004'),
                'token_version' => 1,
                'is_active' => true,
                'connectivity_status' => 'online',
                'last_reported_latitude' => 2.92783,
                'last_reported_longitude' => -75.2813,
                'last_seen_at' => now()->subMinutes(12),
            ],
            [
                'device_uid' => 'ESP-005',
                'environment_id' => null,
                'name' => 'Bloque administrativo',
                'device_token_hash' => Hash::make('secret_token_005'),
                'token_version' => 1,
                'is_active' => true,
                'connectivity_status' => 'offline',
                'last_reported_latitude' => 2.92784,
                'last_reported_longitude' => -75.2814,
                'last_seen_at' => now()->subHours(3),
            ],
        ];

        foreach ($nodesData as $nodeData) {
            DB::table('nodes')->updateOrInsert(
                ['device_uid' => $nodeData['device_uid']],
                array_merge($nodeData, ['created_at' => now(), 'updated_at' => now()])
>>>>>>> origin/feacture/lizbeth
            );
        }
    }
}
