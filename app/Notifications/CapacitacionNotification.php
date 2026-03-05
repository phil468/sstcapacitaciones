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
        //corregir el asunto del correo para que diga "Nueva capacitación disponible: [nombre de la capacitación]"
        return (new MailMessage)
                    ->subject('Notificación de Capacitación Pendiente')
                    ->line('Tiene alguna(s) Capacitacion(es) pendiente(s).')
                    // ->line('Por favor, revise su sección de "Mis Capacitaciones" para más detalles.')
                    ->action('Ver Mis Capacitaciones', url('/mis-capacitaciones'))
                    ->line('Si ya las realizaste, puedes omitir este recordatorio. Te recordamos que su cumplimiento es obligatorio.')
                    ->line('Gracias por usar nuestra aplicación.');
    }

    public function toArray($notifiable)
    {
        return [
            'capacitacion_id' => $this->capacitacion->id,
            'mensaje' => 'Hay una nueva capacitación disponible.'
        ];
    }
}