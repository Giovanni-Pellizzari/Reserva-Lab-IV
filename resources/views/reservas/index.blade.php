@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Mis Reservas</h1>
    <a href="{{ route('reservas.create') }}" class="btn btn-primary">Nueva Reserva</a>

    <table class="table mt-4">
        <thead>
            <tr>
                <th>Servicio</th>
                <th>Fecha de Reserva</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reservas as $reserva)
                <tr>
                    
                    <td>{{ $reserva->service->name }}</td> 
                    <td>{{ $reserva->reservation_date }}</td> 
                    <td>{{ $reserva->status }}</td> 
                    <td>
                        <form action="{{ route('reservas.cancel', $reserva->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-danger btn-sm">Cancelar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
