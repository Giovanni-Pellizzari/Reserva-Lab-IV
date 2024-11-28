@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Reserva</h2>
    
    <form action="{{ route('reservas.update', $reserva->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group">
    <label for="service_id">Servicio</label>
    <select class="form-control" name="service_id" id="service_id" disabled>
        @foreach($servicios as $servicio)
            <option value="{{ $servicio->id }}" {{ $servicio->id == $reserva->service_id ? 'selected' : '' }}>
                {{ $servicio->name }}
            </option>
        @endforeach
    </select>
</div>


    <div class="form-group">
        <label for="reservation_date">Fecha de Reserva</label>
        <input type="date" class="form-control" name="reservation_date" id="reservation_date" value="{{ $reserva->reservation_date }}" required>
    </div>

    <div class="form-group">
    <label for="reservation_time">Hora de Reserva (Inicio)</label>
    <select class="form-control" name="reservation_time" id="reservation_time" required>
        @foreach($availableSlots as $slot)
            <option value="{{ $slot }}" {{ $slot == $reserva->start_time ? 'selected' : '' }}>
                {{ $slot }}
            </option>
        @endforeach
    </select>
</div>
<button type="submit" class="btn btn-primary">Actualizar Reserva</button>
</form>

</div>

<script>
    // Script para actualizar la hora de finalización basado en la hora de inicio
    document.getElementById('reservation_time').addEventListener('change', function() {
        const startTime = this.value;
        if (startTime) {
            const [hours, minutes] = startTime.split(':');
            const startDate = new Date();
            startDate.setHours(hours, minutes, 0);

            // Sumamos una hora al startTime
            startDate.setHours(startDate.getHours() + 1);

            const endTime = startDate.toTimeString().substr(0, 5); // Formato HH:mm
            document.getElementById('end_time').value = endTime;
        }
    });
</script>

@endsection
