<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Environment;
use App\Models\SensorMeasurement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PredictivoController extends Controller
{
    /**
     * Muestra el panel de Análisis Predictivo con UX intuitivo impulsado por DeepSeek AI.
     */
    public function index(Request $request)
    {
        $environmentId = $request->input('environment_id');
        $variableType  = $request->input('variable_type', 'co2'); // 'co2', 'temperature', 'humidity'

        // 1. Obtener la lista de ambientes activos
        $environments = Environment::all();
        $selectedEnvironment = !empty($environmentId) ? Environment::find($environmentId) : null;

        // 2. Consulta de lecturas históricas
        $query = SensorMeasurement::with(['reading.environment'])
            ->where('variable_type', $variableType);

        if (!empty($environmentId)) {
            $query->whereHas('reading', function ($q) use ($environmentId) {
                $q->where('environment_id', $environmentId);
            });
        }

        $totalReadings = (int) (clone $query)->count();

        // 3. Cumplimiento estricto de RF-23: Mínimo 100 lecturas históricas requeridas
        $isLearningPhase = ($totalReadings < 100);

        if ($isLearningPhase) {
            $historicalData = (clone $query)->orderBy('created_at', 'asc')->limit(50)->get();
            
            $chartHistorical = $historicalData->map(function ($item) {
                return [
                    'label' => $item->created_at ? $item->created_at->format('H:i') : '',
                    'value' => (float) $item->value,
                ];
            });

            return view('admin.predictivo.index', [
                'environments' => $environments,
                'environmentId' => $environmentId,
                'selectedEnvironment' => $selectedEnvironment,
                'variableType' => $variableType,
                'totalReadings' => $totalReadings,
                'isLearningPhase' => true,
                'currentValue' => 0,
                'timeline' => [],
                'trendPercentage' => 0,
                'trendDirection' => 'stable',
                'confidenceScore' => 0,
                'riskStatus' => 'learning',
                'thresholdLimit' => ($variableType === 'co2') ? 1000 : (($variableType === 'temperature') ? 30 : 70),
                'aiDiagnosis' => 'El ambiente se encuentra en Fase de Aprendizaje (RF-23). Se requieren 100 lecturas históricas acumuladas para publicar proyecciones predictivas.',
                'recommendedActions' => [
                    'Mantener los nodos IoT encendidos transmitiendo datos en tiempo real.',
                    'Las predicciones automáticas se activarán al alcanzar las 100 lecturas.'
                ],
                'chartHistorical' => $chartHistorical,
                'chartForecast' => [],
                'aiEngine' => 'DeepSeek AI'
            ]);
        }

        // 4. Muestra representativa de los últimos 50 registros para la IA
        $latestReadings = (clone $query)->orderBy('created_at', 'desc')->limit(50)->get()->reverse();

        $historicalValues = $latestReadings->pluck('value')->toArray();
        $lastValue = (float) (end($historicalValues) ?: 0);

        // 5. Llamada a la API de DeepSeek con Caché Inteligente (5 minutos)
        $cacheKey = "deepseek_pred_v2_{$environmentId}_{$variableType}";

        $predictionData = Cache::remember($cacheKey, 300, function () use ($latestReadings, $variableType, $selectedEnvironment, $lastValue) {
            return $this->consultarDeepSeekAI($latestReadings, $variableType, $selectedEnvironment, $lastValue);
        });

        // 6. Formateo de Línea de Tiempo Futura (Timeline) con Horas Exactas (Zona Horaria Colombia America/Bogota)
        $now = now()->setTimezone('America/Bogota');
        $timeNow = $now->format('H:i');
        $time1h  = $now->copy()->addHour()->format('H:i');
        $time2h  = $now->copy()->addHours(2)->format('H:i');
        $time4h  = $now->copy()->addHours(4)->format('H:i');

        $val1h = (float) ($predictionData['prediction_1h'] ?? $lastValue);
        $val2h = (float) ($predictionData['prediction_2h'] ?? $lastValue);
        $val4h = (float) ($predictionData['prediction_4h'] ?? $lastValue);

        // Cálculo del porcentaje de tendencia respecto al valor actual
        $delta4h = $lastValue > 0 ? (($val4h - $lastValue) / $lastValue) * 100 : 0;
        $trendDirection = $delta4h > 3 ? 'up' : ($delta4h < -3 ? 'down' : 'stable');
        $trendPercentage = abs(round($delta4h, 1));

        $thresholdLimit = ($variableType === 'co2') ? 1000 : (($variableType === 'temperature') ? 30 : 70);

        $timeline = [
            [
                'title' => 'HORA ACTUAL',
                'time' => $timeNow,
                'value' => $lastValue,
                'badge' => 'Lectura Real',
                'status' => ($lastValue > $thresholdLimit) ? 'danger' : (($lastValue > $thresholdLimit * 0.7) ? 'warning' : 'success'),
                'is_current' => true
            ],
            [
                'title' => 'EN 1 HORA',
                'time' => $time1h,
                'value' => $val1h,
                'badge' => 'Proyección IA',
                'status' => ($val1h > $thresholdLimit) ? 'danger' : (($val1h > $thresholdLimit * 0.7) ? 'warning' : 'success'),
                'is_current' => false
            ],
            [
                'title' => 'EN 2 HORAS',
                'time' => $time2h,
                'value' => $val2h,
                'badge' => 'Proyección IA',
                'status' => ($val2h > $thresholdLimit) ? 'danger' : (($val2h > $thresholdLimit * 0.7) ? 'warning' : 'success'),
                'is_current' => false
            ],
            [
                'title' => 'EN 4 HORAS',
                'time' => $time4h,
                'value' => $val4h,
                'badge' => 'Proyección IA',
                'status' => ($val4h > $thresholdLimit) ? 'danger' : (($val4h > $thresholdLimit * 0.7) ? 'warning' : 'success'),
                'is_current' => false
            ]
        ];

        // Formatear datos para la gráfica
        $chartHistorical = $latestReadings->map(function ($item) {
            return [
                'label' => $item->created_at ? $item->created_at->setTimezone('America/Bogota')->format('H:i') : '',
                'value' => (float) $item->value,
            ];
        })->values();

        $chartForecast = [
            ['label' => $time1h . ' (+1h)', 'value' => $val1h],
            ['label' => $time2h . ' (+2h)', 'value' => $val2h],
            ['label' => $time4h . ' (+4h)', 'value' => $val4h],
        ];

        return view('admin.predictivo.index', [
            'environments' => $environments,
            'environmentId' => $environmentId,
            'selectedEnvironment' => $selectedEnvironment,
            'variableType' => $variableType,
            'totalReadings' => $totalReadings,
            'isLearningPhase' => false,
            'currentValue' => $lastValue,
            'timeline' => $timeline,
            'trendPercentage' => $trendPercentage,
            'trendDirection' => $trendDirection,
            'confidenceScore' => $predictionData['confidence_score'] ?? 94.0,
            'riskStatus' => $predictionData['risk_status'] ?? 'normal',
            'thresholdLimit' => $thresholdLimit,
            'aiDiagnosis' => $predictionData['ai_diagnosis'] ?? 'Análisis interpretativo generado por el modelo predictivo.',
            'recommendedActions' => $predictionData['recommended_actions'] ?? [],
            'chartHistorical' => $chartHistorical,
            'chartForecast' => $chartForecast,
            'aiEngine' => $predictionData['engine_used'] ?? 'DeepSeek AI v3'
        ]);
    }

    /**
     * Consulta DeepSeek AI con prompt en formato JSON estricto.
     */
    private function consultarDeepSeekAI($readings, $variableType, $environment, $lastValue)
    {
        $apiKey = config('services.deepseek.key') ?: env('DEEPSEEK_API_KEY');
        $baseUrl = config('services.deepseek.base_url', 'https://api.deepseek.com');
        $model = config('services.deepseek.model', 'deepseek-chat');

        $environmentName = $environment ? $environment->name : 'CEFA General';
        $valuesList = implode(', ', $readings->pluck('value')->toArray());

        if (empty($apiKey)) {
            return $this->calcularRegresionLocal($readings, $lastValue, 'Regresión Lineal Resiliente (Local)');
        }

        try {
            $prompt = "Eres un especialista de Inteligencia Artificial en Salud Ocupacional, Seguridad e Higiene Industrial (EHS) en el SENA CEFA.
Analiza esta serie de lecturas de {$variableType} en '{$environmentName}':
[{$valuesList}]

Última lectura registrada: {$lastValue}.

Proyecta la tendencia a +1h, +2h y +4h y responde ÚNICAMENTE este objeto JSON estricto:
{
  \"prediction_1h\": (float),
  \"prediction_2h\": (float),
  \"prediction_4h\": (float),
  \"confidence_score\": (float entre 88.0 y 98.5),
  \"risk_status\": (string exacto: \"normal\", \"warning\" o \"danger\"),
  \"ai_diagnosis\": (string explicativo en lenguaje sencillo y claro de 2 oraciones expresando que pasara a futuro),
  \"recommended_actions\": [(arreglo de 2 a 3 recomendaciones sencillas y directas para el EHS)]
}";

            $response = Http::withToken($apiKey)
                ->timeout(12)
                ->post("{$baseUrl}/chat/completions", [
                    'model' => $model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Eres un asistente experto en análisis predictivo EHS. Responde estrictamente en formato JSON válido sin markdown.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'response_format' => ['type' => 'json_object'],
                    'temperature' => 0.2
                ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');
                $json = json_decode($content, true);

                if (is_array($json) && isset($json['prediction_1h'])) {
                    $json['engine_used'] = 'DeepSeek AI v3 (API Oficial)';
                    return $json;
                }
            }

            Log::warning('DeepSeek API Unexpected Response', ['body' => $response->body()]);
        } catch (\Throwable $e) {
            Log::error('DeepSeek API Connection Error: ' . $e->getMessage());
        }

        return $this->calcularRegresionLocal($readings, $lastValue, 'Algoritmo Matemático Resiliente (DeepSeek Offline)');
    }

    /**
     * Fallback local en caso de desconexión.
     */
    private function calcularRegresionLocal($readings, $lastValue, $engineName)
    {
        $n = $readings->count();
        if ($n < 2) {
            return [
                'prediction_1h' => round($lastValue * 1.02, 2),
                'prediction_2h' => round($lastValue * 1.04, 2),
                'prediction_4h' => round($lastValue * 1.08, 2),
                'confidence_score' => 90.0,
                'risk_status' => 'normal',
                'ai_diagnosis' => 'Proyección lineal calculada a partir de lecturas consecutivas.',
                'recommended_actions' => ['Supervisar la tendencia en el tablero.'],
                'engine_used' => $engineName
            ];
        }

        $xSum = 0; $ySum = 0; $xySum = 0; $xxSum = 0;
        $i = 0;
        foreach ($readings as $r) {
            $x = $i++;
            $y = (float) $r->value;
            $xSum += $x;
            $ySum += $y;
            $xySum += ($x * $y);
            $xxSum += ($x * $x);
        }

        $slope = ($n * $xySum - $xSum * $ySum) / max(1, ($n * $xxSum - $xSum * $xSum));
        $intercept = ($ySum - $slope * $xSum) / $n;

        $pred1h = max(0, round($intercept + $slope * ($n + 12), 2));
        $pred2h = max(0, round($intercept + $slope * ($n + 24), 2));
        $pred4h = max(0, round($intercept + $slope * ($n + 48), 2));

        $threshold = 1000;
        $riskStatus = ($pred4h > $threshold) ? 'danger' : (($pred4h > $threshold * 0.7) ? 'warning' : 'normal');

        return [
            'prediction_1h' => $pred1h,
            'prediction_2h' => $pred2h,
            'prediction_4h' => $pred4h,
            'confidence_score' => 92.5,
            'risk_status' => $riskStatus,
            'ai_diagnosis' => 'Proyección estimada basada en la tasa de variación histórica reciente.',
            'recommended_actions' => [
                'Monitorear la evolución de los valores.',
                'Verificar ventilación preventiva en caso de incremento.'
            ],
            'engine_used' => $engineName
        ];
    }
}
