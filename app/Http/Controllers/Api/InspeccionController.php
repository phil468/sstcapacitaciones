<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Inspeccione;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InspeccionController extends Controller
{
    public function index()
    {
        return Inspeccione::all();
    }

    public function store(Request $request)
    {
        $empresa = Inspeccione::create($request->all());        
        if($request->has('created_at')){
            $empresa->created_at = $request->created_at;
        }
        if($request->has('updated_at')){
            $empresa->updated_at = $request->updated_at;
        }
        if($request->has('deleted_at')){
            $empresa->deleted_at = $request->deleted_at;
        }
        $empresa->save();
        return response()->json($empresa, 201);
    }

    public function show($id)
    {
        return Inspeccione::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $empresa = Inspeccione::find($id);

        if (!$empresa) {
            return response()->json(['error' => 'Empresa not found'], 404);
        }

        // Convertir las fechas a instancias de Carbon
        $requestDate = Carbon::parse($request->updated_at);
        $empresaDate = Carbon::parse($empresa->updated_at);
        // Convertir las fechas a timestamps para comparar
        // $requestUpdatedAt = Carbon::parse($request->updated_at)->timestamp;
        // $empresaUpdatedAt = $empresa->updated_at->timestamp;

        // Comparar timestamps para resolver conflictos
        // return response()->json([$request->updated_at , $empresa->updated_at]);
        // return response()->json([$requestUpdatedAt , $empresaUpdatedAt]);
        // return response()->json([$requestDate > $empresaDate]);
        // Comparar timestamps para resolver conflictos
        if ($requestDate->greaterThanOrEqualTo($empresaDate)) {
            
            $empresa->update($request->all());
            if($request->has('updated_at')){
                $empresa->updated_at = $request->updated_at;
            }
            if($request->has('deleted_at')){
                $empresa->deleted_at = $request->deleted_at;
            }
            $empresa->save();

            return response()->json($empresa, 200);
        } else {
            return response()->json([
                'error' => 'Conflict detected',
                'message' => 'The provided updated_at is older than the current updated_at in the database.',
                'provided_updated_at' => $request->updated_at,
                'current_updated_at' => $empresa->updated_at
            ], 409);
        }
    }

    public function destroy($id)
    {
        Inspeccione::destroy($id);
        return response()->json(null, 204);
    }
}
