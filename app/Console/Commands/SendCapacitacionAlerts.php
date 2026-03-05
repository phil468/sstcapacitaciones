<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CapacitacionHasPersonal;
use App\Models\SesionAccessLog;
use App\Models\Alerta;
use App\Models\AlertaEnviada;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class SendCapacitacionAlerts extends Command
{
    protected $signature = 'alerts:send-capacitacion';
    protected $description = 'Send alerts for capacitaciones based on rules';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $now = Carbon::now();
        $alertas = Alerta::where('estado', 1)->get();
        $enviados = 0;

        foreach ($alertas as $alerta) {
            $dias = $alerta->dias;
            $campo = $alerta->campo;
            $condicion = $alerta->condicion;

            // Solo procesar condiciones válidas
            if (!in_array($condicion, ['antes', 'despues'])) {
                $this->warn("Alerta #{$alerta->id}: condición '{$condicion}' no válida, se omite.");
                continue;
            }

            $query = CapacitacionHasPersonal::query();

            if ($condicion == 'antes') {
                $query->whereDate($campo, '=', $now->copy()->addDays($dias));
            } else {
                $query->whereDate($campo, '=', $now->copy()->subDays($dias));
            }

            // Excluir los que ya tienen alerta enviada hoy
            $query->whereDoesntHave('alertasEnviadas', function ($q) use ($now) {
                $q->whereDate('fecha_envio', $now->toDateString());
            });

            // Eager load para evitar N+1 queries
            $query->with(['personal.user', 'capacitacion.tema']);

            $query->chunk(200, function ($capacitaciones) use ($now, &$enviados) {
                foreach ($capacitaciones as $capacitacion) {
                    $personal = $capacitacion->personal;
                    if (!$personal) continue;

                    $sesionAccessLogs = SesionAccessLog::where('capacitacion_id', $capacitacion->capacitacion_id)
                        ->where('personal_id', $capacitacion->personal_id)
                        ->get();

                    $pendientes = [];
                    $enDesarrollo = [];

                    if ($now->between($capacitacion->fecha_inicio, $capacitacion->fecha_fin)) {
                        if ($sesionAccessLogs->isEmpty()) {
                            $pendientes[] = $capacitacion;
                        } else {
                            $ultimoRegistro = $sesionAccessLogs->last();
                            if ($ultimoRegistro->numero_de_evaluacion < $capacitacion->intentos_de_evaluacion) {
                                $enDesarrollo[] = $capacitacion;
                            }
                        }
                    }

                    if (!empty($pendientes) || !empty($enDesarrollo)) {
                        $this->sendAlertEmail($personal, $pendientes, $enDesarrollo);

                        AlertaEnviada::create([
                            'capacitacion_has_personal_id' => $capacitacion->id,
                            'fecha_envio' => $now->toDateString(),
                        ]);
                        $enviados++;
                    }
                }
            });
        }

        $this->info("Capacitacion alerts sent successfully. Enviados: $enviados");
    }

    protected function sendAlertEmail($personal, $pendientes, $enDesarrollo)
    {
        $subject = 'Alertas de Capacitaciones';
        $to = $personal->user->email??null;
        $view = 'emails.capacitacion_alerts';
        $data = [
            'pendientes' => $pendientes,
            'enDesarrollo' => $enDesarrollo,
            'link' => url('/'),
        ];

        // dd($to);
        if (!$to) {
            return;
        }

        Mail::send($view, $data, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
    }
}
