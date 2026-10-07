<?php

use App\Http\Controllers\Admin\AsignacionController;
use App\Http\Controllers\Admin\NodoController;
use App\Http\Controllers\InstructorDashboardController;
use App\Http\Controllers\Instructor\InstructorDashboardController;
use App\Http\Controllers\Instructor\InstructorPredictivoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\Ehs\EHSController;
use App\Http\Controllers\Ehs\ContingenciaController;
use App\Http\Controllers\Ehs\ReporteController;
use App\Http\Controllers\Ehs\HistorialController;
use App\Http\Controllers\Ehs\PredictivoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {   
    if (Auth::check()) {
        $user = Auth::user();
        $roleCode = $user->role ? $user->role->code : null;
        $roleId = $user->role_id;

        if ($roleCode === 'ADMIN' || $roleId == 1) {
            return redirect()->route('admin.dashboard');
        } elseif ($roleCode === 'SST' || $roleId == 2) {
            return redirect()->route('ehscefa.dashboard');
        } elseif ($roleCode === 'INSTRUCTOR' || $roleId == 3) {
            return redirect()->route('instructor.dashboard');
        }
    }

    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    $roleCode = $user->role ? $user->role->code : null;
    $roleId = $user->role_id ?? null;

    if ($roleCode === 'ADMIN' || $roleId == 1) {
        return redirect()->route('admin.dashboard');
    } elseif ($roleCode === 'SST' || $roleId == 2) {
        return redirect()->route('ehscefa.dashboard');
    } elseif ($roleCode === 'INSTRUCTOR' || $roleId == 3) {
        return redirect()->route('instructor.dashboard');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 1. Grupo Administrador
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        $roleCode = $user->role ? $user->role->code : null;
        $roleId = $user->role_id;

        if ($roleCode !== 'ADMIN' && $roleId != 1) {
            return redirect()->route('dashboard');
        }

        return view('admin.dashboard');
    })->name('dashboard');
    Route::get('/nodos', [NodoController::class, 'index'])->name('nodos');
    Route::post('/nodos/asignar-ambiente', [NodoController::class, 'asignarAmbiente'])->name('nodos.asignar-ambiente');

    // Asignación de Ambientes a Instructores
    Route::get('/asignaciones', [AsignacionController::class, 'index'])->name('asignaciones.index');
    Route::post('/asignaciones', [AsignacionController::class, 'store'])->name('asignaciones.store');
    Route::delete('/asignaciones/{id}', [AsignacionController::class, 'destroy'])->name('asignaciones.destroy');
    // Gestión de usuarios
    Route::get('/usuarios/crear', [\App\Http\Controllers\Admin\UsuarioController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios/crear', [\App\Http\Controllers\Admin\UsuarioController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios', [\App\Http\Controllers\Admin\UsuarioController::class, 'index'])->name('usuarios.index');
    Route::put('/usuarios/{usuario}', [\App\Http\Controllers\Admin\UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{usuario}', [\App\Http\Controllers\Admin\UsuarioController::class, 'destroy'])->name('usuarios.destroy');
    // Módulos copiados de EHS
    Route::get('/historico', [\App\Http\Controllers\Admin\HistorialController::class, 'index'])->name('historico.index');
    Route::get('/reportes', [\App\Http\Controllers\Admin\ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/imprimir-pdf', [\App\Http\Controllers\Admin\ReporteController::class, 'imprimirPdf'])->name('reportes.pdf');
    Route::get('/reportes/exportar-csv', [\App\Http\Controllers\Admin\ReporteController::class, 'exportarCsv'])->name('reportes.csv');
    Route::get('/predictivo', [\App\Http\Controllers\Admin\PredictivoController::class, 'index'])->name('predictivo.index');
});

// 2. Grupo SST / EHS CEFA
Route::middleware(['auth'])->prefix('ehscefa')->name('ehscefa.')->group(function () {

    // Ruta del Módulo Estado de Hardware
    Route::get('/hardware/nodo', [EHSController::class, 'estadoHardware'])->name('hardware.nodo');
    Route::get('/contingencias', [ContingenciaController::class, 'index'])->name('contingencias.index');
    Route::post('/contingencias', [ContingenciaController::class, 'store'])->name('contingencias.store');
    Route::put('/contingencias/{id}', [ContingenciaController::class, 'update'])->name('contingencias.update');
    Route::delete('/contingencias/{id}', [ContingenciaController::class, 'destroy'])->name('contingencias.destroy');
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/imprimir-pdf', [ReporteController::class, 'imprimirPdf'])->name('reportes.pdf');
    Route::get('/reportes/exportar-csv', [ReporteController::class, 'exportarCsv'])->name('reportes.csv');

    Route::get('/historico', [HistorialController::class, 'index'])->name('historico.index');
    Route::get('/predictivo', [PredictivoController::class, 'index'])->name('predictivo.index');

    Route::get('/dashboard', function () {
        $user = Auth::user();
        $roleCode = $user->role ? $user->role->code : null;
        $roleId = $user->role_id;

        if ($roleCode !== 'SST' && $roleId != 2 && $roleCode !== 'ADMIN' && $roleId != 1) {
            return redirect()->route('dashboard');
        }

        return view('ehscefa.dashboard');
    })->name('dashboard');
});

// 3. Grupo Instructor
Route::middleware(['auth'])->prefix('instructor')->name('instructor.')->group(function () {
    Route::get('/dashboard', [InstructorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/predictivo', [InstructorPredictivoController::class, 'index'])->name('predictivo.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::redirect('/nodos', '/admin/nodos');

require __DIR__.'/auth.php';

