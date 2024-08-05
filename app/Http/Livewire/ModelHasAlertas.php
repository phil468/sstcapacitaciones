<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ModelHasAlerta;

class ModelHasAlertas extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $model_type, $model_id, $value, $alerta_id;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.model-has-alertas.view', [
            'modelHasAlertas' => ModelHasAlerta::latest()
						->orWhere('model_type', 'LIKE', $keyWord)
						->orWhere('model_id', 'LIKE', $keyWord)
						->orWhere('value', 'LIKE', $keyWord)
						->orWhere('alerta_id', 'LIKE', $keyWord)
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
		$this->model_type = null;
		$this->model_id = null;
		$this->value = null;
		$this->alerta_id = null;
    }


	public function create() 
	{
	}
    
    public function store()
    {
        $this->validate([
        ]);

        ModelHasAlerta::create([ 
			'model_type' => $this-> model_type,
			'model_id' => $this-> model_id,
			'value' => $this-> value,
			'alerta_id' => $this-> alerta_id
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Model Has Alerta creado correctamente.');
    }

    public function edit($id)
    {
        $record = ModelHasAlerta::findOrFail($id);

        $this->selected_id = $id; 
		$this->model_type = $record-> model_type;
		$this->model_id = $record-> model_id;
		$this->value = $record-> value;
		$this->alerta_id = $record-> alerta_id;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
        ]);

        if ($this->selected_id) {
			$record = ModelHasAlerta::find($this->selected_id);
            $record->update([ 
			'model_type' => $this-> model_type,
			'model_id' => $this-> model_id,
			'value' => $this-> value,
			'alerta_id' => $this-> alerta_id
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Model Has Alerta actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = ModelHasAlerta::where('id', $id);
            $record->delete();
        }
    }
}
