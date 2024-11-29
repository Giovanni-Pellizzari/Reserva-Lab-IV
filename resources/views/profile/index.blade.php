{{-- resources/views/profile/index.blade.php --}}

@extends('layouts.app')

@section('content')           
<div class="card-header text-center ">
    <h1 class="text-black font-weight-bold">
        <span class="recuadro-fondo">Perfil de Usuario</span>
    </h1>
</div>

<div class="container py-5">
@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-dark text-white shadow-lg rounded">

                <div class="card-body bg-white">
                    <h5 class="text-center mb-4 text-black">Información Personal</h5>

                    <ul class="list-group list-group-flush bg-white">
                        <li class="list-group-item bg-white text-black"><strong>Nombre:</strong> {{ Auth::user()->name }}</li>
                        <li class="list-group-item bg-white text-black"><strong>Email:</strong> {{ Auth::user()->email }}</li>
                        {{-- Agrega más campos aquí si necesitas mostrar información adicional del usuario --}}
                    </ul>

                    <!-- Contenedor para los botones -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                            Modificar
                        </a>

                        {{-- Botón para eliminar el perfil --}}
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
    /* Fondo general */
    body {
            background-color: rgb(2, 65, 82); 
        }

        .content-wrapper{
            background-color: rgb(2, 65, 82); 
        }

    .card-header {
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

    /* Botón Modificar */
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        transition: background-color 0.1s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    /* Botón Eliminar */
    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
        transition: background-color 0.1s ease;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    /* Ajustar márgenes en el contenedor */
    .container {
        margin-top: 10px;
    }

    /* Estilo para la alerta de éxito */
    .alert {
        background-color: #28a745;
        color: #ffffff;
        font-weight: bold;
        padding: 10px;
    }
</style>
@endsection
