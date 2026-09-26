<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Node;
use Symfony\Component\HttpFoundation\Response;

class ValidateIoTDevice
{
    /**
     * Handle an incoming request for IoT telemetry.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $deviceUid = $request->header('X-Device-UID') ?? $request->input('device_uid');
        $deviceToken = $request->header('X-Device-Token') ?? $request->header('Authorization');

        // Limpiar Bearer si viene en Authorization
        if ($deviceToken && str_starts_with($deviceToken, 'Bearer ')) {
            $deviceToken = substr($deviceToken, 7);
        }

        if (empty($deviceUid) || empty($deviceToken)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Autenticación IoT fallida: Encabezados X-Device-UID y X-Device-Token requeridos.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $node = Node::where('device_uid', $deviceUid)->first();

        if (!$node || !$node->is_active) {
            return response()->json([
                'status' => 'error',
                'message' => 'Autenticación IoT fallida: Dispositivo no registrado o desactivado.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (!Hash::check($deviceToken, $node->device_token_hash)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Autenticación IoT fallida: Token de dispositivo inválido.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Inyectar el nodo validado en la petición
        $request->attributes->set('authenticated_node', $node);

        return $next($request);
    }
}
