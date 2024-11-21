<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ResultadosInspeccion;

class ResultadosInspeccions extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $inspeccion_id, $descripcion, $nivel_riesgo, $registro_fotografico, $accion_a_tomar, $responsable_id, $estado, $fecha_ejecucion;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.resultados-inspeccion.view', [
            'resultadosInspeccions' => ResultadosInspeccion::latest()
						->orWhere('inspeccion_id', 'LIKE', $keyWord)
						->orWhere('descripcion', 'LIKE', $keyWord)
						->orWhere('nivel_riesgo', 'LIKE', $keyWord)
						->orWhere('registro_fotografico', 'LIKE', $keyWord)
						->orWhere('accion_a_tomar', 'LIKE', $keyWord)
						->orWhere('responsable_id', 'LIKE', $keyWord)
						->orWhere('estado', 'LIKE', $keyWord)
						->orWhere('fecha_ejecucion', 'LIKE', $keyWord)
						->paginate(10),
        ]);
    }
	
    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }
	
    private function resetInput()
    {		
		$this->inspeccion_id = null;
		$this->descripcion = null;
		$this->nivel_riesgo = null;
		$this->registro_fotografico = null;
		$this->accion_a_tomar = null;
		$this->responsable_id = null;
		$this->estado = null;
		$this->fecha_ejecucion = null;
    }


	public function create() 
	{
	}
    
    public function store()
    {
        $this->validate([
		'inspeccion_id' => 'required',
		'nivel_riesgo' => 'required',
		'responsable_id' => 'required',
		'estado' => 'required',
        ]);

        ResultadosInspeccion::create([ 
			'inspeccion_id' => $this-> inspeccion_id,
			'descripcion' => $this-> descripcion,
			'nivel_riesgo' => $this-> nivel_riesgo,
			'registro_fotografico' => $this-> registro_fotografico,
			'accion_a_tomar' => $this-> accion_a_tomar,
			'responsable_id' => $this-> responsable_id,
			'estado' => $this-> estado,
			'fecha_ejecucion' => $this-> fecha_ejecucion
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Resultados Inspeccion creado correctamente.');
    }

    public function edit($id)
    {
        $record = ResultadosInspeccion::findOrFail($id);

        $this->selected_id = $id; 
		$this->inspeccion_id = $record-> inspeccion_id;
		$this->descripcion = $record-> descripcion;
		$this->nivel_riesgo = $record-> nivel_riesgo;
		$this->registro_fotografico = $record-> registro_fotografico;
		$this->accion_a_tomar = $record-> accion_a_tomar;
		$this->responsable_id = $record-> responsable_id;
		$this->estado = $record-> estado;
		$this->fecha_ejecucion = $record-> fecha_ejecucion;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'inspeccion_id' => 'required',
		'nivel_riesgo' => 'required',
		'responsable_id' => 'required',
		'estado' => 'required',
        ]);

        if ($this->selected_id) {
			$record = ResultadosInspeccion::find($this->selected_id);
            $record->update([ 
			'inspeccion_id' => $this-> inspeccion_id,
			'descripcion' => $this-> descripcion,
			'nivel_riesgo' => $this-> nivel_riesgo,
			'registro_fotografico' => $this-> registro_fotografico,
			'accion_a_tomar' => $this-> accion_a_tomar,
			'responsable_id' => $this-> responsable_id,
			'estado' => $this-> estado,
			'fecha_ejecucion' => $this-> fecha_ejecucion
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Resultados Inspeccion actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = ResultadosInspeccion::where('id', $id);
            $record->delete();
        }
    }
}
