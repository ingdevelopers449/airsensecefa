<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role_id;

        if ($role == 1) {
            return redirect()->route('admin.dashboard');
        } elseif ($role == 2) {
            return redirect()->route('ehscefa.dashboard');
        } elseif ($role == 3) {
            return redirect()->route('instructor.dashboard');
        }
    }

    return view('welcome');
});

Route::get('/dashboard', function () {
    $role = Auth::user()->role_id ?? null;

    if ($role == 1) {
        return redirect()->route('admin.dashboard');
    } elseif ($role == 2) {
        return redirect()->route('ehscefa.dashboard');
    } elseif ($role == 3) {
        return redirect()->route('instructor.dashboard');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 1. Grupo Administrador
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});

// 2. Grupo SST / EHS CEFA
Route::middleware(['auth'])->prefix('ehscefa')->name('ehscefa.')->group(function () {
    Route::get('/dashboard', function () {
        return view('ehscefa.dashboard');
    })->name('dashboard');
});

// 3. Grupo Instructor
Route::middleware(['auth'])->prefix('instructor')->name('instructor.')->group(function () {
    Route::get('/dashboard', function () {
        return view('instructor.dashboard');
    })->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
