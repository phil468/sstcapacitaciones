<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmpresaController extends Controller
{
    public function index()
    {
        return Empresa::all();
    }

    public function store(Request $request)
    {
        $data = $request->all();

        // Convertir la fecha updated_at a UTC-5 si está presente en el request
        if ($request->has('updated_at')) {
            $data['created_at'] = Carbon::parse($request->updated_at)->setTimezone('UTC')->subHours(5);
            $data['updated_at'] = Carbon::parse($request->updated_at)->setTimezone('UTC')->subHours(5);
        }

        $empresa = Empresa::create($request->all());        

        $empresa->save();
        return response()->json($empresa, 201);
    }

    public function show($id)
    {
        return Empresa::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $empresa = Empresa::find($id);

        if (!$empresa) {
            return response()->json(['error' => 'Empresa not found'], 404);
        }

        // Convertir las fechas a instancias de Carbon
        // $requestDate = Carbon::parse($request->updated_at)->setTimezone('UTC')->subHours(5);
        // $empresaDate = Carbon::parse($empresa->updated_at);
        // dd($requestDate, $empresaDate);
        // Convertir las fechas a timestamps para comparar
        // $requestUpdatedAt = Carbon::parse($request->updated_at)->setTimezone('America/Lima');
        // $empresaUpdatedAt = $empresa->updated_at;

        // Convertir la fecha del request a America/Lima
        $requestDate = Carbon::parse($request->updated_at)->setTimezone('America/Lima');
        $empresaDate = Carbon::parse($empresa->updated_at);
        // dd(
        //     Carbon::parse($request->updated_at)->setTimezone('UTC')->subHours(5)->toDate(),
        //     $requestUpdatedAt, 
        //     $empresaUpdatedAt);
        // Comparar timestamps para resolver conflictos
        // return response()->json([$request->updated_at , $empresa->updated_at]);
        // return response()->json([$requestUpdatedAt , $empresaUpdatedAt]);
        // return response()->json([$requestDate > $empresaDate]);
        // Comparar timestamps para resolver conflictos
        if ($requestDate->greaterThanOrEqualTo($empresaDate)) {
            $data = $request->all();

            // Convertir la fecha updated_at a UTC-5 si está presente en el request
            if ($request->has('updated_at')) {
                $data['updated_at'] = Carbon::parse($request->updated_at)->setTimezone('America/Lima');
            }

            if ($request->has('deleted_at')) {
                $data['deleted_at'] = Carbon::parse($request->updated_at)->setTimezone('America/Lima');
            }

            $empresa->update($data);

            $empresa->save();

            return response()->json($empresa, 200);
        } else {
            return response()->json([
                'error' => 'Conflict detected',
                'message' => 'The provided updated_at is older than the current updated_at in the database.',
                'provided_updated_at' => $requestDate,
                'current_updated_at' => $empresaDate
            ], 409);
        }
    }

    public function destroy($id)
    {
        Empresa::destroy($id);
        return response()->json(null, 204);
    }
}