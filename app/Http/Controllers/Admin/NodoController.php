<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Node;
use App\Models\Environment;
use Illuminate\Http\Request;

class NodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    { 
        // Traemos todos los nodos junto con su ambiente
        $nodes = Node::with('environment')->get();

        // Traemos los ambientes activos para mostrarlos en el select
        $environments = Environment::where('is_active', true)
            ->orderBy('name')
            ->get();

        // Contadores dinámicos calculados directamente desde la base de datos
        $unregisteredCount = $nodes->whereNull('environment_id')->count();
        $locationChangedCount = $nodes->where('location_changed', true)->count();

        // Enviamos las variables a la vista
        return view('admin.nodos.index', compact('nodes', 'environments', 'unregisteredCount', 'locationChangedCount'));
    }

    /** Asigna un ambiente a un nodo. */
    public function asignarAmbiente(Request $request)
{
    // Validamos los datos recibidos
    $request->validate([
        'node_id' => 'required|exists:nodes,id',
        'environment_id' => 'required|exists:environments,id',
    ]);

    // Buscamos el nodo
    $node = Node::findOrFail($request->node_id);

    // Cambiamos el ambiente asignado
    $node->environment_id = $request->environment_id;

    // Guardamos los cambios
    $node->save();

    // Regresamos a la página de nodos
    return redirect()
        ->route('admin.nodos')
        ->with('success', 'Ambiente asignado correctamente.');
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
