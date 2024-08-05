<?php

namespace App\Http\Livewire;

use App\Models\Asignacione;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\EvaluadorHasEvaluado;
use App\Models\Sesione;

class MisCapacitaciones extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $updateMode = false;
    public $selected_id, $evaluador_id, $evaluado_id, $evaluacion;
    public $misCapacitaciones;
    public $capacitacion_id=0;
    public $capacitacion;
    public $sesion;
    public $sesion_id;
    public $asignacion = null;
    public $asignacion_id=0;
    public $vistaAlternativa = false;
    public $preguntasAleatorias = [];
    public $viewEvaluation = false;
    public $viewAsignacion = false;
    public $viewSesion = false;

    protected $queryString = [
        // 'capacitacion_id' => ['as' => 'c', 'except'=>0],
        'asignacion_id' => ['as' => 'a', 'except'=>0],
        'sesion_id' => ['as' => 's', 'except'=>0],
        'viewEvaluation' => ['as' => 'e', 'except'=>false],
    ];

    public function mount()
    {
        $this->misCapacitaciones = Asignacione::where('personal_id', auth()->user()->personal_id)->get();

        $this->asignacion($this->asignacion_id);

        $this->sesion($this->sesion_id);

        $this->evaluacion($this->viewEvaluation);
    }
    
    public function render()
    {
        return view('livewire.mis-capacitaciones.view');
    }

    public function asignacion($id)
    {
        // Depuración inicial
        // dd('hola');
        
        try {
            if ($id == 0) {
                $this->asignacion_id = 0;
                $this->asignacion = null;
            } else {
                $this->asignacion = $this->misCapacitaciones->find($id);
                
                if ($this->asignacion) {
                    $this->asignacion_id = $this->asignacion->id;
                } else {
                    // Manejo del caso donde no se encuentra la asignación
                    $this->asignacion_id = null;
                    $this->asignacion = null;
                }
            }
        } catch (\Exception $e) {
            // Manejo de excepciones
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
	
    public function sesion($id)
    {        
        if ($id == 0) {
            $this->sesion_id = 0;
            $this->sesion = null;
        } else {
            $this->sesion = Sesione::find($id);
            $this->sesion_id = $this->sesion->id;

            if ($this->sesion_id) {

            } else {

            }
        }
    }

    public function evaluacion($bool)
    {
        if ($bool) {
            if(!empty($this->asignacion)) {
                $preguntas = $this->asignacion->capacitacion->preguntas()->inRandomOrder()->limit(5)->get();
                $this->preguntasAleatorias = $preguntas;
                $this->viewEvaluation = true;
            }
        } else {
            $this->viewEvaluation = false;
        }
    }

    public function enviarEvaluacion() {
        // Aquí puedes procesar las respuestas del usuario
        // Por ejemplo, podrías almacenarlas en la base de datos
        // O podrías hacer algo con ellas, como calcular el puntaje
        // O simplemente podrías mostrar un mensaje de éxito

        session()->flash('message', '¡Evaluación enviada con éxito!');
        $this->emit('alert', ['type' => 'success', 'message' => '¡Evaluación enviada con éxito!']);

        $this->viewEvaluation = false;
    }


    public function changeView() {
        // $this->vistaAlternativa = !$this->vistaAlternativa;
    }
    
    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }
	
    private function resetInput()
    {		
		$this->evaluador_id = null;
		$this->evaluado_id = null;
		$this->evaluacion = null;
    }

    public function store()
    {
        $this->validate([
        ]);

        EvaluadorHasEvaluado::create([ 
			'evaluador_id' => $this-> evaluador_id,
			'evaluado_id' => $this-> evaluado_id,
			'evaluacion' => $this-> evaluacion
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'EvaluadorHasEvaluado creado correctamente.');
    }

    public function edit($id)
    {
        $record = EvaluadorHasEvaluado::findOrFail($id);

        $this->selected_id = $id; 
		$this->evaluador_id = $record-> evaluador_id;
		$this->evaluado_id = $record-> evaluado_id;
		$this->evaluacion = $record-> evaluacion;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
        ]);

        if ($this->selected_id) {
			$record = EvaluadorHasEvaluado::find($this->selected_id);
            $record->update([ 
			'evaluador_id' => $this-> evaluador_id,
			'evaluado_id' => $this-> evaluado_id,
			'evaluacion' => $this-> evaluacion
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'EvaluadorHasEvaluado actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = EvaluadorHasEvaluado::where('id', $id);
            $record->delete();
        }
    }
}
