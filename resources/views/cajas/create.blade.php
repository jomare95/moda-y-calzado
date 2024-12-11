@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold">Abrir Nueva Caja</h1>
    <form action="{{ route('cajas.apertura') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="monto_inicial">Monto Inicial</label>
            <input type="number" name="monto_inicial" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Abrir Caja</button>
    </form>
</div>
@endsection
