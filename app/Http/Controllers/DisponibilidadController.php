<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DisponibilidadController extends Controller
{
    public function index()
    {
        // Lógica para mostrar la disponibilidad
        return view('disponibilidad.index'); // Asegúrate de tener esta vista
    }

    // Puedes agregar más métodos según sea necesario
}
