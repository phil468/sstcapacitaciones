<?php
namespace App\Http\Controllers\Api\sst\inspecciones;

use App\Http\Controllers\Controller;
use App\Models\Inspecciones\Luces\ParteLuzEmergencia;
use Illuminate\Http\Request;

class ParteLuzEmergenciaController extends Controller
{
    public function index()
    {
        return ParteLuzEmergencia::all();
    }

    public function store(Request $request)
    {
        $parteLuzEmergencia = ParteLuzEmergencia::create($request->all());
        return response()->json($parteLuzEmergencia, 201);
    }

    public function show($id)
    {
        return ParteLuzEmergencia::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $parteLuzEmergencia = ParteLuzEmergencia::findOrFail($id);
        $parteLuzEmergencia->update($request->all());
        return response()->json($parteLuzEmergencia, 200);
    }

    public function destroy($id)
    {
        ParteLuzEmergencia::destroy($id);
        return response()->json(null, 204);
    }
}