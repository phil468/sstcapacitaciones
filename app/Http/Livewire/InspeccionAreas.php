<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\InspeccionArea;

class InspeccionAreas extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $inspeccion_id, $area_id;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.inspeccionAreas.view', [
            'inspeccionAreas' => InspeccionArea::latest()
						->orWhere('inspeccion_id', 'LIKE', $keyWord)
						->orWhere('area_id', 'LIKE', $keyWord)
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
		$this->area_id = null;
    }

    public function store()
    {
        $this->validate([
		'inspeccion_id' => 'required',
		'area_id' => 'required',
        ]);

        InspeccionArea::create([ 
			'inspeccion_id' => $this-> inspeccion_id,
			'area_id' => $this-> area_id
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'InspeccionArea Successfully created.');
    }

    public function edit($id)
    {
        $record = InspeccionArea::findOrFail($id);

        $this->selected_id = $id; 
		$this->inspeccion_id = $record-> inspeccion_id;
		$this->area_id = $record-> area_id;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'inspeccion_id' => 'required',
		'area_id' => 'required',
        ]);

        if ($this->selected_id) {
			$record = InspeccionArea::find($this->selected_id);
            $record->update([ 
			'inspeccion_id' => $this-> inspeccion_id,
			'area_id' => $this-> area_id
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'InspeccionArea Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = InspeccionArea::where('id', $id);
            $record->delete();
        }
    }
}
