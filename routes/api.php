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
    // Rutas protegidas para ingesta de hardware ESP32
    Route::middleware([ValidateIoTDevice::class])->group(function () {
        Route::post('telemetry', [IoTTelemetryController::class, 'store']);
        Route::post('ping', [IoTTelemetryController::class, 'ping']);
    });
});
