<?php

use App\Http\Controllers\Admin\NodoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Ehs\EHSController;
use App\Http\Controllers\UsuarioController;
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
});

// 2. Grupo SST / EHS CEFA
Route::middleware(['auth'])->prefix('ehscefa')->name('ehscefa.')->group(function () {

    // Ruta del Módulo Estado de Hardware
    Route::get('/hardware/nodo', [EHSController::class, 'estadoHardware'])->name('hardware.nodo');

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
    Route::get('/dashboard', function () {
        $user = Auth::user();
        $roleCode = $user->role ? $user->role->code : null;
        $roleId = $user->role_id;

        if ($roleCode !== 'INSTRUCTOR' && $roleId != 3 && $roleCode !== 'ADMIN' && $roleId != 1) {
            return redirect()->route('dashboard');
        }

        return view('instructor.dashboard');
    })->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::redirect('/nodos', '/admin/nodos');

require __DIR__.'/auth.php';

