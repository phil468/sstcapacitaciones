<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Prueba;

class Pruebas extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $personal_id, $capacitacion_id, $puntaje, $correctas, $incorrectas, $fecha_inicio, $fecha_fin, $duracion, $status_id;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.pruebas.view', [
            'pruebas' => Prueba::latest()
						->orWhere('personal_id', 'LIKE', $keyWord)
						->orWhere('capacitacion_id', 'LIKE', $keyWord)
						->orWhere('puntaje', 'LIKE', $keyWord)
						->orWhere('correctas', 'LIKE', $keyWord)
						->orWhere('incorrectas', 'LIKE', $keyWord)
						->orWhere('fecha_inicio', 'LIKE', $keyWord)
						->orWhere('fecha_fin', 'LIKE', $keyWord)
						->orWhere('duracion', 'LIKE', $keyWord)
						->orWhere('status_id', 'LIKE', $keyWord)
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
		$this->personal_id = null;
		$this->capacitacion_id = null;
		$this->puntaje = null;
		$this->correctas = null;
		$this->incorrectas = null;
		$this->fecha_inicio = null;
		$this->fecha_fin = null;
		$this->duracion = null;
		$this->status_id = null;
    }


	public function create() 
	{
	}
    
    public function store()
    {
        $this->validate([
		'personal_id' => 'required',
		'capacitacion_id' => 'required',
		'puntaje' => 'required',
		'correctas' => 'required',
		'incorrectas' => 'required',
		'fecha_inicio' => 'required',
		'fecha_fin' => 'required',
		'duracion' => 'required',
		'status_id' => 'required',
        ]);

        Prueba::create([ 
			'personal_id' => $this-> personal_id,
			'capacitacion_id' => $this-> capacitacion_id,
			'puntaje' => $this-> puntaje,
			'correctas' => $this-> correctas,
			'incorrectas' => $this-> incorrectas,
			'fecha_inicio' => $this-> fecha_inicio,
			'fecha_fin' => $this-> fecha_fin,
			'duracion' => $this-> duracion,
			'status_id' => $this-> status_id
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Prueba creado correctamente.');
    }

    public function edit($id)
    {
        $record = Prueba::findOrFail($id);

        $this->selected_id = $id; 
		$this->personal_id = $record-> personal_id;
		$this->capacitacion_id = $record-> capacitacion_id;
		$this->puntaje = $record-> puntaje;
		$this->correctas = $record-> correctas;
		$this->incorrectas = $record-> incorrectas;
		$this->fecha_inicio = $record-> fecha_inicio;
		$this->fecha_fin = $record-> fecha_fin;
		$this->duracion = $record-> duracion;
		$this->status_id = $record-> status_id;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'personal_id' => 'required',
		'capacitacion_id' => 'required',
		'puntaje' => 'required',
		'correctas' => 'required',
		'incorrectas' => 'required',
		'fecha_inicio' => 'required',
		'fecha_fin' => 'required',
		'duracion' => 'required',
		'status_id' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Prueba::find($this->selected_id);
            $record->update([ 
			'personal_id' => $this-> personal_id,
			'capacitacion_id' => $this-> capacitacion_id,
			'puntaje' => $this-> puntaje,
			'correctas' => $this-> correctas,
			'incorrectas' => $this-> incorrectas,
			'fecha_inicio' => $this-> fecha_inicio,
			'fecha_fin' => $this-> fecha_fin,
			'duracion' => $this-> duracion,
			'status_id' => $this-> status_id
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Prueba actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Prueba::where('id', $id);
            $record->delete();
        }
    }
}
