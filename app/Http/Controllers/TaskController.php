<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Mostrar todas las tareas
    public function index()
    {
        $tasks = Task::all(); // Obtener todas las tareas
        return view('tasks.index', compact('tasks')); // Pasar las tareas a la vista
    }

    // Mostrar el formulario para crear una nueva tarea
    public function create()
    {
        return view('tasks.create');
    }

    // Almacenar una nueva tarea
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
        ]);

        Task::create($request->all()); // Crear y guardar la tarea
        return redirect()->route('tasks.index')->with('success', 'Tarea creada correctamente.');
    }
}
