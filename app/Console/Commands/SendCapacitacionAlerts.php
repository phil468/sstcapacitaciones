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

        foreach ($alertas as $alerta) {
            $dias = $alerta->dias;
            $campo = $alerta->campo;
            $condicion = $alerta->condicion;

            $capacitaciones = CapacitacionHasPersonal::where(function ($query) use ($now, $dias, $campo, $condicion) {
                if ($condicion == 'antes') {
                    $query->whereDate($campo, '=', $now->copy()->addDays($dias));
                } elseif ($condicion == 'despues') {
                    $query->whereDate($campo, '=', $now->copy()->subDays($dias));
                }
            })->get();

            foreach ($capacitaciones as $capacitacion) {
                // Verificar si ya se envió una alerta hoy
                
                $alertaEnviada = AlertaEnviada::where('capacitacion_has_personal_id', $capacitacion->id)
                    ->whereDate('fecha_envio', $now->toDateString())
                    ->first();
                
                    if ($alertaEnviada) {
                        continue; // Ya se envió una alerta hoy, saltar
                    }

                $personal = $capacitacion->personal;
                $capacitacionModel = $capacitacion;

                $sesionAccessLogs = SesionAccessLog::where('capacitacion_id', $capacitacion->capacitacion_id)
                    ->where('personal_id', $capacitacion->personal_id)
                    ->get();

                $pendientes = [];
                $enDesarrollo = [];

                if ($now->between($capacitacion->fecha_inicio, $capacitacion->fecha_fin)) {
                    if ($sesionAccessLogs->isEmpty()) {
                        $pendientes[] = $capacitacionModel;
                    } else {
                        $ultimoRegistro = $sesionAccessLogs->last();
                        if ($ultimoRegistro->numero_de_evaluacion < $capacitacion->intentos_de_evaluacion) {
                            $enDesarrollo[] = $capacitacionModel;
                        }
                    }
                }

                if (!empty($pendientes) || !empty($enDesarrollo)) {
                    $this->sendAlertEmail($personal, $pendientes, $enDesarrollo);
                
                    // Registrar la alerta enviada
                    AlertaEnviada::create([
                        'capacitacion_has_personal_id' => $capacitacion->id,
                        'fecha_envio' => $now->toDateString(),
                    ]);

                }
            }
        }

        $this->info('Capacitacion alerts sent successfully.');
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