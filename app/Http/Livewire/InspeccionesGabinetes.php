<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\InspeccionesGabinete;

class InspeccionesGabinetes extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $fecha_inspeccion, $hora_inspeccion, $inspector, $lugar;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.inspecciones-gabinetes.view', [
            'inspeccionesGabinetes' => InspeccionesGabinete::latest()
						->orWhere('fecha_inspeccion', 'LIKE', $keyWord)
						->orWhere('hora_inspeccion', 'LIKE', $keyWord)
						->orWhere('inspector', 'LIKE', $keyWord)
						->orWhere('lugar', 'LIKE', $keyWord)
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
		$this->fecha_inspeccion = null;
		$this->hora_inspeccion = null;
		$this->inspector = null;
		$this->lugar = null;
    }


	public function create() 
	{
	}
    
    public function store()
    {
        $this->validate([
		'fecha_inspeccion' => 'required',
		'hora_inspeccion' => 'required',
		'inspector' => 'required',
        ]);

        InspeccionesGabinete::create([ 
			'fecha_inspeccion' => $this-> fecha_inspeccion,
			'hora_inspeccion' => $this-> hora_inspeccion,
			'inspector' => $this-> inspector,
			'lugar' => $this-> lugar
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Inspecciones Gabinete creado correctamente.');
    }

    public function edit($id)
    {
        $record = InspeccionesGabinete::findOrFail($id);

        $this->selected_id = $id; 
		$this->fecha_inspeccion = $record-> fecha_inspeccion;
		$this->hora_inspeccion = $record-> hora_inspeccion;
		$this->inspector = $record-> inspector;
		$this->lugar = $record-> lugar;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'fecha_inspeccion' => 'required',
		'hora_inspeccion' => 'required',
		'inspector' => 'required',
        ]);

        if ($this->selected_id) {
			$record = InspeccionesGabinete::find($this->selected_id);
            $record->update([ 
			'fecha_inspeccion' => $this-> fecha_inspeccion,
			'hora_inspeccion' => $this-> hora_inspeccion,
			'inspector' => $this-> inspector,
			'lugar' => $this-> lugar
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Inspecciones Gabinete actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = InspeccionesGabinete::where('id', $id);
            $record->delete();
        }
    }
}
