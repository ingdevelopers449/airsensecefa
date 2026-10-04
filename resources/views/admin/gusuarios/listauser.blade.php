@extends('layouts.sidebaradmincefa')
@section('tituloPagina', 'Lista de Usuarios')
@section('content') 

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mt-4 mx-4 sm:mx-0">
    <div class="overflow-x-auto">
            <table id="usuariosTable" class="w-full text-sm text-left text-slate-600 border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="px-4 py-3 rounded-tl-lg">ID</th>
                        <th class="px-4 py-3">Usuarios</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Rol</th>
                        <th class="px-4 py-3 text-center">Editar</th>
                        <th class="px-4 py-3 text-center rounded-tr-lg">Eliminar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                 @foreach ($usuarios as $usuario)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-4 py-3 font-semibold text-slate-800">{{ $usuario->id }}</td>
                            <td class="px-4 py-3 font-medium text-slate-700">{{ $usuario->name }}</td>
                            <td class="px-4 py-3">{{ $usuario->email }}</td>
                            <td class="px-4 py-3">
                                @if($usuario->role_id == 1) Administrador
                                @elseif($usuario->role_id == 2) Funcionario de SST
                                @elseif($usuario->role_id == 3) Instructor
                                @else Sin Rol
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button type="button" 
                                    class="editbtn inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-200 hover:text-amber-700 transition-colors"
                                    data-id="{{ $usuario->id }}"
                                    data-name="{{ $usuario->name }}"
                                    data-email="{{ $usuario->email }}"
                                    data-role="{{ $usuario->role_id }}">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </button>
                            </td>
                            <td class="px-4 py-3 text-center">                     
                                <button type="button" 
                                    class="deletebtn inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-100 text-rose-600 hover:bg-rose-200 hover:text-rose-700 transition-colors"
                                    data-id="{{ $usuario->id }}">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach  
                </tbody>
            </table>
        </div>
</div>


<!-- MODAL DE EDITAR USUARIO -->
<div id="editarModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop oscuro difuminado acorde al sidebar -->
    <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs transition-opacity modal-close"></div>

    <!-- Contenedor scrollable centrado -->
    <div class="fixed inset-0 z-10 overflow-y-auto p-4 sm:p-6 flex min-h-full items-center justify-center">
        <!-- Panel del modal estilo Enterprise / AgroNexa -->
        <div class="relative w-full max-w-lg overflow-hidden rounded-2xl bg-white text-left shadow-2xl border border-slate-200 transform transition-all">
            <form id="formEditar" action="{{ route('admin.usuarios.update', $usuario->id ?? 0) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Encabezado con Isotipo Agro y Título -->
                <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/80 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-agro-600 to-agro-400 flex items-center justify-center text-white shadow-md shadow-agro-900/20">
                            <i class="fas fa-user-edit text-sm"></i>
                        </div>
                        <div>
                            <h3 class="font-heading font-extrabold text-slate-800 text-lg tracking-tight" id="modal-title">
                                Editar Usuario
                            </h3>
                            <p class="text-xs text-slate-500">Actualiza la información y permisos del usuario</p>
                        </div>
                    </div>
                    <button type="button" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-200/60 transition-colors modal-close">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>

                <!-- Cuerpo del Formulario -->
                <div class="p-6 space-y-5">
                    <div>
                        <label for="edit-name" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                            Nombre de Usuario <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-800 bg-white placeholder-slate-400 focus:border-agro-500 focus:ring-4 focus:ring-agro-500/10 outline-none transition-all duration-150" id="edit-name" name="name" required placeholder="Nombre completo">
                        </div>
                    </div>  

                    <div>
                        <label for="edit-email" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                            Correo Electrónico <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="email" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-800 bg-white placeholder-slate-400 focus:border-agro-500 focus:ring-4 focus:ring-agro-500/10 outline-none transition-all duration-150" id="edit-email" name="email" required placeholder="correo@ejemplo.com">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label for="edit-role" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                                Rol del Sistema <span class="text-rose-500">*</span>
                            </label>
                            <select class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-800 bg-white focus:border-agro-500 focus:ring-4 focus:ring-agro-500/10 outline-none transition-all duration-150" id="edit-role" name="role_id" required>
                                <option value="">Seleccionar Rol</option>
                                <option value="1">Administrador</option>
                                <option value="2">Funcionario de SST</option>
                                <option value="3">Instructor</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="px-6 py-4 bg-slate-50/80 border-t border-slate-200/80 flex justify-end gap-3">
                    <button type="button" class="px-4 py-2.5 text-sm font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-100 hover:text-slate-800 rounded-xl transition-all shadow-xs modal-close">
                        Cancelar
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-agro-600 hover:bg-agro-700 active:bg-agro-800 rounded-xl shadow-md shadow-agro-900/20 transition-all flex items-center gap-2 transform active:scale-95">
                        <i class="fas fa-save text-xs"></i>
                        <span>Guardar Cambios</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('editarModal');

    function openModal() {
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Abrir modal al click en botón editar
    document.querySelectorAll('.editbtn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id      = this.dataset.id;
            const name    = this.dataset.name;
            const email   = this.dataset.email;
            const role    = this.dataset.role;

            // Rellenar campos
            document.getElementById('edit-name').value  = name || '';
            document.getElementById('edit-email').value = email || '';

            const roleSelect = document.getElementById('edit-role');
            if (roleSelect) {
                roleSelect.value = role || '';
            }

            // Actualizar action del formulario
            document.getElementById('formEditar').action = '/admin/usuarios/' + id;

            // Mostrar modal
            openModal();
        });
    });

    // Cerrar modal con cualquier elemento .modal-close
    document.querySelectorAll('.modal-close').forEach(function (el) {
        el.addEventListener('click', function () {
            closeModal();
        });
    });

    // Cerrar modal con tecla Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
});
</script>

<!--Modal Eliminar-->
      <div id="eliminarModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm modal-close-delete" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                    <form id="formEliminar" action="{{ route('admin.usuarios.destroy', $usuario->id ?? 0) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="px-6 py-4 border-b border-slate-200 bg-rose-50/50 flex items-center justify-between">
                            <h3 class="font-heading font-bold text-slate-800 text-lg flex items-center gap-2">
                                <i class="fas fa-exclamation-triangle text-rose-500"></i> Confirmar Eliminación
                            </h3>
                            <button type="button" class="text-slate-400 hover:text-slate-600 transition-colors modal-close-delete">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        <div class="p-6">
                            <p class="text-slate-600 text-sm">¿Está seguro de que desea eliminar este Usuario? Esta acción no se puede deshacer.</p>
                        </div>
                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-3">
                            <button type="button" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-colors modal-close-delete">Cancelar</button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-rose-500 hover:bg-rose-600 rounded-xl transition-colors">Eliminar</button>
                        </div>
                    </form>


                </div>
            </div>
        </div>


<!--Script Eliminar-->
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const modalEliminar = document.getElementById('eliminarModal');

    // Abrir modal con botón eliminar
    document.querySelectorAll('.deletebtn').forEach(function (btn) {
        btn.addEventListener('click', function () {

            const id = this.dataset.id;
            const form = document.getElementById('formEliminar');

            form.action = '/admin/usuarios/' + id;

            modalEliminar.classList.remove('hidden');
        });
    });

    // Cerrar modal
    document.querySelectorAll('.modal-close-delete').forEach(function (el) {
        el.addEventListener('click', function () {
            modalEliminar.classList.add('hidden');
        });
    });

    // Cerrar con tecla Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            modalEliminar.classList.add('hidden');
        }
    });
});
</script>

@endsection

