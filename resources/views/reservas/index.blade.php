@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Mostrar el mensaje de éxito si existe -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card-header text-center ">
    <h1 class="text-black font-weight-bold">
        <span class="recuadro-fondo">Mis Reservas</span>
    </h1>
</div>

    <table class="table table-white table-striped table-hover mt-4">
                    <thead class="text-center">
                        <tr>
                            <th>Servicio</th>
                            <th>Fecha de Reserva</th>
                            <th>Hora de Inicio</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($reservas as $reserva)
                            <tr>
                                <td>{{ $reserva->service->name }}</td>
                                <td>{{ $reserva->reservation_date }}</td>
                                <td>{{ $reserva->start_time }}</td>
                                <td>{{ $reserva->status }}</td>
                                <td>
                                    @if ($reserva->status !== 'cancelled')
                                        <form action="{{ route('reservas.cancel', $reserva->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas cancelar esta reserva?');" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Cancelar</button>
                                        </form>
                                    @else
                                        <a href="{{ route('reservas.edit', $reserva->id) }}" class="btn btn-warning btn-sm">Modificar</a>
                                        <form action="{{ route('reservas.destroy', $reserva->id) }}" method="POST" class="d-inline">
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

<style>

body {
            background-color: rgb(2, 65, 82); 
        }

        .content-wrapper{
            background-color: rgb(2, 65, 82); 
        }

    .card-header {
    display: flex;
    background-color: transparent !important; 
    justify-content: center; /* Centra horizontalmente */
    align-items: center; /* Centra verticalmente */
    height: 100px; /* Ajusta la altura según sea necesario */
    border-bottom: none;
}

.recuadro-fondo {
    margin-top: 0px;
    background-color: white; 
    padding: 10px 20px; /* Espaciado alrededor del texto */
    border-radius: 30px; /* Bordes redondeados */
    display: inline-block; /* Asegura que el fondo solo cubra el texto */
    text-align: center; /* Centra el texto dentro del recuadro */
    font-family: sans-serif;
}
    /* Fondo de la tarjeta y la tabla */
    .card {
        border-radius: 0px;
    
    }

    .card-header {
        background-color: #343a40; /* Fondo oscuro para el encabezado */
    }

    /* Títulos en el encabezado de la tarjeta */
    .card-header h2 {
        font-size: 32px;
        font-weight: bold;
    }

    /* Estilo para la tabla */
    .table-dark {
        background-color: #343a40;
    }

    .table thead th {
        font-size: 18px;
        color: black;
    }

    .table tbody td {
        font-size: 16px;
    }

    /* Botones dentro de la tabla */
    .btn-sm {
        padding: 5px 10px;
        font-size: 14px;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #000;
    }
</style>


@endsection
