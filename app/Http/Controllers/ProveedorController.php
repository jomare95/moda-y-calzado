<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::latest('created_at')->paginate(10);
        return view('proveedores.index', compact('proveedores'));
    }

    public function create()
    {
        return view('proveedores.create');
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $proveedor = new Proveedor();
            $proveedor->razon_social = $request->razon_social;
            $proveedor->tipo_documento = $request->tipo_documento;
            $proveedor->numero_documento = $request->numero_documento;
            $proveedor->direccion = $request->direccion;
            $proveedor->telefono = $request->telefono;
            $proveedor->email = $request->email;
            $proveedor->contacto_nombre = $request->contacto_nombre;
            $proveedor->contacto_telefono = $request->contacto_telefono;
            $proveedor->estado = 1;
            
            $proveedor->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Proveedor guardado correctamente']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function edit(Proveedor $proveedor)
    {
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $request->validate([
            'razon_social' => 'required|string|max:100',
            'tipo_documento' => 'required|in:DNI,CUIT,RUC',
            'numero_documento' => 'required|string|max:20|unique:proveedores,numero_documento,' . $proveedor->id_proveedor . ',id_proveedor',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'contacto_nombre' => 'nullable|string|max:100',
            'contacto_telefono' => 'nullable|string|max:20'
        ]);

        try {
            DB::beginTransaction();

            $proveedor->update($request->all());

            DB::commit();
            return redirect()->route('proveedores.index')
                ->with('success', 'Proveedor actualizado correctamente');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Error al actualizar el proveedor: ' . $e->getMessage());
        }
    }
} 