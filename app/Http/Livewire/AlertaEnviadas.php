<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AlertaEnviada;

class AlertaEnviadas extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $capacitacion_has_personal_id, $fecha_envio;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.alerta-enviadas.view', [
            'alertaEnviadas' => AlertaEnviada::latest()
						->orWhere('capacitacion_has_personal_id', 'LIKE', $keyWord)
						->orWhere('fecha_envio', 'LIKE', $keyWord)
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
		$this->capacitacion_has_personal_id = null;
		$this->fecha_envio = null;
    }


	public function create() 
	{
	}
    
    public function store()
    {
        $this->validate([
		'capacitacion_has_personal_id' => 'required',
		'fecha_envio' => 'required',
        ]);

        AlertaEnviada::create([ 
			'capacitacion_has_personal_id' => $this-> capacitacion_has_personal_id,
			'fecha_envio' => $this-> fecha_envio
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Alerta Enviada creado correctamente.');
    }

    public function edit($id)
    {
        $record = AlertaEnviada::findOrFail($id);

        $this->selected_id = $id; 
		$this->capacitacion_has_personal_id = $record-> capacitacion_has_personal_id;
		$this->fecha_envio = $record-> fecha_envio;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'capacitacion_has_personal_id' => 'required',
		'fecha_envio' => 'required',
        ]);

        if ($this->selected_id) {
			$record = AlertaEnviada::find($this->selected_id);
            $record->update([ 
			'capacitacion_has_personal_id' => $this-> capacitacion_has_personal_id,
			'fecha_envio' => $this-> fecha_envio
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Alerta Enviada actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = AlertaEnviada::where('id', $id);
            $record->delete();
        }
    }
}
