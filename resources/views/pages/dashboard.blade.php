@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Bienvenido al Dashboard</h1>
    <p class="text-center">Administra tus reservas aquí.</p>

    <!-- Formulario para crear un nuevo servicio -->
    <div class="mb-4">
        <h4>Crear Nuevo Servicio</h4>
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
            <div class="form-group">
                <label for="working_days">Días de Trabajo</label>
                <select multiple class="form-control" id="working_days" name="working_days[]" required>
                    <option value="Lunes">Lunes</option>
                    <option value="Martes">Martes</option>
                    <option value="Miércoles">Miércoles</option>
                    <option value="Jueves">Jueves</option>
                    <option value="Viernes">Viernes</option>
                    <option value="Sábado">Sábado</option>
                    <option value="Domingo">Domingo</option>
                </select>
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
            <button type="submit" class="btn btn-primary">Crear Servicio</button>
        </form>
    </div>

    <!-- Listado de Servicios -->
    @forelse ($services ?? [] as $service)
        <li class="list-group-item bg-dark text-white mb-3">
            <div class="border p-3">
                <strong>{{ $service->name }}</strong>
                <div class="mt-2">{{ $service->description }}</div>

                @if ($service->image)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}" style="width: 100px; height: auto;">
                    </div>
                @else
                    <div class="mt-2">No hay imagen disponible.</div>
                @endif

                <div class="mt-2">
                    <strong>Días de Trabajo:</strong>
                    @if ($service->working_days)
                        <ul>
                            @foreach (json_decode($service->working_days) as $day)
                                <li>{{ $day }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p>No se han especificado días de trabajo.</p>
                    @endif
                </div>

                <div class="mt-2">
                    <strong>Horario de Trabajo:</strong>
                    @if ($service->working_hours_start && $service->working_hours_end)
                        {{ $service->working_hours_start }} - {{ $service->working_hours_end }}
                    @else
                        <p>No se ha especificado horario de trabajo.</p>
                    @endif
                </div>

                <div class="mt-2">
                    <strong>Intervalo de Reservas:</strong>
                    @if ($service->reservation_intervals)
                        {{ $service->reservation_intervals }} hs
                    @else
                        <p>No se ha especificado intervalo de reservas.</p>
                    @endif
                </div>

                <div class="mt-2">
                    <strong>Precio:</strong>
                    @if ($service->price)
                        ${{ number_format($service->price, 2) }}
                    @else
                        <p>No se ha especificado precio.</p>
                    @endif
                </div>

                <div class="text-right mt-3">
                    <form action="{{ route('services.destroy', $service->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este servicio?');">Eliminar</button>
                    </form>
                </div>
            </div>
        </li>
    @empty
        <li class="list-group-item bg-dark text-white">No tienes servicios creados.</li>
    @endforelse
</div>

<style>
    body {
        background-color: rgb(72, 61, 139); /* Color de fondo suave */
    }

    .container {
        margin-top: 50px; /* Espaciado superior */
        padding: 20px;
        background-color: #0d0d0d; /* Fondo oscuro para el contenedor */
        border: 2px solid rgb(72, 61, 139); /* Borde fino del color del fondo de la página */
        border-radius: 0px; /* Esquinas redondeadas */
    }

    h1 {
        color: white; /* Color del título principal */
        margin-top: 20px; /* Margen superior adicional */
    }

    h4 {
        margin-top: 40px; /* Espaciado superior para subtítulos */
        color: white; /* Color para subtítulos */
    }

    .list-group-item {
        background-color: #343a40; /* Fondo oscuro para los elementos de la lista */
        color: #fff; /* Color del texto blanco */
    }

    .border {
        border: 1px solid #fff; /* Borde blanco alrededor de cada servicio */
        border-radius: 5px; /* Esquinas redondeadas */
    }

    .mb-3 {
        margin-bottom: 1rem; /* Margen inferior para separar los servicios */
    }

    .btn-primary {
        margin-top: 10px; /* Margen superior para el botón */
    }
</style>
@endsection
