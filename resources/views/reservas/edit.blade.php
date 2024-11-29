@extends('layouts.app')

@section('content')
<div class="card-header text-center ">
    <h1 class="text-black font-weight-bold">
        <span class="recuadro-fondo">Editar Reserva</span>
    </h1>
</div>
<div class="container bg-white text-black p-5">
    <form action="{{ route('reservas.update', $reserva->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="reservation_date">Fecha de Reserva</label>
            <input type="date" class="form-control bg-white text-black" name="reservation_date" id="reservation_date" value="{{ $reserva->reservation_date }}" required>
        </div>

        <div class="form-group">
            <label for="reservation_time">Hora de Reserva (Inicio)</label>
            <select class="form-control bg-white text-black" name="reservation_time" id="reservation_time" required>
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

.form-group{

    background-color: white;

}


</style>

@endsection
