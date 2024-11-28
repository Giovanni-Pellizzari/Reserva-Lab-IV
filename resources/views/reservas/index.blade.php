@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Mostrar el mensaje de éxito si existe -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <h1>Mis Reservas</h1>

    <table class="table mt-4">
        <thead>
            <tr>
                <th>Servicio</th>
                <th>Fecha de Reserva</th>
                <th>Hora de Inicio</th> <!-- Mostrar la hora de inicio -->
                <th>Hora de Fin</th> <!-- Nueva columna para la hora de fin -->
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reservas as $reserva)
                <tr>
                    <td>{{ $reserva->service->name }}</td>
                    <td>{{ $reserva->reservation_date }}</td>
                    <td>{{ $reserva->start_time }}</td> <!-- Mostrar la hora de la reserva -->
                    <td>{{ $reserva->end_time }}</td> <!-- Mostrar la hora de finalización -->
                    <td>{{ $reserva->status }}</td>
                    <td>
                        {{-- Mostrar botón de cancelar si la reserva no está cancelada --}}
                        @if ($reserva->status !== 'cancelled')
                            <form action="{{ route('reservas.cancel', $reserva->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas cancelar esta reserva?');">
                                @csrf
                                @method('DELETE') <!-- Asegúrate de que se use DELETE -->
                                <button type="submit" class="btn btn-danger btn-sm">Cancelar Reserva</button>
                            </form>
                        @else
                            <!-- Si la reserva está cancelada, mostrar botones para modificar y eliminar -->
                            <a href="{{ route('reservas.edit', $reserva->id) }}" class="btn btn-warning btn-sm">Modificar</a>

                            {{-- Formulario para eliminar la reserva --}}
                            <form action="{{ route('reservas.destroy', $reserva->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        @endif

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
