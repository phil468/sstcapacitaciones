<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Asignacione;

class Asignaciones extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $personal_id, $capacitacion_id, $fecha_inicio, $fecha_fin, $intentos_de_evaluacion, $realizado, $finalizado, $created_by, $updated_by, $deleted_by;
    public $updateMode = false;

	public function mount($capacitacion_id = null) {
		$this->capacitacion_id = $capacitacion_id;
	}

    public function render()
    {
		$asignaciones = Asignacione::latest()
						->when($this->capacitacion_id, function ($query) {
							return $query->where('capacitacion_id', $this->capacitacion_id);
						})
						->paginate(5);

        return view('livewire.asignaciones.view', [
            'asignaciones' => $asignaciones,
        ]);
    }
	
    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }
	
    private function resetInput()
    {		
		$this->personal_id = null;
		$this->capacitacion_id = null;
		$this->fecha_inicio = null;
		$this->fecha_fin = null;
		$this->intentos_de_evaluacion = null;
		$this->realizado = null;
		$this->finalizado = null;
		$this->created_by = null;
		$this->updated_by = null;
		$this->deleted_by = null;
    }


	public function create() 
	{
	}
    
    public function store()
    {
        $this->validate([
        ]);

        Asignacione::create([ 
			'personal_id' => $this-> personal_id,
			'capacitacion_id' => $this-> capacitacion_id,
			'fecha_inicio' => $this-> fecha_inicio,
			'fecha_fin' => $this-> fecha_fin,
			'intentos_de_evaluacion' => $this-> intentos_de_evaluacion,
			'realizado' => $this-> realizado,
			'finalizado' => $this-> finalizado,
			'created_by' => $this-> created_by,
			'updated_by' => $this-> updated_by,
			'deleted_by' => $this-> deleted_by
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Asignacione creado correctamente.');
    }

    public function edit($id)
    {
        $record = Asignacione::findOrFail($id);

        $this->selected_id = $id; 
		$this->personal_id = $record-> personal_id;
		$this->capacitacion_id = $record-> capacitacion_id;
		$this->fecha_inicio = $record-> fecha_inicio;
		$this->fecha_fin = $record-> fecha_fin;
		$this->intentos_de_evaluacion = $record-> intentos_de_evaluacion;
		$this->realizado = $record-> realizado;
		$this->finalizado = $record-> finalizado;
		$this->created_by = $record-> created_by;
		$this->updated_by = $record-> updated_by;
		$this->deleted_by = $record-> deleted_by;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
        ]);

        if ($this->selected_id) {
			$record = Asignacione::find($this->selected_id);
            $record->update([ 
			'personal_id' => $this-> personal_id,
			'capacitacion_id' => $this-> capacitacion_id,
			'fecha_inicio' => $this-> fecha_inicio,
			'fecha_fin' => $this-> fecha_fin,
			'intentos_de_evaluacion' => $this-> intentos_de_evaluacion,
			'realizado' => $this-> realizado,
			'finalizado' => $this-> finalizado,
			'created_by' => $this-> created_by,
			'updated_by' => $this-> updated_by,
			'deleted_by' => $this-> deleted_by
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Asignacione actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Asignacione::where('id', $id);
            $record->delete();
        }
    }
}
