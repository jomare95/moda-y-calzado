<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-indigo-600 px-6 py-8">
                <h2 class="text-2xl font-bold text-center text-white mb-2">
                    Moda y Calzado
                </h2>
                <p class="text-center text-indigo-200">Ingresa tus credenciales para acceder</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="px-6 py-8">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Correo Electrónico
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mt-6">
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Contraseña
                    </label>
                    <input id="password" type="password" name="password" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="mt-6 flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                            Recordarme
                        </label>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Iniciar Sesión
                    </button>
                </div>
            </form>

            <div class="mt-4 text-center">
                <p class="text-sm text-gray-600">
                    ¿No tienes una cuenta? 
                    <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">
                        Regístrate aquí
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
