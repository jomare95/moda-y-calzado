@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Editar Proveedor</h1>
        <a href="{{ route('proveedores.index') }}" 
           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-arrow-left mr-2"></i>
            Volver
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <form action="{{ route('proveedores.update', $proveedor->id_proveedor) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Razón Social -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Razón Social *
                    </label>
                    <input type="text" 
                           name="razon_social"
                           class="form-input w-full rounded-md shadow-sm @error('razon_social') border-red-500 @enderror" 
                           value="{{ old('razon_social', $proveedor->razon_social) }}" 
                           required>
                    @error('razon_social')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipo Documento -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo Documento *
                    </label>
                    <select name="tipo_documento"
                            class="form-select w-full rounded-md shadow-sm @error('tipo_documento') border-red-500 @enderror" 
                            required>
                        <option value="">Seleccione...</option>
                        <option value="DNI" {{ old('tipo_documento', $proveedor->tipo_documento) == 'DNI' ? 'selected' : '' }}>DNI</option>
                        <option value="RUC" {{ old('tipo_documento', $proveedor->tipo_documento) == 'RUC' ? 'selected' : '' }}>RUC</option>
                        <option value="CUIT" {{ old('tipo_documento', $proveedor->tipo_documento) == 'CUIT' ? 'selected' : '' }}>CUIT</option>
                    </select>
                    @error('tipo_documento')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Número Documento -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Número Documento *
                    </label>
                    <input type="text" 
                           name="numero_documento"
                           class="form-input w-full rounded-md shadow-sm @error('numero_documento') border-red-500 @enderror" 
                           value="{{ old('numero_documento', $proveedor->numero_documento) }}" 
                           required>
                    @error('numero_documento')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Teléfono -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Teléfono
                    </label>
                    <input type="text" 
                           name="telefono"
                           class="form-input w-full rounded-md shadow-sm @error('telefono') border-red-500 @enderror" 
                           value="{{ old('telefono', $proveedor->telefono) }}">
                    @error('telefono')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input type="email" 
                           name="email"
                           class="form-input w-full rounded-md shadow-sm @error('email') border-red-500 @enderror" 
                           value="{{ old('email', $proveedor->email) }}">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contacto Nombre -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre de Contacto
                    </label>
                    <input type="text" 
                           name="contacto_nombre"
                           class="form-input w-full rounded-md shadow-sm @error('contacto_nombre') border-red-500 @enderror" 
                           value="{{ old('contacto_nombre', $proveedor->contacto_nombre) }}">
                    @error('contacto_nombre')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contacto Teléfono -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Teléfono de Contacto
                    </label>
                    <input type="text" 
                           name="contacto_telefono"
                           class="form-input w-full rounded-md shadow-sm @error('contacto_telefono') border-red-500 @enderror" 
                           value="{{ old('contacto_telefono', $proveedor->contacto_telefono) }}">
                    @error('contacto_telefono')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Dirección -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Dirección
                </label>
                <textarea name="direccion" 
                          rows="2" 
                          class="form-textarea w-full rounded-md shadow-sm @error('direccion') border-red-500 @enderror">{{ old('direccion', $proveedor->direccion) }}</textarea>
                @error('direccion')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('proveedores.index') }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Actualizar Proveedor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 