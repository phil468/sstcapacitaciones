<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\EvaluadorHasEvaluado;

class EvaluadorHasEvaluados extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';

    public function mount()
    {

    }
    
    public function render()
    {

    }
    
    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }
	
    private function resetInput()
    {		
		$this->evaluador_id = null;
		$this->evaluado_id = null;
		$this->evaluacion = null;
    }

    public function store()
    {
        $this->validate([
        ]);

        EvaluadorHasEvaluado::create([ 
			'evaluador_id' => $this-> evaluador_id,
			'evaluado_id' => $this-> evaluado_id,
			'evaluacion' => $this-> evaluacion
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'EvaluadorHasEvaluado creado correctamente.');
    }

    public function edit($id)
    {
        $record = EvaluadorHasEvaluado::findOrFail($id);

        $this->selected_id = $id; 
		$this->evaluador_id = $record-> evaluador_id;
		$this->evaluado_id = $record-> evaluado_id;
		$this->evaluacion = $record-> evaluacion;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
        ]);

        if ($this->selected_id) {
			$record = EvaluadorHasEvaluado::find($this->selected_id);
            $record->update([ 
			'evaluador_id' => $this-> evaluador_id,
			'evaluado_id' => $this-> evaluado_id,
			'evaluacion' => $this-> evaluacion
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'EvaluadorHasEvaluado actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = EvaluadorHasEvaluado::where('id', $id);
            $record->delete();
        }
    }
}
