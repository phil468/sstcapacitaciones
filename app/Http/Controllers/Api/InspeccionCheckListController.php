<?php

namespace App\Http\Controllers\Api;

use App\Exports\InspeccionCheckListExport;
use App\Exports\InspeccionEppExport;
use App\Http\Controllers\Controller;
use App\Models\DetallesCheckList;
use App\Models\InspeccionCheckList;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class InspeccionCheckListController extends Controller
{
    public function index()
    {
        return response()->json(
            InspeccionCheckList::with([
                'detalles', 
                'inspector', 
                'area',
                'empresa',
            ])->get(), 200);
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

        // Asegúrate de que el campo 'id' esté presente y sea único
        if (!$request->has('id')) {
            return response()->json(['message' => 'El campo id es obligatorio'], 422);
        }

        $inspeccion = InspeccionCheckList::create($data);

        if ($request->has('detalles')) {
            // foreach ($request->detalles as $detalle) {
                $detalleModel = $inspeccion->detalles()->create($request->detalles);
            // }
        }

        return response()->json($inspeccion->load([
            'detalles', 
            'inspector', 
            'area',
            'empresa',
        ]), 201);
    }

    public function show($id)
    {
        $inspeccion = InspeccionCheckList::with([
            'detalles', 
            'inspector', 
            'area',
            'empresa',
            ]
        )->findOrFail($id);
    
        return response()->json($inspeccion, 200);
    }

    public function update(Request $request, $id)
    {
        $inspeccion = InspeccionCheckList::find($id);

        if (!$inspeccion) {
            return $this->store($request);
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
            // Obtener los UUIDs de los detalles enviados en la solicitud
            $detallesUUIDs = array_column($request->detalles, 'id');

            // Eliminar los detalles que no están en la solicitud
            $inspeccion->detalles()->whereNotIn('id', $detallesUUIDs)->delete();

            // Actualizar o crear los detalles
            // foreach ($request->detalles as $detalle) {
                $existingDetalle =  DetallesCheckList::where('id', $request->detalles['id'])->first();
                if ($existingDetalle) {
                    $existingDetalle->update($request->detalles);
                } else {
                    // $detalle['id'] = (string) \Illuminate\Support\Str::uuid();
                    $detalleModel = $inspeccion->detalles()->create($request->detalles);


                }
            // }
        }

        return response()->json($inspeccion->load(
            [
                'detalles', 
                'inspector', 
                'area',
                'empresa',
            ]

        ), 200);
    }

    public function destroy($id)
    {
        InspeccionCheckList::destroy($id);
        return response()->json(null, 204);
    }
    
    public function descargarReporte($id)
    {
        $inspeccion = InspeccionCheckList::findOrFail($id);
        $exporter = new InspeccionCheckListExport($inspeccion);
        $filePath = $exporter->export();

        return Response::download($filePath, 'inspeccion_check_list_'.$inspeccion->id.'.xlsx')->deleteFileAfterSend(true);
    }

}