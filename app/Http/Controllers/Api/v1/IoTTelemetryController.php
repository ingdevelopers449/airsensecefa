<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Environment;
use App\Models\Node;
use App\Models\SensorMeasurement;
use App\Models\SensorReading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class IoTTelemetryController extends Controller
{
    /**
     * Recibe e ingesta paquetes de lecturas enviados por nodos ESP32.
     */
    public function store(Request $request): Response
    {
        /** @var Node $node */
        $node = $request->attributes->get('authenticated_node');

        if (!$node) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nodo no identificado en el contexto de autenticación.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $validator = Validator::make($request->all(), [
            'device_message_id' => 'nullable|string|max:180',
            'measured_at' => 'nullable|date',
            'measurements' => 'required|array|min:1',
            'measurements.*.variable_type' => 'required|string|in:co2,temperature,humidity',
            'measurements.*.value' => 'required|numeric',
            'measurements.*.unit' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error de validación en la estructura del payload.',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $reading = DB::transaction(function () use ($node, $request) {
                // Obtener o asignar un ambiente válido
                $environmentId = $node->environment_id;
                
                if (!$environmentId) {
                    $defaultEnv = Environment::firstOrCreate(
                        ['code' => 'UNASSIGNED'],
                        ['name' => 'Sin Asignar (Pendiente por Admin)', 'description' => 'Ambiente temporal para nodos sin ubicar', 'is_active' => true]
                    );
                    $environmentId = $defaultEnv->id;
                }

                $measuredAt = $request->input('measured_at') ? now()->parse($request->input('measured_at')) : now();
                $messageId = $request->input('device_message_id') ?? ('MSG_' . time() . '_' . $node->device_uid . '_' . rand(100, 999));

                // 1. Registrar la cabecera en sensor_readings
                $sensorReading = SensorReading::create([
                    'node_id' => $node->id,
                    'environment_id' => $environmentId,
                    'device_message_id' => $messageId,
                    'measured_at' => $measuredAt,
                    'received_at' => now(),
                    'source' => 'online',
                    'is_valid' => true,
                    'reported_latitude' => $request->input('latitude'),
                    'reported_longitude' => $request->input('longitude'),
                    'raw_payload' => $request->all(),
                    'created_at' => now(),
                ]);

                // 2. Registrar cada variable en sensor_measurements
                foreach ($request->input('measurements') as $item) {
                    SensorMeasurement::create([
                        'reading_id' => $sensorReading->id,
                        'variable_type' => $item['variable_type'],
                        'value' => $item['value'],
                        'unit' => $item['unit'],
                        'is_valid' => true,
                        'created_at' => now(),
                    ]);
                }

                // 3. Actualizar estado y timestamps del nodo ESP32
                $node->update([
                    'connectivity_status' => 'online',
                    'last_seen_at' => now(),
                    'last_keep_alive_at' => now(),
                ]);

                return $sensorReading;
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Lectura y mediciones registradas correctamente.',
                'reading_id' => $reading->id,
                'node_id' => $node->id,
                'device_uid' => $node->device_uid,
                'received_at' => now()->toDateTimeString(),
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error interno procesando la lectura del sensor: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Endpoint de ping / keep-alive para actualizar el estado del nodo.
     */
    public function ping(Request $request): Response
    {
        /** @var Node $node */
        $node = $request->attributes->get('authenticated_node');

        if (!$node) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nodo no identificado.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $node->update([
            'connectivity_status' => 'online',
            'last_seen_at' => now(),
            'last_keep_alive_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Keep-alive recibido correctamente.',
            'device_uid' => $node->device_uid,
            'timestamp' => now()->toDateTimeString()
        ], Response::HTTP_OK);
    }
}
