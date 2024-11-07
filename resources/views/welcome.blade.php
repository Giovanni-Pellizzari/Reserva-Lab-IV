@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-transparent dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg text-center">
            <div class="p-6 bg-transparent dark:bg-gray-800 border-b border-gray-200">
                <div class="flex justify-center">
                    <x-application-logo /> <!-- El logo se ajustará automáticamente según el componente -->
                </div>
                <h1 class="text-xl font-semibold mt-4">Bienvenido a nuestra plataforma</h1>
                <p>Por favor, inicia sesión para continuar.</p>
            </div>
        </div>
    </div>
</div>

@endsection
