@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Perfil de Usuario</h1>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Información del Perfil -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center mb-6">
                    <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center text-gray-600">
                        <i class="fas fa-user-circle text-4xl"></i>
                    </div>
                    <div class="ml-6">
                        <h2 class="text-xl font-semibold">{{ Auth::user()->name }}</h2>
                        <p class="text-gray-600">{{ Auth::user()->email }}</p>
                        <p class="text-sm text-gray-500">Usuario desde: {{ Auth::user()->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>

                <!-- Formulario de Actualización de Perfil -->
                <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('patch')

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="name" id="name" value="{{ old('name', Auth::user()->name) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                        <input type="email" name="email" id="email" value="{{ old('email', Auth::user()->email) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-150">
                            <i class="fas fa-save mr-2"></i>
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>

            <!-- Actualizar Contraseña -->
            <div class="p-6 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Actualizar Contraseña</h3>
                <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    @method('put')

                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Contraseña Actual</label>
                        <input type="password" name="current_password" id="current_password"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Nueva Contraseña</label>
                        <input type="password" name="password" id="password"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Nueva Contraseña</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-150">
                            <i class="fas fa-key mr-2"></i>
                            Actualizar Contraseña
                        </button>
                    </div>
                </form>
            </div>

            <!-- Eliminar Cuenta -->
            <div class="p-6 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Eliminar Cuenta</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Una vez que se elimine tu cuenta, todos tus recursos y datos serán eliminados permanentemente.
                        </p>
                    </div>
                    <button type="button" 
                            onclick="confirmarEliminacion()"
                            class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-150">
                        <i class="fas fa-trash-alt mr-2"></i>
                        Eliminar Cuenta
                    </button>
                </div>

                <!-- Formulario oculto para eliminar cuenta -->
                <form id="deleteForm" method="post" action="{{ route('profile.destroy') }}" class="hidden">
                    @csrf
                    @method('delete')
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmarEliminacion() {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar cuenta',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm').submit();
        }
    });
}

// Mostrar mensaje de éxito si existe
@if (session('status') === 'profile-updated')
    Swal.fire({
        icon: 'success',
        title: '¡Perfil actualizado!',
        text: 'Los cambios se han guardado correctamente',
        timer: 2000,
        showConfirmButton: false
    });
@endif

@if (session('status') === 'password-updated')
    Swal.fire({
        icon: 'success',
        title: '¡Contraseña actualizada!',
        text: 'Tu contraseña ha sido cambiada correctamente',
        timer: 2000,
        showConfirmButton: false
    });
@endif
</script>
@endpush
@endsection
