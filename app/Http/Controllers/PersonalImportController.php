<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PersonalImport;
use App\Models\Capacitacione;
use App\Models\Empresa;
use App\Models\Gerencia;
use App\Models\Sede;
use App\Models\Area;
use App\Models\CapacitacionHasPersonal;
use App\Models\Personal;
use Illuminate\Support\Facades\DB;

class PersonalImportController extends Controller
{
    public function showImportForm()
    {
        return view('livewire.capacitaciones.personal.import-form');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);

        $path = $request->file('file')->store('temp');
        $data = Excel::toArray(new PersonalImport, $path);

        // Aplicar trim a todos los campos del array
        $data = $this->trimArray($data);

        // Validar y preparar los datos para la vista previa
        $previewData = $this->preparePreviewData($data);

        return view(
            'livewire.capacitaciones.personal.preview-import', 
            [
                'data' => $previewData, 
                'empresas' => Empresa::all(), 
                'gerencias' => Gerencia::all(), 
                'sedes' => Sede::all(), 
                'areas' => Area::all()
            ]
        );
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

    private function preparePreviewData(array $data)
    {
        $previewData = [];
        foreach ($data as $sheet) {
            foreach ($sheet as $row) {
                $row['valid'] = true;
                $row['errors'] = [];

                // Validar identificador unico de capacitacion
                if (!Capacitacione::where('identificador_unico', $row['identificador_unico_de_capacitacion'])->where('es_aula_virtual',0)->exists()) {
                    $row['valid'] = false;
                    $row['errors'][] = "CAPACITACIÓN no existe";
                }

                // Validar dni de personal
                if (strlen($row['dni_de_personal']) != 8 || !is_numeric($row['dni_de_personal'])) {
                    $row['valid'] = false;
                    $row['errors'][] = "DNI de personal no válido";
                    $row['nombre_de_personal'] = 'No encontrado';
                } else {
                    // Consultar a NISIRA
                    $personal = $this->actualizarPersonalNisira($row['dni_de_personal']);
                    if (!$personal['res']) {
                        $row['valid'] = false;
                        $row['errors'][] = $personal['message'];
                        $row['nombre_de_personal'] = 'No encontrado';
                    } else {
                        $personal = Personal::where('dni',$row['dni_de_personal'])->first();
                        $row['nombre_de_personal'] = $personal->name;
                        $row['empresa_de_personal'] = $personal->empresa->name??null;
                        $row['gerencia_de_personal'] = $personal->gerencia->name??null;
                        $row['sede_de_personal'] = $personal->sede->name??null;
                        $row['area_de_personal'] = $personal->area->name??null;
                    }
                }

                // Validar empresa
                if (!empty($row['empresa']) && !Empresa::where('name', $row['empresa'])->exists()) {
                    $row['new_empresa'] = true;
                }

                // Validar gerencia
                if (!empty($row['gerencia']) && !Gerencia::where('name', $row['gerencia'])->exists()) {
                    $row['new_gerencia'] = true;
                }

                // Validar sede
                if (!empty($row['sede']) && !Sede::where('name', $row['sede'])->exists()) {
                    $row['new_sede'] = true;
                }

                // Validar area
                if (!empty($row['area']) && !Area::where('name', $row['area'])->exists()) {
                    $row['new_area'] = true;
                }

                $previewData[] = $row;
            }
        }
        return $previewData;
    }

    public function confirmImport(Request $request)
    {
        $data = $request->input('data');
        // dd($data);
        $importResult = [];
    
        DB::transaction(function () use ($data, &$importResult) {
            foreach ($data as $row) {
                $result = [
                    'row' => $row,
                    'status' => 'success',
                    'estado_importacion' => 'Importado',
                    'message' => ''
                ];
    
                if ($row['valid']) {
                    try {

                        $personal = Personal::where('dni', $row['dni_de_personal'])->first();

                        // Insertar o actualizar empresa
                        if (!empty($row['empresa'])) {
                            $empresa = Empresa::firstOrCreate(['name'=> $row['empresa']],['estado' => 1])->id;
                        } else {
                            $empresa = $personal->empresa->id ?? null;
                        }
    
                        // Insertar o actualizar gerencia
                        if (!empty($row['gerencia'])) {
                            $gerencia = Gerencia::firstOrCreate(['name'=> $row['gerencia']],['estado' => 1])->id;
                        } else {
                            $gerencia = $personal->gerencia->id ?? null;
                        }
    
                        // Insertar o actualizar sede
                        if (!empty($row['sede'])) {
                            $sede = Sede::firstOrCreate(['name'=> $row['sede']],['estado' => 1])->id;
                        } else {
                            $sede = $personal->sede->id ?? null;
                        }
    
                        // Insertar o actualizar area
                        if (!empty($row['area'])) {
                            $area = Area::firstOrCreate(['name'=> $row['area']],['estado' => 1])->id;
                        } else {
                            $area = $personal->area->id ?? null;
                        }
    
                        // Insertar o actualizar personal
                        // $personal = Personal::updateOrCreate(
                        //     ['dni' => $row['dni_de_personal']],
                        //     ['nombre' => $row['nombre_de_personal']]
                        // );
                        
                        $capacitacion_id = Capacitacione::where('identificador_unico', $row['identificador_unico_de_capacitacion'])->where('es_aula_virtual',false)->first()->id;

                        // Insertar o actualizar capacitacion_has_personal
                        if (CapacitacionHasPersonal::where(['personal_id' => $personal->id, 'capacitacion_id' => $capacitacion_id])
                            ->exists()) {
                            $result['message'] = 'Actualizado';
                        } else {
                            $result['message'] = 'Insertado';
                        }

                        CapacitacionHasPersonal::updateOrCreate(
                            [
                                'capacitacion_id' => $capacitacion_id,
                                'personal_id' => $personal->id
                            ],
                            [
                                'empresa' => $empresa??null,
                                'gerencia' => $gerencia??null,
                                'sede' => $sede??null,
                                'area' => $area??null,
                            ]
                        );

                        Personal::where('dni', $row['dni_de_personal'])->update([
                            'empresa_id' => $empresa,
                            'gerencia_id' => $gerencia,
                            'sede_id' => $sede,
                            'area_id' => $area,
                        ]);

                    } catch (\Exception $e) {
                        $result['status'] = 'error';
                        $result['estado_importacion'] = 'No Importado';
                        $result['message'] = $e->getMessage();
                    }
                    
                } else {
                    $result['status'] = 'error';
                    $result['estado_importacion'] = 'No Importado';
                    $result['message'] = implode(', ', $row['errors']);
                }
    
                $importResult[] = $result;
            }
        });
    
        session(['import_result' => $importResult]);
    
        return redirect()->route('capacitaciones.personal.result-import');
    }

    public function showResultImport()
    {
        // Aquí puedes obtener los resultados de la importación
        $result = session('import_result', []);
    
        return view('livewire.capacitaciones.personal.result-import', ['result' => $result]);
    }

    private function actualizarPersonalNisira($dni)
    {
        // Aquí debes implementar la lógica para consultar a NISIRA
        // y devolver la información del personal
        // Por ahora, devolveremos un objeto de ejemplo

        $nombre='';
        $res = app('App\Http\Controllers\PersonalController')->actualizarPersonalNisira($dni);

        return $res;
        // if($res['res']){
        //     $message = $res['message'] . "\n";
        //     $nombre = Personal::where('dni',$dni)->first()->name;
        //     return (object) [
        //         'dni' => $dni,
        //         'nombre' => $nombre,
        //     ];
        // } else {
        //     $message = $message . "Error en la linea " . $index . " " . $res['message'] . "\n";
        // }


    }
}