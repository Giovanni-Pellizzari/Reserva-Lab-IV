<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservaCreada extends Notification
{
    public function toMail($notifiable)
{
    return (new MailMessage)
                ->line('Has creado una nueva reserva.')
                ->action('Ver Reserva', url('/reservas'))
                ->line('Gracias por usar nuestro sistema de reservas!');
}

}
