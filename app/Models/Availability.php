<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Availability extends Model
{
    use HasFactory;

    // Los campos que se pueden asignar masivamente
    protected $fillable = [
        'service_id',
        'availability_date',
        'start_time',
        'end_time',
        'is_available',
    ];

    // Eliminar 'availability_date' de los campos tratados como fechas
    protected $dates = [
        // 'start_time', // Eliminarlo
        // 'end_time',    // Eliminarlo
    ];

    // Relación inversa: un Availability pertenece a un Service
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    
    public function reservas()
{
    return $this->hasMany(Reserva::class);
}

    // Accesor para el formato de la hora de inicio
    public function getFormattedStartTimeAttribute()
    {
        return $this->start_time ? Carbon::parse($this->start_time)->format('H:i') : null;
    }

    // Accesor para el formato de la hora de fin
    public function getFormattedEndTimeAttribute()
    {
        return $this->end_time ? Carbon::parse($this->end_time)->format('H:i') : null;
    }
}
