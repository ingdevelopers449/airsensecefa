<?php

namespace App\Http\Controllers\Ehs;

use App\Http\Controllers\Controller;
use App\Models\Node;
use App\Models\SensorReading;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MapaController extends Controller
{
    /**
     * Muestra la vista principal del mapa interactivo geolocalizado CEFA.
     */
    public function index()
    {
        // El mapa está incrustado en el dashboard, por lo que redirigimos allá
        return redirect()->route('ehscefa.dashboard');
    }

    /**
     * Retorna la colección GeoJSON con la ubicación y telemetría en tiempo real de los nodos.
     */
    public function apiGeojson(): JsonResponse
    {
        $nodes = Node::with(['environment'])->where('is_active', true)->get();

        $features = [];

        foreach ($nodes as $node) {
            // Obtener la última lectura de telemetría del nodo
            $latestReading = SensorReading::with('measurements')
                ->where('node_id', $node->id)
                ->orderBy('measured_at', 'desc')
                ->first();

            $co2Value = 450;
            $tempValue = 24.0;
            $humValue = 60.0;

            if ($latestReading) {
                foreach ($latestReading->measurements as $m) {
                    if ($m->variable_type === 'CO2') $co2Value = (float)$m->value;
                    if ($m->variable_type === 'TEMPERATURE') $tempValue = (float)$m->value;
                    if ($m->variable_type === 'HUMIDITY') $humValue = (float)$m->value;
                }
            }

            // Calcular estado y recomendación EHS según CO2
            $color = '#39A900'; // Verde (< 800)
            $statusText = 'Óptimo';
            $statusCode = 'optimo';
            $recomendacionEhs = 'Niveles de CO₂ dentro del rango saludable. Mantenga la ventilación natural continua.';

            if ($co2Value >= 1200) {
                $color = '#ef4444'; // Rojo (Peligro)
                $statusText = 'Crítico';
                $statusCode = 'critico';
                $recomendacionEhs = 'Abra puertas y ventanas de inmediato. Active extracción mecánica y evalúe evacuación preventiva.';
            } else if ($co2Value >= 800) {
                $color = '#f59e0b'; // Amarillo (Moderado)
                $statusText = 'Precaución';
                $statusCode = 'precaucion';
                $recomendacionEhs = 'Ventile el área. Aumente la circulación de aire fresco para reducir la acumulación de CO₂.';
            }

            // Determinar categoría por nombre de ambiente o nodo
            $ambienteNombre = $node->environment ? $node->environment->name : 'Área General';
            $categoria = 'General';
            if (stripos($ambienteNombre, 'ganader') !== false || stripos($ambienteNombre, 'acopio') !== false || stripos($ambienteNombre, 'agrícola') !== false) {
                $categoria = 'Agrícola';
            } else if (stripos($ambienteNombre, 'ambiente') !== false || stripos($ambienteNombre, 'aula') !== false || stripos($ambienteNombre, 'biblioteca') !== false) {
                $categoria = 'Académico';
            } else if (stripos($ambienteNombre, 'lab') !== false || stripos($ambienteNombre, 'alimento') !== false) {
                $categoria = 'Laboratorio';
            } else if (stripos($ambienteNombre, 'admin') !== false || stripos($ambienteNombre, 'bloque') !== false) {
                $categoria = 'Administrativo';
            }

            // Coordenadas por defecto (CEFA La Angostura) si no ha reportado GPS
            $lat = $node->last_reported_latitude ? (float)$node->last_reported_latitude : 2.441200;
            $lng = $node->last_reported_longitude ? (float)$node->last_reported_longitude : -75.641000;

            $isOnline = $node->last_seen_at ? $node->last_seen_at->diffInMinutes(now()) <= 60 : true;

            $features[] = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [$lng, $lat] // GeoJSON requiere [Longitud, Latitud]
                ],
                'properties' => [
                    'id' => $node->id,
                    'name' => $node->name ?: $node->device_uid,
                    'device_uid' => $node->device_uid,
                    'ambiente' => $ambienteNombre,
                    'categoria' => $categoria,
                    'co2' => $co2Value,
                    'temperatura' => $tempValue,
                    'humedad' => $humValue,
                    'status_text' => $statusText,
                    'status_code' => $statusCode,
                    'color' => $color,
                    'recomendacion_ehs' => $recomendacionEhs,
                    'is_online' => $isOnline,
                    'last_seen' => $node->last_seen_at ? $node->last_seen_at->diffForHumans() : 'Recientemente',
                ]
            ];
        }


        // Obtener la Delimitación Poligonal desde la base de datos (tabla coordinates)
        $polygonPoints = \Illuminate\Support\Facades\DB::table('coordinates')
            ->where('description', 'like', 'Delimitación CEFA%')
            ->orderBy('id')
            ->get();

        $polygonCoords = [];
        foreach ($polygonPoints as $pt) {
            // GeoJSON usa [Longitud, Latitud]
            $polygonCoords[] = [(float)$pt->length, (float)$pt->latitude];
        }

        // Si hay suficientes puntos para formar un polígono, lo agregamos
        if (count($polygonCoords) >= 3) {
            // Para cerrar el polígono, el último punto debe ser igual al primero
            $polygonCoords[] = $polygonCoords[0];

            $features[] = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Polygon',
                    'coordinates' => [$polygonCoords]
                ],
                'properties' => [
                    'type' => 'boundary',
                    'name' => 'Perímetro CEFA La Angostura',
                    'description' => 'Centro de Formación Agroindustrial SENA Campoalegre',
                    'stroke' => '#eab308',
                    'stroke-width' => 4,
                    'fill' => '#39A900',
                    'fill-opacity' => 0.25
                ]
            ];
        }

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features
        ]);
    }
}

