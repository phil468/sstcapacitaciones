<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Epp;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EppController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Epp::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $epp = Epp::create($request->all());
        if ($request->has('created_at')) {
            $epp->created_at = $request->created_at;
        }
        if ($request->has('updated_at')) {
            $epp->updated_at = $request->updated_at;
        }
        if ($request->has('deleted_at')) {
            $epp->deleted_at = $request->deleted_at;
        }
        $epp->save();
        return response()->json($epp, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Epp::findOrFail($id);
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
        $epp = Epp::find($id);

        if (!$epp) {
            return response()->json(['error' => 'Epp not found'], 404);
        }

        // Convertir las fechas a instancias de Carbon
        $requestDate = Carbon::parse($request->updated_at);
        $eppDate = Carbon::parse($epp->updated_at);

        // Comparar las fechas
        if ($requestDate->greaterThanOrEqualTo($eppDate)) {
            $epp->update($request->all());
            if ($request->has('updated_at')) {
                $epp->updated_at = $request->updated_at;
            }
            if ($request->has('deleted_at')) {
                $epp->deleted_at = $request->deleted_at;
            }
            $epp->save();

            return response()->json($epp, 200);
        } else {
            return response()->json([
                'error' => 'Conflict detected',
                'message' => 'La fecha de actualización proporcionada es anterior a la fecha de actualización actual en la base de datos.',
                'provided_updated_at' => $request->updated_at,
                'current_updated_at' => $epp->updated_at
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
        Epp::destroy($id);
        return response()->json(null, 204);
    }
}