{{-- resources/views/profile/index.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="card-header text-center ">
    <h1 class="text-black font-weight-bold">
        <span class="recuadro-fondo">Editar Perfil</span>
    </h1>
</div>
<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body bg-white">
                    @if (session('status') === 'profile-updated')
                        <div class="alert alert-success">
                            Tu perfil ha sido actualizado.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                    <label for="name" class="form-label text-black">Nombre</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="form-control">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label text-black">Correo Electrónico</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="form-control">
                </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('profile') }}" class="btn btn-secondary">Cerrar</a>
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                Cambiar Contraseña
                            </button>
                            <button type="submit" class="btn btn-primary">Actualizar Perfil</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para cambiar la contraseña -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">Cambiar Contraseña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
            <div class="mb-3">
<label for="password" class="form-label">Nueva Contraseña</label>
            <input type="password" name="password" id="password" required class="form-control">
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required class="form-control">
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
    </div>
</form>


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
    margin-top: 10px;
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
</style>
@endsection
