<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tareas</title>
</head>
<body>
    <h1>Lista de Tareas</h1>
    <a href="{{ route('tasks.create') }}">Agregar Nueva Tarea</a>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <ul>
        @foreach ($tasks as $task)
            <li>
                <h3>{{ $task->title }}</h3>
                <p>{{ $task->description }}</p>
            </li>
        @endforeach
    </ul>
</body>
</html>
