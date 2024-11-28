@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear Nueva Reserva</h1>

    <!-- Mostrar errores si los hay -->
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('reservas.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="service_id">Servicio:</label>
            <select class="form-control" name="service_id" id="service_id" required>
                <option value="">Seleccione un servicio</option>
                @foreach($servicios as $service)
                    <option value="{{ $service->id }}">{{ $service->name }} - ${{ number_format($service->price, 2) }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="reservation_date">Fecha de Reserva:</label>
            <input type="date" class="form-control" name="reservation_date" id="reservation_date" required>
        </div>

        <div class="form-group">
            <label for="reservation_time">Hora de Reserva:</label>
            <select class="form-control" name="reservation_time" id="reservation_time" required>
                <option value="">Seleccione una hora</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Crear Reserva</button>
    </form>
</div>

<script>
    document.getElementById('service_id').addEventListener('change', function () {
        updateAvailableSlots();
    });

    document.getElementById('reservation_date').addEventListener('change', function () {
        updateAvailableSlots();
    });

    function updateAvailableSlots() {
        var serviceId = document.getElementById('service_id').value;
        var reservationDate = document.getElementById('reservation_date').value;

        if (serviceId && reservationDate) {
            fetch("{{ route('reservas.getAvailableSlots') }}?service_id=" + serviceId + "&reservation_date=" + reservationDate)

    .then(response => response.json())
    .then(data => {
        var slotSelect = document.getElementById('reservation_time');
        slotSelect.innerHTML = '<option value="">Seleccione una hora</option>'; // Limpiar opciones previas

        // Si la respuesta contiene horarios, añadirlos a la lista
        if (data.length > 0) {
            data.forEach(slot => {
                var option = document.createElement('option');
                option.value = slot;
                option.textContent = slot;
                slotSelect.appendChild(option);
            });
        } else {
            var option = document.createElement('option');
            option.value = "";
            option.textContent = "No hay horarios disponibles";
            slotSelect.appendChild(option);
        }
    })
    .catch(error => {
        console.error('Error al cargar los horarios disponibles:', error);
    });

        }
    }
</script>
@endsection
