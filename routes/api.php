<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\IoTTelemetryController;
use App\Http\Middleware\ValidateIoTDevice;

/*
|--------------------------------------------------------------------------
| API Routes - AirSense CEFA
|--------------------------------------------------------------------------
*/

Route::prefix('v1/nodes')->group(function () {
    // Consulta pública de últimas lecturas (para sincronización cloud-local y dashboard)
    Route::get('latest-telemetry', [IoTTelemetryController::class, 'latestTelemetry']);

    // Rutas protegidas para ingesta de hardware ESP32
    Route::middleware([ValidateIoTDevice::class])->group(function () {
        Route::post('telemetry', [IoTTelemetryController::class, 'store']);
        Route::post('ping', [IoTTelemetryController::class, 'ping']);
    });
});
