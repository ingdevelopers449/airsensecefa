<?php

namespace App\Http\Controllers\Ehs;

use App\Http\Controllers\Controller;
use App\Models\Environment;
use App\Models\SensorMeasurement;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $environmentId = $request->input('environment_id');
        $variableType  = $request->input('variable_type', 'co2');
        $startDate     = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate       = $request->input('end_date', now()->format('Y-m-d'));

        // Consulta de estadísticas para resumen previo
        $query = SensorMeasurement::with(['reading.environment'])
            ->where('variable_type', $variableType)
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);

        if (!empty($environmentId)) {
            $query->whereHas('reading', function ($q) use ($environmentId) {
                $q->where('environment_id', $environmentId);
            });
        }

        $totalReadings = (int) (clone $query)->count();
        $avgValue      = (float) ((clone $query)->avg('value') ?? 0);
        $maxValue      = (float) ((clone $query)->max('value') ?? 0);
        $minValue      = (float) ((clone $query)->min('value') ?? 0);

        // Generar Hash de integridad único para la vista previa
        $hashSignature = hash('sha256', "AIRSENSE-CEFA|{$environmentId}|{$variableType}|{$startDate}|{$endDate}|{$totalReadings}|" . config('app.key'));

        $environments = Environment::all();

        return view('ehscefa.reportes.index', compact(
            'environments',
            'environmentId',
            'variableType',
            'startDate',
            'endDate',
            'totalReadings',
            'avgValue',
            'maxValue',
            'minValue',
            'hashSignature'
        ));
    }

    public function imprimirPdf(Request $request)
    {
        $environmentId = $request->input('environment_id');
        $variableType  = $request->input('variable_type', 'co2');
        $startDate     = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate       = $request->input('end_date', now()->format('Y-m-d'));

        $query = SensorMeasurement::with(['reading.environment', 'reading.node'])
            ->where('variable_type', $variableType)
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);

        if (!empty($environmentId)) {
            $query->whereHas('reading', function ($q) use ($environmentId) {
                $q->where('environment_id', $environmentId);
            });
        }

        $measurements = (clone $query)->orderBy('created_at', 'asc')->limit(500)->get();

        $totalReadings = $measurements->count();
        $avgValue      = (float) ((clone $query)->avg('value') ?? 0);
        $maxValue      = (float) ((clone $query)->max('value') ?? 0);
        $minValue      = (float) ((clone $query)->min('value') ?? 0);

        // Firma Digital de Integridad Hash SHA-256
        $hashSignature = strtoupper(hash('sha256', "OFFICIAL_REPORT|AIRSENSE_CEFA|{$environmentId}|{$variableType}|{$startDate}|{$endDate}|{$totalReadings}|" . config('app.key')));

        $environmentObj = !empty($environmentId) ? Environment::find($environmentId) : null;

        return view('ehscefa.reportes.pdf', compact(
            'measurements',
            'environmentObj',
            'variableType',
            'startDate',
            'endDate',
            'totalReadings',
            'avgValue',
            'maxValue',
            'minValue',
            'hashSignature'
        ));
    }

    public function exportarCsv(Request $request): StreamedResponse
    {
        $environmentId = $request->input('environment_id');
        $variableType  = $request->input('variable_type', 'co2');
        $startDate     = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate       = $request->input('end_date', now()->format('Y-m-d'));

        $query = SensorMeasurement::with(['reading.environment', 'reading.node'])
            ->where('variable_type', $variableType)
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);

        if (!empty($environmentId)) {
            $query->whereHas('reading', function ($q) use ($environmentId) {
                $q->where('environment_id', $environmentId);
            });
        }

        $measurements = $query->orderBy('created_at', 'asc')->get();

        $fileName = "reporte_ehs_" . $variableType . "_" . date('Ymd_His') . ".csv";

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($measurements, $variableType) {
            $file = fopen('php://output', 'w');
            // Bom UTF-8 para Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Encabezados
            fputcsv($file, ['ID Lectura', 'Fecha y Hora', 'Ambiente', 'Nodo IoT', 'Variable', 'Valor Medido', 'Unidad', 'Estado']);

            foreach ($measurements as $item) {
                $estado = 'NORMAL';
                if ($variableType === 'co2') {
                    $estado = $item->value > 1000 ? 'CRITICO' : ($item->value > 700 ? 'ADVERTENCIA' : 'NORMAL');
                } elseif ($variableType === 'temperature') {
                    $estado = $item->value > 30 ? 'PELIGRO' : 'OPTIMO';
                }

                fputcsv($file, [
                    $item->id,
                    $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : 'N/A',
                    $item->reading && $item->reading->environment ? $item->reading->environment->name : 'CEFA General',
                    $item->reading && $item->reading->node ? $item->reading->node->name : 'ESP32',
                    strtoupper($variableType),
                    $item->value,
                    $item->unit,
                    $estado
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
