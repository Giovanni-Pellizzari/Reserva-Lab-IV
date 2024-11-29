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
        // Obtener las reservas del usuario autenticado
        $reservas = Reserva::where('user_id', auth()->id())->get();

        // Formatear la hora de cada reserva antes de enviarla a la vista
        foreach ($reservas as $reserva) {
            $reserva->start_time = Carbon::parse($reserva->start_time)->format('H:i') . 'hs';
            $reserva->end_time = Carbon::parse($reserva->end_time)->format('H:i') . 'hs';
        }

        // Pasar las reservas formateadas a la vista
        return view('reservas.index', compact('reservas'));
    }

    // Mostrar formulario de creación de una nueva reserva
    public function create()
    {
        $servicios = Service::all();
        $availableSlots = [];

        // Obtener los horarios disponibles para cada servicio
        foreach ($servicios as $service) {
            $availableSlots[$service->id] = $this->getServiceSlotsForToday($service);
        }

        return view('reservas.create', compact('servicios', 'availableSlots'));
    }

    public function store(Request $request)
    {
        // Validar los datos antes de guardar
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required|date_format:H:i',  // Cambié de start_time a reservation_time
        ]);

        // Convertir reservation_time (reservation_time) en un objeto Carbon para manipularlo
        $startTime = Carbon::createFromFormat('H:i', $validated['reservation_time']);
        $endTime = $startTime->copy()->addHour(); // Añadir una hora de duración

        // Depurar: Verificar que las fechas y horas están correctamente formateadas
        \Log::info("Start Time: " . $startTime->toDateTimeString());
        \Log::info("End Time: " . $endTime->toDateTimeString());

        // Verificar disponibilidad del turno seleccionado
        $availability = Availability::where('service_id', $validated['service_id'])
            ->where('availability_date', $validated['reservation_date']) // Usamos availability_date
            ->where('start_time', $validated['reservation_time']) // Usamos reservation_time
            ->where('is_available', true)
            ->first();

        if (!$availability) {
            return back()->with('error', 'El turno no está disponible.');
        }

        // Marcar el turno como no disponible
        $availability->is_available = false;
        $availability->save();

        // Depurar: Verificar que el cambio en Availability fue exitoso
        \Log::info("Availability updated: " . $availability->toJson());

        // Crear la reserva con el campo end_time
        $reserva = Reserva::create([
            'user_id' => auth()->id(),
            'service_id' => $validated['service_id'],
            'reservation_date' => $validated['reservation_date'], // Usamos reservation_date
            'start_time' => $validated['reservation_time'], // Usamos reservation_time
            'end_time' => $endTime->format('H:i'), // Guardar la hora de finalización
            'status' => 'confirmed',
            'is_notified' => false,
        ]);

        // Depurar: Verificar que la reserva se ha creado
        \Log::info("Reserva creada: " . $reserva->toJson());

        // Notificar al usuario
        $reserva->sendNotification();

        return redirect()->route('reservas.index')->with('success', 'Reserva creada con éxito');
    }

    public function edit($id)
{
    // Encuentra la reserva por el ID
    $reserva = Reserva::findOrFail($id);

    // Obtener los servicios que el usuario ha reservado previamente
    $servicios = Service::whereIn('id', function ($query) use ($reserva) {
        $query->select('service_id')
            ->from('reservas')
            ->where('user_id', auth()->id())
            ->where('status', 'confirmed'); // Solo servicios confirmados
    })->get();

    // Obtener los horarios disponibles para el servicio seleccionado en la reserva
    $availableSlots = $this->getAvailableSlotsForServiceAndDate($reserva->service_id, $reserva->reservation_date);

    // Pasa tanto la reserva, los servicios y los horarios disponibles a la vista
    return view('reservas.edit', compact('reserva', 'servicios', 'availableSlots'));
}

// Obtener los turnos disponibles para un servicio y una fecha
private function getAvailableSlotsForServiceAndDate($serviceId, $reservationDate)
{
    // Obtener los horarios disponibles para ese servicio y fecha
    $availabilities = Availability::where('service_id', $serviceId)
        ->where('availability_date', $reservationDate)
        ->where('is_available', true)
        ->get();

    // Obtener los horarios ocupados por reservas
    $occupiedSlots = Reserva::where('service_id', $serviceId)
        ->where('reservation_date', $reservationDate)
        ->pluck('start_time')
        ->toArray();

    // Filtrar horarios disponibles
    $availableSlots = $availabilities->filter(function ($availability) use ($occupiedSlots) {
        return !in_array($availability->start_time, $occupiedSlots);
    })->map(function ($availability) {
        return $availability->start_time;
    });

    return $availableSlots;
}


public function update(Request $request, $id)
{
    // Encuentra la reserva por el ID
    $reserva = Reserva::findOrFail($id);

    // Validar los datos antes de actualizar
    $validated = $request->validate([
        'reservation_date' => 'required|date',
        'reservation_time' => 'required|date_format:H:i',
    ]);

    // Verificar disponibilidad del nuevo horario
    $startTime = Carbon::createFromFormat('H:i', $validated['reservation_time']);
    $availability = Availability::where('service_id', $reserva->service_id)
        ->where('availability_date', $validated['reservation_date'])
        ->where('start_time', $validated['reservation_time'])
        ->where('is_available', true)
        ->first();

    if (!$availability) {
        return back()->with('error', 'El turno no está disponible.');
    }

    // Liberar la disponibilidad del horario anterior si es necesario
    if ($reserva->status == 'confirmed') {
        $oldAvailability = Availability::where('service_id', $reserva->service_id)
            ->where('availability_date', $reserva->reservation_date)
            ->where('start_time', $reserva->start_time)
            ->first();

        if ($oldAvailability) {
            $oldAvailability->is_available = true;  // Marcar como disponible
            $oldAvailability->save();
        }
    }

    // Actualizar la reserva con los nuevos datos
    $endTime = $startTime->copy()->addHour(); // Añadir una hora de duración
    $reserva->update([
        'reservation_date' => $validated['reservation_date'],
        'start_time' => $validated['reservation_time'],
        'end_time' => $endTime->format('H:i'),
        'status' => 'confirmed', // Estado actualizado a 'confirmed'
    ]);

    // Marcar el nuevo turno como ocupado
    $availability->is_available = false;
    $availability->save();

    return redirect()->route('reservas.index')->with('success', 'Reserva actualizada y confirmada con éxito');
}



    public function destroy($id)
    {
        $reserva = Reserva::findOrFail($id);

        // Eliminar la reserva de la base de datos
        $reserva->delete();

        // Liberar la disponibilidad si es necesario
        $availability = Availability::where('service_id', $reserva->service_id)
            ->where('availability_date', $reserva->reservation_date)
            ->where('start_time', $reserva->start_time)
            ->first();

        if ($availability) {
            $availability->is_available = true;
            $availability->save();
        }

        return back()->with('success', 'Reserva eliminada con éxito.');
    }

    public function cancel($id)
    {
        // Encontrar la reserva
        $reserva = Reserva::findOrFail($id);
    
        // Encontrar la disponibilidad asociada a esta reserva
        $availability = $reserva->availability; // Suponiendo que la relación esté configurada correctamente
    
        // Verificar si existe la disponibilidad y actualizarla
        if ($availability) {
            $availability->is_available = true; // O 'status' según lo que uses
            $availability->save();
        }
    
        // Cancelar la reserva (marcarla como cancelada)
        $reserva->status = 'cancelled';
        $reserva->save();
    
        return redirect()->route('reservas.index')->with('success', 'Reserva cancelada y turno disponible nuevamente');
    }
    



    // Obtener los turnos disponibles por servicio y fecha
    public function getAvailableSlots(Request $request)
    {
        $serviceId = $request->get('service_id');
        $reservationDate = $request->get('reservation_date');

        // Verifica que el servicio exista
        $service = Service::find($serviceId);
        if (!$service) {
            return response()->json(['error' => 'Servicio no encontrado'], 404);
        }

        // Obtener disponibilidades predefinidas
        $availabilities = Availability::where('service_id', $serviceId)
            ->where('availability_date', $reservationDate)
            ->where('is_available', true)
            ->get();

        // Obtener horarios ocupados
        $occupiedSlots = Reserva::where('service_id', $serviceId)
            ->where('reservation_date', $reservationDate)
            ->pluck('start_time')
            ->toArray();

        // Filtrar horarios disponibles
        $availableSlots = $availabilities->filter(function ($availability) use ($occupiedSlots) {
            return !in_array($availability->start_time, $occupiedSlots);
        })->map(function ($availability) {
            return $availability->start_time;
        });

        return response()->json($availableSlots);
    }

    // Calcular los turnos disponibles para hoy
    private function getServiceSlotsForToday($service, $date = null)
    {
        $date = $date ?: now()->toDateString(); // Usa la fecha proporcionada o la fecha actual

        return Availability::where('service_id', $service->id)
            ->where('availability_date', $date)
            ->where('is_available', true)
            ->pluck('start_time');
    }
}