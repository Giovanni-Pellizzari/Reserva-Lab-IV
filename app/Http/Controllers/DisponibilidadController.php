<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Service;

class DisponibilidadController extends Controller
{
    public function index()
    {
        // Obtener los servicios del usuario autenticado
        $userServices = Service::where('user_id', auth()->user()->id)->pluck('id');

        // Obtener las reservas asociadas a esos servicios
        $reservas = Reserva::whereIn('service_id', $userServices)
            ->with(['user', 'service']) // Relación para mostrar datos adicionales
            ->get();

        return view('disponibilidad.index', compact('reservas'));
    }
}
