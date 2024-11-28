{{-- resources/views/profile/index.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container py-5">
@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-greytext-white">
                <div class="card-header text-center bg-dark">
                    <h2 class="text-white font-weight-bold">Perfil de Usuario</h2>
                </div>

                <div class="card-body bg-dark">
                    <h4 class="text-center mb-4 text-white">Información Personal</h4>

                    <ul class="list-group list-group-flush bg-dark">
                        <li class="list-group-item bg-dark text-white"><strong>Nombre:</strong> {{ Auth::user()->name }}</li>
                        <li class="list-group-item bg-dark text-white"><strong>Email:</strong> {{ Auth::user()->email }}</li>
                        {{-- Agrega más campos aquí si necesitas mostrar información adicional del usuario --}}
                    </ul>

                    <div class="text-center mt-4">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                            Modificar
                        </a>
                    </div>

                    {{-- Botón para eliminar el perfil --}}
                    <div class="text-center mt-3">
                        <form action="{{ route('profile.destroy') }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar tu perfil? Esta acción es irreversible.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar Perfil</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background-color: rgb(72, 61, 139); /* Cambia el color de fondo para pruebas */
}

    .card {
    border-radius: 0px;
    background: rgb(72, 61, 139);
}

/* Títulos en el encabezado de la tarjeta */
.card-header h2 {
    font-size: 40px ;
    font-weight: bold;
}

/* Ajustar el espacio entre los elementos de la lista */
.list-group-item {
    padding: 15px;
}

/* Botón Modificar */
.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

/* Botón Eliminar */
.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

/* Mejorar la apariencia de los encabezados */
h4.text-center {
    font-size: 20px;
    font-weight: bold;
}

/* Ajustar márgenes en el contenedor */
.container {
    margin-top: 20px;
}
</style>

@endsection
