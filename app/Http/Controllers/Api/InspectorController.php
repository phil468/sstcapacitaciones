<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inspectore as Inspector;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InspectorController extends Controller
{
    public function index()
    {
        return Inspector::with('personal','personal.cargo')->get();
    }

    public function store(Request $request)
    {
        $inspector = Inspector::create($request->all());
        if ($request->has('created_at')) {
            $inspector->created_at = $request->created_at;
        }
        if ($request->has('updated_at')) {
            $inspector->updated_at = $request->updated_at;
        }
        if ($request->has('deleted_at')) {
            $inspector->deleted_at = $request->deleted_at;
        }
        $inspector->save();

        // Cargar la relación personal
        $inspector->load('personal');

        return response()->json($inspector, 201);
    }

    public function show($id)
    {
        return Inspector::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $inspector = Inspector::find($id);

        if (!$inspector) {
            return response()->json(['error' => 'Inspector not found'], 404);
        }

        // Convertir las fechas a instancias de Carbon
        $requestDate = Carbon::parse($request->updated_at);
        $inspectorDate = Carbon::parse($inspector->updated_at);

        // Comparar las fechas
        if ($requestDate->greaterThanOrEqualTo($inspectorDate)) {
            $inspector->update($request->all());
            if ($request->has('updated_at')) {
                $inspector->updated_at = $request->updated_at;
            }
            if ($request->has('deleted_at')) {
                $inspector->deleted_at = $request->deleted_at;
            }
            $inspector->save();

            // Cargar la relación personal
            $inspector->load('personal');
            
            return response()->json($inspector, 200);
        } else {
            return response()->json([
                'error' => 'Conflict detected',
                'message' => 'The provided updated_at is older than the current updated_at in the database.',
                'provided_updated_at' => $request->updated_at,
                'current_updated_at' => $inspector->updated_at
            ], 409);
        }
    }

    public function destroy($id)
    {
        Inspector::destroy($id);
        return response()->json(null, 204);
    }
}