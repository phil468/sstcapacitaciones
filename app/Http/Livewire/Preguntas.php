<?php

namespace App\Http\Livewire;

use App\Models\Capacitacione;
use App\Models\Opcione;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pregunta;
use App\Models\Solucione;

class Preguntas extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $seccion_id, $evaluacion_id, $qid, $pregunta, $tipo_de_pregunta_id, $numero_orden;
    public $updateMode = false;
    public $capacitacion_id;
    public $capacitacion_id_general;
    public $capacitacion;
    public $opciones = [];
    public $solucion_id;
    public $originalOpciones = [];

    public function mount($capacitacion_id = null) {
        $this->capacitacion_id_general = $capacitacion_id ?? null;
        if ( $this->capacitacion_id_general) {
            $this->capacitacion = Capacitacione::find($capacitacion_id); //
        }
    }

    public function render()
    {
		// $keyWord = '%'.$this->keyWord .'%';
        $preguntas = Pregunta::latest()
                        ->when($this->capacitacion_id, function ($query) {
                            return $query->where('capacitacion_id', $this->capacitacion_id);
                        })
                        ->with(['opciones', 'solucion.opcion'])
                        ->paginate(5);
        return view('livewire.preguntas.view', [
            'preguntas' => $preguntas
        ]);
    }
    
    public function addOpcion()
    {
        if (count($this->opciones) < 5) {
            $this->opciones[] = ['opcion' => ''];
        }
    }

    public function removeOpcion($index)
    {
        unset($this->opciones[$index]);
        $this->opciones = array_values($this->opciones);
    }
	
    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }
	
    private function resetInput()
    {
        $this->capacitacion_id = $this->capacitacion_id_general ?? null;

		$this->pregunta = null;
		$this->tipo_de_pregunta_id = null;
        $this->opciones = [];
		$this->numero_orden = null;
        $this->solucion_id = null;
        $this->originalOpciones = [];
    }

    public function store()
    {
        $this->validate([
		    'pregunta' => 'required',
            'capacitacion_id' => 'required',
            'opciones' => 'required|array|size:5',
            'opciones.*.opcion' => 'required|string',
            'solucion_id' => 'required|integer'
        ]);
        // dd('hasta aquí');
        $pregunta = Pregunta::create([ 
			'pregunta' => $this-> pregunta,
			'tipo_de_pregunta_id' => $this-> tipo_de_pregunta_id??1,
			'opciones_requeridas' => 5,
			'numero_orden' => $this-> numero_orden,
            'capacitacion_id' => $this->capacitacion_id
        ]);

        foreach ($this->opciones as $index => $opcion) {
            $opcionModel = Opcione::create([
                'pregunta_id' => $pregunta->id,
                'opcion' => $opcion['opcion']
            ]);

            if ($index == $this->solucion_id) {
                Solucione::create([
                    'pregunta_id' => $pregunta->id,
                    'opcion_id' => $opcionModel->id
                ]);
            }
        }
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Pregunta creada correctamente.');
    }

    public function edit($id)
    {
        $this->resetValidation();
        $this->resetInput();
        
		if ($id != 0) {
            $record = Pregunta::findOrFail($id);
            $this->selected_id = $id; 
            $this->seccion_id = $record-> seccion_id;
            $this->evaluacion_id = $record-> evaluacion_id;
            $this->qid = $record-> qid;
            $this->pregunta = $record-> pregunta;
            $this->tipo_de_pregunta_id = $record-> tipo_de_pregunta_id;
            $this->opciones = $record->opciones->toArray(); // Asumiendo que las opciones están en un formato adecuado
            $this->numero_orden = $record-> numero_orden;            
            $this->solucion_id = $record->solucion ? $record->solucion->opcion_id : null;
            $this->originalOpciones = $record->opciones->pluck('id')->toArray();
        }
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		    'pregunta' => 'required',
            'capacitacion_id' => 'required',
            'opciones' => 'required|array|size:5',
            'opciones.*.opcion' => 'required|string',
            'solucion_id' => 'required|integer'
        ]);

        if ($this->selected_id) {
			$pregunta = Pregunta::find($this->selected_id);
            $pregunta->update([ 
                'seccion_id' => $this-> seccion_id,
                'evaluacion_id' => $this-> evaluacion_id,
                'qid' => $this-> qid,
                'pregunta' => $this-> pregunta,
                'tipo_de_pregunta_id' => $this-> tipo_de_pregunta_id??1,
                'opciones_requeridas' => 5,
                'numero_orden' => $this-> numero_orden
            ]);
            
            // Actualizar opciones
            $currentOpcionesIds = [];
            // Actualizar opciones
            foreach ($this->opciones as $index => $opcion) {
                if (isset($opcion['id'])) {
                    $opcionModel = Opcione::find($opcion['id']);
                    $opcionModel->update(['opcion' => $opcion['opcion']]);
                    $currentOpcionesIds[] = $opcion['id'];
                } else {
                    $opcionModel = Opcione::create([
                        'pregunta_id' => $pregunta->id,
                        'opcion' => $opcion['opcion']
                    ]);
                    $currentOpcionesIds[] = $opcionModel->id;
                }
                // Actualizar solución
                if ($index == $this->solucion_id) {
                    Solucione::updateOrCreate(
                        ['pregunta_id' => $pregunta->id],
                        ['opcion_id' => $opcionModel->id]
                    );
                }
            }
            
            // Eliminar opciones que ya no están presentes
            $opcionesToDelete = array_diff($this->originalOpciones, $currentOpcionesIds);
            Opcione::destroy($opcionesToDelete);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Pregunta actualizada correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Pregunta::where('id', $id);
            $record->delete();
        }
    }
}
