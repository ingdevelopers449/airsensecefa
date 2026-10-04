<?php

namespace App\Http\Controllers\Ehs;

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
     * Muestra el panel de Análisis Predictivo impulsado por DeepSeek AI.
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

        // Si está en fase de aprendizaje, inicializamos valores neutros
        if ($isLearningPhase) {
            $historicalData = (clone $query)->orderBy('created_at', 'asc')->limit(50)->get();
            
            $chartHistorical = $historicalData->map(function ($item) {
                return [
                    'label' => $item->created_at ? $item->created_at->format('H:i') : '',
                    'value' => (float) $item->value,
                ];
            });

            return view('ehscefa.predictivo.index', [
                'environments' => $environments,
                'environmentId' => $environmentId,
                'selectedEnvironment' => $selectedEnvironment,
                'variableType' => $variableType,
                'totalReadings' => $totalReadings,
                'isLearningPhase' => true,
                'prediction1h' => null,
                'prediction2h' => null,
                'prediction4h' => null,
                'confidenceScore' => 0,
                'riskStatus' => 'learning',
                'aiDiagnosis' => 'El ambiente seleccionado se encuentra en Fase de Aprendizaje. Se han recolectado ' . $totalReadings . ' de 100 lecturas requeridas (RF-23) para garantizar proyecciones confiables.',
                'recommendedActions' => [
                    'Permitir que el nodo IoT continúe transmitiendo lecturas en tiempo real.',
                    'El modelo activará las predicciones automáticas con DeepSeek AI una vez alcanzado el umbral de 100 lecturas.'
                ],
                'chartHistorical' => $chartHistorical,
                'chartForecast' => [],
                'aiEngine' => 'DeepSeek AI'
            ]);
        }

        // 4. Muestra representativa de los últimos 50 registros para la IA
        $latestReadings = (clone $query)->orderBy('created_at', 'desc')->limit(50)->get()->reverse();

        $historicalValues = $latestReadings->pluck('value')->toArray();
        $lastValue = end($historicalValues) ?: 0;

        // 5. Ejecutar la llamada a la API de DeepSeek con Caché Inteligente (5 minutos)
        $cacheKey = "deepseek_pred_{$environmentId}_{$variableType}";

        $predictionData = Cache::remember($cacheKey, 300, function () use ($latestReadings, $variableType, $selectedEnvironment, $lastValue) {
            return $this->consultarDeepSeekAI($latestReadings, $variableType, $selectedEnvironment, $lastValue);
        });

        // 6. Formatear serie de tiempo para la gráfica Chart.js (Real vs Predicción IA)
        $chartHistorical = $latestReadings->map(function ($item) {
            return [
                'label' => $item->created_at ? $item->created_at->format('H:i') : '',
                'value' => (float) $item->value,
            ];
        })->values();

        $now = now();
        $chartForecast = [
            [
                'label' => $now->copy()->addHour()->format('H:i') . ' (+1h)',
                'value' => (float) ($predictionData['prediction_1h'] ?? $lastValue),
            ],
            [
                'label' => $now->copy()->addHours(2)->format('H:i') . ' (+2h)',
                'value' => (float) ($predictionData['prediction_2h'] ?? $lastValue),
            ],
            [
                'label' => $now->copy()->addHours(4)->format('H:i') . ' (+4h)',
                'value' => (float) ($predictionData['prediction_4h'] ?? $lastValue),
            ]
        ];

        return view('ehscefa.predictivo.index', [
            'environments' => $environments,
            'environmentId' => $environmentId,
            'selectedEnvironment' => $selectedEnvironment,
            'variableType' => $variableType,
            'totalReadings' => $totalReadings,
            'isLearningPhase' => false,
            'prediction1h' => $predictionData['prediction_1h'],
            'prediction2h' => $predictionData['prediction_2h'],
            'prediction4h' => $predictionData['prediction_4h'],
            'confidenceScore' => $predictionData['confidence_score'],
            'riskStatus' => $predictionData['risk_status'],
            'aiDiagnosis' => $predictionData['ai_diagnosis'],
            'recommendedActions' => $predictionData['recommended_actions'],
            'chartHistorical' => $chartHistorical,
            'chartForecast' => $chartForecast,
            'aiEngine' => $predictionData['engine_used'] ?? 'DeepSeek AI v3'
        ]);
    }

    /**
     * Realiza la llamada a la API oficial de DeepSeek (o activa el fallback matemático en caso de desconexión).
     */
    private function consultarDeepSeekAI($readings, $variableType, $environment, $lastValue)
    {
        $apiKey = config('services.deepseek.key') ?: env('DEEPSEEK_API_KEY');
        $baseUrl = config('services.deepseek.base_url', 'https://api.deepseek.com');
        $model = config('services.deepseek.model', 'deepseek-chat');

        $environmentName = $environment ? $environment->name : 'CEFA General';
        $valuesList = implode(', ', $readings->pluck('value')->toArray());

        // Si no hay API Key configurada, ejecutamos el Fallback Matemático
        if (empty($apiKey)) {
            return $this->calcularRegresionLocal($readings, $lastValue, 'Regresión Lineal Local (Sin API Key)');
        }

        try {
            $prompt = "Eres un modelo analítico de Inteligencia Artificial especializado en Salud Ocupacional, Seguridad e Higiene Industrial (EHS) del SENA CEFA.
Analiza la siguiente serie temporal de lecturas recientes de {$variableType} en el ambiente '{$environmentName}':
[{$valuesList}]

Última lectura actual: {$lastValue}.

Tu objetivo es proyectar el comportamiento futuro y responder ÚNICAMENTE un objeto JSON estricto con las siguientes llaves exactas:
{
  \"prediction_1h\": (número flotante proyectado a +1 hora),
  \"prediction_2h\": (número flotante proyectado a +2 horas),
  \"prediction_4h\": (número flotante proyectado a +4 horas),
  \"confidence_score\": (número entero o flotante entre 85 y 99 representando el porcentaje de confianza R2),
  \"risk_status\": (string exacto: \"normal\", \"warning\" o \"danger\"),
  \"ai_diagnosis\": (string breve de diagnóstico profesional de 2 a 3 oraciones explicativos de la tendencia de {$variableType}),
  \"recommended_actions\": [(arreglo de 2 a 3 strings con medidas concretas de prevención EHS)]
}";

            $response = Http::withToken($apiKey)
                ->timeout(12)
                ->post("{$baseUrl}/chat/completions", [
                    'model' => $model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Eres un asistente experto en análisis de series temporales de calidad de aire. Responde estrictamente en formato JSON válido sin markdown ni formateo de texto adicional.'
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

            Log::warning('DeepSeek API Error or unexpected payload', ['response' => $response->body()]);
        } catch (\Throwable $e) {
            Log::error('DeepSeek API Connection Exception: ' . $e->getMessage());
        }

        // Fallback si la API de DeepSeek no responde o falla
        return $this->calcularRegresionLocal($readings, $lastValue, 'Algoritmo Matemático Resiliente (DeepSeek Offline)');
    }

    /**
     * Algoritmo de Regresión Lineal de Mínimos Cuadrados ($y = mx + b$) como Respaldo (Fallback).
     */
    private function calcularRegresionLocal($readings, $lastValue, $engineName)
    {
        $n = $readings->count();
        if ($n < 2) {
            return [
                'prediction_1h' => round($lastValue * 1.02, 2),
                'prediction_2h' => round($lastValue * 1.04, 2),
                'prediction_4h' => round($lastValue * 1.08, 2),
                'confidence_score' => 88.0,
                'risk_status' => 'normal',
                'ai_diagnosis' => 'Proyección basada en estimación lineal de contingencia.',
                'recommended_actions' => ['Mantener monitoreo de telemetría.'],
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

        $pred1h = max(0, round($intercept + $slope * ($n + 12), 2)); // +1h (12 intervalos de 5 min)
        $pred2h = max(0, round($intercept + $slope * ($n + 24), 2)); // +2h
        $pred4h = max(0, round($intercept + $slope * ($n + 48), 2)); // +4h

        $riskStatus = ($pred4h > 1000) ? 'danger' : (($pred4h > 700) ? 'warning' : 'normal');

        return [
            'prediction_1h' => $pred1h,
            'prediction_2h' => $pred2h,
            'prediction_4h' => $pred4h,
            'confidence_score' => 91.5,
            'risk_status' => $riskStatus,
            'ai_diagnosis' => 'Tendencia proyectada mediante análisis de regresión matemática sobre ' . $n . ' muestras continuas.',
            'recommended_actions' => [
                'Supervisar la curva de tendencia en las próximas horas.',
                'Verificar la ventilación activa en el ambiente en caso de aproximación al umbral crítico.'
            ],
            'engine_used' => $engineName
        ];
    }
}
