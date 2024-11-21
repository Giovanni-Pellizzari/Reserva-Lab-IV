<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Availability;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ServiceController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image',
            'working_days' => 'array|nullable', // Deberías validar si el array no está vacío si es necesario
            'working_hours_start' => 'required|date_format:H:i',
            'working_hours_end' => 'required|date_format:H:i',
            'reservation_intervals' => 'required|date_format:H:i',
            'price' => 'required|numeric|min:0',
        ]);
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('services', 'public');
        } else {
            return redirect()->back()->with('error', 'La imagen es obligatoria.');
        }
    
        // Crear el servicio con los datos validados
        $service = Service::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'image' => $imagePath,
            'user_id' => auth()->id(),
            'working_days' => json_encode($validatedData['working_days']),
            'working_hours_start' => $validatedData['working_hours_start'],
            'working_hours_end' => $validatedData['working_hours_end'],
            'reservation_intervals' => $validatedData['reservation_intervals'],
            'price' => $validatedData['price'],
        ]);
    
        // Calcular y crear los intervalos de disponibilidad
        $this->createAvailabilitySlots($service, $validatedData);
    
        return redirect()->route('services.index')->with('success', 'Servicio creado con éxito.');
    }
    
    public function createAvailabilitySlots($service, $validatedData)
    {
        // Asegurarse de que las horas de trabajo son instancias de Carbon
        $start = Carbon::createFromFormat('H:i', $service->working_hours_start);
        $end = Carbon::createFromFormat('H:i', $service->working_hours_end);
    
        // Calcular el intervalo (en minutos) entre los slots de reserva
        $interval = Carbon::createFromFormat('H:i', $service->reservation_intervals)
                        ->diffInMinutes(Carbon::createFromFormat('H:i', '00:00'));
    
        // Calcular la cantidad de slots
        $numberOfSlots = $start->diffInMinutes($end) / $interval;
    
        // Iterar sobre los días de trabajo y crear los intervalos de disponibilidad
        foreach (json_decode($service->working_days) as $day) {
            for ($i = 0; $i <= $numberOfSlots; $i++) {
                $slotTime = clone $start;
                $slotTime->addMinutes($i * $interval);
                if ($slotTime->lt($end)) {
                    // Crear el registro de disponibilidad
                    Availability::create([
                        'service_id' => $service->id,
                        'availability_date' => Carbon::parse($day)->format('Y-m-d'),
                        'start_time' => $slotTime->format('H:i'),
                        'end_time' => $slotTime->addMinutes($interval)->format('H:i'),
                        'is_available' => true,
                    ]);
                }
            }
        }
    }

    public function index()
    {
        $services = Service::all(); // Obtener todos los servicios
        return view('pages.dashboard', compact('services')); // Pasar los servicios a la vista 'dashboard'
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect()->route('services.index')->with('success', 'Servicio eliminado exitosamente.');
    }

    public function show($serviceId)
    {
        $service = Service::findOrFail($serviceId);
        $interval = Carbon::parse($service->reservation_intervals);
    
        // Convierte las horas de inicio y fin a instancias de Carbon
        $start = Carbon::createFromFormat('H:i', $service->working_hours_start);
        $end = Carbon::createFromFormat('H:i', $service->working_hours_end);
    
        // Calcula el tiempo total en minutos
        $totalMinutes = $start->diffInMinutes($end);
    
        // Calcula cuántos slots de reserva se pueden crear
        $numberOfSlots = floor($totalMinutes / $interval->diffInMinutes(Carbon::createFromFormat('H:i', '00:00')));
    
        // Genera los horarios disponibles según el intervalo
        $availableSlots = [];
        for ($i = 0; $i < $numberOfSlots; $i++) {
            $slotTime = clone $start; // Clona el tiempo de inicio para cada slot
            $slotTime->addMinutes($i * $interval); // Agrega el intervalo de reserva
            if ($slotTime->lt($end)) {
                $availableSlots[] = $slotTime->format('H:i'); // Agrega la hora formateada al arreglo
            }
        }
    
        // Verificar el valor de $availableSlots
        dd($availableSlots); // Esto te ayudará a verificar si la variable contiene los valores esperados
    
        return view('services.show', compact('service', 'availableSlots')); // Asegúrate de que estás pasando la variable a la vista
    }
}
