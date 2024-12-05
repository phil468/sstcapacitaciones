<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\DetallesEpp;

class DetallesEpps extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $inspeccion_id, $item, $nombre_trabajador, $dni, $cargo, $casco_tiene, $casco_uso, $casco_condicion, $zapatos_tiene, $zapatos_uso, $zapatos_condicion, $lentes_tiene, $lentes_uso, $lentes_condicion, $respirador_tiene, $respirador_uso, $respirador_condicion, $protector_auditivo_tiene, $protector_auditivo_uso, $protector_auditivo_condicion, $guantes_tiene, $guantes_uso, $guantes_condicion, $otros;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.detalles-epp.view', [
            'detallesEpps' => DetallesEpp::latest()
						->orWhere('inspeccion_id', 'LIKE', $keyWord)
						->orWhere('item', 'LIKE', $keyWord)
						->orWhere('nombre_trabajador', 'LIKE', $keyWord)
						->orWhere('dni', 'LIKE', $keyWord)
						->orWhere('cargo', 'LIKE', $keyWord)
						->orWhere('casco_tiene', 'LIKE', $keyWord)
						->orWhere('casco_uso', 'LIKE', $keyWord)
						->orWhere('casco_condicion', 'LIKE', $keyWord)
						->orWhere('zapatos_tiene', 'LIKE', $keyWord)
						->orWhere('zapatos_uso', 'LIKE', $keyWord)
						->orWhere('zapatos_condicion', 'LIKE', $keyWord)
						->orWhere('lentes_tiene', 'LIKE', $keyWord)
						->orWhere('lentes_uso', 'LIKE', $keyWord)
						->orWhere('lentes_condicion', 'LIKE', $keyWord)
						->orWhere('respirador_tiene', 'LIKE', $keyWord)
						->orWhere('respirador_uso', 'LIKE', $keyWord)
						->orWhere('respirador_condicion', 'LIKE', $keyWord)
						->orWhere('protector_auditivo_tiene', 'LIKE', $keyWord)
						->orWhere('protector_auditivo_uso', 'LIKE', $keyWord)
						->orWhere('protector_auditivo_condicion', 'LIKE', $keyWord)
						->orWhere('guantes_tiene', 'LIKE', $keyWord)
						->orWhere('guantes_uso', 'LIKE', $keyWord)
						->orWhere('guantes_condicion', 'LIKE', $keyWord)
						->orWhere('otros', 'LIKE', $keyWord)
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
		$this->item = null;
		$this->nombre_trabajador = null;
		$this->dni = null;
		$this->cargo = null;
		$this->casco_tiene = null;
		$this->casco_uso = null;
		$this->casco_condicion = null;
		$this->zapatos_tiene = null;
		$this->zapatos_uso = null;
		$this->zapatos_condicion = null;
		$this->lentes_tiene = null;
		$this->lentes_uso = null;
		$this->lentes_condicion = null;
		$this->respirador_tiene = null;
		$this->respirador_uso = null;
		$this->respirador_condicion = null;
		$this->protector_auditivo_tiene = null;
		$this->protector_auditivo_uso = null;
		$this->protector_auditivo_condicion = null;
		$this->guantes_tiene = null;
		$this->guantes_uso = null;
		$this->guantes_condicion = null;
		$this->otros = null;
    }


	public function create() 
	{
	}
    
    public function store()
    {
        $this->validate([
		'inspeccion_id' => 'required',
		'item' => 'required',
		'nombre_trabajador' => 'required',
		'casco_tiene' => 'required',
		'casco_uso' => 'required',
		'casco_condicion' => 'required',
		'zapatos_tiene' => 'required',
		'zapatos_uso' => 'required',
		'zapatos_condicion' => 'required',
		'lentes_tiene' => 'required',
		'lentes_uso' => 'required',
		'lentes_condicion' => 'required',
		'respirador_tiene' => 'required',
		'respirador_uso' => 'required',
		'respirador_condicion' => 'required',
		'protector_auditivo_tiene' => 'required',
		'protector_auditivo_uso' => 'required',
		'protector_auditivo_condicion' => 'required',
		'guantes_tiene' => 'required',
		'guantes_uso' => 'required',
		'guantes_condicion' => 'required',
        ]);

        DetallesEpp::create([ 
			'inspeccion_id' => $this-> inspeccion_id,
			'item' => $this-> item,
			'nombre_trabajador' => $this-> nombre_trabajador,
			'dni' => $this-> dni,
			'cargo' => $this-> cargo,
			'casco_tiene' => $this-> casco_tiene,
			'casco_uso' => $this-> casco_uso,
			'casco_condicion' => $this-> casco_condicion,
			'zapatos_tiene' => $this-> zapatos_tiene,
			'zapatos_uso' => $this-> zapatos_uso,
			'zapatos_condicion' => $this-> zapatos_condicion,
			'lentes_tiene' => $this-> lentes_tiene,
			'lentes_uso' => $this-> lentes_uso,
			'lentes_condicion' => $this-> lentes_condicion,
			'respirador_tiene' => $this-> respirador_tiene,
			'respirador_uso' => $this-> respirador_uso,
			'respirador_condicion' => $this-> respirador_condicion,
			'protector_auditivo_tiene' => $this-> protector_auditivo_tiene,
			'protector_auditivo_uso' => $this-> protector_auditivo_uso,
			'protector_auditivo_condicion' => $this-> protector_auditivo_condicion,
			'guantes_tiene' => $this-> guantes_tiene,
			'guantes_uso' => $this-> guantes_uso,
			'guantes_condicion' => $this-> guantes_condicion,
			'otros' => $this-> otros
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Detalles Epp creado correctamente.');
    }

    public function edit($id)
    {
        $record = DetallesEpp::findOrFail($id);

        $this->selected_id = $id; 
		$this->inspeccion_id = $record-> inspeccion_id;
		$this->item = $record-> item;
		$this->nombre_trabajador = $record-> nombre_trabajador;
		$this->dni = $record-> dni;
		$this->cargo = $record-> cargo;
		$this->casco_tiene = $record-> casco_tiene;
		$this->casco_uso = $record-> casco_uso;
		$this->casco_condicion = $record-> casco_condicion;
		$this->zapatos_tiene = $record-> zapatos_tiene;
		$this->zapatos_uso = $record-> zapatos_uso;
		$this->zapatos_condicion = $record-> zapatos_condicion;
		$this->lentes_tiene = $record-> lentes_tiene;
		$this->lentes_uso = $record-> lentes_uso;
		$this->lentes_condicion = $record-> lentes_condicion;
		$this->respirador_tiene = $record-> respirador_tiene;
		$this->respirador_uso = $record-> respirador_uso;
		$this->respirador_condicion = $record-> respirador_condicion;
		$this->protector_auditivo_tiene = $record-> protector_auditivo_tiene;
		$this->protector_auditivo_uso = $record-> protector_auditivo_uso;
		$this->protector_auditivo_condicion = $record-> protector_auditivo_condicion;
		$this->guantes_tiene = $record-> guantes_tiene;
		$this->guantes_uso = $record-> guantes_uso;
		$this->guantes_condicion = $record-> guantes_condicion;
		$this->otros = $record-> otros;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'inspeccion_id' => 'required',
		'item' => 'required',
		'nombre_trabajador' => 'required',
		'casco_tiene' => 'required',
		'casco_uso' => 'required',
		'casco_condicion' => 'required',
		'zapatos_tiene' => 'required',
		'zapatos_uso' => 'required',
		'zapatos_condicion' => 'required',
		'lentes_tiene' => 'required',
		'lentes_uso' => 'required',
		'lentes_condicion' => 'required',
		'respirador_tiene' => 'required',
		'respirador_uso' => 'required',
		'respirador_condicion' => 'required',
		'protector_auditivo_tiene' => 'required',
		'protector_auditivo_uso' => 'required',
		'protector_auditivo_condicion' => 'required',
		'guantes_tiene' => 'required',
		'guantes_uso' => 'required',
		'guantes_condicion' => 'required',
        ]);

        if ($this->selected_id) {
			$record = DetallesEpp::find($this->selected_id);
            $record->update([ 
			'inspeccion_id' => $this-> inspeccion_id,
			'item' => $this-> item,
			'nombre_trabajador' => $this-> nombre_trabajador,
			'dni' => $this-> dni,
			'cargo' => $this-> cargo,
			'casco_tiene' => $this-> casco_tiene,
			'casco_uso' => $this-> casco_uso,
			'casco_condicion' => $this-> casco_condicion,
			'zapatos_tiene' => $this-> zapatos_tiene,
			'zapatos_uso' => $this-> zapatos_uso,
			'zapatos_condicion' => $this-> zapatos_condicion,
			'lentes_tiene' => $this-> lentes_tiene,
			'lentes_uso' => $this-> lentes_uso,
			'lentes_condicion' => $this-> lentes_condicion,
			'respirador_tiene' => $this-> respirador_tiene,
			'respirador_uso' => $this-> respirador_uso,
			'respirador_condicion' => $this-> respirador_condicion,
			'protector_auditivo_tiene' => $this-> protector_auditivo_tiene,
			'protector_auditivo_uso' => $this-> protector_auditivo_uso,
			'protector_auditivo_condicion' => $this-> protector_auditivo_condicion,
			'guantes_tiene' => $this-> guantes_tiene,
			'guantes_uso' => $this-> guantes_uso,
			'guantes_condicion' => $this-> guantes_condicion,
			'otros' => $this-> otros
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Detalles Epp actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = DetallesEpp::where('id', $id);
            $record->delete();
        }
    }
}
