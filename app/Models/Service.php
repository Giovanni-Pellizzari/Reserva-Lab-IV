<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Availability;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class Service extends Model
{
    // Campos masivos que se pueden asignar
    protected $fillable = [
        'name',
        'description',
        'image',
        'user_id',  // Relación con el usuario
        'working_days',
        'working_hours_start',
        'working_hours_end',
        'reservation_intervals',
        'price',
    ];

    // Relación con el modelo User (Servicio pertenece a un Usuario)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con el modelo Availability (Un Servicio puede tener muchas Disponibilidades)
    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }

    public function reservas()
{
    return $this->hasMany(Reserva::class);
}

    // Lógica para generar disponibilidades después de que se crea un servicio
    protected static function booted()
    {
        static::created(function ($service) {
            // Verifica si este evento se está ejecutando
            \Log::info('Servicio creado, generando disponibilidades...');
            $service->generateAvailabilities();
        });
    }

    public function generateAvailabilities()
    {
        if (!$this->working_hours_start || !$this->working_hours_end || !$this->reservation_intervals) {
            \Log::error('Faltan horas de trabajo o intervalos de reserva para el servicio.');
            return;
        }

        try {
            // Asegurarse de que los horarios están en el formato correcto
            $startTime = Carbon::createFromFormat('H:i:s', $this->working_hours_start);
            $endTime = Carbon::createFromFormat('H:i:s', $this->working_hours_end);
            $interval = Carbon::createFromFormat('H:i:s', $this->reservation_intervals);
        } catch (\Carbon\Exceptions\InvalidFormatException $e) {
            \Log::error('Formato de hora inválido: ' . $e->getMessage());
            return;
        }

        // Log para verificar la fecha de inicio y los intervalos
        \Log::info("Generando disponibilidades desde: {$startTime->format('H:i:s')} hasta: {$endTime->format('H:i:s')} con un intervalo de: {$interval->format('H:i:s')}");

        $date = Carbon::now();
        $daysToGenerate = 30; // Generar disponibilidades para los próximos 30 días

        // Mapeo de días en español a los valores correspondientes de Carbon
        $daysMap = [
            'Domingo' => 0,
            'Lunes' => 1,
            'Martes' => 2,
            'Miércoles' => 3,
            'Jueves' => 4,
            'Viernes' => 5,
            'Sábado' => 6,
        ];

        // Decodificar los días de trabajo desde el JSON y mapearlos
        $workingDays = array_map(fn($day) => $daysMap[$day] ?? null, json_decode($this->working_days));

        // Verifica si la decodificación de días es correcta
        \Log::info("Días de trabajo configurados: ", $workingDays);

        for ($i = 0; $i < $daysToGenerate; $i++) {
            // Verificar si el día actual es un día de trabajo
            if ($workingDays && in_array($date->dayOfWeek, $workingDays)) {
                \Log::info("Generando disponibilidades para el día: {$date->toDateString()}");

                // Crear las disponibilidades para este día
                for ($time = $startTime; $time->lt($endTime); $time->addHours($interval->hour)->addMinutes($interval->minute)) {
                    $endTimeForAvailability = $time->copy()->addHours($interval->hour)->addMinutes($interval->minute);

                    // Crear la disponibilidad
                    Availability::create([
                        'service_id' => $this->id, // Asegúrate de que esto sea el ID del servicio actual
                        'availability_date' => $date->toDateString(),
                        'start_time' => $time->format('H:i:s'),
                        'end_time' => $endTimeForAvailability->format('H:i:s'),
                        'is_available' => true,
                    ]);

                    // Log para verificar que se ha creado la disponibilidad
                    \Log::info("Disponibilidad creada para el día: {$date->toDateString()} de {$time->format('H:i:s')} a {$endTimeForAvailability->format('H:i:s')}");
                }
            } else {
                \Log::info("No es un día laboral para generar disponibilidades: {$date->toDateString()}");
            }

            $date->addDay(); // Pasar al siguiente día
        }
    }
}
