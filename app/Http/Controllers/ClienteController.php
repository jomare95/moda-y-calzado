<?php
namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::orderBy('nombre')->paginate(10);
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'tipo_documento' => 'required|string|max:20',
            'numero_documento' => 'required|string|max:20|unique:clientes',
            'direccion' => 'nullable|string|max:200',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'fecha_nacimiento' => 'nullable|date'
        ]);

        try {
            DB::beginTransaction();

            $cliente = new Cliente();
            $cliente->nombre = $request->nombre;
            $cliente->tipo_documento = $request->tipo_documento;
            $cliente->numero_documento = $request->numero_documento;
            $cliente->direccion = $request->direccion;
            $cliente->telefono = $request->telefono;
            $cliente->email = $request->email;
            $cliente->fecha_nacimiento = $request->fecha_nacimiento;
            $cliente->estado = 1;
            $cliente->save();

            DB::commit();
            return redirect()->route('clientes.index')
                ->with('success', 'Cliente registrado correctamente');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error($e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['error' => 'Error al registrar el cliente: ' . $e->getMessage()]);
        }
    }

    public function show(Cliente $cliente)
    {
        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'tipo_documento' => 'required|string|max:20',
            'numero_documento' => 'required|string|max:20|unique:clientes,numero_documento,' . $cliente->id_cliente . ',id_cliente',
            'direccion' => 'nullable|string|max:200',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'fecha_nacimiento' => 'nullable|date'
        ]);

        try {
            DB::beginTransaction();

            $cliente->nombre = $request->nombre;
            $cliente->tipo_documento = $request->tipo_documento;
            $cliente->numero_documento = $request->numero_documento;
            $cliente->direccion = $request->direccion;
            $cliente->telefono = $request->telefono;
            $cliente->email = $request->email;
            $cliente->fecha_nacimiento = $request->fecha_nacimiento;
            $cliente->save();

            DB::commit();
            return redirect()->route('clientes.index')
                ->with('success', 'Cliente actualizado correctamente');

        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Error al actualizar el cliente: ' . $e->getMessage()]);
        }
    }

    public function destroy(Cliente $cliente)
    {
        try {
            $cliente->estado = 0;
            $cliente->save();

            return redirect()
                ->route('clientes.index')
                ->with('success', 'Cliente desactivado correctamente');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al desactivar el cliente: ' . $e->getMessage());
        }
    }
} 