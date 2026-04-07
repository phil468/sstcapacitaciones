<?php

namespace App\Console;

use App\Models\ConfiguracionGeneral;
use App\Models\ConfiguracionGeneralInspecciones;
use App\Models\Evaluacione;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Obtener la hora de envío de alerta desde la configuración
        $horaEnvioAlerta = ConfiguracionGeneral::getValorByName('hora_de_envio_de_alerta');
        // Verificar si se obtuvo una hora válida
        if ($horaEnvioAlerta) {
            // Log::info("Programando envío de alertas de capacitación a las {$horaEnvioAlerta}.");
            $schedule->command('alerts:send-capacitacion')->dailyAt($horaEnvioAlerta)
    ->withoutOverlapping(30);
        } else {
            // Si no se obtiene una hora válida, usar una hora por defecto
            // Log::info("Programando envío de alertas de capacitación a las 06:00 (hora por defecto).");
            $schedule->command('alerts:send-capacitacion')->dailyAt('06:00')
    ->withoutOverlapping(30);
        }
        
    //     // Obtener la hora de envío de alerta desde la configuración
    //     $horaEnvioAlertaInspecciones = ConfiguracionGeneralInspecciones::getValorByName('hora_de_envio_de_alerta');
    //     // Verificar si se obtuvo una hora válida
    //     if ($horaEnvioAlertaInspecciones) {
    //         $schedule->command('alerts:send-sst')->dailyAt($horaEnvioAlertaInspecciones)
    // ->withoutOverlapping(30);
    //     } else {
    //         // Si no se obtiene una hora válida, usar una hora por defecto
    //         $schedule->command('alerts:send-sst')->dailyAt('06:00')
    // ->withoutOverlapping(30);
    //     }
        
        $schedule->command('personal:actualizar-general')
        // la hora diaria de actualizacion se obtiene de un campo en el .env sino será por defecto a las 09:00
        ->dailyAt(config('app.hora_actualizacion_personal', '09:00'))
        ->appendOutputTo(storage_path('logs/personal-actualizacion.log'));
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
