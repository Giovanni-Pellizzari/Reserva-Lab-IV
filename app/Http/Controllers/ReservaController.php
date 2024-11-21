<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Service; 
use App\Models\Availability;
use Carbon\Carbon; 
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    public function index()
    {
        // Obtener reservas del usuario autenticado
        $reservas = Reserva::where('user_id', auth()->id())->get();
        return view('reservas.index', compact('reservas'));
    }

    public function create()
    {
        // Obtener todos los servicios para el formulario de creación
        $servicios = Service::all(); 
        $availableSlots = []; // Inicializa el array de slots disponibles
    
        foreach ($servicios as $service) {
            $startTime = Carbon::createFromFormat('H:i:s', $service->working_hours_start);
            $endTime = Carbon::createFromFormat('H:i:s', $service->working_hours_end);
            $interval = Carbon::createFromFormat('H:i:s', $service->reservation_intervals);
    
            // Calcular los slots disponibles según el horario de trabajo y el intervalo de reserva
            for ($time = $startTime; $time->lt($endTime); $time->addHours($interval->hour)->addMinutes($interval->minute)) {
                // Verificar si el turno está disponible en la tabla Availability
                $isAvailable = Availability::where('service_id', $service->id)
                    ->where('availability_date', now()->toDateString())
                    ->where('start_time', $time->format('H:i:s'))
                    ->where('is_available', true)
                    ->exists();
    
                if ($isAvailable) {
                    $availableSlots[$service->id][] = $time->format('H:i');
                }
            }
        }
    
        return view('reservas.create', compact('servicios', 'availableSlots'));
    }

    public function store(Request $request)
    {
        // Verificar disponibilidad del turno seleccionado en la tabla Availability
        $availability = Availability::where('service_id', $request->service_id)
            ->where('availability_date', $request->reservation_date)
            ->where('start_time', $request->reservation_time) 
            ->where('is_available', true)
            ->first();

        if (!$availability) {
            return back()->with('error', 'El turno no está disponible.');
        }

        // Marcar el turno como no disponible
        $availability->is_available = false;
        $availability->save();

        // Crear la reserva
        Reserva::create([
            'user_id' => auth()->id(),
            'service_id' => $request->service_id,
            'reservation_date' => $request->reservation_date,
            'start_time' => $request->reservation_time,
            'status' => 'confirmed',
        ]);

        // Lógica de notificación aquí (si es necesario)

        return redirect()->route('dashboard')->with('success', 'Reserva creada con éxito');
    }

    public function cancel(Reserva $reserva)
    {
        // Cambiar el estado de la reserva a cancelado
        $reserva->status = 'cancelled';
        $reserva->save();

        // Liberar el turno en la tabla Availability
        $availability = Availability::where('service_id', $reserva->service_id)
            ->where('availability_date', $reserva->reservation_date)
            ->where('start_time', $reserva->start_time)
            ->first();

        if ($availability) {
            $availability->is_available = true;
            $availability->save();
        }

        return back()->with('success', 'Reserva cancelada y turno liberado.');
    }

    public function getAvailableSlots(Request $request)
    {
        \Log::info("Service ID: {$request->service_id}, Date: {$request->reservation_date}");
    
        $service = Service::findOrFail($request->service_id);
        $availableSlots = [];
    
        $startTime = Carbon::createFromFormat('H:i:s', $service->working_hours_start);
        $endTime = Carbon::createFromFormat('H:i:s', $service->working_hours_end);
        $interval = Carbon::createFromFormat('H:i:s', $service->reservation_intervals);
    
        for ($time = $startTime; $time->lt($endTime); $time->addHours($interval->hour)->addMinutes($interval->minute)) {
            $isAvailable = Availability::where('service_id', $service->id)
                ->where('availability_date', $request->reservation_date)
                ->where('start_time', $time->format('H:i:s'))
                ->where('is_available', true)
                ->exists();
    
            if ($isAvailable) {
                $availableSlots[] = $time->format('H:i');
            }
        }
    
        \Log::info("Available Slots: " . json_encode($availableSlots));
    
        return response()->json($availableSlots);
    }
}
