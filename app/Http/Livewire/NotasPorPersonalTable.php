<?php

namespace App\Http\Livewire;

use App\Models\CapacitacionHasPersonal;
use App\Models\EncargadosPlanesDeAccion;
use App\Models\EvaluadorHasEvaluado;
use App\Models\Prueba;
use App\Models\Respuesta;
use App\Models\SesionAccessLog;
use App\Models\TipoDeEvaluacione;
use Illuminate\Support\Facades\DB;
use Mediconesystems\LivewireDatatables\Action;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\BooleanColumn;
// use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Exports\DatatableExport;
use Mediconesystems\LivewireDatatables\NumberColumn;

//en esta tabla vamos a mostrar los evaluadores 
class NotasPorPersonalTable extends LivewireDatatable
{
    public $hideable = 'inline';
    public $exportable = true;
    public $afterTableSlot = 'components.selected';
    public $numeroSerieValidado=true, $fileUpload;
    public $updateMode = false;
    public $export_name = 'Avance de Capacitaciones';

    public function builder()
    {       
        return CapacitacionHasPersonal::query()
         ->select('capacitacion_has_personal.*')
         ->where('capacitacion_has_personal.deleted_at', null)
         ->where('capacitaciones.es_aula_virtual', true)
            ->join('capacitaciones', 'capacitacion_has_personal.capacitacion_id', '=', 'capacitaciones.id')
            ->join('personal', 'capacitacion_has_personal.personal_id', '=', 'personal.id');
    }

    public $model = CapacitacionHasPersonal::class;

    public function columns()
    {
        return [
            Column::name('personal.name')->label('Personal'),
            Column::name('capacitacion.tema.name')->label('Capacitación'),
            Column::callback('capacitacion_id,personal_id', function ($capacitacion_id, $personal_id) {
                return $this->calcularAvance($capacitacion_id, $personal_id);
            },[],'Avance')->label('Avance'),
            Column::callback('capacitacion_id, personal_id', function ($capacitacion_id, $personal_id) {
                return $this->calcularNota($capacitacion_id, $personal_id);
            },[],'Nota')->label('Nota'),
            DateColumn::name('fecha_inicio')->format('d/m/Y h:i:s a')
            ->label('Fecha de inicio')->searchable()->filterable()->sortable(),

            DateColumn::name('fecha_fin')->format('d/m/Y h:i:s a')
            ->label('Fecha de fin')->searchable()->filterable()->sortable(),

            // Column::callback(['fecha_inicio', 'fecha_fin'], function ($fecha_inicio, $fecha_fin) {
            //     return ($fecha_inicio ? ($fecha_inicio->format('d/m/Y h:i:s a').' - ') : '') . ($fecha_fin ? $fecha_fin->format('d/m/Y h:i:s a') : '');
            // },[],'Fechas')->label('Fechas'),
            Column::callback('fecha_inicio, fecha_fin', function ($fecha_inicio, $fecha_fin) {
                $now = now();
                if ($fecha_inicio > $now) {
                    return 'Por iniciar';
                } else if ($fecha_fin < $now) {
                    return 'Finalizado';
                } else {
                    return 'En progreso';
                }
            },[],'Estado')->label('Estado Fechas'),
        ];
    }
    
    private function calcularAvance($capacitacion_id, $personal_id)
    {
        $capacitacionHasPersonal = CapacitacionHasPersonal::where('capacitacion_id', $capacitacion_id)
            ->where('personal_id', $personal_id)
            ->first();

        $sesionAccessLog = SesionAccessLog::where('capacitacion_id', $capacitacion_id)
            ->where('personal_id', $personal_id)
            ->orderBy('accessed_at', 'desc')
            ->first();

        if ($sesionAccessLog) {
            if ($sesionAccessLog->numero_de_evaluacion == $capacitacionHasPersonal->intentos_de_evaluacion) {
                return 'Evaluación final completada';
            } else if ($sesionAccessLog->numero_de_evaluacion) {
                return 'Intento de evaluación ' . $sesionAccessLog->numero_de_evaluacion. ' completado';
            } else if ($sesionAccessLog->ingreso_a_evaluacion) {
                return 'Primer intento de evaluación en progreso';
            } else if ($sesionAccessLog->numero_de_sesion) {
                return "Sesión {$sesionAccessLog->numero_de_sesion}";
            } else if($sesionAccessLog->ingreso_a_capacitacion) {
                return 'Ingresó a la capacitación pero aun no ha visto ninguna sesión';
            }
        }

        return 'No iniciado';
    }

    private function calcularNota($capacitacion_id, $personal_id)
    {
        $prueba = Prueba::where('capacitacion_id', $capacitacion_id)
            ->where('personal_id', $personal_id)
            ->where('status_id', 2)
            ->orderBy('intento', 'desc')
            ->first();

        if (!$prueba) {
            // Crear el registro de la prueba con el primer intento
            $intentosRegistrados = 0;
            return 'No iniciado';
        } else {
            // Verificar si el usuario tiene intentos disponibles
            $intentosRegistrados = $prueba->intento;
            $puntaje = $prueba->puntaje;
            $nota_minima_aprobatoria = $capacitacion->nota_minima_aprobatoria??10.50;
            
            return 'Intento: '. $intentosRegistrados. ' | '  .($puntaje >= $nota_minima_aprobatoria ? 'Aprobado: ' : 'Desaprobado: ') . $puntaje;
        }
    }

    public function export()
    {
        $this->forgetComputed();
        $export = new DatatableExport($this->getExportResultsSet());
        $export->setFileName('Avance y notas de personal.xlsx');
        return $export->download();
    }
}