<?php

namespace App\Http\Controllers;

use App\Services\SensorThresholdService;
use Illuminate\Support\Facades\Auth;

class InstructorDashboardController extends Controller
{
    public function __construct(
        private SensorThresholdService $thresholdService
    ) {}

    /**
     * Muestra el dashboard del instructor con datos reales de sensores.
     */
    public function index()
    {
        $user = Auth::user();
        $roleCode = $user->role ? $user->role->code : null;
        $roleId = $user->role_id;

        if ($roleCode !== 'INSTRUCTOR' && $roleId != 3 && $roleCode !== 'ADMIN' && $roleId != 1) {
            return redirect()->route('dashboard');
        }

        // Datos reales desde SensorMeasurement vía el servicio de umbrales
        $dashboardData = $this->thresholdService->getDashboardData();

        return view('instructor.dashboard', $dashboardData);
    }
}
