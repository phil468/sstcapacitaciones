<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmpresaController extends Controller
{
    public function index()
    {
        return Empresa::all();
    }

    public function store(Request $request)
    {
        $empresa = Empresa::create($request->all());
        return response()->json($empresa, 201);
    }

    public function show($id)
    {
        return Empresa::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $empresa = Empresa::find($id);

        if (!$empresa) {
            return response()->json(['error' => 'Empresa not found'], 404);
        }

        // Convertir las fechas a timestamps para comparar
        $requestUpdatedAt = Carbon::parse($request->updated_at)->timestamp;
        $empresaUpdatedAt = $empresa->updated_at->timestamp;

        // Comparar timestamps para resolver conflictos
        // return response()->json([$request->updated_at , $empresa->updated_at]);
        // return response()->json([$requestUpdatedAt , $empresaUpdatedAt]);
        // Comparar timestamps para resolver conflictos
        if ($requestUpdatedAt > $empresaUpdatedAt) {
            $empresa->update($request->all());
            return response()->json($empresa, 200);
        } else {
            return response()->json(['error' => 'Conflict detected'], 409);
        }
    }


    public function destroy($id)
    {
        Empresa::destroy($id);
        return response()->json(null, 204);
    }
}