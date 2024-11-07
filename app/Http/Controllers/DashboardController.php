<?php

namespace App\Http\Controllers;

use App\Models\Service; // Asegúrate de que esta línea esté presente
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Recuperar los servicios del usuario autenticado
        $services = Service::where('user_id', Auth::id())->get();
        
        // Pasar la lista de servicios a la vista
        return view('pages.dashboard', compact('services'));
    }
}

