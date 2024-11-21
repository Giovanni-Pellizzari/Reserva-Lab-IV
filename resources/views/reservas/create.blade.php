@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear Nueva Reserva</h1>
    <form action="{{ route('reservas.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="servicio">Servicio:</label>
            <input type="text" class="form-control" name="servicio" required>
        </div>
        <div class="form-group">
            <label for="fecha_reserva">Fecha de Reserva:</label>
            <input type="datetime-local" class="form-control" name="fecha_reserva" required>
        </div>
        <button type="submit" class="btn btn-primary">Crear Reserva</button>
    </form>
</div>
@endsection
