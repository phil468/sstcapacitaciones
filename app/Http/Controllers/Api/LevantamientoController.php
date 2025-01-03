<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AlertasLevantamiento;
use App\Models\ResultadosInspeccion;
use Illuminate\Http\Request;

class LevantamientoController extends Controller
{
    public function store(Request $request, $uuid)
    {
        $resultado = ResultadosInspeccion::where('uuid', $uuid)->firstOrFail();

        $alerta = new AlertasLevantamiento();
        $alerta->resultado_inspeccion_uuid = $uuid;
        $alerta->registro_fotografico = $request->input('registro_fotografico');
        $alerta->save();

        return response()->json(['success' => true]);
    }
}