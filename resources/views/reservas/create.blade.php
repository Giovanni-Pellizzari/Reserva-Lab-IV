@extends('layouts.app') 

@section('content')
<div class="container">
    <h1>Crear Nueva Reserva</h1>

    <!-- Mostrar mensajes de éxito y error -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

    <form action="{{ route('reservas.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="service_id">Servicio:</label>
            <select class="form-control" name="service_id" required>
                <option value="">Seleccione un servicio</option>
                @foreach ($servicios as $servicio)
                    <option value="{{ $servicio->id }}">{{ $servicio->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label for="reservation_date">Fecha de Reserva:</label>
            <input type="date" class="form-control" name="reservation_date" required>
        </div>

        <div class="form-group">
            <label for="reservation_time">Hora de Reserva:</label>
            <input type="time" class="form-control" name="reservation_time" required>
        </div>

        <button type="submit" class="btn btn-primary">Crear Reserva</button>
    </form>
</div>
@endsection
