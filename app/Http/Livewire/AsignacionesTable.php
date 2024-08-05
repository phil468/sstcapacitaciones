<?php

namespace App\Http\Livewire;


use App\Models\Area;
use App\Models\Asignacione;
use App\Models\Capacitacione;
use App\Models\Cargo;
use App\Models\Devolucion;
use App\Models\Empresa;
use App\Models\Gerencia;
use App\Models\Modalidade;
use App\Models\Personal;
use App\Models\Sede;
use App\Models\TipoDeCapacitacione;
use Illuminate\Support\Facades\Storage;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class AsignacionesTable extends LivewireDatatable
{
    public $hideable = 'inline';
    public $exportable = true;
    public $afterTableSlot = 'components.selected';
    public $numeroSerieValidado=true, $fileUpload;
    public $updateMode = false;

    protected $listeners = ['closeModal' => '$refresh', 'close' => '$refresh'];

    public function builder()
    {
        return Asignacione::query();
    }

    public $model = Asignacione::class;

    public function columns()
    {
        return [
            Column::callback('id', function ($id) {
                return view('livewire.capacitaciones.table-actions', ['id' => $id]);
            })->unsortable()
            ->label('Acciones')
            ->excludeFromExport(),

            NumberColumn::name('id')
                ->filterable()
                ->label('ID')
                ->defaultSort('DESC')
                ->searchable()
                ->hideable()
                ->sortBy('id'),
            
            Column::name('temas.name')
                ->filterable()
                ->searchable()
                ->sortBy('temas.name')
                ->hideable()
                ->label('Tema'),
            
            NumberColumn::callback('id', function ($id) {
                return Capacitacione::find($id)->sesiones->count();
            },[],'Sesiones')
                ->label('Sesiones')
                ->filterable()
                ->searchable()
                ->hideable()
                ->sortBy('id')
                ->alignCenter(),

            BooleanColumn::callback(['id', 'activo'], function ($id, $activo) {
                    return view('livewire.custom-boolean', ['modelId' => $id, 'field' => 'activo', 'value' => $activo]);
                })
                ->label('Activo')
                ->filterable()
                ->searchable()
                ->hideable()
                ->alignCenter(),
            
            BooleanColumn::callback(['id', 'es_onboarding'], function ($id, $es_onboarding) {
                    return view('livewire.custom-boolean', ['modelId' => $id, 'field' => 'es_onboarding', 'value' => $es_onboarding]);
                })
                ->label('Onboarding')
                ->filterable()
                ->searchable()
                ->hideable()
                ->alignCenter(),

            DateColumn::name('created_at')->label('Fecha de creacion')->format('d/m/Y h:i:s a')->searchable()->filterable()->defaultSort('asc'),
            DateColumn::name('updated_at')->label('Fecha de Modificación')->format('d/m/Y h:i:s a')->searchable()->filterable()->defaultSort('asc'),
            DateColumn::name('deleted_at')->label('Fecha de eliminación')->format('d/m/Y h:i:s a')->searchable()->filterable()->defaultSort('asc'),
        ];
    }

    public function getPersonalProperty()
    {
        return Personal::orderBy('name')->pluck('name');
    }

    public function getSedesProperty()
    {
        return Sede::orderBy('name')->pluck('name');
    }

    public function getEmpresasProperty()
    {
        return Empresa::orderBy('name')->pluck('name');
    }

    public function getModalidadesProperty()
    {
        return Modalidade::orderBy('name')->pluck('name');
    }

    // public function getGerenciasProperty()
    // {
    //     return Gerencia::orderBy('name')->pluck('name');
    // }

    public function getAreasProperty()
    {
        return Area::orderBy('name')->pluck('name');
    }

    public function getCargosProperty()
    {
        return Cargo::orderBy('name')->pluck('name');
    }
    
    public function getRegistradoresProperty()
    {
        $responsable = Capacitacione::pluck('registrador_id')->toArray();
        return Personal::whereIn('id',$responsable)->orderBy('name')->pluck('name');
    }

    public function getExpositoresProperty()
    {
        $exdpositor = Capacitacione::pluck('expositor_id')->toArray();
        return Personal::whereIn('id',$exdpositor)->orderBy('name')->pluck('name');
    }

    // public function getResponsableAreasProperty()
    // {
    //     $areas = Capacitacione::pluck('cargo_registrador_id')->toArray();
    //     return Area::whereIn('id',$areas)->orderBy('name')->pluck('name');
    // }
    
    public function getTipoDeCapacitacionesProperty()
    {
        $tipoDeCapacitaciones = Capacitacione::pluck('capacitaciones_tipo_id')->toArray();
        return TipoDeCapacitacione::whereIn('id',$tipoDeCapacitaciones)->orderBy('name')->pluck('name');
    }

    public function getRegistradorCargosProperty()
    {
        $cargos = Capacitacione::pluck('cargo_registrador_id')->toArray();
        return Cargo::whereIn('id',$cargos)->orderBy('name')->pluck('name');
    }

    public function edit($id)
    {
        $this->emit('edit', $id);
    }

//     public function descargarPDF($pdf)
//     {
//         return Storage::disk('public')->download($pdf);

// //        $this->emit('descargarPDF', $pdf);
//     }

    public function destroy($id)
    {
        if ($id) {
            $record = Devolucion::where('id', $id);
            $record->timestamps = false;
            $record->first()->update([ 
                'deleted_by' => auth()->user()->id
                ]);
            $record->timestamps = true;
            $record->delete();
        }
    }

    public function updateBooleanField($modelId, $field, $newValue)
    {
        $model = Capacitacione::find($modelId); // Asegúrate de reemplazar `Model` con el nombre de tu modelo real
        $model->$field = $newValue;
        $model->save();
        $this->emit('fieldUpdated', $modelId . '.' . $field, $newValue);
    }

}