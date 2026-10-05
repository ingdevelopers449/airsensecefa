@extends('layouts.sidebaradmincefa')
@section('tituloPagina', 'Registro de Usuario')
@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mt-4 max-w-md mx-4 sm:mx-auto">
    <div class="px-5 py-3 border-b border-slate-200 bg-emerald-50/50 flex items-center justify-between">
        <h3 class="font-heading font-bold text-slate-800 text-base flex items-center gap-2">
            <i class="fas fa-user-plus text-emerald-500"></i> Nuevo Usuario
        </h3>
    </div>
    
    <form method="POST" action="{{ route('admin.usuarios.store') }}">
        @csrf
        <div class="px-5 py-4">
            <div class="grid grid-cols-1 gap-4">
                <!-- Rol -->
                <div>
                    <label for="role_id" class="block text-sm font-semibold text-slate-700 mb-2">Rol <span class="text-rose-500">*</span></label>
                    <select id="role_id" name="role_id" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500 outline-none transition-all @error('role_id') border-rose-500 @enderror"> 
                        <option value="">Seleccione un rol</option>
                        @isset($roles)
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ ucfirst($role->nombre) }}</option>
                            @endforeach
                        @else
                            <option value="1" {{ old('role_id') == '1' ? 'selected' : '' }}>Administrador</option>
                            <option value="2" {{ old('role_id') == '2' ? 'selected' : '' }}>Funcionario de SST</option>
                            <option value="3" {{ old('role_id') == '3' ? 'selected' : '' }}>Instructor</option>
                        @endisset
                    </select>
                    @error('role_id')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Nombre <span class="text-rose-500">*</span></label>
                    <input type="text" id="name" name="name" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500 outline-none transition-all @error('name') border-rose-500 @enderror" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Ingrese nombre">
                    @error('name')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Correo Electrónico <span class="text-rose-500">*</span></label>
                    <input type="email" id="email" name="email" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500 outline-none transition-all @error('email') border-rose-500 @enderror" value="{{ old('email') }}" required autocomplete="username" placeholder="Ingrese correo">
                    @error('email')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">Contraseña <span class="text-rose-500">*</span></label>
                    <input type="password" id="password" name="password" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500 outline-none transition-all @error('password') border-rose-500 @enderror" required autocomplete="new-password" placeholder="Ingrese contraseña">
                    @error('password')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">Confirmar Contraseña <span class="text-rose-500">*</span></label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500 outline-none transition-all @error('password_confirmation') border-rose-500 @enderror" required autocomplete="new-password" placeholder="Confirme contraseña">
                    @error('password_confirmation')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        <div class="px-5 py-3 bg-slate-50 border-t border-slate-200 flex justify-center items-center">
            <button type="submit" class="w-full justify-center px-4 py-2 text-sm font-medium text-white bg-emerald-500 hover:bg-emerald-600 rounded-xl transition-colors flex items-center gap-2">
                <i class="fas fa-save"></i> Registrar Usuario
            </button>
        </div>
    </form>
</div>
@endsection