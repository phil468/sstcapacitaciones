<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AlertasLevantamiento;

class AlertasLevantamientos extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $resultado_inspeccion_id, $registro_fotografico, $levantado;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.alertas-levantamiento.view', [
            'alertasLevantamientos' => AlertasLevantamiento::latest()
						->orWhere('resultado_inspeccion_id', 'LIKE', $keyWord)
						->orWhere('registro_fotografico', 'LIKE', $keyWord)
						->orWhere('levantado', 'LIKE', $keyWord)
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
		$this->resultado_inspeccion_id = null;
		$this->registro_fotografico = null;
		$this->levantado = null;
    }


	public function create() 
	{
	}
    
    public function store()
    {
        $this->validate([
		'resultado_inspeccion_id' => 'required',
        ]);

        AlertasLevantamiento::create([ 
			'resultado_inspeccion_id' => $this-> resultado_inspeccion_id,
			'registro_fotografico' => $this-> registro_fotografico,
			'levantado' => $this-> levantado
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Alertas Levantamiento creado correctamente.');
    }

    public function edit($id)
    {
        $record = AlertasLevantamiento::findOrFail($id);

        $this->selected_id = $id; 
		$this->resultado_inspeccion_id = $record-> resultado_inspeccion_id;
		$this->registro_fotografico = $record-> registro_fotografico;
		$this->levantado = $record-> levantado;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'resultado_inspeccion_id' => 'required',
        ]);

        if ($this->selected_id) {
			$record = AlertasLevantamiento::find($this->selected_id);
            $record->update([ 
			'resultado_inspeccion_id' => $this-> resultado_inspeccion_id,
			'registro_fotografico' => $this-> registro_fotografico,
			'levantado' => $this-> levantado
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Alertas Levantamiento actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = AlertasLevantamiento::where('id', $id);
            $record->delete();
        }
    }
}
