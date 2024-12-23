<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Inspeccione;
use App\Models\Inspectore;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exports\InspeccionExport;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

class InspeccionController extends Controller
{
    public function index()
    {
        return Inspeccione::with([
            'areas',
            'empresa',
            'responsables_inspeccion',
            'responsables_area',
            'detalles',
            'responsables_registro',
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

        $inspeccion = Inspeccione::create($data);

        // Manejar las relaciones
        if ($request->has('areas')) {
            // foreach ($request->areas as $area_id) {
                $inspeccion->areas()->attach($request->areas);
            // }
        }

        if ($request->has('responsables_inspeccion')) {
            $inspeccion->responsables_inspeccion()->attach($request->responsables_inspeccion);
        }

        if ($request->has('responsables_area')) {
            $inspeccion->responsables_area()->attach($request->responsables_area);
        }

        if ($request->has('responsables_registro')) {
            foreach ($request->responsables_registro as $responsable) {
                $inspeccion->responsables_registro()->create($responsable);
            }
        }

        if ($request->has('detalles')) {
            foreach ($request->detalles as $detalle) {
                $inspeccion->detalles()->create($detalle);
            }
        }

        return response()->json($inspeccion->load(['areas', 'empresa', 'responsables_inspeccion', 'responsables_area', 'detalles']), 201);
    }

    public function show($id)
    {
        return Inspeccione::with(['areas', 'responsables_inspeccion', 'responsables_area', 'detalles'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $inspeccion = Inspeccione::find($id);

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

        // Manejar las relaciones
        if ($request->has('areas')) {
            $inspeccion->areas()->sync($request->areas);
        }

        if ($request->has('responsables_inspeccion')) {
            $inspeccion->responsables_inspeccion()->sync($request->responsables_inspeccion);
        }

        if ($request->has('responsables_area')) {
            $inspeccion->responsables_area()->sync($request->responsables_area);
        }

        if ($request->has('responsables_registro')) {
            $inspeccion->responsables_registro()->delete();
            foreach ($request->responsables_registro as $responsable) {
                $inspeccion->responsables_registro()->create($responsable);
            }
        }

        if ($request->has('detalles')) {
            $inspeccion->detalles()->delete();
            foreach ($request->detalles as $detalle) {
                $inspeccion->detalles()->create($detalle);
            }
        }

        return response()->json($inspeccion->load(['areas', 'empresa', 'responsables_inspeccion', 'responsables_area', 'detalles']), 200);
    }

    public function destroy($id)
    {
        Inspeccione::destroy($id);
        return response()->json(null, 204);
    }

    public function descargarReporte($id)
    {
        $inspeccion = Inspeccione::findOrFail($id);
        // return Excel::download(new InspeccionExport($inspeccion), 'reporte_inspeccion.xlsx');

        $exporter = new InspeccionExport($inspeccion);
        $filePath = $exporter->export();

        return Response::download($filePath, 'inspecciones.xlsx')->deleteFileAfterSend(true);

    }
}
