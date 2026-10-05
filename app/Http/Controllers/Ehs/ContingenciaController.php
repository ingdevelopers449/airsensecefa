<?php

namespace App\Http\Controllers\Ehs;

use App\Http\Controllers\Controller;
use App\Models\ContingencyProtocol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContingenciaController extends Controller
{
    public function index()
    {
        $protocolos = ContingencyProtocol::with('creator')->orderBy('id', 'desc')->get();
        return view('ehscefa.contingencias.index', compact('protocolos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|in:co2,temperature,humidity',
            'risk_level' => 'required|in:warning,danger',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'action_steps' => 'required|string',
        ]);

        ContingencyProtocol::create([
            'category' => $request->category,
            'risk_level' => $request->risk_level,
            'title' => $request->title,
            'description' => $request->description,
            'action_steps' => $request->action_steps,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('ehscefa.contingencias.index')->with('success', 'Protocolo de contingencia creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $protocolo = ContingencyProtocol::findOrFail($id);

        $request->validate([
            'category' => 'required|in:co2,temperature,humidity',
            'risk_level' => 'required|in:warning,danger',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'action_steps' => 'required|string',
        ]);

        $protocolo->update($request->only(['category', 'risk_level', 'title', 'description', 'action_steps']));

        return redirect()->route('ehscefa.contingencias.index')->with('success', 'Protocolo actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $protocolo = ContingencyProtocol::findOrFail($id);
        $protocolo->delete();

        return redirect()->route('ehscefa.contingencias.index')->with('success', 'Protocolo eliminado de la base de datos.');
    }
}
