<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\InspeccionesEpp;

class InspeccionesEpps extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $numero_inspeccion, $inspector, $firma_inspector, $turno, $condicion, $riesgo, $actividad, $fecha;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.inspecciones-epp.view', [
            'inspeccionesEpps' => InspeccionesEpp::latest()
						->orWhere('numero_inspeccion', 'LIKE', $keyWord)
						->orWhere('inspector', 'LIKE', $keyWord)
						->orWhere('firma_inspector', 'LIKE', $keyWord)
						->orWhere('turno', 'LIKE', $keyWord)
						->orWhere('condicion', 'LIKE', $keyWord)
						->orWhere('riesgo', 'LIKE', $keyWord)
						->orWhere('actividad', 'LIKE', $keyWord)
						->orWhere('fecha', 'LIKE', $keyWord)
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
		$this->numero_inspeccion = null;
		$this->inspector = null;
		$this->firma_inspector = null;
		$this->turno = null;
		$this->condicion = null;
		$this->riesgo = null;
		$this->actividad = null;
		$this->fecha = null;
    }


	public function create() 
	{
	}
    
    public function store()
    {
        $this->validate([
		'numero_inspeccion' => 'required',
		'inspector' => 'required',
		'turno' => 'required',
		'condicion' => 'required',
		'riesgo' => 'required',
		'fecha' => 'required',
        ]);

        InspeccionesEpp::create([ 
			'numero_inspeccion' => $this-> numero_inspeccion,
			'inspector' => $this-> inspector,
			'firma_inspector' => $this-> firma_inspector,
			'turno' => $this-> turno,
			'condicion' => $this-> condicion,
			'riesgo' => $this-> riesgo,
			'actividad' => $this-> actividad,
			'fecha' => $this-> fecha
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Inspecciones Epp creado correctamente.');
    }

    public function edit($id)
    {
        $record = InspeccionesEpp::findOrFail($id);

        $this->selected_id = $id; 
		$this->numero_inspeccion = $record-> numero_inspeccion;
		$this->inspector = $record-> inspector;
		$this->firma_inspector = $record-> firma_inspector;
		$this->turno = $record-> turno;
		$this->condicion = $record-> condicion;
		$this->riesgo = $record-> riesgo;
		$this->actividad = $record-> actividad;
		$this->fecha = $record-> fecha;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'numero_inspeccion' => 'required',
		'inspector' => 'required',
		'turno' => 'required',
		'condicion' => 'required',
		'riesgo' => 'required',
		'fecha' => 'required',
        ]);

        if ($this->selected_id) {
			$record = InspeccionesEpp::find($this->selected_id);
            $record->update([ 
			'numero_inspeccion' => $this-> numero_inspeccion,
			'inspector' => $this-> inspector,
			'firma_inspector' => $this-> firma_inspector,
			'turno' => $this-> turno,
			'condicion' => $this-> condicion,
			'riesgo' => $this-> riesgo,
			'actividad' => $this-> actividad,
			'fecha' => $this-> fecha
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Inspecciones Epp actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = InspeccionesEpp::where('id', $id);
            $record->delete();
        }
    }
}
