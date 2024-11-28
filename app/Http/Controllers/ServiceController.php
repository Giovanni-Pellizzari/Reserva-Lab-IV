<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Availability;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ServiceController extends Controller
{
    // Método para almacenar un servicio
    public function store(Request $request)
    {
        \Log::info($request->all()); // Log de los datos entrantes
        
        // Validación de los datos de entrada
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'working_days' => 'required|array',
            'working_days.*' => 'string|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo',
            'working_hours_start' => 'required|date_format:H:i',
            'working_hours_end' => 'required|date_format:H:i',
            'reservation_intervals' => 'required|date_format:H:i',
            'price' => 'required|numeric|min:0',
        ]);
    
        // Subir la imagen del servicio
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('services', 'public');
            if (!$imagePath) {
                return redirect()->back()->with('error', 'Error al cargar la imagen.');
            }
        } else {
            return redirect()->back()->with('error', 'La imagen es obligatoria.');
        }
    
        // Verifica si el usuario está autenticado
        $userId = auth()->id();
        if (!$userId) {
            return redirect()->back()->with('error', 'No estás autenticado correctamente.');
        }

        // Crea el servicio
        $service = Service::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'image' => $imagePath,
            'user_id' => $userId,
            'working_days' => json_encode($validatedData['working_days']),
            'working_hours_start' => $validatedData['working_hours_start'],
            'working_hours_end' => $validatedData['working_hours_end'],
            'reservation_intervals' => $validatedData['reservation_intervals'],
            'price' => $validatedData['price'],
        ]);
    
        // Crear los intervalos de disponibilidad para el servicio
        $this->createAvailabilitySlots($service, $validatedData);
    
        return redirect()->route('services.index')->with('success', 'Servicio creado con éxito.');
    }
    
    // Función para crear los intervalos de disponibilidad
   public function createAvailabilitySlots($service, $validatedData)
{
    $workingDays = $validatedData['working_days'];
    $intervalInMinutes = Carbon::parse($service->reservation_intervals)->hour * 60 
                        + Carbon::parse($service->reservation_intervals)->minute;

    // Mapeo de días en español a inglés
    $dayMap = [
        'Lunes' => 'Monday',
        'Martes' => 'Tuesday',
        'Miércoles' => 'Wednesday',
        'Jueves' => 'Thursday',
        'Viernes' => 'Friday',
        'Sábado' => 'Saturday',
        'Domingo' => 'Sunday'
    ];

    $availabilityCount = 0; // Contador de disponibilidades creadas

    foreach ($workingDays as $day) {
        $englishDay = $dayMap[$day];  // Convertir el día al formato en inglés
        $startTime = Carbon::parse($service->working_hours_start);
        $endTime = Carbon::parse($service->working_hours_end);

        while ($startTime->lt($endTime)) {
            // Crear una disponibilidad para cada intervalo
            $availability = new Availability();
            $availability->service_id = $service->id;
            $availability->availability_date = Carbon::now()->next($englishDay)->format('Y-m-d');
            $availability->start_time = $startTime->format('H:i');
            $availability->end_time = $startTime->copy()->addMinutes($intervalInMinutes)->format('H:i');

            $availability->save();

            // Avanzar al siguiente intervalo
            $startTime->addMinutes($intervalInMinutes);
            $availabilityCount++; // Incrementar el contador
        }
    }

    return "Se crearon $availabilityCount intervalos de disponibilidad.";
}



    // Mapear los días de la semana al formato de Carbon
    private function mapWeekday($day)
    {
        $daysOfWeek = [
            'Lunes' => Carbon::MONDAY,
            'Martes' => Carbon::TUESDAY,
            'Miércoles' => Carbon::WEDNESDAY,
            'Jueves' => Carbon::THURSDAY,
            'Viernes' => Carbon::FRIDAY,
            'Sábado' => Carbon::SATURDAY,
            'Domingo' => Carbon::SUNDAY,
        ];
        return $daysOfWeek[$day] ?? Carbon::MONDAY;
    }

    // Listar los servicios del usuario autenticado
    public function index()
    {
        $services = Service::where('user_id', auth()->id())->get();
        return view('pages.dashboard', compact('services'));
    }

    // Eliminar un servicio
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect()->route('services.index')->with('success', 'Servicio eliminado exitosamente.');
    }

    // Mostrar un servicio específico y sus intervalos
    public function show($serviceId)
    {
        $service = Service::findOrFail($serviceId);
        $interval = Carbon::parse($service->reservation_intervals);
    
        $start = Carbon::createFromFormat('H:i', $service->working_hours_start);
        $end = Carbon::createFromFormat('H:i', $service->working_hours_end);
    
        $totalMinutes = $start->diffInMinutes($end);
        $intervalMinutes = $interval->hour * 60 + $interval->minute;
        $numberOfSlots = floor($totalMinutes / $intervalMinutes);
    
        $availableSlots = [];
        for ($i = 0; $i < $numberOfSlots; $i++) {
            $slotTime = clone $start;
            $slotTime->addMinutes($i * $intervalMinutes);
            if ($slotTime->lt($end)) {
                $availableSlots[] = $slotTime->format('H:i');
            }
        }
    
        return view('services.show', compact('service', 'availableSlots'));
    }
}
