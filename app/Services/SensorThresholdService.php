<?php

namespace App\Services;

use App\Models\SensorMeasurement;
use App\Models\SensorReading;

/**
 * Servicio centralizado para evaluar umbrales ambientales
 * y obtener las últimas mediciones de sensores.
 *
 * Umbrales definidos:
 *   CO₂:         🟢 < 800 PPM  │  🟡 800–1200 PPM  │  🔴 > 1200 PPM
 *   Temperatura:  🟢 18–26 °C   │  🟡 15–18 / 26–30  │  🔴 < 15 / > 30
 *   Humedad:      🟢 40–60 %    │  🟡 30–40 / 60–70  │  🔴 < 30 / > 70
 */
class SensorThresholdService
{
    // ── Mapeo de estado → estilos visuales ──────────────────────
    private const STATUS_CONFIG = [
        'verde'     => ['label' => 'Bueno',    'bg' => '#e8f5e9', 'color' => '#2e7d32', 'dot' => '#2e7d32'],
        'amarillo'  => ['label' => 'En alerta', 'bg' => '#fff8e1', 'color' => '#e65100', 'dot' => '#f9a825'],
        'rojo'      => ['label' => 'Crítico',   'bg' => '#ffebee', 'color' => '#c62828', 'dot' => '#e63946'],
        'sin_datos' => ['label' => 'Sin datos', 'bg' => '#f5f5f5', 'color' => '#616161', 'dot' => '#9e9e9e'],
    ];

    /**
     * Obtener las últimas mediciones disponibles en la BD.
     *
     * @return array{co2: float|null, temperature: float|null, humidity: float|null, measured_at: string|null}
     */
    public function getLatestMeasurements(): array
    {
        $lastReading = SensorReading::where('is_valid', true)
            ->latest('measured_at')
            ->with('measurements')
            ->first();

        $result = [
            'co2'         => null,
            'temperature' => null,
            'humidity'    => null,
            'measured_at' => null,
        ];

        if (!$lastReading) {
            return $result;
        }

        $result['measured_at'] = $lastReading->measured_at?->toDateTimeString();

        foreach ($lastReading->measurements as $measurement) {
            if (!$measurement->is_valid) {
                continue;
            }

            match ($measurement->variable_type) {
                'co2'         => $result['co2'] = (float) $measurement->value,
                'temperature' => $result['temperature'] = (float) $measurement->value,
                'humidity'    => $result['humidity'] = (float) $measurement->value,
                default       => null,
            };
        }

        return $result;
    }

    // ── Evaluación de umbrales individuales ─────────────────────

    public function evaluateCo2(?float $value): string
    {
        return match (true) {
            $value === null => 'sin_datos',
            $value < 800    => 'verde',
            $value <= 1200  => 'amarillo',
            default         => 'rojo',
        };
    }

    public function evaluateTemperature(?float $value): string
    {
        return match (true) {
            $value === null                                       => 'sin_datos',
            $value >= 18 && $value <= 26                          => 'verde',
            ($value >= 15 && $value < 18) || ($value > 26 && $value <= 30) => 'amarillo',
            default                                               => 'rojo',
        };
    }

    public function evaluateHumidity(?float $value): string
    {
        return match (true) {
            $value === null                                       => 'sin_datos',
            $value >= 40 && $value <= 60                          => 'verde',
            ($value >= 30 && $value < 40) || ($value > 60 && $value <= 70) => 'amarillo',
            default                                               => 'rojo',
        };
    }

    /**
     * Estado general = el peor indicador entre los tres.
     */
    public function evaluateOverallStatus(string $co2Estado, string $tempEstado, string $humEstado): string
    {
        $estados = [$co2Estado, $tempEstado, $humEstado];

        if (in_array('rojo', $estados)) {
            return 'rojo';
        }
        if (in_array('amarillo', $estados)) {
            return 'amarillo';
        }
        if (in_array('verde', $estados)) {
            return 'verde';
        }

        return 'sin_datos';
    }

    /**
     * Retorna TODOS los datos pre-calculados que el dashboard necesita.
     *
     * @return array Datos listos para compact() en la vista.
     */
    public function getDashboardData(): array
    {
        $measurements = $this->getLatestMeasurements();

        $co2Value  = $measurements['co2'];
        $tempValue = $measurements['temperature'];
        $humValue  = $measurements['humidity'];

        // ── Evaluar estados ──
        $co2Estado  = $this->evaluateCo2($co2Value);
        $tempEstado = $this->evaluateTemperature($tempValue);
        $humEstado  = $this->evaluateHumidity($humValue);
        $estado     = $this->evaluateOverallStatus($co2Estado, $tempEstado, $humEstado);

        // ── Configuración visual del estado general ──
        $colorEstado = match ($estado) {
            'verde'    => 'success',
            'amarillo' => 'warning',
            'rojo'     => 'danger',
            default    => 'secondary',
        };

        $etiquetaEstado = match ($estado) {
            'verde'    => 'ÓPTIMO',
            'amarillo' => 'EN ALERTA',
            'rojo'     => 'CRÍTICO',
            default    => 'SIN DATOS',
        };

        $iconoEstado = match ($estado) {
            'verde'    => 'bi-check-circle-fill',
            'amarillo' => 'bi-exclamation-triangle-fill',
            'rojo'     => 'bi-exclamation-octagon-fill',
            default    => 'bi-hourglass-split',
        };

        $mensajeEstado = match ($estado) {
            'verde'    => '¡Excelente! Todas las condiciones ambientales son adecuadas.',
            'amarillo' => 'Uno o más indicadores presentan niveles que requieren atención y seguimiento.',
            'rojo'     => '¡Alerta! Se deben revisar las condiciones ambientales de inmediato.',
            default    => 'Esperando información de los sensores.',
        };

        // ── Configuración visual por variable ──
        $co2Config  = self::STATUS_CONFIG[$co2Estado];
        $tempConfig = self::STATUS_CONFIG[$tempEstado];
        $humConfig  = self::STATUS_CONFIG[$humEstado];

        // ── Descripciones de indicadores ──
        $co2Desc = match ($co2Estado) {
            'verde'    => 'Las condiciones del aire se mantienen adecuadas.',
            'amarillo' => 'Se recomienda mejorar la ventilación del espacio.',
            'rojo'     => 'Se requiere atención inmediata en la ventilación.',
            default    => 'Esperando datos del sensor de CO₂.',
        };

        $tempDesc = match ($tempEstado) {
            'verde'    => 'La temperatura se encuentra en rango confortable.',
            'amarillo' => 'La temperatura requiere monitoreo continuo.',
            'rojo'     => 'Temperatura fuera del rango aceptable.',
            default    => 'Esperando datos del sensor de temperatura.',
        };

        $humDesc = match ($humEstado) {
            'verde'    => 'La humedad relativa se mantiene adecuada.',
            'amarillo' => 'La humedad requiere seguimiento y atención.',
            'rojo'     => 'Humedad fuera del rango aceptable.',
            default    => 'Esperando datos del sensor de humedad.',
        };

        // ── Gauge circular de CO₂ ──
        $gaugeMax      = 2000;
        $gaugePercent  = $co2Value !== null ? min(100, ($co2Value / $gaugeMax) * 100) : 0;
        $circumference = 2 * M_PI * 50; // ≈ 314.16
        $gaugeOffset   = $circumference - ($circumference * $gaugePercent / 100);
        $gaugeColor    = match ($co2Estado) {
            'verde'    => '#39A900',
            'amarillo' => '#f9a825',
            'rojo'     => '#e63946',
            default    => '#6c757d',
        };

        return [
            // Valores crudos
            'co2'         => $co2Value,
            'temperatura' => $tempValue,
            'humedad'     => $humValue,
            'measuredAt'  => $measurements['measured_at'],

            // Estados evaluados
            'co2Estado'  => $co2Estado,
            'tempEstado' => $tempEstado,
            'humEstado'  => $humEstado,
            'estado'     => $estado,

            // Configuración visual general
            'colorEstado'    => $colorEstado,
            'etiquetaEstado' => $etiquetaEstado,
            'iconoEstado'    => $iconoEstado,
            'mensajeEstado'  => $mensajeEstado,

            // Configuración visual por variable
            'co2Config'  => $co2Config,
            'tempConfig' => $tempConfig,
            'humConfig'  => $humConfig,

            // Descripciones
            'co2Desc'  => $co2Desc,
            'tempDesc' => $tempDesc,
            'humDesc'  => $humDesc,

            // Gauge
            'gaugeMax'        => $gaugeMax,
            'gaugePercent'    => $gaugePercent,
            'circumference'   => $circumference,
            'gaugeOffset'     => $gaugeOffset,
            'gaugeColor'      => $gaugeColor,

            // Datos de ocupación (fallbacks por ahora)
            'aforoActual'     => 0,
            'aforoMaximo'     => 30,
            'porcentajeAforo' => 0,

            // Ambiente (fallback)
            'ambienteNombre' => 'Sin Ambiente Asignado',
            'aulaNumero'     => '0',
        ];
    }
}
