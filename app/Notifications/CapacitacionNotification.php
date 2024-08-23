<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CapacitacionNotification extends Notification
{
    use Queueable;

    protected $capacitacion;

    public function __construct($capacitacion)
    {
        $this->capacitacion = $capacitacion;
        // dd($this->capacitacion);
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Tiene alguna(s) Capacitacion(es) pendiente(s).')
                    ->action('Ver Mis Capacitaciones', url('/mis-capacitaciones'))
                    ->line('Gracias por usar nuestra aplicación!');
    }

    public function toArray($notifiable)
    {
        return [
            'capacitacion_id' => $this->capacitacion->id,
            'mensaje' => 'Hay una nueva capacitación disponible.'
        ];
    }
}