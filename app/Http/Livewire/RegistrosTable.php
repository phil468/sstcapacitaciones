<?php

namespace App\Http\Livewire;

use App\Models\CapacitacionHasPersonal;
use App\Models\Personal;
use App\Models\Prueba;
use App\Models\Respuesta;
use App\Models\SesionAccessLog;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class RegistrosTable extends LivewireDatatable
{
    public $capacitacion_id, $selected_personal_id, $sede_id, $gerencia_id, $subgerencia_id, $area_id;
    public $hideable = 'inline';
    public $exportable = true;
    public $beforeTableSlot = 'components.edicionMasiva';
    // public $updateMode = false;
    public $selected = [];
    // public $showColumns = ['id', 'dni']; // Columnas predeterminadas
    public $listaParaAgregar = false;
    public $modalEdicionMasiva = '#updateRegistroModal';
        
    protected $listeners = [
        'closeModal' => '$refresh',
        'refrescarRegistroTable' => '$refresh',
        'limpiarSeleccionRegistroTable'=>'limpiarSeleccionRegistroTable'
    ];    
    
    public function limpiarSeleccionRegistroTable()
    {
        $this->selected = [];
    }

    public function edicionMasiva()
    {
        $this->emitUp('edicionMasiva', $this->selected);
    }

    // public function updatedSelected($value)
    // {
    //     $this->emitUp('selectedRegistroUpdated', $value);
    // }
    
    // public function toggleSelectAll()
    // {
    //     if (count($this->selected) === $this->getQuery()->count()) {
    //         $this->selected = [];
    //     } else {
    //         $this->selected = $this->checkboxQuery()->values()->toArray();
    //     }
    //     $this->forgetComputed();
        
    //     $this->emitUp('selectedRegistroUpdated', $this->selected);
    // }

    public function builder()
    {
        return CapacitacionHasPersonal::query()->where('capacitacion_id', $this->capacitacion_id);
    }

    public function columns()
    {
        return [
            Column::checkbox()
            ->label('Add'),

            Column::callback('id,personal.name', function ($id,$name) {
                return view('livewire.capacitacion-has-personals.table-actions', ['id' => $id, 'name'=>$name]);
            })->unsortable()
            ->label('Acciones')
            ->excludeFromExport(),
            
            // Column::name('id')->label('ID'),
            Column::name('personal.dni')->label('DNI')->sortable()->searchable(),
            Column::name('personal.name')->label('Nombre del Personal')->sortable()->searchable()
            ->defaultSort('ASC'),
            
            DateColumn::name('fecha_inicio')->format('d/m/Y h:i:s a')
            ->label('Fecha de inicio (Aula Virtual)')->searchable()->filterable()->sortable(),

            DateColumn::name('fecha_fin')->format('d/m/Y h:i:s a')
            ->label('Fecha de fin (Aula Virtual)')->searchable()->filterable()->sortable(),

            NumberColumn::callback('id', function ($id) {
                return CapacitacionHasPersonal::find($id)->intentos_de_evaluacion;
            })->label('Intentos de Evaluación')->sortable()->searchable(),

            Column::name('empresa.name')->label('Empresa')->sortable()->searchable(),
            Column::name('gerencia.name')->label('Gerencia')->sortable()->searchable(),
            Column::name('sede.name')->label('Sede')->sortable()->searchable(),
            // Column::name('subgerencia.name')->label('Subgerencia')->sortable()->searchable(),
            Column::name('area.name')->label('Área')->sortable()->searchable(),
            Column::name('cargo.name')->label('Cargo')->sortable()->searchable(),
            Column::name('planilla.name')->label('Planilla')->sortable()->searchable(),
            Column::name('tipo_de_trabajador.name')->label('Tipo de Trabajador')->sortable()->searchable(),
            Column::name('tipo_de_personal.name')->label('Tipo de Personal')->sortable()->searchable(),
            // Column::name('capacitacion_has_personal.name')->label('Capacitación')->sortable()->searchable(),
            // ->hide(),
            // Column::name('capacitacion')->label('Nombre de la Capacitación'),
            // Agrega más columnas según tus necesidades
            // Column::callback(['id'], function ($id) {
            //     return view('livewire.capacitacion-has-personal-table-actions', ['id' => $id]);
            // })->label('Acciones')->unsortable()->excludeFromExport(),
        ];
    }

    public function edit($id)
    {
        // dd($id);
        $this->emitUp('edit', $id);
    }

    public function destroy($id)
    {
        if ($id) {
            $record = CapacitacionHasPersonal::find($id);

            SesionAccessLog::where('personal_id', $record->personal_id)
            ->where('capacitacion_id', $record->capacitacion_id)
            ->delete();

            // eliminar pruebas
            $pruebas = Prueba::where('personal_id', $record->personal_id)
            ->where('capacitacion_id', $record->capacitacion_id)
            ->get();

            foreach ($pruebas as $prueba) {
                // Eliminar respuestas asociadas a la prueba
                Respuesta::where('prueba_id', $prueba->id)->delete();
            }
            
            // Eliminar las pruebas
            Prueba::where('personal_id', $record->personal_id)
                ->where('capacitacion_id', $record->capacitacion_id)
                ->delete();

            $record->delete();
            
            $this->emit('alert', ['type' => 'success', 'message' => 'Registro eliminado con éxito.']);
        }
    }
}