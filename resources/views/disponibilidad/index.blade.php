@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Mis Servicios Reservados</h1>
    <p>Aquí puedes ver las reservas realizadas a tus servicios.</p>

    @if ($reservas->isEmpty())
        <p>No tienes reservas aún.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Servicio</th>
                    <th>Fecha</th>
                    <th>Hora de inicio</th>
                    <th>Hora de finalización</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reservas as $reserva)
                    <tr>
                        <td>{{ $reserva->user->name }}</td>
                        <td>{{ $reserva->service->name }}</td>
                        <td>{{ $reserva->reservation_date }}</td>
                        <td>{{ $reserva->start_time }}</td>
                        <td>{{ $reserva->end_time }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>


@endsection
