<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ConfiguracionGeneral;

class ConfiguracionGenerals extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $valor, $tipo_de_dato_id, $created_by, $updated_by, $deleted_by;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.configuracion-general.view', [
            'configuracionGenerals' => ConfiguracionGeneral::latest()
						->orWhere('name', 'LIKE', $keyWord)
						->orWhere('valor', 'LIKE', $keyWord)
						->orWhere('tipo_de_dato_id', 'LIKE', $keyWord)
						->orWhere('created_by', 'LIKE', $keyWord)
						->orWhere('updated_by', 'LIKE', $keyWord)
						->orWhere('deleted_by', 'LIKE', $keyWord)
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
		$this->valor = null;
		$this->tipo_de_dato_id = null;
		$this->created_by = null;
		$this->updated_by = null;
		$this->deleted_by = null;
    }


	public function create() 
	{
	}
    
    public function store()
    {
        $this->validate([
            'name' => 'required',
            'valor' => 'required',
        ]);

        ConfiguracionGeneral::create([ 
			'name' => $this-> name,
			'valor' => $this-> valor,
			'tipo_de_dato_id' => $this-> tipo_de_dato_id,
			'created_by' => $this-> created_by,
			'updated_by' => $this-> updated_by,
			'deleted_by' => $this-> deleted_by
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Configuracion General creado correctamente.');
    }

    public function edit($id)
    {
        $record = ConfiguracionGeneral::findOrFail($id);

        $this->selected_id = $id; 
		$this->name = $record-> name;
		$this->valor = $record-> valor;
		$this->tipo_de_dato_id = $record-> tipo_de_dato_id;
		$this->created_by = $record-> created_by;
		$this->updated_by = $record-> updated_by;
		$this->deleted_by = $record-> deleted_by;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'valor' => 'required',
        ]);

        if ($this->selected_id) {
			$record = ConfiguracionGeneral::find($this->selected_id);
            $record->update([ 
			// 'name' => $this-> name,
			'valor' => $this-> valor,
			'tipo_de_dato_id' => $this-> tipo_de_dato_id,
			'created_by' => $this-> created_by,
			'updated_by' => $this-> updated_by,
			'deleted_by' => $this-> deleted_by
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Configuracion General actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = ConfiguracionGeneral::where('id', $id);
            $record->delete();
        }
    }
}
