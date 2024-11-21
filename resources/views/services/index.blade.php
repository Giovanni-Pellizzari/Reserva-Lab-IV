{{-- resources/views/services/index.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Servicios</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <h1>Lista de Servicios</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($services->isEmpty())
            <p>No hay servicios disponibles.</p>
        @else
            <ul>
                @foreach($services as $service)
                    <li>
                        {{ $service->name }} - {{ $service->description }}
                        <a href="{{ route('services.show', $service->id) }}">Ver</a> |
                        <a href="{{ route('services.edit', $service->id) }}">Editar</a> |
                        <form action="{{ route('services.destroy', $service->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('¿Estás seguro de eliminar este servicio?')" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif

        <a href="{{ route('services.create') }}" class="btn btn-primary">Crear Servicio</a>
    </div>
</body>
</html>
