@extends('layouts.app')

@section('content')
<div class="card-header text-center ">
    <h1 class="text-black font-weight-bold">
        <span class="recuadro-fondo">Mis Servicios Reservados</span>
    </h1>
</div>
<div class="container">
    @if ($reservas->isEmpty())
        <p>No tienes reservas aún.</p>
    @else
    <table class="table table-white table-striped table-hover mt-4">
                    <thead class="text-center text-black">
                        <tr>
                            <th>Cliente</th>
                            <th>Servicio</th>
                            <th>Fecha</th>
                            <th>Hora de Inicio</th>
                            <th>Hora de Finalización</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
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

    /* Fondo de la tarjeta */
    .card {
        border-radius: 0px;
        background: rgb(72, 61, 139);
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

    /* Alternancia de colores en las filas */
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #2c2f33;
    }

    .table-striped tbody tr:nth-of-type(even) {
        background-color: #343a40;
    }

    /* Mejorar visibilidad de los bordes */
    .table th, .table td {
        border: 1px solid #444;
    }

    /* Espaciado adicional para la tabla */
    .table {
        margin-bottom: 0;
    }
</style>

@endsection
