<?php
namespace App\Http\Controllers;

use App\Models\Caja;
use Illuminate\Http\Request;

class CajaController extends Controller
{
    public function apertura(Request $request)
    {
        $request->validate([
            'monto_inicial' => 'required|numeric|min:0'
        ]);

        Caja::create([
            'fecha_apertura' => now(),
            'monto_inicial' => $request->monto_inicial,
            'estado' => 'abierta',
            'user_id' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Caja abierta correctamente');
    }

    public function cierre(Caja $caja)
    {
        $caja->update([
            'fecha_cierre' => now(),
            'estado' => 'cerrada'
        ]);

        return redirect()->route('cajas.index')->with('success', 'Caja cerrada correctamente');
    }

    public function index()
    {
        $cajas = Caja::orderBy('fecha_apertura')->paginate(10);
        return view('cajas.index', compact('cajas'));
    }

    public function create()
    {
        return view('cajas.create');
    }
} 