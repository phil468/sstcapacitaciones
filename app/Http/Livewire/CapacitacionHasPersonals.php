<?php

namespace App\Http\Livewire;

use App\Models\Area;
use App\Models\Capacitacione;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CapacitacionHasPersonal;
use App\Models\Empresa;
use App\Models\Gerencia;
use App\Models\Personal;
use App\Models\Sede;

class CapacitacionHasPersonals extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $personal_id, $active, $observaciones, $empresa_id, $gerencia_id, $area_id, $cargo_id, $planilla_id, $sede_id, $tipo_de_trabajador_id, $tipo_de_personal_id;
    public $areas, $gerencias, $sedes, $name_personal = [], $fecha_inicio, $fecha_fin, $intentos_de_evaluacion;
	public $updateMode = false;

	public $capacitacion_id;
	public $capacitacion_id_general;

	public $capacitacion;
	public $es_aula_virtual;
	
	public $edit_gerencia = false;
	public $edit_area = false;
	public $edit_sede = false;
	public $edit_fecha_inicio = false;
	public $edit_fecha_fin = false;
	public $edit_intentos_de_evaluacion = false;
	public $editMasiva = false;

	protected $listeners = [
        'edit' => 'edit',
		'selectedUpdated' => 'updateSelected',
		'edicionMasiva' => 'edicionMasiva'
    ];
	
	public function listarSelects() {
		// $this->empresas 	= 	Empresa::		orderBy('name')->where('estado',1)->select('name as label', 'id as value')->get()->toArray();
		$this->gerencias 	= 	Gerencia::		orderBy('name')->where('estado',1)->select('name as label', 'id as value')->get()->toArray();
		$this->sedes 		= 	Sede::			orderBy('name')->where('estado',1)->select('name as label', 'id as value')->get()->toArray();
		$this->areas 		= 	Area::			orderBy('name')->where('estado',1)->select('name as label', 'id as value')->get()->toArray();

		$this->emit('listar_selects_personal',
			$this->gerencias,
			$this->sedes,
			$this->areas,

			$this->gerencia_id,
			$this->sede_id,
			$this->area_id
		);
		// $this->actualizarDatosPersonal();
	}

	public function actualizarDatosPersonal () {
		$this->emit('actualizarDatosP',
			$this->gerencia_id,
			$this->sede_id,
			$this->area_id,
		);
	}

	public $selectedFromPersonalTable = [];
	public $selectedFromRegistroTable = [];

	public function updateSelected($value)
	{
		$this->selectedFromPersonalTable = $value;
	}

    public function mount($capacitacion_id, $es_aula_virtual = false)
    {
        $this->capacitacion_id = $capacitacion_id;
        $this->capacitacion_id_general = $capacitacion_id ?? null;
		$this->capacitacion = Capacitacione::where('id',$capacitacion_id)
		->with(['tipo_capacitacion','tema','sede','expositor','empresa','status','modalidad','registrador'])
		->get()
		->toArray();
    }

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.capacitacion-has-personals.view', 
		[ 
			'capacitacion' => $this->capacitacion,
            'capacitacionHasPersonals' => CapacitacionHasPersonal::orderBy('personal_name', 'asc')
			->select('capacitacion_has_personal.*','personal.name as personal_name')
						->where('capacitacion_id', $this->capacitacion_id)
						->with(['personal'])
						->leftJoin('personal', 'personal.id', 'capacitacion_has_personal.personal_id')
						->get(),
        ]);
    }

	public function agregarSeleccionados()
	{
		$capacitacion = Capacitacione::find($this->capacitacion_id);

		// Supongamos que $this->selectedFromPersonalTable es un array de ids
		$personalIds = $this->selectedFromPersonalTable;

		// Realiza una consulta en el modelo Personal para obtener los datos correspondientes a esos ids
		$personalData = Personal::whereIn('id', $personalIds)->get();

		// Inicializa un array para almacenar los datos reorganizados
		$resultArray = [];

		$fecha_inicio = null;
		$fecha_fin = null;

		if ($capacitacion->es_aula_virtual) {
			$fecha_inicio = $capacitacion->fecha_inicio ?? now()->setTime(10, 0, 0);
			$fecha_fin = $capacitacion->fecha_fin ?? now()->addMonth()->endOfDay();
		}
		// dd($fecha_inicio,$fecha_fin);
		
		// Construye el array de datos reorganizados
		$resultArray = $personalData->mapWithKeys(function ($personal) use ($fecha_inicio, $fecha_fin) {
			return [
				$personal->id => [
					'gerencia_id' => $personal->gerencia_id,
					'area_id' => $personal->area_id,
					'sede_id' => $personal->sede_id,
					'cargo_id' => $personal->cargo_id,
					'planilla_id' => $personal->planilla_id,
					'tipo_de_trabajador_id' => $personal->tipo_de_trabajador_id,
					'tipo_de_personal_id' => $personal->tipo_de_personal_id,
					'empresa_id' => $personal->empresa_id,
					'fecha_inicio' => $fecha_inicio??null,
					'fecha_fin' => $fecha_fin??null,
				],
			];
		})->toArray();

		$result = $capacitacion->personal()->syncWithoutDetaching($resultArray);

		// Obtener los IDs de las nuevas relaciones
		$nuevasRelacionesIds = $result['attached'];

		// Realiza una consulta para obtener los registros de CapacitacionHasPersonal correspondientes a esos IDs
		$nuevasRelaciones = CapacitacionHasPersonal::whereIn('personal_id', $nuevasRelacionesIds)->where('capacitacion_id',$this->capacitacion_id)->select('id')->get()->toArray();

		$this->selectedFromPersonalTable = [];
		$this->emit('limpiarSeleccionPersonalTable');
		$this->emit('refrescarRegistroTable');
		$this->edicionMasiva($nuevasRelaciones);
	}

	public function cancelarSeleccionados()
	{
		$this->selectedFromPersonalTable = [];
		// session()->flash('success', 'Selección de personal cancelada.');
        $this->emit('alert', ['type' => 'success', 'message' => 'Selección de personal cancelada.']);
	}

	public function quitarAsistente($value)
	{
		CapacitacionHasPersonal::find($value)->delete();
		$this->selectedFromPersonalTable = [];
		// session()->flash('success', 'Personal quitado correctamente.');
        $this->emit('alert', ['type' => 'success', 'message' => 'Personal quitado correctamente.']);		
	}

    public function cancel()
    {
        $this->resetInput();
		$this->editMasiva = false;
		$this->emit('limpiarDatosP');
    }
	
    private function resetInput()
    {		
		$this->name_personal = [];
		$this->personal_id = null;
		$this->capacitacion_id = $this->capacitacion_id_general ?? null;
		$this->active = null;
		$this->observaciones = null;
		$this->empresa_id = null;
		$this->gerencia_id = null;
		$this->area_id = null;
		$this->cargo_id = null;
		$this->planilla_id = null;
		$this->sede_id = null;
		$this->tipo_de_trabajador_id = null;
		$this->tipo_de_personal_id = null;
		$this->fecha_inicio = null;
		$this->fecha_fin = null;
		$this->intentos_de_evaluacion = null;
		$this->selectedFromRegistroTable = [];
		$this->edit_gerencia = null;
		$this->edit_area = null;
		$this->edit_sede = null;
		$this->edit_fecha_inicio = null;
		$this->edit_fecha_fin = null;
		$this->edit_intentos_de_evaluacion = null;
    }

    public function store()
    {
        $this->validate([
			'personal_id' => 'required',
			'capacitacion_id' => 'required',
        ]);

        CapacitacionHasPersonal::create([ 
			'personal_id' => $this-> personal_id,
			'capacitacion_id' => $this-> capacitacion_id,
			'active' => $this-> active,
			'observaciones' => $this-> observaciones,
			'empresa_id' => $this-> empresa_id,
			'gerencia_id' => $this-> gerencia_id,
			'area_id' => $this-> area_id,
			'cargo_id' => $this-> cargo_id,
			'planilla_id' => $this-> planilla_id,
			'sede_id' => $this-> sede_id,
			'tipo_de_trabajador_id' => $this-> tipo_de_trabajador_id,
			'tipo_de_personal_id' => $this-> tipo_de_personal_id,
			'fecha_inicio' => $this-> fecha_inicio,
			'fecha_fin' => $this-> fecha_fin,
        ]);
        
        $this->resetInput();
		$this->emit('limpiarDatosP');
		$this->emit('closeModal');
		// session()->flash('success', 'Personal registrado correctamente.');
        $this->emit('alert', ['type' => 'success', 'message' => 'Personal registrado correctamente.']);

    }

    public function edit($id)
    {
		$this->editMasiva = false;
		if ($id != 0) {
			$record = CapacitacionHasPersonal::findOrFail($id);

			$this->name_personal[$record->personal_id] = $record->personal->name;
			$this->selected_id = $id; 
			$this->personal_id = $record-> personal_id;
			// $this->capacitacion_id = $record-> capacitacion_id;
			// $this->active = $record-> active;
			// $this->observaciones = $record-> observaciones;
			$this->empresa_id = $record-> empresa_id;
			$this->gerencia_id = $record-> gerencia_id;
			$this->area_id = $record-> area_id;
			// $this->cargo_id = $record-> cargo_id;
			// $this->planilla_id = $record-> planilla_id;
			$this->sede_id = $record-> sede_id;
			$this->fecha_inicio = $record->fecha_inicio ? date('Y-m-d\TH:i', strtotime($record->fecha_inicio)) : '';
			$this->fecha_fin = $record->fecha_fin ? date('Y-m-d\TH:i', strtotime($record->fecha_fin)) : '';
			$this->intentos_de_evaluacion = $record->intentos_de_evaluacion;
			// $this->tipo_de_trabajador_id = $record-> tipo_de_trabajador_id;
			// $this->tipo_de_personal_id = $record-> tipo_de_personal_id;
		} else {
			$this->emit('limpiarDatosP');
			$this->resetValidation();
			$this->resetInput();
			$this->selected_id = 0; 
			$this->active=true;
		}

		$this->updateMode = true;
		$this->listarSelects();
    }

	public function edicionMasiva($selectedFromRegistroTable)
    {
		$this->editMasiva = true;
		$this->selectedFromRegistroTable = $selectedFromRegistroTable;

		$records = CapacitacionHasPersonal::whereIn('id',$this->selectedFromRegistroTable)->get();

		foreach ($records as $record) {
			$this->name_personal[$record->personal_id] = $record->personal->name;
		}

		if (count($selectedFromRegistroTable) > 0) {
			$this->gerencia_id = null;
			$this->area_id = null;
			$this->sede_id = null;
			$this->fecha_inicio = $records[0]->fecha_inicio? date('Y-m-d\TH:i', strtotime($records[0]->fecha_inicio)) :null;
			$this->fecha_fin = $records[0]->fecha_fin? date('Y-m-d\TH:i', strtotime($records[0]->fecha_fin)) :null;
			$this->intentos_de_evaluacion = null;

			$this->emit('openRegistroModal');
			$this->updateMode = true;
			$this->listarSelects();
		}
    }

    public function update()
    {		
        $this->validate([
			'personal_id' => 'required',
			'capacitacion_id' => 'required',
        ]);

		$personal = Personal::find($this->personal_id);

        if ($this->selected_id) {
			$record = CapacitacionHasPersonal::find($this->selected_id);
            $record->update([ 
			// 'personal_id' => $this-> personal_id,
			// 'capacitacion_id' => $this-> capacitacion_id,
			'active' => true,
			// 'observaciones' => $this-> observaciones,
			// 'empresa_id' => $this-> empresa_id,
			'gerencia_id' => $this-> gerencia_id,
			'area_id' => $this-> area_id,
			'cargo_id' => $personal-> cargo_id,
			'planilla_id' => $personal-> planilla_id,
			'sede_id' => $this-> sede_id,
			'fecha_inicio' => $this-> fecha_inicio == "" ? null : $this-> fecha_inicio,
			'fecha_fin' => $this-> fecha_fin == "" ? null : $this-> fecha_fin,
			'intentos_de_evaluacion' => $this-> intentos_de_evaluacion,
			'tipo_de_trabajador_id' => $personal-> tipo_de_trabajador_id,
			'tipo_de_personal_id' => $personal-> tipo_de_personal_id
            ]);

			$personal->update([
				'gerencia_id' => $this-> gerencia_id,
				'area_id' => $this-> area_id,
				'sede_id' => $this-> sede_id,
			]);

			// session()->flash('success', 'Registro actualizado correctamente.');
			$this->emit('alert', ['type' => 'success', 'message' => 'Registro actualizado correctamente.']);

			$this->emit('limpiarDatosP');
            $this->resetInput();
            $this->resetValidation();
            $this->updateMode = false;
		    $this->emit('closeModal');
        }
    }

	public function updateMasivo()
	{
		$rules = [];

		$rules['fecha_inicio'] 	= 'required';
		$rules['fecha_fin'] 	= 'required';
		
		if ($this->edit_gerencia) 				{ $rules['gerencia_id'] 			= 'required'; }
		if ($this->edit_area) 					{ $rules['area_id'] 				= 'required'; }
		if ($this->edit_sede) 					{ $rules['sede_id'] 				= 'required'; }
		// if ($this->edit_fecha_inicio) 		{ $rules['fecha_inicio'] 			= 'required'; }
		// if ($this->edit_fecha_fin) 			{ $rules['fecha_fin'] 				= 'required'; }
		if ($this->edit_intentos_de_evaluacion) { $rules['intentos_de_evaluacion'] 	= 'required'; }

		// // Validar que al menos uno de los checkboxes esté marcado
		// if (!$this->edit_gerencia && !$this->edit_area && !$this->edit_sede 
		// // && !$this->edit_fecha_inicio && !$this->edit_fecha_fin 
		// && !$this->edit_intentos_de_evaluacion) {
		// 	// session()->flash('errorEdicionMasiva', 'Debe seleccionar al menos una opción para editar.');
		// 	$this->emit('alert', ['type' => 'warning', 'message' => 'Debe seleccionar al menos una opción para editar.']);
		// 	return;
		// }

		// dd($rules);

		$this->validate($rules);

		if ($this->selectedFromRegistroTable) {
			$updateData = [];

			$personalUpdateData = [];

			if ($this->edit_gerencia) {
				$updateData['gerencia_id'] = $this->gerencia_id;
				$personalUpdateData['gerencia_id'] = $this->gerencia_id;
			}
			if ($this->edit_area) {
				$updateData['area_id'] = $this->area_id;

				$personalUpdateData['area_id'] = $this->area_id;
			}
			if ($this->edit_sede) {
				$updateData['sede_id'] = $this->sede_id;

				$personalUpdateData['sede_id'] = $this->sede_id;
			}
			// if ($this->edit_fecha_inicio) {
				// dd($this->fecha_inicio);
				$updateData['fecha_inicio'] = $this->fecha_inicio;
			// }
			// if ($this->edit_fecha_fin) {
				$updateData['fecha_fin'] = $this->fecha_fin;
			// }
			if ($this->edit_intentos_de_evaluacion) {
				$updateData['intentos_de_evaluacion'] = $this->intentos_de_evaluacion;
			}

			CapacitacionHasPersonal::whereIn('id', $this->selectedFromRegistroTable)->update($updateData);

			Personal::whereIn('id', $this->selectedFromRegistroTable)->update($personalUpdateData);

			// session()->flash('success', 'Registros actualizados correctamente.');
			$this->emit('alert', ['type' => 'success', 'message' => 'Registros actualizados correctamente.']);

			$this->emit('limpiarDatosP');
            $this->resetValidation();

			$this->resetInput();
			$this->updateMode = false;

			$this->emit('closeModal');
			$this->emit('limpiarSeleccionRegistroTable');
		}
	}

    public function destroy($id)
    {
        if ($id) {
            $record = CapacitacionHasPersonal::where('id', $id);
            $record->delete();
        }
    }


}
