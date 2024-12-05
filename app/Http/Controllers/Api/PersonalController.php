<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Personal;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PersonalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Personal::with('cargo')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $personal = Personal::create($request->all());
        if ($request->has('created_at')) {
            $personal->created_at = $request->created_at;
        }
        if ($request->has('updated_at')) {
            $personal->updated_at = $request->updated_at;
        }
        if ($request->has('deleted_at')) {
            $personal->deleted_at = $request->deleted_at;
        }
        $personal->save();
        return response()->json($personal, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Personal::findOrFail($id);
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
        $personal = Personal::find($id);

        if (!$personal) {
            return response()->json(['error' => 'Personal not found'], 404);
        }

        // Convertir las fechas a instancias de Carbon
        $requestDate = Carbon::parse($request->updated_at);
        $personalDate = Carbon::parse($personal->updated_at);

        // Comparar las fechas
        if ($requestDate->greaterThanOrEqualTo($personalDate)) {
            $personal->update($request->all());
            if ($request->has('updated_at')) {
                $personal->updated_at = $request->updated_at;
            }
            if ($request->has('deleted_at')) {
                $personal->deleted_at = $request->deleted_at;
            }
            $personal->save();

            return response()->json($personal, 200);
        } else {
            return response()->json([
                'error' => 'Conflict detected',
                'message' => 'The provided updated_at is older than the current updated_at in the database.',
                'provided_updated_at' => $request->updated_at,
                'current_updated_at' => $personal->updated_at
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
        Personal::destroy($id);
        return response()->json(null, 204);
    }
}