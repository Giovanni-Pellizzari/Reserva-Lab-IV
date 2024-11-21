{{-- resources/views/services/create.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Crear Servicio</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">Nombre del Servicio</label>
                <input type="text" name="name" id="name" required class="form-control @error('name') is-invalid @enderror">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="description">Descripción</label>
                <textarea name="description" id="description" required class="form-control @error('description') is-invalid @enderror"></textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="image">Imagen</label>
                <input type="file" name="image" id="image" accept="image/*" required class="form-control @error('image') is-invalid @enderror">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="working_days">Días de Trabajo (opcional)</label>
                <input type="text" name="working_days[]" id="working_days" class="form-control">
            </div>

            <div class="form-group">
                <label for="working_hours_start">Hora de Inicio</label>
                <input type="time" name="working_hours_start" id="working_hours_start" required class="form-control @error('working_hours_start') is-invalid @enderror">
                @error('working_hours_start')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="working_hours_end">Hora de Fin</label>
                <input type="time" name="working_hours_end" id="working_hours_end" required class="form-control @error('working_hours_end') is-invalid @enderror">
                @error('working_hours_end')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="reservation_intervals">Intervalos de Reserva</label>
                <input type="time" name="reservation_intervals" id="reservation_intervals" required class="form-control @error('reservation_intervals') is-invalid @enderror">
                @error('reservation_intervals')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="price">Precio</label>
                <input type="number" name="price" id="price" required class="form-control @error('price') is-invalid @enderror">
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Crear Servicio</button>
        </form>
    </div>
@endsection
