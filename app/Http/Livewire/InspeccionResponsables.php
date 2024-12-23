<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\InspeccionResponsable;

class InspeccionResponsables extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $inspeccion_id, $user_id, $cargo;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.inspeccionResponsables.view', [
            'inspeccionResponsables' => InspeccionResponsable::latest()
						->orWhere('inspeccion_id', 'LIKE', $keyWord)
						->orWhere('user_id', 'LIKE', $keyWord)
						->orWhere('cargo', 'LIKE', $keyWord)
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
		$this->user_id = null;
		$this->cargo = null;
    }

    public function store()
    {
        $this->validate([
		'inspeccion_id' => 'required',
		'user_id' => 'required',
		'cargo' => 'required',
        ]);

        InspeccionResponsable::create([ 
			'inspeccion_id' => $this-> inspeccion_id,
			'user_id' => $this-> user_id,
			'cargo' => $this-> cargo
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'InspeccionResponsable Successfully created.');
    }

    public function edit($id)
    {
        $record = InspeccionResponsable::findOrFail($id);

        $this->selected_id = $id; 
		$this->inspeccion_id = $record-> inspeccion_id;
		$this->user_id = $record-> user_id;
		$this->cargo = $record-> cargo;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'inspeccion_id' => 'required',
		'user_id' => 'required',
		'cargo' => 'required',
        ]);

        if ($this->selected_id) {
			$record = InspeccionResponsable::find($this->selected_id);
            $record->update([ 
			'inspeccion_id' => $this-> inspeccion_id,
			'user_id' => $this-> user_id,
			'cargo' => $this-> cargo
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'InspeccionResponsable Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = InspeccionResponsable::where('id', $id);
            $record->delete();
        }
    }
}
