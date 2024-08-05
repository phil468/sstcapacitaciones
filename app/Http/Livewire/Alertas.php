<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Alerta;

class Alertas extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $estado;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.alertas.view', [
            'alertas' => Alerta::latest()
						->orWhere('name', 'LIKE', $keyWord)
						->orWhere('estado', 'LIKE', $keyWord)
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
		$this->name = null;
		$this->estado = null;
    }


	public function create() 
	{
	}
    
    public function store()
    {
        $this->validate([
        ]);

        Alerta::create([ 
			'name' => $this-> name,
			'estado' => $this-> estado
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Alerta creado correctamente.');
    }

    public function edit($id)
    {
        $record = Alerta::findOrFail($id);

        $this->selected_id = $id; 
		$this->name = $record-> name;
		$this->estado = $record-> estado;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
        ]);

        if ($this->selected_id) {
			$record = Alerta::find($this->selected_id);
            $record->update([ 
			'name' => $this-> name,
			'estado' => $this-> estado
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Alerta actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Alerta::where('id', $id);
            $record->delete();
        }
    }
}
