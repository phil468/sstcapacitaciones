<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AlertaInspeccion;
use App\Models\ConfiguracionAlertaInspeccionSST;
use Illuminate\Http\Request;

class ConfiguracionAlertaInspeccionController extends Controller
{
    public function index()
    {
        return ConfiguracionAlertaInspeccionSST::all();
    }

    public function store(Request $request)
    {
        $alerta = ConfiguracionAlertaInspeccionSST::create($request->all());
        return response()->json($alerta, 201);
    }

    public function show($id)
    {
        return ConfiguracionAlertaInspeccionSST::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $alerta = ConfiguracionAlertaInspeccionSST::findOrFail($id);
        $alerta->update($request->all());
        return response()->json($alerta, 200);
    }

    public function destroy($id)
    {
        ConfiguracionAlertaInspeccionSST::destroy($id);
        return response()->json(null, 204);
    }
}