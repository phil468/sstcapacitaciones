<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Solucione;

class Soluciones extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $pregunta_id, $opcion_id;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.soluciones.view', [
            'soluciones' => Solucione::latest()
						->orWhere('pregunta_id', 'LIKE', $keyWord)
						->orWhere('opcion_id', 'LIKE', $keyWord)
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
		$this->pregunta_id = null;
		$this->opcion_id = null;
    }


	public function create() 
	{
	}
    
    public function store()
    {
        $this->validate([
        ]);

        Solucione::create([ 
			'pregunta_id' => $this-> pregunta_id,
			'opcion_id' => $this-> opcion_id
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Solucione creado correctamente.');
    }

    public function edit($id)
    {
        $record = Solucione::findOrFail($id);

        $this->selected_id = $id; 
		$this->pregunta_id = $record-> pregunta_id;
		$this->opcion_id = $record-> opcion_id;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
        ]);

        if ($this->selected_id) {
			$record = Solucione::find($this->selected_id);
            $record->update([ 
			'pregunta_id' => $this-> pregunta_id,
			'opcion_id' => $this-> opcion_id
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Solucione actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Solucione::where('id', $id);
            $record->delete();
        }
    }
}
