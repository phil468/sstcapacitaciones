<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PreviewCapacitacionEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'preview:capacitacion-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera una vista previa HTML del email de alertas de capacitaciones en storage/app/preview_capacitacion.html';

    public function handle()
    {
        $pendientes = [];
        $enDesarrollo = [];

        // crear 2 pendientes de ejemplo
        for ($i=1;$i<=2;$i++) {
            $cap = new \stdClass();
            $capacitacion = new \stdClass();
            $tema = new \stdClass();
            $tema->name = "Capacitación de ejemplo #$i";
            $capacitacion->tema = $tema;
            $capacitacion->descripcion = "Descripción de la capacitación de ejemplo #$i. Contenido breve para mostrar en la plantilla de correo.";
            $capacitacion->responsable = 'Responsable Ejemplo';

            $cap->capacitacion = $capacitacion;
            $cap->fecha_inicio = Carbon::now()->subDays(2 + $i);
            $cap->fecha_fin = Carbon::now()->addDays(5 + $i);

            $pendientes[] = $cap;
        }

        // crear 2 en desarrollo
        for ($i=1;$i<=2;$i++) {
            $cap = new \stdClass();
            $capacitacion = new \stdClass();
            $tema = new \stdClass();
            $tema->name = "Curso activo #$i";
            $capacitacion->tema = $tema;
            $capacitacion->responsable = 'Equipo de Formación';

            $cap->capacitacion = $capacitacion;
            $cap->fecha_inicio = Carbon::now()->subDays($i);
            $cap->fecha_fin = Carbon::now()->addDays(10 - $i);

            $enDesarrollo[] = $cap;
        }

        $link = url('/');

        $html = view('emails.capacitacion_alerts', compact('pendientes','enDesarrollo','link'))->render();

        $path = storage_path('app/preview_capacitacion.html');
        file_put_contents($path, $html);

        $this->info("Vista previa generada: $path");
        return 0;
    }
}
