<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Alerta;

class Alertas extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $estado, $dias, $condicion, $campo;
    public $updateMode = false;

    public function render()
    {
        $keyWord = '%'.$this->keyWord .'%';
        return view('livewire.alertas.view', [
            'alertas' => Alerta::latest()
                        ->orWhere('name', 'LIKE', $keyWord)
                        ->orWhere('estado', 'LIKE', $keyWord)
                        ->orWhere('dias', 'LIKE', $keyWord)
                        ->orWhere('condicion', 'LIKE', $keyWord)
                        ->orWhere('campo', 'LIKE', $keyWord)
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
        $this->dias = null;
        $this->condicion = null;
        $this->campo = null;
    }

    public function create() 
    {
    }
    
    public function store()
    {
        $this->validate([
            'name' => 'required',
            'estado' => 'required|boolean',
            'dias' => 'required|integer',
            'condicion' => 'required',
            'campo' => 'required',
        ]);

        Alerta::create([ 
            'name' => $this->name,
            'estado' => $this->estado,
            'dias' => $this->dias,
            'condicion' => $this->condicion,
            'campo' => $this->campo,
        ]);
        
        $this->resetInput();
        $this->emit('closeModal');
        session()->flash('message', 'Alerta creada correctamente.');
    }

    public function edit($id)
    {
        $record = Alerta::findOrFail($id);

        $this->selected_id = $id; 
        $this->name = $record->name;
        $this->estado = $record->estado;
        $this->dias = $record->dias;
        $this->condicion = $record->condicion;
        $this->campo = $record->campo;
        
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'estado' => 'required|boolean',
            'dias' => 'required|integer',
            'condicion' => 'required',
            'campo' => 'required',
        ]);

        if ($this->selected_id) {
            $record = Alerta::find($this->selected_id);
            $record->update([ 
                'name' => $this->name,
                'estado' => $this->estado,
                'dias' => $this->dias,
                'condicion' => $this->condicion,
                'campo' => $this->campo,
            ]);

            $this->resetInput();
            $this->updateMode = false;
            $this->emit('closeModal');
            session()->flash('message', 'Alerta actualizada correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Alerta::where('id', $id);
            $record->delete();
        }
    }
}