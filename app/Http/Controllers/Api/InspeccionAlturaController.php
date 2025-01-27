<?php
namespace App\Http\Controllers\Api;

use App\Exports\InspeccionLucesExport;
use App\Http\Controllers\Controller;
use App\Models\DetalleInspeccionAltura;
// use App\Models\Inspecciones\Luces\ParteLuzEmergencia;
use App\Models\InspeccionAltura;
// use App\Models\Inspecciones\Luces\InspeccionLuzResponsable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class InspeccionAlturaController extends Controller
{
    public function index()
    {
        return response()->json(
            InspeccionAltura::with(
                'detalles',
                'inspector', 
                'area',
                'empresa',
                'detalles.area',
                )->get(), 200);
    }

    public function show($id)
    {
        $inspeccion = InspeccionAltura::with(
                'detalles', 
                'inspector', 
                'area',
                'empresa',
                'detalles.area',
                )->findOrFail($id);
        return response()->json($inspeccion, 200);
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

        if (!$request->has('id')) {
            return response()->json(['message' => 'El campo id es obligatorio'], 422);
        }

        $inspeccion = InspeccionAltura::create($data);

        if ($request->has('detalles')) {
            foreach ($request->detalles as $detalle) {
                $detalleModel = $inspeccion->detalles()->create($detalle);
                
                // if (isset($detalle['partes'])) {
                //     $detalleModel->partes()->sync($detalle['partes']);
                // }
            }
        }        

        // if ($request->has('responsables')) {
        //     foreach ($request->responsables as $responsable) {
        //         $inspeccion->responsables()->create($responsable);
        //     }
        // }

        return response()->json($inspeccion->load([
            'empresa','area','inspector',
            'detalles','detalles.area'

        ]), 201);

    }

    public function update(Request $request, $id)
    {
        $inspeccion = InspeccionAltura::find($id);
        
        if (!$inspeccion) {
            $this->store($request);
            return;
        }

        $data = $request->all();
        // $inspeccion->update($request->all());

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
            // DetalleInspeccionAltura::whereNotIn('id', $detallesUUIDs)->delete();
            $inspeccion->detalles()->whereNotIn('id', $detallesUUIDs)->delete();

            // Actualizar o crear los detalles
            foreach ($request->detalles as $detalle) {
                $existingDetalle = 
                DetalleInspeccionAltura::where('id', $detalle['id'])->first();
                if ($existingDetalle) {
                    $existingDetalle->update($detalle);
                    // if (isset($detalle['partes'])) {
                    //     $existingDetalle->partes()->sync($detalle['partes']);
                    // }
                } else {
                    $detalleModel = $inspeccion->detalles()->create($detalle);
                    // if (isset($detalle['partes'])) {
                    //     $detalleModel->partes()->sync($detalle['partes']);
                    // }
                }
            }
        }

        // if ($request->has('responsables')) {
        //     // Obtener los UUIDs de los detalles enviados en la solicitud
        //     $UUIDs = array_column($request->responsables, 'id');

        //     // Eliminar los detalles que no están en la solicitud
        //     InspeccionLuzResponsable::whereNotIn('id', $UUIDs)->delete();

        //     // Actualizar o crear los detalles
        //     foreach ($request->responsables as $responsable) {
        //         $existingResponsable = 
        //         InspeccionLuzResponsable::where('id', $responsable['id'])->first();
        //         if ($existingDetalle) {
        //             $existingResponsable->update($responsable);
        //         } else {
        //             $responsableModel = $inspeccion->responsables()->create($responsable);
        //         }
        //     }
        // }
        return response()->json($inspeccion->load(
            [
                'empresa','area','inspector',
            'detalles','detalles.area'
            ]
        ), 200);
    }

    public function destroy($id)
    {
        $inspeccion = InspeccionAltura::findOrFail($id);
        $inspeccion->delete();

        return response()->json(null, 204);
    }   

    // public function descargarReporte($id)
    // {
    //     $inspeccion = InspeccionAltura::findOrFail($id);
    //     $exporter = new InspeccionAlturaExport($inspeccion);
    //     $filePath = $exporter->export();

    //     return Response::download($filePath, 'inspecciones_luces.xlsx')->deleteFileAfterSend(true);
    // }

}