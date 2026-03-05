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
    protected $nombreInspeccion;
    protected $urlTipoInspeccion;
    protected $tipo;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ResultadosInspeccion $detalle, $nombreInspeccion = null, $urlTipoInspeccion = null, $tipo = null)
    {
        $this->detalle = $detalle;
        $this->inspeccion = $detalle->inspeccion;
        $this->nombreInspeccion = $nombreInspeccion;
        $this->urlTipoInspeccion = $urlTipoInspeccion;
        $this->tipo = $tipo;
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

        if($this->tipo == 'notificar-detalle') {
            if ($this->detalle->estado == 'Pendiente') {
                
                return (new MailMessage)
                    ->subject('Observación Pendiente de Inspección')

                    ->salutation('Saludos, ' . $notifiable->name)

                    // ->line($this->detalle->notificado == 0 ? '(Reenvío de notificación)': '')
                    
                    ->line('Datos de la inspección.')
                    ->line('Número de Registro: ' . $this->inspeccion->numero_registro)
                    ->line('Empresa: ' . $this->inspeccion->empresa->razon_social)
                    ->line('Fecha de Inspección: ' . $this->inspeccion->fecha_inspeccion)
                    ->line('Hora de Inspección: ' . $this->inspeccion->hora_inspeccion)

                    ->line('Se le ha asignado una nueva observación pendiente de inspección.')
                    ->line('Descripción: ' . $this->detalle->descripcion)
                    ->line(new \Illuminate\Support\HtmlString('<img src="' . $this->detalle->registro_fotografico . '" alt="Registro Fotográfico" />'))
                    ->line('Nivel de Riesgo: ' . $this->detalle->nivel_riesgo)
                    ->line('Acción a Tomar: ' . $this->detalle->accion_a_tomar)

                    ->line('Estado: ' . $this->detalle->estado)
                    ->line('Fecha de Ejecución: ' . $this->detalle->fecha_ejecucion)
                    ->line('Debe subsanar la observación en el plazo de corrección: ' . $this->detalle->fecha_ejecucion)
                    ->action('Ver Inspección', $url . '/' . 'inspecciones/' . ($this->urlTipoInspeccion??'')."/".'levantamiento/'.$this->detalle->uuid)
                    ->line('¡Gracias por usar nuestra aplicación.')->priority(1);
            }
        }
        
        if($this->tipo == 'levantamiento') {
            if ($this->detalle->levantamiento) {
                if ($this->detalle->levantamiento->levantado == 1) {
                    return (new MailMessage)
                        ->subject('Observación Levantada')

                        ->salutation('Saludos, ' . $notifiable->name)

                        ->line('Datos de la inspección.')
                        ->line('Número de Registro: ' . $this->inspeccion->numero_registro)
                        ->line('Empresa: ' . $this->inspeccion->empresa->razon_social)
                        ->line('Fecha de Inspección: ' . $this->inspeccion->fecha_inspeccion)
                        ->line('Hora de Inspección: ' . $this->inspeccion->hora_inspeccion)
                        
                        ->line('Se ha levantado la observación pendiente de inspección.')
                        ->line('Descripción: ' . $this->detalle->descripcion)
                        ->line(new \Illuminate\Support\HtmlString('<img src="' . $this->detalle->registro_fotografico . '" alt="Registro Fotográfico" />'))
                        ->line('Nivel de Riesgo: ' . $this->detalle->nivel_riesgo)
                        
                        ->line('Acción a Tomar: ' . $this->detalle->accion_a_tomar)
                        ->line('Estado: ' . $this->detalle->estado)
                        ->line('Fecha de Ejecución: ' . $this->detalle->fecha_ejecucion)
                        ->line('Evidencia de levantamiento:')
                        ->line(new \Illuminate\Support\HtmlString('<img src="' . $this->detalle->levantamiento->registro_fotografico . '" alt="Registro Fotográfico" />'))
                        // ->line('Fecha de Ejecución: ' . $this->detalle->fecha_ejecucion)
                        // ->line('Debe subsanar la observación en el plazo de corrección: ' .  $this->detalle->fecha_ejecucion)
                        // ->action('Ver Inspección', $url . '/' . 'inspecciones/' . $this->urlTipoInspeccion??'')
                        ->line('Gracias por usar nuestra aplicación.')->priority(1);
                } elseif ($this->detalle->levantamiento->levantado == 0) {
                    return (new MailMessage)
                        ->subject('Observación Rechazada')
    
                        ->line('Datos de la inspección.')
                        ->line('Número de Registro: ' . $this->inspeccion->numero_registro)
                        ->line('Empresa: ' . $this->inspeccion->empresa->razon_social)
                        ->line('Fecha de Inspección: ' . $this->inspeccion->fecha_inspeccion)
                        ->line('Hora de Inspección: ' . $this->inspeccion->hora_inspeccion)
    
                        ->line('Se ha rechazado la observación pendiente de inspección.')
                        ->line('Descripción: ' . $this->detalle->descripcion)
                        ->line(new \Illuminate\Support\HtmlString('<img src="' . $this->detalle->registro_fotografico . '" alt="Registro Fotográfico" />'))
                        ->line('Nivel de Riesgo: ' . $this->detalle->nivel_riesgo)
                        ->line('Acción a Tomar: ' . $this->detalle->accion_a_tomar)

                        ->line('Estado: ' . $this->detalle->estado)
                        ->line('Fecha de Ejecución: ' . $this->detalle->fecha_ejecucion)
                        ->line('Debe subsanar la observación en el plazo de corrección: ' .  $this->detalle->fecha_ejecucion)
                        ->action('Ver Inspección', $url . '/' . 'inspecciones/' . ($this->urlTipoInspeccion??'')."/".'levantamiento/'.$this->detalle->uuid)
                        ->line('Gracias por usar nuestra aplicación.')->priority(1);
                }
            }
        }        
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
