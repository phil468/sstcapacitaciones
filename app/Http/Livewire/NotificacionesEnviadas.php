<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\NotificacionesEnviada;

class NotificacionesEnviadas extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $capacitacion_id, $personal_id;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.notificaciones-enviadas.view', [
            'notificacionesEnviadas' => NotificacionesEnviada::latest()
						->orWhere('capacitacion_id', 'LIKE', $keyWord)
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
		$this->capacitacion_id = null;
		$this->personal_id = null;
    }


	public function create() 
	{
	}
    
    public function store()
    {
        $this->validate([
		'capacitacion_id' => 'required',
		'personal_id' => 'required',
        ]);

        NotificacionesEnviada::create([ 
			'capacitacion_id' => $this-> capacitacion_id,
			'personal_id' => $this-> personal_id
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Notificaciones Enviada creado correctamente.');
    }

    public function edit($id)
    {
        $record = NotificacionesEnviada::findOrFail($id);

        $this->selected_id = $id; 
		$this->capacitacion_id = $record-> capacitacion_id;
		$this->personal_id = $record-> personal_id;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'capacitacion_id' => 'required',
		'personal_id' => 'required',
        ]);

        if ($this->selected_id) {
			$record = NotificacionesEnviada::find($this->selected_id);
            $record->update([ 
			'capacitacion_id' => $this-> capacitacion_id,
			'personal_id' => $this-> personal_id
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Notificaciones Enviada actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = NotificacionesEnviada::where('id', $id);
            $record->delete();
        }
    }
}
