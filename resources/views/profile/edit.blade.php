{{-- resources/views/profile/index.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-dark text-white text-center">
                    <h2 class="font-weight-bold">Editar Perfil</h2>
                </div>

                <div class="card-body bg-dark">
                    @if (session('status') === 'profile-updated')
                        <div class="alert alert-success">
                            Tu perfil ha sido actualizado.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                    <label for="name" class="form-label text-white">Nombre</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="form-control">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label text-white">Correo Electrónico</label>
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
