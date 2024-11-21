<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Inspeccione;

class Inspecciones extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $empresa_id, $area_id, $tipo_inspeccion, $vigencia_inicio, $vigencia_fin, $comentario;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.inspecciones.view', [
            'inspecciones' => Inspeccione::latest()
						->orWhere('empresa_id', 'LIKE', $keyWord)
						->orWhere('area_id', 'LIKE', $keyWord)
						->orWhere('tipo_inspeccion', 'LIKE', $keyWord)
						->orWhere('vigencia_inicio', 'LIKE', $keyWord)
						->orWhere('vigencia_fin', 'LIKE', $keyWord)
						->orWhere('comentario', 'LIKE', $keyWord)
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
		$this->empresa_id = null;
		$this->area_id = null;
		$this->tipo_inspeccion = null;
		$this->vigencia_inicio = null;
		$this->vigencia_fin = null;
		$this->comentario = null;
    }


	public function create() 
	{
	}
    
    public function store()
    {
        $this->validate([
		'empresa_id' => 'required',
		'area_id' => 'required',
		'tipo_inspeccion' => 'required',
        ]);

        Inspeccione::create([ 
			'empresa_id' => $this-> empresa_id,
			'area_id' => $this-> area_id,
			'tipo_inspeccion' => $this-> tipo_inspeccion,
			'vigencia_inicio' => $this-> vigencia_inicio,
			'vigencia_fin' => $this-> vigencia_fin,
			'comentario' => $this-> comentario
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Inspeccione creado correctamente.');
    }

    public function edit($id)
    {
        $record = Inspeccione::findOrFail($id);

        $this->selected_id = $id; 
		$this->empresa_id = $record-> empresa_id;
		$this->area_id = $record-> area_id;
		$this->tipo_inspeccion = $record-> tipo_inspeccion;
		$this->vigencia_inicio = $record-> vigencia_inicio;
		$this->vigencia_fin = $record-> vigencia_fin;
		$this->comentario = $record-> comentario;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'empresa_id' => 'required',
		'area_id' => 'required',
		'tipo_inspeccion' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Inspeccione::find($this->selected_id);
            $record->update([ 
			'empresa_id' => $this-> empresa_id,
			'area_id' => $this-> area_id,
			'tipo_inspeccion' => $this-> tipo_inspeccion,
			'vigencia_inicio' => $this-> vigencia_inicio,
			'vigencia_fin' => $this-> vigencia_fin,
			'comentario' => $this-> comentario
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Inspeccione actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Inspeccione::where('id', $id);
            $record->delete();
        }
    }
}
