<?php

namespace App\Console\Commands;

use App\Models\Environment;
use App\Models\Node;
use App\Models\SensorMeasurement;
use App\Models\SensorReading;
use Illuminate\Console\Command;
<<<<<<< HEAD
=======
use Illuminate\Support\Facades\Hash;
>>>>>>> origin/feacture/lizbeth
use Illuminate\Support\Facades\Http;

class SyncCloudTelemetry extends Command
{
    /**
     * El nombre y firma del comando Artisan.
     *
     * @var string
     */
    protected $signature = 'sync:telemetry {--limit=20 : Número de registros a solicitar de la nube}';

    /**
     * Descripción del comando.
     *
     * @var string
     */
    protected $description = 'Descarga de Hostinger la telemetría real e inserta los registros en la base de datos MySQL local.';

    /**
     * Ejecuta el comando.
     */
    public function handle()
    {
        $limit = $this->option('limit');
        $cloudUrl = config('services.cloud_api.url', 'https://airsensecefa.site/api/v1/nodes/latest-telemetry');

        $this->info("📡 Conectando a Hostinger ({$cloudUrl})...");

        try {
            $response = Http::timeout(10)->get($cloudUrl, [
                'limit' => $limit,
            ]);

            if ($response->failed()) {
                $this->error("❌ Error consultando el servidor Hostinger. Código HTTP: " . $response->status());
                return 1;
            }

            $json = $response->json();
            $readings = $json['data'] ?? [];

            if (empty($readings)) {
                $this->warn("⚠️ No se encontraron lecturas recientes en la nube.");
                return 0;
            }

            $nuevos = 0;

            foreach ($readings as $item) {
                // 1. Asegurar la existencia del Nodo local
                $nodeData = $item['node'] ?? null;
                if (!$nodeData) continue;

                $node = Node::firstOrCreate(
                    ['device_uid' => $nodeData['device_uid']],
                    [
                        'name' => $nodeData['name'] ?? 'Nodo ESP32 (Sincronizado)',
<<<<<<< HEAD
                        'mac_address' => $nodeData['mac_address'] ?? ('MAC_' . substr(md5($nodeData['device_uid']), 0, 6)),
                        'device_token' => $nodeData['device_token'] ?? 'token_sync_auto',
=======
                        'device_token_hash' => Hash::make($nodeData['device_token'] ?? 'token_sync_auto'),
>>>>>>> origin/feacture/lizbeth
                        'connectivity_status' => 'online',
                        'last_seen_at' => now(),
                    ]
                );

                // Actualizar coordenadas GPS en el Nodo si están disponibles
                $reportedLat = $nodeData['latitude'] ?? ($item['reported_latitude'] ?? null);
                $reportedLng = $nodeData['longitude'] ?? ($item['reported_longitude'] ?? null);

                $updateData = [
                    'connectivity_status' => 'online',
                    'last_seen_at' => now(),
                ];

                if ($reportedLat && $reportedLng) {
<<<<<<< HEAD
                    $updateData['latitude'] = $reportedLat;
                    $updateData['longitude'] = $reportedLng;
=======
                    $updateData['last_reported_latitude'] = $reportedLat;
                    $updateData['last_reported_longitude'] = $reportedLng;
>>>>>>> origin/feacture/lizbeth
                }

                $node->update($updateData);

                // 2. Asegurar Ambiente local
                $envData = $item['environment'] ?? null;
                $environmentId = $node->environment_id;

                if ($envData && !$environmentId) {
                    $env = Environment::firstOrCreate(
                        ['code' => $envData['code'] ?? 'ENV_CLOUD'],
                        ['name' => $envData['name'] ?? 'Ambiente Nube', 'is_active' => true]
                    );
                    $environmentId = $env->id;
                    $node->update(['environment_id' => $environmentId]);
                }

                // 3. Crear lectura en sensor_readings local evitando duplicados por message_id
                $messageId = $item['device_message_id'] ?? ('MSG_' . $item['id']);
                
                $sensorReading = SensorReading::firstOrCreate(
                    ['device_message_id' => $messageId],
                    [
                        'node_id' => $node->id,
                        'environment_id' => $environmentId ?? 1,
                        'measured_at' => $item['measured_at'] ?? now(),
                        'received_at' => now(),
                        'source' => 'online',
                        'is_valid' => true,
                        'reported_latitude' => $item['reported_latitude'] ?? null,
                        'reported_longitude' => $item['reported_longitude'] ?? null,
                        'raw_payload' => $item['raw_payload'] ?? null,
                        'created_at' => now(),
                    ]
                );

                if ($sensorReading->wasRecentlyCreated) {
                    $nuevos++;

                    // 4. Crear mediciones en sensor_measurements local
                    foreach ($item['measurements'] ?? [] as $m) {
                        SensorMeasurement::create([
                            'reading_id' => $sensorReading->id,
                            'variable_type' => $m['variable_type'],
                            'value' => $m['value'],
                            'unit' => $m['unit'],
                            'is_valid' => true,
                            'created_at' => now(),
                        ]);
                    }
                }
            }

            $this->info("✅ ¡Sincronización completa! Se insertaron {$nuevos} nuevas lecturas reales desde la nube.");
            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Excepción procesando la sincronización: " . $e->getMessage());
            return 1;
        }
    }
}
