@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full px-6 py-8">
        <div class="text-center">
            <h1 class="text-9xl font-bold text-gray-200">419</h1>
            <p class="text-2xl font-semibold text-gray-600 mt-4">Sesión Expirada</p>
            <p class="text-gray-500 mt-2">Tu sesión ha expirado. Por favor, vuelve a iniciar sesión.</p>
            
            <div class="mt-6">
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Iniciar Sesión
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 