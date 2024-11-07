<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservaStatusNotification extends Notification
{
    use Queueable;

    private $reserva;

    /**
     * Create a new notification instance.
     */
    public function __construct($reserva)
    {
        $this->reserva = $reserva;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail']; // Puedes añadir otros canales como 'database' o 'sms'
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Actualización de Estado de Reserva')
                    ->line("Tu reserva para el servicio '{$this->reserva->service->name}' ha sido actualizada.")
                    ->line("Estado actual: {$this->reserva->status}")
                    ->action('Ver Reserva', url("/reservations/{$this->reserva->id}"))
                    ->line('Gracias por usar nuestro sistema de reservas.');
    }
}
