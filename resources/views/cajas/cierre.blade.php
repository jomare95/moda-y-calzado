@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold">Cerrar Caja</h1>
    <form action="{{ route('cajas.cierre', $caja->id_caja) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="monto_final">Monto Final</label>
            <input type="number" name="monto_final" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-danger">Cerrar Caja</button>
    </form>
</div>
@endsection
