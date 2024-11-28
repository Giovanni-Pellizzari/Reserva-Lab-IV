<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\ReservaStatusNotification;

class Reserva extends Model
{
    use HasFactory;

    // Definir campos asignables
    protected $fillable = [
        'user_id',
        'service_id',
        'reservation_date',
        'start_time',
        'end_time',  // Agrega end_time aquí
        'status',
        'is_notified',
    ];

    // Definir si usaremos timestamps (si no lo usas, agrega $timestamps = false)
    public $timestamps = true;

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con el servicio
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Enviar notificación al crear o actualizar la reserva
    protected static function booted()
    {
        static::created(function ($reserva) {
            $reserva->sendNotification();
        });

        static::updated(function ($reserva) {
            // Enviar notificación solo si el estado cambia
            if ($reserva->isDirty('status')) {
                $reserva->sendNotification();
            }
        });
    }

    // Método para enviar la notificación
    public function sendNotification()
    {
        // Verificar si la notificación ya fue enviada
        if ($this->is_notified === false) {
            // Enviar la notificación de cambio de estado de la reserva
            $this->user->notify(new ReservaStatusNotification($this));
            
            // Actualizar los campos de notificación
            $this->is_notified = true;
            $this->notified_at = now();
            $this->save();
        }
    }
}
