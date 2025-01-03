<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AlertasLevantamiento;
use App\Models\ResultadosInspeccion;
use App\Notifications\DetallePendienteNotificacion;

class RevisarLevantamientoController extends Controller
{
    public function index()
    {
        $alertas = AlertasLevantamiento::where('levantado', null)->get();
        return response()->json($alertas);
    }

    public function update(Request $request, $id)
    {
        $alerta = AlertasLevantamiento::findOrFail($id);
        $alerta->levantado = $request->input('levantado');

        $resultado = ResultadosInspeccion::where('uuid', $alerta->resultado_inspeccion_uuid)->first();

        if ($request->input('levantado')) {
            $resultado->estado = 'Ejecutado';
            $resultado->fecha_ejecucion = now();
            // $alerta->notificado = true;
        } else {
            $resultado->estado = 'Pendiente';            
            // $alerta->notificado = true;
        }
        
        $alerta->save();
        $resultado->save();

        // Enviar notificación de nuevo pendiente
        // $inspeccion = $resultado->inspeccion;
        $responsable = $resultado->responsable->user ?? $resultado->responsable->personal;
        $responsable->notify(
            new DetallePendienteNotificacion
            ($resultado, 
            'Inpecciones Internas', 
            'inspecciones-internas',
            'levantamiento'
            )
        );

        return response()->json(['success' => true]);
    }
}