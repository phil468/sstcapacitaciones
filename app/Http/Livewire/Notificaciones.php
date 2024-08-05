<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Notificacione;

class Notificaciones extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $estado;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.notificaciones.view', [
            'notificaciones' => Notificacione::latest()
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

        Notificacione::create([ 
			'name' => $this-> name,
			'estado' => $this-> estado
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Notificacione creado correctamente.');
    }

    public function edit($id)
    {
        $record = Notificacione::findOrFail($id);

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
			$record = Notificacione::find($this->selected_id);
            $record->update([ 
			'name' => $this-> name,
			'estado' => $this-> estado
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Notificacione actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Notificacione::where('id', $id);
            $record->delete();
        }
    }
}
