<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ResultadosInspeccion;
use App\Models\AlertaEnviadaInspeccion;
use App\Models\ConfiguracionAlertaInspeccionSST;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendSSTAlerts extends Command
{
    protected $signature = 'alerts:send-sst';
    protected $description = 'Send alerts for SST inspections based on rules';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Log::channel('sst_alerts')->info('Iniciando el envío de alertas SST.');

        $now = Carbon::now();
        $alertas = ConfiguracionAlertaInspeccionSST::where('estado', 1)->get();

        foreach ($alertas as $alerta) {
            $dias = $alerta->dias;
            $campo = $alerta->campo;
            $condicion = $alerta->condicion;
            $recurrente = $alerta->recurrente;

            Log::channel('sst_alerts')->info("Procesando alerta: {$alerta->name}, Días: {$dias}, Condición: {$condicion}, Recurrente: {$recurrente}");

            $resultados = ResultadosInspeccion::where('estado', 'pendiente')
                ->where(function ($query) use ($now, $dias, $campo, $condicion, $recurrente) {
                    if ($condicion == 'antes') {
                        $query->whereDate($campo, '=', $now->copy()->addDays($dias)->toDateString());
                    } elseif ($condicion == 'despues') {
                        $query->whereDate($campo, '=', $now->copy()->subDays($dias)->toDateString());
                    } elseif ($condicion == 'vencido') {
                        $query->whereDate($campo, '=', $now->toDateString());
                    }

                    if ($recurrente) {
                        $query->orWhere(function ($subQuery) use ($now, $dias, $campo) {
                            $subQuery->whereDate($campo, '<', $now->toDateString())
                                ->whereRaw("DATEDIFF(?, $campo) % ? = 0", [$now->toDateString(), $dias]);
                        });
                    }
                })->get();
            
            Log::channel('sst_alerts')->info("Resultados encontrados: {$resultados->count()}");

            foreach ($resultados as $resultado) {
                // Verificar si ya se envió una alerta hoy
                // $alertaEnviada = AlertaEnviadaInspeccion::where('resultado_inspeccion_id', $resultado->id)
                //     ->whereDate('fecha_envio', $now->toDateString())
                //     ->first();

                // if ($alertaEnviada) {
                //     Log::channel('sst_alerts')->info("Alerta ya enviada hoy para el resultado de inspección ID: {$resultado->id}");
                //     continue; // Ya se envió una alerta hoy, saltar
                // }

                $responsable = $resultado->responsable->user ?? $resultado->responsable->personal;

                if ($responsable) {
                    $this->sendAlertEmail($responsable, $resultado);

                    // Registrar la alerta enviada
                    AlertaEnviadaInspeccion::create([
                        'resultado_inspeccion_id' => $resultado->id,
                        'fecha_envio' => $now->toDateString(),
                    ]);

                    Log::channel('sst_alerts')->info("Alerta enviada a {$responsable->email} para el resultado de inspección ID: {$resultado->id}");
                } else {
                    Log::channel('sst_alerts')->warning("No se encontró un responsable para el resultado de inspección ID: {$resultado->id}");
                }
            }
        }

        $this->info('SST alerts sent successfully.');
        
        Log::channel('sst_alerts')->info('Envío de alertas SST completado.');
    }

    protected function sendAlertEmail($responsable, $resultado)
    {        
        $url = config('app.env') === 'production' 
            ? env('APP_URL_PRODUCTION') 
            : env('APP_URL_LOCAL');

        $urlTipoInspeccion="inspecciones-internas";

        $url_inspeccion = $url . '/' . 'inspecciones/' . ($urlTipoInspeccion??'')."/".'levantamiento/'.$resultado->uuid;

        $subject = 'Alerta de Inspección SST';
        $to = $responsable->email;
        $view = 'emails.sst_alerts';
        $data = [
            'resultado' => $resultado,
            'link' => $url_inspeccion,
        ];

        Mail::send($view, $data, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
    }
}