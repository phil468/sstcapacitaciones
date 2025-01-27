<?php

namespace App\Http\Controllers\Api;

use App\Exports\InspeccionEppExport;
use App\Http\Controllers\Controller;
use App\Models\InspeccionesEpp;
use App\Models\DetallesEpp;
use App\Models\InspeccionEppOtros;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class InspeccionEppController extends Controller
{
    public function index()
    {
        return response()->json(
            InspeccionesEpp::with([
                'detalles', 
                'detalles.detalles_epp_otros',
                'inspector', 
                'area',
                'empresa',
                'otros'
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

        $inspeccion = InspeccionesEpp::create($data);
        
        if ($request->has('otros')) {
            foreach ($request->otros as $otro) {
                // $otro['id'] = (string) \Illuminate\Support\Str::uuid();
                $inspeccion->otros()->create($otro);
            }
        }

        if ($request->has('detalles')) {
            foreach ($request->detalles as $detalle) {
                // $detalle['id'] = (string) \Illuminate\Support\Str::uuid();
                $detalleModel = $inspeccion->detalles()->create($detalle);

                if (isset($detalle['detalles_epp_otros'])) {
                    foreach ($detalle['detalles_epp_otros'] as $detalleOtro) {
                        // $detalleOtro['id'] = (string) \Illuminate\Support\Str::uuid();
                        $detalleModel->detalles_epp_otros()->create($detalleOtro);
                    }
                }
            }
        }


        return response()->json($inspeccion->load([
            'detalles', 'detalles.detalles_epp_otros', 'inspector', 'area', 'empresa', 'otros'
        ]), 201);
    }

    public function show($id)
    {
        $inspeccion = InspeccionesEpp::with(
            ['detalles', 'detalles.detalles_epp_otros', 'inspector', 'area', 'empresa', 'otros']
        )->findOrFail($id);
    
        return response()->json($inspeccion, 200);
    }

    public function update(Request $request, $id)
    {
        $inspeccion = InspeccionesEpp::find($id);

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
        
        if ($request->has('otros')) {
            $otrosUUIDs = array_column($request->otros, 'id');
            // InspeccionEppOtros
            $inspeccion->otros()->whereNotIn('id', $otrosUUIDs)->delete();

            foreach ($request->otros as $otro) {
                $existingOtro = $inspeccion->otros()->where('id', $otro['id'])->first();
                if ($existingOtro) {
                    $existingOtro->update($otro);
                } else {
                    // $otro['id'] = (string) \Illuminate\Support\Str::uuid();
                    $inspeccion->otros()->create($otro);
                }
            }
        }

        if ($request->has('detalles')) {
            // Obtener los UUIDs de los detalles enviados en la solicitud
            $detallesUUIDs = array_column($request->detalles, 'id');

            // Eliminar los detalles que no están en la solicitud
            $inspeccion->detalles()->whereNotIn('id', $detallesUUIDs)->delete();

            // Actualizar o crear los detalles
            foreach ($request->detalles as $detalle) {
                $existingDetalle = DetallesEpp::where('id', $detalle['id'])->first();
                if ($existingDetalle) {
                    $existingDetalle->update($detalle);

                    if (isset($detalle['detalles_epp_otros'])) {
                        $detalles_epp_otrosUUIDs = array_column($detalle['detalles_epp_otros'], 'id');
                        $existingDetalle->detalles_epp_otros()->whereNotIn('id', $detalles_epp_otrosUUIDs)->delete();

                        foreach ($detalle['detalles_epp_otros'] as $detalleOtro) {
                            $existingDetalleOtro = $existingDetalle->detalles_epp_otros()->where('id', $detalleOtro['id'])->first();
                            if ($existingDetalleOtro) {
                                $existingDetalleOtro->update($detalleOtro);
                            } else {
                                // $detalleOtro['id'] = (string) \Illuminate\Support\Str::uuid();
                                $existingDetalle->detalles_epp_otros()->create($detalleOtro);
                            }
                        }
                    }
                } else {
                    // $detalle['id'] = (string) \Illuminate\Support\Str::uuid();
                    $detalleModel = $inspeccion->detalles()->create($detalle);

                    if (isset($detalle['detalles_epp_otros'])) {
                        foreach ($detalle['detalles_epp_otros'] as $detalleOtro) {
                            // $detalleOtro['id'] = (string) \Illuminate\Support\Str::uuid();
                            $detalleModel->detalles_epp_otros()->create($detalleOtro);
                        }
                    }
                }
            }
        }

        return response()->json($inspeccion->load(
            ['detalles', 'detalles.detalles_epp_otros', 'inspector', 'area', 'empresa', 'otros']
        ), 200);
    }

    public function destroy($id)
    {
        InspeccionesEpp::destroy($id);
        return response()->json(null, 204);
    }
    
    public function descargarReporte($id)
    {
        $inspeccion = InspeccionesEpp::findOrFail($id);
        $exporter = new InspeccionEppExport($inspeccion);
        $filePath = $exporter->export();

        return Response::download($filePath, 'inspeccion_epp.xlsx')->deleteFileAfterSend(true);
    }

}