<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Inspectore;

class Inspectores extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $personal_id, $estado;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.inspectores.view', [
            'inspectores' => Inspectore::latest()
						->orWhere('personal_id', 'LIKE', $keyWord)
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
		$this->personal_id = null;
		$this->estado = null;
    }


	public function create() 
	{
	}
    
    public function store()
    {
        $this->validate([
		'personal_id' => 'required',
        ]);

        Inspectore::create([ 
			'personal_id' => $this-> personal_id,
			'estado' => $this-> estado
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Inspectore creado correctamente.');
    }

    public function edit($id)
    {
        $record = Inspectore::findOrFail($id);

        $this->selected_id = $id; 
		$this->personal_id = $record-> personal_id;
		$this->estado = $record-> estado;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'personal_id' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Inspectore::find($this->selected_id);
            $record->update([ 
			'personal_id' => $this-> personal_id,
			'estado' => $this-> estado
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Inspectore actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Inspectore::where('id', $id);
            $record->delete();
        }
    }
}
