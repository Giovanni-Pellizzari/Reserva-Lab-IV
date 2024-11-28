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
                        <td>{{ \Carbon\Carbon::parse($reserva->reservation_date)->format('d/m/Y') }}</td> <!-- Fecha formateada -->
                        <td>{{ \Carbon\Carbon::parse($reserva->start_time)->format('H:i') }}hs</td> <!-- Hora de inicio formateada -->
                        <td>{{ \Carbon\Carbon::parse($reserva->end_time)->format('H:i') }}hs</td> <!-- Hora de finalización formateada -->
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
