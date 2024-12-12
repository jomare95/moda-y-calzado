@php
    $user = Auth::user(); // Obtener el usuario autenticado
    $isAdmin = $user && $user->rol === 'Administrador'; // Verificar si es administrador
@endphp

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-gray-800">
                        Moda y Calzado
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <!-- Inicio -->
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border-b-2 text-base font-medium leading-5 focus:outline-none transition duration-150 ease-in-out {{ request()->routeIs('dashboard') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <i class="fas fa-home mr-2"></i>
                        Inicio
                    </a>

                    <!-- Productos -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="inline-flex items-center px-4 py-2 border-b-2 text-base font-medium leading-5 focus:outline-none transition duration-150 ease-in-out {{ request()->routeIs('productos.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <i class="fas fa-box mr-2"></i>
                            Productos
                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" class="absolute z-50 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                            <div class="py-1">
                                <a href="{{ route('productos.index') }}" class="group flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700">
                                    <i class="fas fa-list mr-3 text-gray-400 group-hover:text-indigo-500"></i>
                                    Listado de Productos
                                </a>
                                @if($isAdmin) <!-- Solo para administradores -->
                                    <a href="{{ route('productos.create') }}" class="group flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700">
                                        <i class="fas fa-plus-circle mr-3 text-gray-400 group-hover:text-indigo-500"></i>
                                        Nuevo Producto
                                    </a>
                                    <a href="{{ route('categorias.index') }}" class="group flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700">
                                        <i class="fas fa-tags mr-3 text-gray-400 group-hover:text-indigo-500"></i>
                                        Categorías
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Compras (solo para administradores) -->
                    @if($isAdmin)
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="inline-flex items-center px-4 py-2 border-b-2 text-base font-medium leading-5 focus:outline-none transition duration-150 ease-in-out {{ request()->routeIs('compras.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <i class="fas fa-shopping-bag mr-2"></i>
                            Compras
                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" class="absolute z-50 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                            <div class="py-1">
                                <a href="{{ route('compras.create') }}" class="group flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700">
                                    <i class="fas fa-plus-circle mr-3 text-gray-400 group-hover:text-green-500"></i>
                                    Nueva Compra
                                </a>
                                <a href="{{ route('compras.index') }}" class="group flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700">
                                    <i class="fas fa-list mr-3 text-gray-400 group-hover:text-green-500"></i>
                                    Listado de Compras
                                </a>
                                <a href="{{ route('proveedores.index') }}" class="group flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700">
                                    <i class="fas fa-truck mr-3 text-gray-400 group-hover:text-green-500"></i>
                                    Proveedores
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Ventas -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="inline-flex items-center px-4 py-2 border-b-2 text-base font-medium leading-5 focus:outline-none transition duration-150 ease-in-out {{ request()->routeIs('ventas.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <i class="fas fa-cash-register mr-2"></i>
                            Ventas
                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" class="absolute z-50 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                            <div class="py-1">
                                <a href="{{ route('ventas.create') }}" class="group flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700">
                                    <i class="fas fa-plus-circle mr-3 text-gray-400 group-hover:text-blue-500"></i>
                                    Nueva Venta
                                </a>
                                <a href="{{ route('ventas.index') }}" class="group flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700">
                                    <i class="fas fa-list mr-3 text-gray-400 group-hover:text-blue-500"></i>
                                    Listado de Ventas
                                </a>
                                <a href="{{ route('clientes.index') }}" class="group flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700">
                                    <i class="fas fa-users mr-3 text-gray-400 group-hover:text-blue-500"></i>
                                    Clientes
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <div class="ml-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 px-4 py-2 rounded-lg transition-colors duration-150">
                                <i class="fas fa-user-circle text-xl mr-2"></i>
                                {{ Auth::user()->name }}
                                <i class="fas fa-chevron-down ml-2 text-xs"></i>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Profile -->
                            <x-dropdown-link href="{{ route('profile.edit') }}" class="flex items-center">
                                <i class="fas fa-user-cog mr-2"></i>
                                {{ __('Perfil') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link href="{{ route('logout') }}"
                                    class="flex items-center text-red-600 hover:text-red-800 hover:bg-red-50"
                                    onclick="event.preventDefault();
                                    this.closest('form').submit();">
                                    <i class="fas fa-sign-out-alt mr-2"></i>
                                    {{ __('Cerrar Sesión') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </div>
    </div>
</nav>
