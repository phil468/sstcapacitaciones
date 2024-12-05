<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Area::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $area = Area::create($request->all());
        if ($request->has('created_at')) {
            $area->created_at = $request->created_at;
        }
        if ($request->has('updated_at')) {
            $area->updated_at = $request->updated_at;
        }
        if ($request->has('deleted_at')) {
            $area->deleted_at = $request->deleted_at;
        }
        $area->save();
        return response()->json($area, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Area::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $area = Area::find($id);

        if (!$area) {
            return response()->json(['error' => 'Area not found'], 404);
        }

        // Convertir las fechas a instancias de Carbon
        $requestDate = Carbon::parse($request->updated_at);
        $areaDate = Carbon::parse($area->updated_at);

        // Comparar las fechas
        if ($requestDate->greaterThanOrEqualTo($areaDate)) {
            $area->update($request->all());
            if ($request->has('updated_at')) {
                $area->updated_at = $request->updated_at;
            }
            if ($request->has('deleted_at')) {
                $area->deleted_at = $request->deleted_at;
            }
            $area->save();

            return response()->json($area, 200);
        } else {
            return response()->json([
                'error' => 'Conflict detected',
                'message' => 'The provided updated_at is older than the current updated_at in the database.',
                'provided_updated_at' => $request->updated_at,
                'current_updated_at' => $area->updated_at
            ], 409);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Area::destroy($id);
        return response()->json(null, 204);
    }
}