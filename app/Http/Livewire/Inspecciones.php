<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Inspeccione;

class Inspecciones extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $empresa_id, $area_id, $tipo_inspeccion, $vigencia_inicio, $vigencia_fin, $comentario, $razon_social, $ruc, $domicilio, $actividad_economica, $numero_registro, $tipo_inspeccion_otro, $fecha_inspeccion, $hora_inspeccion;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.inspecciones.view', [
            'inspecciones' => Inspeccione::latest()
						->orWhere('empresa_id', 'LIKE', $keyWord)
						->orWhere('area_id', 'LIKE', $keyWord)
						->orWhere('tipo_inspeccion', 'LIKE', $keyWord)
						->orWhere('vigencia_inicio', 'LIKE', $keyWord)
						->orWhere('vigencia_fin', 'LIKE', $keyWord)
						->orWhere('comentario', 'LIKE', $keyWord)
						->orWhere('razon_social', 'LIKE', $keyWord)
						->orWhere('ruc', 'LIKE', $keyWord)
						->orWhere('domicilio', 'LIKE', $keyWord)
						->orWhere('actividad_economica', 'LIKE', $keyWord)
						->orWhere('numero_registro', 'LIKE', $keyWord)
						->orWhere('tipo_inspeccion_otro', 'LIKE', $keyWord)
						->orWhere('fecha_inspeccion', 'LIKE', $keyWord)
						->orWhere('hora_inspeccion', 'LIKE', $keyWord)
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
		$this->empresa_id = null;
		$this->area_id = null;
		$this->tipo_inspeccion = null;
		$this->vigencia_inicio = null;
		$this->vigencia_fin = null;
		$this->comentario = null;
		$this->razon_social = null;
		$this->ruc = null;
		$this->domicilio = null;
		$this->actividad_economica = null;
		$this->numero_registro = null;
		$this->tipo_inspeccion_otro = null;
		$this->fecha_inspeccion = null;
		$this->hora_inspeccion = null;
    }

    public function store()
    {
        $this->validate([
		'empresa_id' => 'required',
		'area_id' => 'required',
		'tipo_inspeccion' => 'required',
		'razon_social' => 'required',
		'ruc' => 'required',
		'domicilio' => 'required',
		'actividad_economica' => 'required',
		'numero_registro' => 'required',
        ]);

        Inspeccione::create([ 
			'empresa_id' => $this-> empresa_id,
			'area_id' => $this-> area_id,
			'tipo_inspeccion' => $this-> tipo_inspeccion,
			'vigencia_inicio' => $this-> vigencia_inicio,
			'vigencia_fin' => $this-> vigencia_fin,
			'comentario' => $this-> comentario,
			'razon_social' => $this-> razon_social,
			'ruc' => $this-> ruc,
			'domicilio' => $this-> domicilio,
			'actividad_economica' => $this-> actividad_economica,
			'numero_registro' => $this-> numero_registro,
			'tipo_inspeccion_otro' => $this-> tipo_inspeccion_otro,
			'fecha_inspeccion' => $this-> fecha_inspeccion,
			'hora_inspeccion' => $this-> hora_inspeccion
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Inspeccione Successfully created.');
    }

    public function edit($id)
    {
        $record = Inspeccione::findOrFail($id);

        $this->selected_id = $id; 
		$this->empresa_id = $record-> empresa_id;
		$this->area_id = $record-> area_id;
		$this->tipo_inspeccion = $record-> tipo_inspeccion;
		$this->vigencia_inicio = $record-> vigencia_inicio;
		$this->vigencia_fin = $record-> vigencia_fin;
		$this->comentario = $record-> comentario;
		$this->razon_social = $record-> razon_social;
		$this->ruc = $record-> ruc;
		$this->domicilio = $record-> domicilio;
		$this->actividad_economica = $record-> actividad_economica;
		$this->numero_registro = $record-> numero_registro;
		$this->tipo_inspeccion_otro = $record-> tipo_inspeccion_otro;
		$this->fecha_inspeccion = $record-> fecha_inspeccion;
		$this->hora_inspeccion = $record-> hora_inspeccion;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'empresa_id' => 'required',
		'area_id' => 'required',
		'tipo_inspeccion' => 'required',
		'razon_social' => 'required',
		'ruc' => 'required',
		'domicilio' => 'required',
		'actividad_economica' => 'required',
		'numero_registro' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Inspeccione::find($this->selected_id);
            $record->update([ 
			'empresa_id' => $this-> empresa_id,
			'area_id' => $this-> area_id,
			'tipo_inspeccion' => $this-> tipo_inspeccion,
			'vigencia_inicio' => $this-> vigencia_inicio,
			'vigencia_fin' => $this-> vigencia_fin,
			'comentario' => $this-> comentario,
			'razon_social' => $this-> razon_social,
			'ruc' => $this-> ruc,
			'domicilio' => $this-> domicilio,
			'actividad_economica' => $this-> actividad_economica,
			'numero_registro' => $this-> numero_registro,
			'tipo_inspeccion_otro' => $this-> tipo_inspeccion_otro,
			'fecha_inspeccion' => $this-> fecha_inspeccion,
			'hora_inspeccion' => $this-> hora_inspeccion
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Inspeccione Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Inspeccione::where('id', $id);
            $record->delete();
        }
    }
}
