@extends('layouts.app')

@section('content')
<div class="card-header text-center ">
    <h1 class="text-black font-weight-bold">
        <span class="recuadro-fondo">Bienvenido a nuestra plataforma</span>
    </h1>
</div>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-transparent dark:bg-gray-800 overflow-hidden  text-center">
            <div class="p-6 bg-transparent dark:bg-gray-800 border-b border-gray-200">
                <div >
                    <h5>Por favor, inicia sesión para continuar.</h5>
                </div>
            </div>
        </div>
    </div>
</div>

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
</style>

@endsection
