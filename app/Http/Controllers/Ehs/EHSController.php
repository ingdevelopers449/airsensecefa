<?php

namespace App\Http\Controllers\Ehs;

use App\Http\Controllers\Controller;
use App\Models\Node;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EHSController extends Controller
{
    /**
     * Muestra el módulo de Estado de Hardware & Conectividad IoT para EHS.
     */
    public function estadoHardware()
    {
        $user = Auth::user();
        $roleCode = $user->role ? $user->role->code : null;
        $roleId = $user->role_id;
        // Verificación de rol (EHS/SST o ADMIN)
        if ($roleCode !== 'SST' && $roleId != 2 && $roleCode !== 'ADMIN' && $roleId != 1) {
            return redirect()->route('dashboard');
        }
        // Consultar nodos con su última lectura
        $nodos = Node::with(['readings' => function($query) {
            $query->latest()->limit(1)->with('measurements');
        }])->get();
        // Calcular KPIs de conectividad
        $totalNodos = $nodos->count();
        $nodosOnline = $nodos->filter(function($nodo) {
            $ultimaLectura = $nodo->readings->first();
            return $ultimaLectura && $ultimaLectura->created_at->diffInMinutes(now()) <= 5;
        })->count();
        $nodosOffline = $totalNodos - $nodosOnline;
        return view('ehscefa.hardware.nodo', compact('nodos', 'totalNodos', 'nodosOnline', 'nodosOffline'));
    }
}
