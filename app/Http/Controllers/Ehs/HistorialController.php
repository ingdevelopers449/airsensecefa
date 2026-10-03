<?php

namespace App\Http\Controllers\Ehs;

use App\Http\Controllers\Controller;
use App\Models\Environment;
use App\Models\SensorMeasurement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HistorialController extends Controller
{
    /**
     * Muestra el historial de lecturas ambientales con filtros y métricas.
     */
    public function index(Request $request)
    {
        // 1. Parámetros de filtro con valores por defecto
        $environmentId = $request->input('environment_id');
        $variableType  = $request->input('variable_type', 'co2'); // 'co2', 'temperature', 'humidity'
        $startDate     = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate       = $request->input('end_date', now()->format('Y-m-d'));

        // 2. Consulta base con relaciones
        $query = SensorMeasurement::with(['reading.environment', 'reading.node'])
            ->where('variable_type', $variableType)
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);

        // Filtrar por ambiente si fue seleccionado
        if (!empty($environmentId)) {
            $query->whereHas('reading', function ($q) use ($environmentId) {
                $q->where('environment_id', $environmentId);
            });
        }

        // Medición paginada para la tabla
        $measurementsQuery = clone $query;
        $measurements = $measurementsQuery->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // 3. Métricas calculadas para las tarjetas KPI
        $avgValue = (float) ((clone $query)->avg('value') ?? 0);
        $maxValue = (float) ((clone $query)->max('value') ?? 0);
        $minValue = (float) ((clone $query)->min('value') ?? 0);
        $totalReadings = (int) (clone $query)->count();

        // 4. Datos estructurados para la gráfica Chart.js
        $chartData = (clone $query)
            ->orderBy('created_at', 'asc')
            ->limit(100)
            ->get()
            ->map(function ($item) {
                return [
                    'label' => $item->created_at ? $item->created_at->format('d/m H:i') : '',
                    'value' => (float) $item->value,
                    'environment' => ($item->reading && $item->reading->environment) ? $item->reading->environment->name : 'General',
                ];
            });

        // 5. Cargar ambientes para el filtro desplegable
        $environments = Environment::all();

        return view('ehscefa.historico.index', compact(
            'measurements',
            'environments',
            'environmentId',
            'variableType',
            'startDate',
            'endDate',
            'avgValue',
            'maxValue',
            'minValue',
            'totalReadings',
            'chartData'
        ));
    }
}
