@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold">Cajas</h1>
    <a href="{{ route('cajas.create') }}" class="btn btn-primary">Abrir Nueva Caja</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha Apertura</th>
                <th>Monto Inicial</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cajas as $caja)
            <tr>
                <td>{{ $caja->id_caja }}</td>
                <td>{{ $caja->fecha_apertura }}</td>
                <td>{{ $caja->monto_inicial }}</td>
                <td>{{ $caja->estado }}</td>
                <td>
                    <form action="{{ route('cajas.cierre', $caja->id_caja) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger">Cerrar Caja</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $cajas->links() }}
</div>
@endsection
