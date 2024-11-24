<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|unique:marcas,nombre'
            ]);

            $marca = Marca::create([
                'nombre' => $request->nombre
            ]);

            return response()->json([
                'success' => true,
                'marca' => $marca,
                'message' => 'Marca creada exitosamente'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al crear marca: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la marca'
            ], 500);
        }
    }
} 