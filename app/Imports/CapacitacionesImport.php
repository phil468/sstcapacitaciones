<?php

namespace App\Imports;

use App\Models\Area;
use App\Models\Capacitacione;
use App\Models\Empresa;
use App\Models\TipoDeCapacitacione;
use App\Models\Tema;
use App\Models\Sede;
use App\Models\Modalidade;
use App\Models\Personal;
use App\Models\Status;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CapacitacionesImport implements ToModel, WithHeadingRow, WithValidation
{
    
    public function model(array $row)
    {
        // Validar y obtener los datos relacionados
        $empresa = trim($row['empresa']); // Empresa::where('name', $row['empresa'])->first();
        $tipoCapacitacion = trim($row['tipo_de_capacitacion']); // TipoDeCapacitacione::where('name', $row['tipo_de_capacitacion'])->first();
        $tema = trim($row['tema']); // Tema::where('name', $row['tema'])->first();
        $sede = trim($row['sede']); // Sede::where('name', $row['sede'])->first();
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

        $uuid = $row['identificador_unico'] ?? $this->generateUniqueUuid();
        $capacitacion = Capacitacione::where('uuid', $uuid)->first();
    
        $capacitacionData = [
            'identificador_unico' => $uuid,
            'empresa' => $empresa,
            'capacitaciones_tipo' => $tipoCapacitacion,
            'tema_id' => $tema,
            'sede_id' => $sede,
            'fecha_inicio' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['fecha_de_inicio']),
            'fecha_fin' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['fecha_de_fin']),
            'modalidad_id' => $modalidad->id,
            'modalidad' => $modalidad->name,
            'expositor_id' => $expositorId,
            'nombre_expositor_interno' => $row['modalidad'] == 'INTERNA' ? $expositor->name : '',
            'expositor_externo' => $row['modalidad'] == 'EXTERNA' ? true : false,
            'nombre_expositor_externo' => $nombreExpositorExterno,
            'activo' => $row['habilitada'] == 'SI' ? 1 : 0,
            'status_id' => $estado->id,
            'estado' => $estado->name,
            'cantidad_de_sesiones' => $row['cantidad_de_sesiones'],
        ];
    
        if ($capacitacion) {
            $capacitacion->update($capacitacionData);
        } else {
            $capacitacion = Capacitacione::create($capacitacionData);
        }

        // Procesar las áreas
        if (isset($row['areas'])) {
            $areas = explode(',', $row['areas']);
            $areaIds = [];
            foreach ($areas as $areaName) {
                $area = Area::firstOrCreate(['name' => trim($areaName)]);
                $areaIds[] = $area->id;
            }
            $capacitacion->areas()->sync($areaIds);
        }

        return $capacitacion;

        // Crear o actualizar la capacitación
        // return Capacitacione::updateOrCreate(
        //     ['identificador_unico' => $row['identificador_unico']],
        //     [
        //         'empresa' => $empresa,
        //         'capacitaciones_tipo' => $tipoCapacitacion,
        //         'tema_id' => $tema,
        //         'sede_id' => $sede,
        //         'fecha_inicio' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['fecha_de_inicio']),
        //         'fecha_fin' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['fecha_de_fin']),
        //         'modalidad_id' => $modalidad->id,
        //         'modalidad' => $modalidad->name,
        //         'expositor_id' => $expositorId,
        //         'nombre_expositor_interno' => $row['modalidad'] == 'INTERNA'? $expositor->name : '',
        //         'expositor_externo' => $row['modalidad'] == 'EXTERNA'? true : false,
        //         'nombre_expositor_externo' => $nombreExpositorExterno,
        //         'activo' => $row['habilitada'] == 'SI' ? 1 : 0,
        //         'status_id' => $estado->id,
        //         'estado' => $estado->name,
        //         'cantidad_de_sesiones' => $row['cantidad_de_sesiones'],
        //     ]
        // );
    }

    public function rules(): array
    {
        return [
            // '*.identificador_unico' => 'required|max:10|unique:capacitaciones,identificador_unico',
            '*.identificador_unico' => [
                'required',
                // 'max:10',
                Rule::unique('capacitaciones', 'identificador_unico')->ignore($this->getCurrentId())
            ],
            '*.empresa' => 'required|string',
            '*.tipo_de_capacitacion' => 'required|string',
            '*.tema' => 'required|string',
            '*.sede' => 'required|string',
            '*.fecha_de_inicio' => 'required|date',
            '*.fecha_de_fin' => 'required|date|after_or_equal:*.fecha_de_inicio',
            '*.modalidad' => 'required|in:INTERNA,EXTERNA',
            '*.dni_de_expositor_interno' => 'required_if:*.modalidad,INTERNA|nullable|exists:personal,dni',
            '*.nombre_de_expositor_externo' => 'required_if:*.modalidad,EXTERNA|nullable',
            '*.habilitada' => 'required|in:SI,NO',
            '*.estado' => 'required|in:PENDIENTE,CANCELADA,REALIZADA',
            '*.cantidad_de_sesiones' => 'required|integer|min:1|max:50',
        ];
    }

    private function getCurrentId()
    {
        // Implementa la lógica para obtener el ID del registro actual si estás actualizando
        // Por ejemplo, podrías buscar el ID basado en el identificador_unico
        return Capacitacione::where('identificador_unico', request()->input('identificador_unico'))->value('id');
    }

    private function generateUniqueUuid()
    {
        do {
            // Generar un nuevo UUID
            $uuid = (string) Str::uuid();

            // Verificar si el UUID ya existe en la base de datos
            $exists = Capacitacione::where('uuid', $uuid)->exists();
        } while ($exists);

        return $uuid;
    }
}