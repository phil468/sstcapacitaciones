<?php

namespace App\Http\Controllers\Api;

use App\Exports\InspeccionExtintoresExport;
use App\Http\Controllers\Controller;
use App\Models\InspeccionExtintor;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exports\InspeccionExtintorExport;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

class InspeccionExtintorController extends Controller
{
    public function index()
    {
        return InspeccionExtintor::with(['detalles'])->get();
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

        $inspeccion = InspeccionExtintor::create($data);

        if ($request->has('detalles')) {
            foreach ($request->detalles as $detalle) {
                $inspeccion->detalles()->create($detalle);
            }
        }

        return response()->json($inspeccion->load(['detalles']), 201);
    }

    public function show($id)
    {
        return InspeccionExtintor::with(['detalles'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $inspeccion = InspeccionExtintor::find($id);

        if (!$inspeccion) {
            return response()->json(['error' => 'Inspeccion not found'], 404);
        }

        $data = $request->all();

        if ($request->has('updated_at')) {
            $data['updated_at'] = Carbon::parse($request->updated_at)->setTimezone('UTC')->subHours(5);
        }
        if ($request->has('deleted_at')) {
            $data['deleted_at'] = Carbon::parse($request->deleted_at)->setTimezone('UTC')->subHours(5);
        }

        $inspeccion->update($data);

        if ($request->has('detalles')) {
            $inspeccion->detalles()->delete();
            foreach ($request->detalles as $detalle) {
                $inspeccion->detalles()->create($detalle);
            }
        }

        return response()->json($inspeccion->load(['detalles']), 200);
    }

    public function destroy($id)
    {
        InspeccionExtintor::destroy($id);
        return response()->json(null, 204);
    }

    public function descargarReporte($id)
    {
        $inspeccion = InspeccionExtintor::findOrFail($id);
        $exporter = new InspeccionExtintoresExport($inspeccion);
        $filePath = $exporter->export();

        return Response::download($filePath, 'inspecciones_extintores.xlsx')->deleteFileAfterSend(true);
    }
}