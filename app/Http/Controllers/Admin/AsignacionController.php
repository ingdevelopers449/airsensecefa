<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Environment;
use App\Models\EnvironmentAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AsignacionController extends Controller
{
    /**
     * Muestra la lista de instructores y sus ambientes asignados.
     */
    public function index()
    {
        // Consultar instructores activos (usuarios con rol INSTRUCTOR o role_id = 3, o todos los activos)
        $instructors = User::with('role')
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereHas('role', function ($r) {
                    $r->where('code', 'INSTRUCTOR');
                })->orWhere('role_id', 3);
            })
            ->orderBy('name')
            ->get();

        // En caso de que no haya instructores filtrados por rol aún, traemos todos los usuarios activos
        if ($instructors->isEmpty()) {
            $instructors = User::where('is_active', true)->orderBy('name')->get();
        }

        // Consultar ambientes de formación activos
        $environments = Environment::where('is_active', true)
            ->orderBy('name')
            ->get();

        // Consultar asignaciones vigentes con sus relaciones
        $currentAssignments = EnvironmentAssignment::with(['environment', 'instructor', 'assignedBy'])
            ->where('is_current', true)
            ->get();

        // Mapear ambiente asignado por instructor para acceso rápido en la vista
        $instructorAssignments = $currentAssignments->keyBy('instructor_user_id');

        return view('admin.asignaciones.index', compact(
            'instructors',
            'environments',
            'currentAssignments',
            'instructorAssignments'
        ));
    }

    /**
     * Asigna o reasigna un ambiente de formación a un instructor.
     */
    public function store(Request $request)
    {
        $request->validate([
            'instructor_user_id' => 'required|exists:users,id',
            'environment_id' => 'required|exists:environments,id',
        ], [
            'instructor_user_id.required' => 'Debe seleccionar un instructor.',
            'environment_id.required' => 'Debe seleccionar un ambiente de formación.',
        ]);

        $instructorId = $request->instructor_user_id;
        $environmentId = $request->environment_id;

        // 1. Finalizar la asignación previa vigente del instructor si existe
        EnvironmentAssignment::where('instructor_user_id', $instructorId)
            ->where('is_current', true)
            ->update([
                'is_current' => false,
                'ended_at' => now(),
            ]);

        // 2. Finalizar cualquier asignación previa vigente que tuviera este ambiente
        EnvironmentAssignment::where('environment_id', $environmentId)
            ->where('is_current', true)
            ->update([
                'is_current' => false,
                'ended_at' => now(),
            ]);

        // 3. Crear la nueva asignación vigente
        EnvironmentAssignment::create([
            'environment_id' => $environmentId,
            'instructor_user_id' => $instructorId,
            'assigned_by' => Auth::id(),
            'assigned_at' => now(),
            'is_current' => true,
        ]);

        return redirect()
            ->route('admin.asignaciones.index')
            ->with('success', 'Ambiente asignado al instructor correctamente.');
    }

    /**
     * Finaliza la asignación actual de un instructor.
     */
    public function destroy($id)
    {
        $assignment = EnvironmentAssignment::findOrFail($id);

        $assignment->update([
            'is_current' => false,
            'ended_at' => now(),
        ]);

        return redirect()
            ->route('admin.asignaciones.index')
            ->with('success', 'La asignación del ambiente ha sido finalizada.');
    }
}
