{{-- resources/views/pages/dashboard.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="card-header text-center">
    <h1 class="text-black font-weight-bold">
        <span class="recuadro-fondo">Bienvenido al Dashboard</span>
    </h1> 
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="mb-5">
            </div>
        </div>
    </div>

    <!-- Formulario para crear un nuevo servicio -->
    <div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card bg-white text-black mb-4">
            <div class="card-header text-center bg-light">
                <h4 class="text-dark">Crear Nuevo Servicio</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nombre del Servicio</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Descripción</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="image">Imagen</label>
                        <input type="file" class="form-control-file" id="image" name="image" accept="image/*" required>
                    </div>
                    <!-- Campos de días de trabajo, horarios, intervalos y precio -->
                    <div class="form-group">
                        <label for="working_days">Días de Trabajo</label><br>
                        @foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $day)
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" id="{{ strtolower($day) }}" name="working_days[]" value="{{ $day }}">
                                <label class="form-check-label" for="{{ strtolower($day) }}">{{ $day }}</label>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-group">
                        <label for="working_hours_start">Horario de Inicio</label>
                        <input type="time" class="form-control" id="working_hours_start" name="working_hours_start" required>
                    </div>
                    <div class="form-group">
                        <label for="working_hours_end">Horario de Fin</label>
                        <input type="time" class="form-control" id="working_hours_end" name="working_hours_end" required>
                    </div>
                    <div class="form-group">
                        <label for="reservation_intervals">Intervalo de Reserva (HH:MM)</label>
                        <input type="time" class="form-control" id="reservation_intervals" name="reservation_intervals" required>
                    </div>
                    <div class="form-group">
                        <label for="price">Precio</label>
                        <input type="number" class="form-control" id="price" name="price" required min="0" step="0.01">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Crear Servicio</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <div class="card-Mis-servicios text-center mb-4">
        <h2 class="text-white font-weight-bold">Mis Servicios</h2>
    </div>

    <!-- Listado de Servicios -->
    <div class="row justify-content-center">
        <div class="col-md-10">
            @forelse ($services ?? [] as $service)
                <div class="card bg-white text-black mb-3">
                    <div class="card-body">
                        <h4 class="card-title text-black">{{ $service->name }}</h4>
                        <p class="card-text">{{ $service->description }}</p>

                        @if ($service->image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}" class="img-thumbnail" style="width: 100px; height: auto;">
                            </div>
                        @else
                            <p class="text-muted mt-2">No hay imagen disponible.</p>
                        @endif

                        <div class="mt-2">
                            <strong>Días de Trabajo:</strong>
                            <ul>
                                @foreach (json_decode($service->working_days) as $day)
                                    <li>{{ $day }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="mt-2">
                            <strong>Horario de Trabajo:</strong>
                            {{ $service->working_hours_start }} - {{ $service->working_hours_end }}
                        </div>

                        <div class="mt-2">
                            <strong>Intervalo de Reservas:</strong>
                            {{ $service->reservation_intervals }} hs
                        </div>

                        <div class="mt-2">
                            <strong>Precio:</strong>
                            ${{ number_format($service->price, 2) }}
                        </div>

                        <div class="text-right mt-3">
                            <form action="{{ route('services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este servicio?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-warning text-center">
                    No tienes servicios creados.
                </div>
            @endforelse
        </div>
    
<style>

body {
            background-color: rgb(2, 65, 82); 
        }

        .content-wrapper{
            background-color: rgb(2, 65, 82); 
        }

.card-header {
    margin-top: 0px;
    display: flex;
    background-color: rgb(2, 65, 82); 
    justify-content: center; /* Centra horizontalmente */
    align-items: center; /* Centra verticalmente */
    height: 100px; /* Ajusta la altura según sea necesario */
    border-bottom: none;
}

.recuadro-fondo {
    background-color: white; 
    padding: 10px 20px; /* Espaciado alrededor del texto */
    border-radius: 30px; /* Bordes redondeados */
    display: inline-block; /* Asegura que el fondo solo cubra el texto */
    text-align: center; /* Centra el texto dentro del recuadro */
    font-family: sans-serif;
}

    /* Contenedor principal para la página */
    .dashboard-container {
        max-width: 900px;
        margin: auto;
        background-color: #ffffff;
        color: #333333;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    /* Títulos de la página */
    .dashboard-container h1,
    .dashboard-container h4,
    .dashboard-container p {
        color: #333333;
    }

    /* Ajuste de los títulos */
    .dashboard-container h1 {
        font-size: 32px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    /* Estilos del formulario */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: bold;
        color: #333333;
    }

    /* Estilo de los inputs */
    .form-control, .form-control-file {
        background-color: white;
        border: 1px solid #ced4da;
        color: #333333; 
    }

    /* No cambiar el color del texto cuando se escribe */
    .form-control:focus {
        border-color: #007bff;
        background-color: white;
        
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    /* Estilo de las tarjetas de servicios */
    .service-card {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .service-card strong {
        font-size: 20px;
        color: #333333 !important;
        display: block;
        margin-bottom: 10px;
    }

    /* Estilo para las imágenes dentro de las tarjetas */
    .service-card img {
        width: 120px;
        height: auto;
        border-radius: 5px;
        margin-top: 10px;
    }

    /* Estilo de los detalles dentro de las tarjetas */
    .service-card .service-details {
        margin-top: 15px;
        color: #666666;
    }

    .service-card .service-details strong {
        color: #333333;
    }

    .service-card .service-details ul {
        list-style-type: disc;
        padding-left: 20px;
    }

    .service-card .service-details p {
        margin: 5px 0;
    }

    /* Botón de eliminar */
    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
        transition: background-color 0.3s ease;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    footer {
    position: relative;
    width: 100%; /* Asegura que ocupe el 100% del ancho */
    background-color: #333; /* Color de fondo opcional */
    color: white; /* Color de texto opcional */
    padding: 20px 0; /* Ajusta el espacio vertical */
    bottom: 0;
}
</style>

@endsection
