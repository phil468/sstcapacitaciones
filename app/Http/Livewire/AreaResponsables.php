<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AreaResponsable;

class AreaResponsables extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $area_id, $personal_id;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.area-responsable.view', [
            'areaResponsables' => AreaResponsable::latest()
						->orWhere('area_id', 'LIKE', $keyWord)
						->orWhere('personal_id', 'LIKE', $keyWord)
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
		$this->area_id = null;
		$this->personal_id = null;
    }


	public function create() 
	{
	}
    
    public function store()
    {
        $this->validate([
		'area_id' => 'required',
		'personal_id' => 'required',
        ]);

        AreaResponsable::create([ 
			'area_id' => $this-> area_id,
			'personal_id' => $this-> personal_id
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Area Responsable creado correctamente.');
    }

    public function edit($id)
    {
        $record = AreaResponsable::findOrFail($id);

        $this->selected_id = $id; 
		$this->area_id = $record-> area_id;
		$this->personal_id = $record-> personal_id;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'area_id' => 'required',
		'personal_id' => 'required',
        ]);

        if ($this->selected_id) {
			$record = AreaResponsable::find($this->selected_id);
            $record->update([ 
			'area_id' => $this-> area_id,
			'personal_id' => $this-> personal_id
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Area Responsable actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = AreaResponsable::where('id', $id);
            $record->delete();
        }
    }
}
