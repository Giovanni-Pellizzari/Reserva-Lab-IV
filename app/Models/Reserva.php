<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\ReservaStatusNotification;
use Illuminate\Support\Facades\Notification;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'service_id', 'reserva_date', 'status', 'is_notified', 'notified_at'
    ];

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
            $reserva->sendNotification();
        });
    }

    // Método para enviar la notificación
    public function sendNotification()
    {
        if ($this->is_notified === false) {
            $this->user->notify(new ReservaStatusNotification($this));
            $this->is_notified = true;
            $this->notified_at = now();
            $this->save();
        }
    }
}
