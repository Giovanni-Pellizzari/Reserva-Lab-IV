<?php

namespace App\Console\Commands;
use App\Models\Availability;  // Agregar esta línea
use Carbon\Carbon;
use App\Models\Service;
use Illuminate\Console\Command;

class GenerateAvailability extends Command
{
    // El nombre y la firma del comando
    protected $signature = 'availability:generate';

    // Descripción del comando
    protected $description = 'Generar turnos para todos los servicios existentes';

    // Método para manejar la lógica del comando
    public function handle()
{
    $services = Service::all();

    foreach ($services as $service) {
        $this->info("Generando turnos para el servicio: {$service->name}");

        // Calcular los turnos disponibles
        $startTime = Carbon::createFromFormat('H:i:s', $service->working_hours_start);
        $endTime = Carbon::createFromFormat('H:i:s', $service->working_hours_end);
        $interval = Carbon::createFromFormat('H:i:s', $service->reservation_intervals);

        // Iterar sobre el rango de horas y generar las disponibilidades
        while ($startTime->lt($endTime)) {
            $availabilityDate = Carbon::now()->toDateString(); // Usar la fecha actual

            // Calcular el 'end_time' sumando el intervalo al 'start_time'
            $endTimeSlot = $startTime->copy()->addHours($interval->hour)->addMinutes($interval->minute);

            // Insertar la disponibilidad
            Availability::create([
                'service_id' => $service->id,
                'availability_date' => $availabilityDate,
                'start_time' => $startTime->format('H:i:s'),
                'end_time' => $endTimeSlot->format('H:i:s'), // Asignar el 'end_time'
                'is_available' => true,  // Asumir que el turno está disponible
            ]);

            // Actualizar el 'start_time' para el siguiente turno
            $startTime = $endTimeSlot;
        }
    }

    $this->info('Todos los turnos han sido generados.');
}
}