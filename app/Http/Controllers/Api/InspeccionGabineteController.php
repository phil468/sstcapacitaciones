<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InspeccionesGabinete;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exports\InspeccionGabineteExport;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

class InspeccionGabineteController extends Controller
{
    public function index()
    {
        // dd(InspeccionesGabinete::with([
        //     'detalles', 
        //     'detalles.gabinete',
        //     'inspector', 
        //     'area'
        //     ])->get());
        return InspeccionesGabinete::with([
            'detalles', 
            'detalles.gabinete',
            'inspector', 
            'area'
            ])->get();
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

        $inspeccion = InspeccionesGabinete::create($data);

        if ($request->has('detalles')) {
            foreach ($request->detalles as $detalle) {
                $inspeccion->detalles()->create($detalle);
            }
        }

        return response()->json($inspeccion->load(['detalles', 'detalles.gabinete']), 201);
    }

    public function show($id)
    {
        return InspeccionesGabinete::with(['detalles', 'detalles.gabinete'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $inspeccion = InspeccionesGabinete::find($id);

        if (!$inspeccion) {
            $this->store($request);
            return;

            // return response()->json(['error' => 'Inspeccion not found'], 404);
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

        return response()->json($inspeccion->load(['detalles', 'detalles.gabinete']), 200);
    }

    public function destroy($id)
    {
        InspeccionesGabinete::destroy($id);
        return response()->json(null, 204);
    }

    public function descargarReporte($id)
    {
        $inspeccion = InspeccionesGabinete::findOrFail($id);
        $exporter = new InspeccionGabineteExport($inspeccion);
        $filePath = $exporter->export();

        return Response::download($filePath, 'inspecciones_gabinetes.xlsx')->deleteFileAfterSend(true);
    }
}