<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AlertaEnviadaInspeccion;
use Illuminate\Http\Request;

class AlertaEnviadaInspeccionController extends Controller
{
    public function index()
    {
        return AlertaEnviadaInspeccion::with(
            'resultado_inspeccion',
            'resultado_inspeccion.inspeccion',
            'resultado_inspeccion.inspeccion.empresa',
            'resultado_inspeccion.responsable',
            'resultado_inspeccion.cargo'
        )->get();
    }

    public function store(Request $request)
    {
        $alertaEnviada = AlertaEnviadaInspeccion::create($request->all());
        return response()->json($alertaEnviada, 201);
    }

    public function show($id)
    {
        return AlertaEnviadaInspeccion::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $alertaEnviada = AlertaEnviadaInspeccion::findOrFail($id);
        $alertaEnviada->update($request->all());
        return response()->json($alertaEnviada, 200);
    }

    public function destroy($id)
    {
        AlertaEnviadaInspeccion::destroy($id);
        return response()->json(null, 204);
    }
}