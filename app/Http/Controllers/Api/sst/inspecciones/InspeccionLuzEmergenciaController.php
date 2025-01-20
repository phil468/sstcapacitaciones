<?php
namespace App\Http\Controllers\Api\sst\inspecciones;

use App\Exports\InspeccionLucesExport;
use App\Http\Controllers\Controller;
// use App\Models\Inspecciones\Luces\ParteLuzEmergencia;
use App\Models\Inspecciones\Luces\InspeccionLuzEmergencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class InspeccionLuzEmergenciaController extends Controller
{
    public function index()
    {
        return response()->json(InspeccionLuzEmergencia::with('detalles', 'inspectores', 'responsables')->get(), 200);
    }

    public function show($id)
    {
        $inspeccion = InspeccionLuzEmergencia::with('detalles', 'inspectores', 'responsables')->findOrFail($id);
        return response()->json($inspeccion, 200);
    }

    public function store(Request $request)
    {
        // $this->validate($request, [
        //     'empresa_id' => 'required|exists:empresas,id',
        //     'razon_social' => 'required',
        //     'ruc' => 'required',
        //     'domicilio' => 'required',
        //     'actividad_economica' => 'required',
        //     'num_trabajadores' => 'required|numeric',
        //     'fecha_hora_inspeccion' => 'required|date',
        //     'lugar' => 'required',
        //     'inspectores' => 'required|array',
        //     'responsables' => 'required|array'
        // ]);

        $inspeccion = InspeccionLuzEmergencia::create($request->all());
        
        if ($request->has('detalles')) {
            foreach ($request->detalles as $detalle) {
                $inspeccion->detalles()->create($detalle);
            }
        }
        // $inspeccion->inspectores()->sync($request->inspectores);
        // $inspeccion->responsables()->sync($request->responsables);

        return response()->json($inspeccion, 201);
    }

    public function update(Request $request, $id)
    {
        // $this->validate($request, [
        //     'empresa_id' => 'required|exists:empresas,id',
        //     'razon_social' => 'required',
        //     'ruc' => 'required',
        //     'domicilio' => 'required',
        //     'actividad_economica' => 'required',
        //     'num_trabajadores' => 'required|numeric',
        //     'fecha_hora_inspeccion' => 'required|date',
        //     'lugar' => 'required',
        //     'inspectores' => 'required|array',
        //     'responsables' => 'required|array'
        // ]);

        $inspeccion = InspeccionLuzEmergencia::findOrFail($id);
        $inspeccion->update($request->all());

        // $inspeccion->inspectores()->sync($request->inspectores);
        // $inspeccion->responsables()->sync($request->responsables);

        return response()->json($inspeccion, 200);
    }

    public function destroy($id)
    {
        $inspeccion = InspeccionLuzEmergencia::findOrFail($id);
        $inspeccion->delete();

        return response()->json(null, 204);
    }   

    public function descargarReporte($id)
    {
        $inspeccion = InspeccionLuzEmergencia::findOrFail($id);
        $exporter = new InspeccionLucesExport($inspeccion);
        $filePath = $exporter->export();

        return Response::download($filePath, 'inspecciones_luces.xlsx')->deleteFileAfterSend(true);
    }

}