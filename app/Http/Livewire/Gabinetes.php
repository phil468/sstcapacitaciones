<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Gabinete;

class Gabinetes extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $numero_gabinete, $ubicacion, $inspeccion_id, $enrollada_correctamente, $acoples_estado, $limpieza_manguera, $empaques_estado, $pintura_gabinete, $limpieza_gabinete, $vidrio_estado, $senalizacion, $piton_obstruido, $piton_estado, $valvula_principal_estado, $valvula_principal_abierta, $manometro_estado, $valvula_angular_estado, $observaciones;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.gabinetes.view', [
            'gabinetes' => Gabinete::latest()
						->orWhere('numero_gabinete', 'LIKE', $keyWord)
						->orWhere('ubicacion', 'LIKE', $keyWord)
						->orWhere('inspeccion_id', 'LIKE', $keyWord)
						->orWhere('enrollada_correctamente', 'LIKE', $keyWord)
						->orWhere('acoples_estado', 'LIKE', $keyWord)
						->orWhere('limpieza_manguera', 'LIKE', $keyWord)
						->orWhere('empaques_estado', 'LIKE', $keyWord)
						->orWhere('pintura_gabinete', 'LIKE', $keyWord)
						->orWhere('limpieza_gabinete', 'LIKE', $keyWord)
						->orWhere('vidrio_estado', 'LIKE', $keyWord)
						->orWhere('senalizacion', 'LIKE', $keyWord)
						->orWhere('piton_obstruido', 'LIKE', $keyWord)
						->orWhere('piton_estado', 'LIKE', $keyWord)
						->orWhere('valvula_principal_estado', 'LIKE', $keyWord)
						->orWhere('valvula_principal_abierta', 'LIKE', $keyWord)
						->orWhere('manometro_estado', 'LIKE', $keyWord)
						->orWhere('valvula_angular_estado', 'LIKE', $keyWord)
						->orWhere('observaciones', 'LIKE', $keyWord)
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
		$this->numero_gabinete = null;
		$this->ubicacion = null;
		$this->inspeccion_id = null;
		$this->enrollada_correctamente = null;
		$this->acoples_estado = null;
		$this->limpieza_manguera = null;
		$this->empaques_estado = null;
		$this->pintura_gabinete = null;
		$this->limpieza_gabinete = null;
		$this->vidrio_estado = null;
		$this->senalizacion = null;
		$this->piton_obstruido = null;
		$this->piton_estado = null;
		$this->valvula_principal_estado = null;
		$this->valvula_principal_abierta = null;
		$this->manometro_estado = null;
		$this->valvula_angular_estado = null;
		$this->observaciones = null;
    }


	public function create() 
	{
	}
    
    public function store()
    {
        $this->validate([
		'numero_gabinete' => 'required',
		'ubicacion' => 'required',
        ]);

        Gabinete::create([ 
			'numero_gabinete' => $this-> numero_gabinete,
			'ubicacion' => $this-> ubicacion,
			'inspeccion_id' => $this-> inspeccion_id,
			'enrollada_correctamente' => $this-> enrollada_correctamente,
			'acoples_estado' => $this-> acoples_estado,
			'limpieza_manguera' => $this-> limpieza_manguera,
			'empaques_estado' => $this-> empaques_estado,
			'pintura_gabinete' => $this-> pintura_gabinete,
			'limpieza_gabinete' => $this-> limpieza_gabinete,
			'vidrio_estado' => $this-> vidrio_estado,
			'senalizacion' => $this-> senalizacion,
			'piton_obstruido' => $this-> piton_obstruido,
			'piton_estado' => $this-> piton_estado,
			'valvula_principal_estado' => $this-> valvula_principal_estado,
			'valvula_principal_abierta' => $this-> valvula_principal_abierta,
			'manometro_estado' => $this-> manometro_estado,
			'valvula_angular_estado' => $this-> valvula_angular_estado,
			'observaciones' => $this-> observaciones
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Gabinete creado correctamente.');
    }

    public function edit($id)
    {
        $record = Gabinete::findOrFail($id);

        $this->selected_id = $id; 
		$this->numero_gabinete = $record-> numero_gabinete;
		$this->ubicacion = $record-> ubicacion;
		$this->inspeccion_id = $record-> inspeccion_id;
		$this->enrollada_correctamente = $record-> enrollada_correctamente;
		$this->acoples_estado = $record-> acoples_estado;
		$this->limpieza_manguera = $record-> limpieza_manguera;
		$this->empaques_estado = $record-> empaques_estado;
		$this->pintura_gabinete = $record-> pintura_gabinete;
		$this->limpieza_gabinete = $record-> limpieza_gabinete;
		$this->vidrio_estado = $record-> vidrio_estado;
		$this->senalizacion = $record-> senalizacion;
		$this->piton_obstruido = $record-> piton_obstruido;
		$this->piton_estado = $record-> piton_estado;
		$this->valvula_principal_estado = $record-> valvula_principal_estado;
		$this->valvula_principal_abierta = $record-> valvula_principal_abierta;
		$this->manometro_estado = $record-> manometro_estado;
		$this->valvula_angular_estado = $record-> valvula_angular_estado;
		$this->observaciones = $record-> observaciones;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'numero_gabinete' => 'required',
		'ubicacion' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Gabinete::find($this->selected_id);
            $record->update([ 
			'numero_gabinete' => $this-> numero_gabinete,
			'ubicacion' => $this-> ubicacion,
			'inspeccion_id' => $this-> inspeccion_id,
			'enrollada_correctamente' => $this-> enrollada_correctamente,
			'acoples_estado' => $this-> acoples_estado,
			'limpieza_manguera' => $this-> limpieza_manguera,
			'empaques_estado' => $this-> empaques_estado,
			'pintura_gabinete' => $this-> pintura_gabinete,
			'limpieza_gabinete' => $this-> limpieza_gabinete,
			'vidrio_estado' => $this-> vidrio_estado,
			'senalizacion' => $this-> senalizacion,
			'piton_obstruido' => $this-> piton_obstruido,
			'piton_estado' => $this-> piton_estado,
			'valvula_principal_estado' => $this-> valvula_principal_estado,
			'valvula_principal_abierta' => $this-> valvula_principal_abierta,
			'manometro_estado' => $this-> manometro_estado,
			'valvula_angular_estado' => $this-> valvula_angular_estado,
			'observaciones' => $this-> observaciones
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Gabinete actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Gabinete::where('id', $id);
            $record->delete();
        }
    }
}
