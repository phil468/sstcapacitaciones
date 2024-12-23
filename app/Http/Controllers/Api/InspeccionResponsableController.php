<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InspeccionResponsable;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InspeccionResponsableController extends Controller
{
    public function index()
    {
        return InspeccionResponsable::all();
    }

    public function store(Request $request)
    {
        $data = $request->all();

        if ($request->has('created_at')) {
            $data['created_at'] = Carbon::parse($request->created_at)->setTimezone('UTC')->subHours(5);
        }
        if ($request->has('updated_at')) {
            $data['updated_at'] = Carbon::parse($request->updated_at)->setTimezone('UTC')->subHours(5);
        }
        if ($request->has('deleted_at')) {
            $data['deleted_at'] = Carbon::parse($request->deleted_at)->setTimezone('UTC')->subHours(5);
        }

        $inspeccionResponsable = InspeccionResponsable::create($data);
        return response()->json($inspeccionResponsable, 201);
    }

    public function show($id)
    {
        return InspeccionResponsable::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $inspeccionResponsable = InspeccionResponsable::find($id);

        if (!$inspeccionResponsable) {
            return response()->json(['error' => 'InspeccionResponsable not found'], 404);
        }

        $data = $request->all();

        if ($request->has('updated_at')) {
            $data['updated_at'] = Carbon::parse($request->updated_at)->setTimezone('UTC')->subHours(5);
        }
        if ($request->has('deleted_at')) {
            $data['deleted_at'] = Carbon::parse($request->deleted_at)->setTimezone('UTC')->subHours(5);
        }

        $inspeccionResponsable->update($data);
        return response()->json($inspeccionResponsable, 200);
    }

    public function destroy($id)
    {
        InspeccionResponsable::destroy($id);
        return response()->json(null, 204);
    }
}