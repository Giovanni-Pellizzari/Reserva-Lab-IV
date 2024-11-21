{{-- resources/views/services/index.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Servicios</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Incluye tus estilos CSS si es necesario -->
</head>
<body>
    <h1>Lista de Servicios</h1>
    <ul>
        @foreach($services as $service)
            <li>{{ $service->name }} - {{ $service->description }}</li>
        @endforeach
    </ul>
</body>
</html>
