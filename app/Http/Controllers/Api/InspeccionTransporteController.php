<?php

namespace App\Http\Controllers\Api;

use App\Exports\InspeccionTransporteExport;
use App\Http\Controllers\Controller;
use App\Models\InspeccionTransporte;
use App\Models\InspeccionTransporteDocumentacionConductor;
use App\Models\InspeccionTransporteDocumentacionVehiculo;
use App\Models\InspeccionTransporteEquipoPrimerosAuxilios;
use App\Models\InspeccionTransporteEquipoSeguridad;
use App\Models\InspeccionTransporteEstadoVehiculo;
use App\Models\InspeccionTransporteFuncionamientoVehiculo;
use App\Models\InspeccionTransporteInformacionConductor;
use App\Models\InspeccionTransporteResponsables;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class InspeccionTransporteController extends Controller
{
    public function index()
    {
        return response()->json(
            InspeccionTransporte::with([
                'inspector', 
                'area',
                'empresa',
                'informacionConductor',
                'funcionamientoVehiculo',
                'estadoVehiculo',
                'documentacionVehiculo',
                'documentacionConductor',
                'equipoSeguridad',
                'equipoPrimerosAuxilios',

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

        $inspeccion = InspeccionTransporte::create($data);

        if ($request->has('informacionConductor')) {
                $detalleModel = $inspeccion->informacionConductor()->create($request->informacionConductor);
        }
        if ($request->has('funcionamientoVehiculo')) {
                $detalleModel = $inspeccion->funcionamientoVehiculo()->create($request->funcionamientoVehiculo);
        }
        if ($request->has('estadoVehiculo')) {
                $detalleModel = $inspeccion->estadoVehiculo()->create($request->estadoVehiculo);
        }
        if ($request->has('documentacionVehiculo')) {
                $detalleModel = $inspeccion->documentacionVehiculo()->create($request->documentacionVehiculo);
        }
        if ($request->has('documentacionConductor')) {
                $detalleModel = $inspeccion->documentacionConductor()->create($request->documentacionConductor);
        }
        if ($request->has('equipoSeguridad')) {
                $detalleModel = $inspeccion->equipoSeguridad()->create($request->equipoSeguridad);
        }
        if ($request->has('equipoPrimerosAuxilios')) {
                $detalleModel = $inspeccion->equipoPrimerosAuxilios()->create($request->equipoPrimerosAuxilios);
        }

        if ($request->has('responsables')) {
            foreach ($request->responsables as $responsable) {
                $inspeccion->responsables()->create($responsable);
            }
        }

        return response()->json($inspeccion->load([
            'inspector', 
            'area',
            'empresa',
            'informacionConductor',
            'funcionamientoVehiculo',
            'estadoVehiculo',
            'documentacionVehiculo',
            'documentacionConductor',
            'equipoSeguridad',
            'equipoPrimerosAuxilios',
        ]), 201);
    }

    public function show($id)
    {
        $inspeccion = InspeccionTransporte::with([
            'inspector', 
            'area',
            'empresa',
            'informacionConductor',
            'funcionamientoVehiculo',
            'estadoVehiculo',
            'documentacionVehiculo',
            'documentacionConductor',
            'equipoSeguridad',
            'equipoPrimerosAuxilios',
            ]
        )->findOrFail($id);
    
        return response()->json($inspeccion, 200);
    }

    public function update(Request $request, $id)
    {
        // dd('0');
        $inspeccion = InspeccionTransporte::find($id);

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


        if ($request->has('informacionConductor')) {
                $existingDetalle =  InspeccionTransporteInformacionConductor::where('id', $request->informacionConductor['id'])->first();
                if ($existingDetalle) {
                    $existingDetalle->update($request->informacionConductor);
                } else {
                    $detalleModel = $inspeccion->informacionConductor()->create($request->informacionConductor);
                }
        }

        if ($request->has('funcionamientoVehiculo')) {
                $existingDetalle =  InspeccionTransporteFuncionamientoVehiculo::where('id', $request->funcionamientoVehiculo['id'])->first();
                if ($existingDetalle) {
                    $existingDetalle->update($request->funcionamientoVehiculo);
                } else {
                    $detalleModel = $inspeccion->funcionamientoVehiculo()->create($request->funcionamientoVehiculo);
                }
        }

        if ($request->has('estadoVehiculo')) {
                $existingDetalle =  InspeccionTransporteEstadoVehiculo::where('id', $request->estadoVehiculo['id'])->first();
                if ($existingDetalle) {
                    $existingDetalle->update($request->estadoVehiculo);
                } else {
                    $detalleModel = $inspeccion->estadoVehiculo()->create($request->estadoVehiculo);
                }
        }

        if ($request->has('documentacionVehiculo')) {
                $existingDetalle =  InspeccionTransporteDocumentacionVehiculo::where('id', $request->documentacionVehiculo['id'])->first();
                if ($existingDetalle) {
                    $existingDetalle->update($request->documentacionVehiculo);
                } else {
                    $detalleModel = $inspeccion->documentacionVehiculo()->create($request->documentacionVehiculo);
                }
        }

        if ($request->has('documentacionConductor')) {
                $existingDetalle =  InspeccionTransporteDocumentacionConductor::where('id', $request->documentacionConductor['id'])->first();
                if ($existingDetalle) {
                    $existingDetalle->update($request->documentacionConductor);
                } else {
                    $detalleModel = $inspeccion->documentacionConductor()->create($request->documentacionConductor);
                }
        }

        if ($request->has('equipoSeguridad')) {
                $existingDetalle =  InspeccionTransporteEquipoSeguridad::where('id', $request->equipoSeguridad['id'])->first();
                if ($existingDetalle) {
                    $existingDetalle->update($request->equipoSeguridad);
                } else {
                    $detalleModel = $inspeccion->equipoSeguridad()->create($request->equipoSeguridad);
                }
        }

        if ($request->has('equipoPrimerosAuxilios')) {
                $existingDetalle =  InspeccionTransporteEquipoPrimerosAuxilios::where('id', $request->equipoPrimerosAuxilios['id'])->first();
                if ($existingDetalle) {
                    $existingDetalle->update($request->equipoPrimerosAuxilios);
                } else {
                    $detalleModel = $inspeccion->equipoPrimerosAuxilios()->create($request->equipoPrimerosAuxilios);
                }
        }
        
        if ($request->has('responsables')) {
            // Obtener los UUIDs de los detalles enviados en la solicitud
            $UUIDs = array_column($request->responsables, 'id');

            // Eliminar los detalles que no están en la solicitud
            // InspeccionLuzResponsable::whereNotIn('id', $UUIDs)->delete();
            $inspeccion->responsables()->whereNotIn('id', $UUIDs)->delete();

            // Actualizar o crear los detalles
            foreach ($request->responsables as $responsable) {
                $existingResponsable = 
                InspeccionTransporteResponsables::where('id', $responsable['id'])->first();
                if ($existingDetalle) {
                    $existingResponsable->update($responsable);
                } else {
                    $responsableModel = $inspeccion->responsables()->create($responsable);
                }
            }
        }

        return response()->json($inspeccion->load(
            [
                'inspector', 
                'area',
                'empresa',
                'informacionConductor',
                'funcionamientoVehiculo',
                'estadoVehiculo',
                'documentacionVehiculo',
                'documentacionConductor',
                'equipoSeguridad',
                'equipoPrimerosAuxilios',
            ]

        ), 200);
    }

    public function destroy($id)
    {
        InspeccionTransporte::destroy($id);
        return response()->json(null, 204);
    }
    
    public function descargarReporte($id)
    {
        $inspeccion = InspeccionTransporte::findOrFail($id);
        $exporter = new InspeccionTransporteExport($inspeccion);
        $filePath = $exporter->export();

        return Response::download($filePath, 'inspeccion_transporte_'.$inspeccion->id.'.xlsx')->deleteFileAfterSend(true);
    }

}