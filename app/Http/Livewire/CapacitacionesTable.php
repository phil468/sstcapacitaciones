<?php

namespace App\Http\Livewire;


use App\Models\Area;
use App\Models\Capacitacione;
use App\Models\Cargo;
use App\Models\Devolucion;
use App\Models\Empresa;
use App\Models\Gerencia;
use App\Models\Modalidade;
use App\Models\Personal;
use App\Models\Sede;
use App\Models\TipoDeCapacitacione;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class CapacitacionesTable extends LivewireDatatable
{
    public $hideable = 'inline';
    public $exportable = true;
    public $afterTableSlot = 'components.selected';
    public $numeroSerieValidado=true, $fileUpload;
    public $updateMode = false;
    public $ingresar_capacitaciones_de_aula_virtual, $ingresar_capacitaciones_de_no_aula_virtual;
    

    protected $listeners = ['closeModal' => '$refresh', 'close' => '$refresh'];

    public function builder()
    {
        
		$this->ingresar_capacitaciones_de_aula_virtual 		= Auth::user()->can('ingresar-capacitaciones-de-aula-virtual');
		$this->ingresar_capacitaciones_de_no_aula_virtual 	= Auth::user()->can('ingresar-capacitaciones-de-no-aula-virtual');

        $query = Capacitacione::query()
        ->with('personal')
        ->leftJoin('empresas', 'empresas.id', 'capacitaciones.empresa_id')
        ->leftJoin('tipo_de_capacitaciones', 'tipo_de_capacitaciones.id', 'capacitaciones.capacitaciones_tipo_id')
        ->leftJoin('temas', 'temas.id', 'capacitaciones.tema_id')
        ->leftJoin('sedes', 'sedes.id', 'capacitaciones.sede_id')
        ->leftJoin('personal as expositores', 'expositores.id', 'capacitaciones.expositor_id')
        ->leftJoin('cargos as cargo_expositores', 'cargo_expositores.id', 'capacitaciones.cargo_expositor_id')
        ->leftJoin('personal as registradores', 'registradores.id', 'capacitaciones.registrador_id')
        ->leftJoin('cargos as cargo_registradores', 'cargo_registradores.id', 'capacitaciones.cargo_registrador_id')
        ->leftJoin('statuses', 'statuses.id', 'capacitaciones.status_id')
        ->leftJoin('modalidades', 'modalidades.id', 'capacitaciones.modalidad_id')
        ;

        // Aplicar filtros según los permisos
        if ($this->ingresar_capacitaciones_de_aula_virtual && !$this->ingresar_capacitaciones_de_no_aula_virtual) {
            $query->where('capacitaciones.es_aula_virtual', true);
        } elseif (!$this->ingresar_capacitaciones_de_aula_virtual && $this->ingresar_capacitaciones_de_no_aula_virtual) {
            $query->where('capacitaciones.es_aula_virtual', false)->orWhere('capacitaciones.es_aula_virtual', null);
        }

        return $query;

    }

    public $model = Capacitacione::class;

    public function columns()
    {
		$this->ingresar_capacitaciones_de_aula_virtual 		= Auth::user()->can('ingresar-capacitaciones-de-aula-virtual');
		$this->ingresar_capacitaciones_de_no_aula_virtual 	= Auth::user()->can('ingresar-capacitaciones-de-no-aula-virtual');

        $columns = [
            Column::callback('id,es_aula_virtual', function ($id, $es_aula_virtual) {
                if($es_aula_virtual){
                    return view('livewire.capacitaciones.table-actions', ['id' => $id]);
                } else {
                    return view('livewire.capacitaciones.table-actions-aula-no-virtual', ['id' => $id]);
                }
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

            Column::name('identificador_unico')
                ->filterable()
                ->label('identificador_unico')
                ->defaultSort('DESC')
                ->searchable()
                ->hideable()
                ->sortBy('identificador_unico'),
            
            Column::name('temas.name')
                ->filterable()
                ->searchable()
                ->sortBy('temas.name')
                ->hideable()
                ->label('Tema'),
            
            NumberColumn::callback('id', function ($id) {
                // si es_aula_virtual es true
                if (Capacitacione::find($id)->es_aula_virtual) {
                    return Capacitacione::find($id)->sesiones->count();
                } else {
                    return Capacitacione::find($id)->cantidad_de_sesiones;
                }
                
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
            
            DateColumn::name('fecha_inicio')
                ->label('Fecha de inicio')->format('d/m/Y h:i:s a')
                ->filterable()
                ->searchable()
                ->hideable()
                ->sortBy('fecha_inicio'),
            
            DateColumn::name('fecha_fin')
                ->label('Fecha de fin')->format('d/m/Y h:i:s a')
                ->filterable()
                ->searchable()
                ->hideable()
                ->sortBy('fecha_fin'),

            Column::name('tipo_de_capacitaciones.name')
                ->filterable($this->tipo_de_capacitaciones)
                ->searchable()
                ->sortBy('tipo_de_capacitaciones.name')
                ->hideable()
                ->label('Tipo de capacitación'),

            // DateColumn::name('fecha_capacitacion')
            //     ->label('Fecha')
            //     ->filterable()
            //     ->searchable()
            //     ->hideable()
            //     ->sortBy('fecha_capacitacion'),
                
            Column::name('empresa.name')
                ->filterable($this->empresas)
                ->searchable()
                ->sortBy('empresas.name')
                ->hideable()
                ->label('Empresa'),

            Column::name('sede.name')
                ->filterable($this->sedes)
                ->searchable()
                ->sortBy('sedes.name')
                ->hideable()
                ->label('Sede'),

            Column::name('modalidades.name')
                ->filterable($this->modalidades)
                ->searchable()
                ->sortBy('modalidades.name')
                ->hideable()
                ->label('Modalidad'),

            Column::name('expositores.dni')
                ->filterable()
                ->searchable()
                ->sortBy('expositores.dni')
                ->hideable()
                ->label('dni expositor'),

            Column::name('expositores.name')
                ->filterable($this->expositores)
                ->searchable()
                ->sortBy('expositores.name')
                ->hideable()
                ->label('Expositor'),

            Column::name('cargo_expositores.name')
                ->filterable($this->cargos)
                ->searchable()
                ->sortBy('cargo_expositores.name')
                ->hideable()
                ->label('Cargo de expositor'),
            
            Column::name('nombre_expositor_externo')
                ->filterable()
                ->searchable()
                ->sortBy('nombre_expositor_externo')
                ->hideable()
                ->label('Expositor externo'),

            Column::name('areas.name')
                ->filterable($this->areas)
                ->searchable()
                ->sortBy('areas.name')
                ->hideable()
                ->label('Área'),

            // Column::name('registradores.name')
            //     ->filterable($this->registradores)
            //     ->searchable()
            //     ->sortBy('registradores.name')
            //     ->hideable()
            //     ->label('Registrador'),
            
            // Column::name('cargo_registrador.name')
            //     ->filterable($this->registrador_cargos)
            //     ->searchable()
            //     ->sortBy('cargo_registrador.name')
            //     ->hideable()
            //     ->label('Cargo de registrador'),

            // NumberColumn::name('cantidad_de_sesiones')
            //     ->label('Sesiones')
            //     ->filterable()
            //     ->searchable()
            //     ->hideable()
            //     ->sortBy('cantidad_de_sesiones')
            //     ->alignCenter(),

            Column::callback('id', function ($id) {
                    $capacitacion = Capacitacione::find($id);
                    return $capacitacion->cantidad_personas;
                },[],'Inscritos')
                ->label('Registrados')
                ->searchable()
                ->hideable(),
        ];

        // dd($this->ingresar_capacitaciones_de_aula_virtual);
        if ($this->ingresar_capacitaciones_de_aula_virtual) {
            // dd('hola');
            $columns = array_merge($columns, [
                BooleanColumn::name('es_aula_virtual')->label('Es Aula Virtual')->searchable()->filterable(),
                BooleanColumn::name('es_onboarding')->label('Es Onboarding')->searchable()->filterable(),
                NumberColumn::name('cantidad_de_preguntas_a_mostrar')->label('Cantidad de Preguntas a Mostrar')->searchable()->filterable(),
                NumberColumn::name('nota_minima_aprobatoria')->label('Nota Mínima Aprobatoria')->searchable()->filterable(),
                NumberColumn::name('intentos_de_evaluacion')->label('Intentos de Evaluación')->searchable()->filterable(),
            ]);
        }

        $columns = array_merge($columns, [
            DateColumn::name('created_at')->label('Fecha de creacion')->format('d/m/Y h:i:s a')->searchable()->filterable()->defaultSort('asc'),
            DateColumn::name('updated_at')->label('Fecha de Modificación')->format('d/m/Y h:i:s a')->searchable()->filterable()->defaultSort('asc'),
            DateColumn::name('deleted_at')->label('Fecha de eliminación')->format('d/m/Y h:i:s a')->searchable()->filterable()->defaultSort('asc'),
        ]);

        return $columns;
    
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
            $record = Capacitacione::where('id', $id);
            // $record->timestamps = false;
            // $record->first()->update([ 
            //     'deleted_by' => auth()->user()->id
            //     ]);
            // $record->timestamps = true;
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