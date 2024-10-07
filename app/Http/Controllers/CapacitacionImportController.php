<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CapacitacionesImport;
use App\Models\Capacitacione;
use App\Models\Empresa;
use App\Models\Modalidade;
use App\Models\Personal;
use App\Models\Sede;
use App\Models\Status;
use App\Models\Tema;
use App\Models\TipoDeCapacitacione;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class CapacitacionImportController extends Controller
{
    public function showImportForm()
    {
        return view('livewire.capacitaciones.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);
    
        // $path = $request->file('file')->getRealPath();

        $data = Excel::toArray(new CapacitacionesImport, $request->file('file'));
        $data = $this->trimArray($data);

        foreach ($data as &$sheet) {
            foreach ($sheet as &$row) {
                if (isset($row['fecha_de_inicio'])) {
                    // dd($row['fecha_de_inicio']);
                    $row['fecha_de_inicio'] = $this->convertExcelDateToDatetimeLocal($row['fecha_de_inicio']);
                }
                if (isset($row['fecha_de_fin'])) {
                    $row['fecha_de_fin'] = $this->convertExcelDateToDatetimeLocal($row['fecha_de_fin']);
                }
            }
        }
    
        // Pasar los datos a la vista para la vista previa
        return view('livewire.capacitaciones.preview-import', ['data' => $data[0]]);
    }

    private function trimArray(array $array): array
    {
        return array_map(function($item) {
            if (is_array($item)) {
                return $this->trimArray($item);
            }
            return is_string($item) ? strtoupper(trim($item)) : $item;
        }, $array);
    }

    public function convertExcelDateToDatetimeLocal($excelDate)
    {
        $dateTime = Date::excelToDateTimeObject($excelDate);
        return $dateTime->format('Y-m-d\TH:i');
    }

    public function confirmImport(Request $request)
    {
        $data = $request->input('data');
        $result = [];
    
        foreach ($data as $row) {
            try {
                // dd( $row['fecha_de_inicio']);
                $empresa = Empresa::firstOrCreate(['name'=> $row['empresa']],['estado' => 1]);
                $tipoCapacitacion = TipoDeCapacitacione::firstOrCreate(['name'=> $row['tipo_de_capacitacion']],['estado' => 1]);
                $tema = Tema::firstOrCreate(['name'=> $row['tema']],['estado' => 1]);
                $sede = Sede::firstOrCreate(['name'=> $row['sede']],['estado' => 1]);
    
                $modalidad = Modalidade::where('name', $row['modalidad'])->first();
                $estado = Status::where('name', $row['estado'])->first();
    
                // Validar modalidad y expositor
                $expositorId = null;
                $nombreExpositorExterno = null;
                if ($row['modalidad'] == 'INTERNA') {
                    $expositor = Personal::where('dni', $row['dni_de_expositor_interno'])->first();
                    if ($expositor) {
                        $expositorId = $expositor->id;
                    }
                } elseif ($row['modalidad'] == 'EXTERNA') {
                    $nombreExpositorExterno = $row['nombre_de_expositor_externo'];
                }
                // // dd($row['identificador_unico']);
                // if (isset($row['identificador_unico'])) {
                //     $identificador = $row['identificador_unico'];
                // } else {
                //     $identificador = $this->generateUniqueUuid();
                // }
                if(Capacitacione::where('identificador_unico', $row['identificador_unico'])->where(
                    'es_aula_virtual', $row['es_aula_virtual'] ?? false
                ) ->exists()) {
                    $edicion_creacion = 'Editado';
                }
                else {
                    $edicion_creacion = 'Ingresado';
                }

                // Crear o actualizar la capacitación
                Capacitacione::updateOrCreate(
                    [
                        'identificador_unico' => $row['identificador_unico'],
                        'es_aula_virtual' => $row['es_aula_virtual'] ?? false,
                    ],
                    [
                        'empresa_id' => $empresa->id,
                        'capacitaciones_tipo_id' => $tipoCapacitacion->id,
                        'tema_id' => $tema->id,
                        'sede_id' => $sede->id,
                        'fecha_inicio' => $row['fecha_de_inicio'],
                        'fecha_fin' => $row['fecha_de_fin'],
                        'modalidad_id' => $modalidad->id,
                        'expositor_id' => $expositorId,
                        'expositor_externo' => $row['modalidad'] == 'EXTERNA'? true : false,
                        'nombre_expositor_externo' => $nombreExpositorExterno,
                        'activo' => $row['habilitada'] == 'SI' ? 1 : 0,
                        'status_id' => $estado->id,
                        'cantidad_de_sesiones' => $row['cantidad_de_sesiones'],
                    ]
                );
    
                $result[] = ['row' => $row, 'estado_importacion' =>$edicion_creacion, 'status' => 'success', 'message' => 'Capacitación importada correctamente'];
            } catch (\Exception $e) {
                $result[] = ['row' => $row, 'estado_importacion' =>'Error', 'status' => 'error', 'message' => $e->getMessage()];
            }
        }
    
        return view('livewire.capacitaciones.result-import', ['result' => $result]);
    }
}