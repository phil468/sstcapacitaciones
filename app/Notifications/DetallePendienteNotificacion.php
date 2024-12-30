<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ResultadosInspeccion;

class DetallePendienteNotificacion extends Notification
{
    use Queueable;

    protected $detalle;
    protected $inspeccion;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ResultadosInspeccion $detalle)
    {
        $this->detalle = $detalle;
        $this->inspeccion = $detalle->inspeccion;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = config('app.env') === 'production' 
            ? env('APP_URL_PRODUCTION') 
            : env('APP_URL_LOCAL');

        $plazoCorreccion = $this->getPlazoCorreccion($this->detalle->nivel_riesgo);

        return (new MailMessage)
                    ->subject('Observación Pendiente de Inspección')
                    ->line('Se le ha asignado una nueva observación pendiente de inspección.')
                    ->line('Descripción: ' . $this->detalle->descripcion)
                    ->line('Nivel de Riesgo: ' . $this->detalle->nivel_riesgo)
                    // ->line('Registro Fotográfico: ' . $this->detalle->registro_fotografico)
                    ->line('Acción a Tomar: ' . $this->detalle->accion_a_tomar)
                    ->line('Cargo: ' . $this->detalle->cargo->name)
                    ->line('Estado: ' . $this->detalle->estado)
                    ->line('Fecha de Ejecución: ' . $this->detalle->fecha_ejecucion)
                    ->line('Número de Registro: ' . $this->inspeccion->numero_registro)
                    ->line('Empresa: ' . $this->inspeccion->empresa->razon_social)
                    ->line('Fecha de Inspección: ' . $this->inspeccion->fecha_inspeccion)
                    ->line('Hora de Inspección: ' . $this->inspeccion->hora_inspeccion)
                    ->line('Objetivo: ' . $this->inspeccion->objetivo)
                    ->line('Debe subsanar la observación en el plazo de corrección: ' . $plazoCorreccion)
                    ->line(new \Illuminate\Support\HtmlString('<img src="' . $this->detalle->registro_fotografico . '" alt="Registro Fotográfico" />'))
                    ->action('Ver Inspección', $url . '/' . 'inspecciones/' . $this->inspeccion->id)
                    ->line('Gracias por usar nuestra aplicación!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'detalle_id' => $this->detalle->id,
            'descripcion' => $this->detalle->descripcion,
            'nivel_riesgo' => $this->detalle->nivel_riesgo,
            'registro_fotografico' => $this->detalle->registro_fotografico,
            'accion_a_tomar' => $this->detalle->accion_a_tomar,
            'cargo' => $this->detalle->cargo->name,
            'estado' => $this->detalle->estado,
            'fecha_ejecucion' => $this->detalle->fecha_ejecucion,
            'inspeccion_id' => $this->inspeccion->id,
            'numero_registro' => $this->inspeccion->numero_registro,
            'empresa' => $this->inspeccion->empresa->razon_social,
            'fecha_inspeccion' => $this->inspeccion->fecha_inspeccion,
            'hora_inspeccion' => $this->inspeccion->hora_inspeccion,
            'objetivo' => $this->inspeccion->objetivo,
            'plazo_correccion' => $this->getPlazoCorreccion($this->detalle->nivel_riesgo),
        ];
    }

    private function getPlazoCorreccion($nivelRiesgo)
    {
        switch ($nivelRiesgo) {
            case 'Alto':
                return '0-48 horas';
            case 'Medio':
                return '0-7 días';
            case 'Bajo':
                return '0-15 días';
            default:
                return 'Desconocido';
        }
    }
}
