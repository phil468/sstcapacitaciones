<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TipoDePregunta;

class TipoDePreguntas extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $estado;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.tipo-de-preguntas.view', [
            'tipoDePreguntas' => TipoDePregunta::latest()
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

        TipoDePregunta::create([ 
			'name' => $this-> name,
			'estado' => $this-> estado
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Tipo De Pregunta creado correctamente.');
    }

    public function edit($id)
    {
        $record = TipoDePregunta::findOrFail($id);

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
			$record = TipoDePregunta::find($this->selected_id);
            $record->update([ 
			'name' => $this-> name,
			'estado' => $this-> estado
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Tipo De Pregunta actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = TipoDePregunta::where('id', $id);
            $record->delete();
        }
    }
}
