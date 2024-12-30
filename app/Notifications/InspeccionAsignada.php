<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Inspeccione;

class InspeccionAsignada extends Notification
{
    use Queueable;

    protected $inspeccion;
    protected $nombreInspeccion;
    protected $urlTipoInspeccion;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Inspeccione $inspeccion, $nombreInspeccion, $urlTipoInspeccion)
    {
        $this->inspeccion = $inspeccion;
        $this->nombreInspeccion = $nombreInspeccion;
        $this->urlTipoInspeccion = $urlTipoInspeccion;
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
        
        $areas = $this->inspeccion->areas()->pluck('name')->implode(', ');

        $tipoInspeccion = $this->inspeccion->tipo_inspeccion;
        if ($tipoInspeccion == 'Otro') {
            $tipoInspeccion = 'Otro: '.$this->inspeccion->tipo_inspeccion_otro;
        }

        return (new MailMessage)
                    ->subject('Nueva Inspección Asignada')
                    ->line('Se le ha asignado una nueva inspección.')
                    ->line('Número de Registro: ' . $this->inspeccion->numero_registro)
                    ->line('Empresa: ' . $this->inspeccion->empresa->razon_social)
                    ->line('Áreas: ' . $areas)
                    ->line('Vigencia Inicio: ' . $this->inspeccion->vigencia_inicio)
                    ->line('Vigencia Fin: ' . $this->inspeccion->vigencia_fin)
                    ->line('Tipo de Inspección: ' . $tipoInspeccion)
                    // ->line('Fecha de Inspección: ' . $this->inspeccion->fecha_inspeccion)
                    // ->line('Hora de Inspección: ' . $this->inspeccion->hora_inspeccion)
                    ->line('Objetivo: ' . $this->inspeccion->objetivo)
                    ->action('Ver Inspección', $url . '/' . 'inspecciones/' . $this->urlTipoInspeccion)

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
        $tipoInspeccion = $this->inspeccion->tipo_inspeccion;
        if ($tipoInspeccion == 'Otro') {
            $tipoInspeccion = $this->inspeccion->tipo_inspeccion_otro;
        }

        return [
            'inspeccion_id' => $this->inspeccion->id,
            'numero_registro' => $this->inspeccion->numero_registro,
            'empresa' => $this->inspeccion->empresa->razon_social,
            'areas' => $this->inspeccion->areas()->pluck('name')->implode(', '),
            'vigencia_inicio' => $this->inspeccion->vigencia_inicio,
            'vigencia_fin' => $this->inspeccion->vigencia_fin,
            'tipo_inspeccion' => $tipoInspeccion,
            'objetivo' => $this->inspeccion->objetivo,
        ];
    }
}
