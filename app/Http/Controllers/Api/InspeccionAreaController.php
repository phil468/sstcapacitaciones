<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InspeccionArea;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InspeccionAreaController extends Controller
{
    public function index()
    {
        return InspeccionArea::all();
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

        $inspeccionArea = InspeccionArea::create($data);
        return response()->json($inspeccionArea, 201);
    }

    public function show($id)
    {
        return InspeccionArea::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $inspeccionArea = InspeccionArea::find($id);

        if (!$inspeccionArea) {
            return response()->json(['error' => 'InspeccionArea not found'], 404);
        }

        $data = $request->all();

        if ($request->has('updated_at')) {
            $data['updated_at'] = Carbon::parse($request->updated_at)->setTimezone('UTC')->subHours(5);
        }
        if ($request->has('deleted_at')) {
            $data['deleted_at'] = Carbon::parse($request->deleted_at)->setTimezone('UTC')->subHours(5);
        }

        $inspeccionArea->update($data);
        return response()->json($inspeccionArea, 200);
    }

    public function destroy($id)
    {
        InspeccionArea::destroy($id);
        return response()->json(null, 204);
    }
}