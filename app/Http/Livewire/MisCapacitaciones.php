<?php

namespace App\Http\Livewire;

use App\Models\Asignacione;
use App\Models\Capacitacione;
use App\Models\CapacitacionHasPersonal;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\EvaluadorHasEvaluado;
use App\Models\Pregunta;
use App\Models\Prueba;
use App\Models\Respuesta;
use App\Models\SesionAccessLog;
use App\Models\Sesione;
use Carbon\Carbon;

class MisCapacitaciones extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $updateMode = false;
    public $selected_id, $evaluador_id, $evaluado_id, $evaluacion;
    public $misCapacitaciones;
    public $capacitacion_id=0;
    public $capacitacion;
    public $sesiones;
    public $sesion;
    public $sesion_id;
    public $asignacion;
    public $asignacion_id=0;
    public $vistaAlternativa = false;
    public $preguntasAleatorias = [];
    public $viewEvaluation = false;
    public $viewAsignacion = false;
    public $viewSesion = false;
    public $allSessionsCompleted = false;
    public $intentosPermitidos;
    public $intentosRegistrados;
    public $respuestas = [];
    public $puntaje;
    public $nota_minima_aprobatoria;

    protected $queryString = [
        'asignacion_id' => ['as' => 'a', 'except'=>0],
        'sesion_id' => ['as' => 's', 'except'=>0],
        'viewEvaluation' => ['as' => 'e', 'except'=>false],
    ];

    public function mount()
    {
        $this->misCapacitaciones = 
        CapacitacionHasPersonal::where('personal_id', auth()->user()->personal_id)
        ->whereHas('capacitacion', function ($query) {
            $query->where('es_aula_virtual', true)
            ->whereHas('sesiones') // Filtra capacitaciones que tienen al menos una sesión
            ->whereHas('preguntas', function ($query) {
                $query->havingRaw('COUNT(*) >= capacitaciones.cantidad_de_preguntas_a_mostrar');
            });
        })
        ->get();

        $this->asignacion($this->asignacion_id??0);
        $this->sesion($this->sesion_id??0);
        $this->evaluacion($this->viewEvaluation??false);
    }
    
    public function render()
    {
        if ($this->asignacion_id) {
            $this->asignacion($this->asignacion_id);
        }

        return view('livewire.mis-capacitaciones.view', [
            'misCapacitaciones' => $this->misCapacitaciones
        ]);
    }

    public function asignacion($id)
    {
        try {
            if ($id == 0) {
                // redirect()->route('mis-capacitaciones');
                $this->asignacion_id = 0;
                $this->asignacion = null;
                $this->capacitacion_id = 0;

                $this->intentosPermitidos = null;
                $this->intentosRegistrados = null;
                $this->puntaje = null;
                // refresh
                // $this->render();
                // redirect()->route('mis-capacitaciones');
            } else {
                $this->asignacion = $this->misCapacitaciones->find($id);

                if ($this->asignacion) {
                    $this->asignacion_id = $this->asignacion->id;
                    $this->capacitacion_id = $this->asignacion->capacitacion_id;

                    foreach ($this->asignacion->capacitacion->sesiones as $sesion) {
                        $sesion->accessed = SesionAccessLog::where('capacitacion_id', $this->capacitacion_id)
                            ->where('personal_id', auth()->user()->personal_id)
                            ->where('sesion_id', $sesion->id)
                            ->exists();
                    }

                    // Verificar si todas las sesiones han sido completadas
                    $totalSesiones = $this->asignacion->capacitacion->sesiones()->count();
                    $sesionesCompletadas = SesionAccessLog::where('capacitacion_id', $this->asignacion->capacitacion_id)
                        ->where('personal_id', auth()->user()->personal_id)
                        ->distinct('sesion_id')
                        ->count('sesion_id');

                    $this->allSessionsCompleted = ($sesionesCompletadas >= $totalSesiones);
                    
                    $exists = SesionAccessLog::where('capacitacion_id', $this->capacitacion_id)
                        ->where('personal_id', auth()->user()->personal_id)
                        ->where('ingreso_a_capacitacion', 1)
                        ->exists();

                    if (!$exists) {
                        SesionAccessLog::create([
                            'capacitacion_id' => $this->capacitacion_id,
                            'personal_id' => auth()->user()->personal_id,
                            'ingreso_a_capacitacion' => true,
                            'accessed_at' => Carbon::now(),
                        ]);
                    }

                    // Verificar si ya existe un registro de prueba
                    $prueba = Prueba::where('capacitacion_id', $this->capacitacion_id)
                    ->where('personal_id', auth()->user()->personal_id)
                    ->where('status_id', 2)
                    ->orderBy('intento', 'desc')
                    ->first();
                    
                    $capacitacion = Capacitacione::find($this->capacitacion_id);
                    // dd($this->asignacion->intentos_de_evaluacion , $capacitacion->intentos_de_evaluacion );
                    $this->intentosPermitidos =  $this->asignacion->intentos_de_evaluacion ?? $capacitacion->intentos_de_evaluacion ?? 1;

                    $this->intentosRegistrados = 0;

                    if (!$prueba) {
                        // Crear el registro de la prueba con el primer intento
                        $this->intentosRegistrados = 0;
                    } else {
                        // Verificar si el usuario tiene intentos disponibles
                        $this->intentosRegistrados = $prueba->intento;
                        $this->puntaje = $prueba->puntaje;
                        $this->nota_minima_aprobatoria = $capacitacion->nota_minima_aprobatoria??10.50;
                    }

                } else {
                    // Manejo del caso donde no se encuentra la asignación
                    $this->asignacion_id = null;
                    $this->asignacion = null;
                }
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
	
    public function sesion($id)
    {
        if ($id == 0) {
            $this->sesion_id = 0;
            $this->sesion = null;
            
            // $this->asignacion($this->asignacion_id);
        } else {
            $this->sesion = Sesione::find($id);
            $numero_de_sesion = $this->sesion->numero_de_sesion;

            $personalId = auth()->user()->personal_id;

            // Verificar si ya ha accedido a la sesión anterior
            $sesionAnterior = SesionAccessLog::where('capacitacion_id', $this->capacitacion_id)
                ->where('personal_id', $personalId)
                ->where('numero_de_sesion', '<', $numero_de_sesion)
                ->orderBy('numero_de_sesion', 'desc')
                ->first();

            if ($sesionAnterior || $numero_de_sesion == 1) {
                // Registrar el acceso a la sesión actual
                $exists = SesionAccessLog::where('capacitacion_id', $this->capacitacion_id)
                ->where('personal_id', auth()->user()->personal_id)
                ->where('sesion_id', $id)
                ->where('numero_de_sesion', $numero_de_sesion)
                ->exists();

                if (!$exists) {
                    SesionAccessLog::create([
                        'capacitacion_id' => $this->capacitacion_id,
                        'personal_id' => $personalId,
                        'sesion_id' => $id,
                        'numero_de_sesion' => $numero_de_sesion,
                        'accessed_at' => Carbon::now(),
                    ]);
                }

                // Redirigir a la sesión
                // return redirect()->route('session.view', ['sessionId' => $id]);
                $this->sesion_id = $this->sesion->id;
            } else {
                session()->flash('error', 'Debe completar las sesiones anteriores antes de acceder a esta.');
                $this->sesion_id = 0;
            }
        }
    }

    public function evaluacion($bool)
    {
        $this->preguntasAleatorias = null;
        $this->respuestas = null;

        if ($bool) {
            // Verificar si todas las sesiones han sido completadas
            $totalSesiones = Capacitacione::find($this->capacitacion_id)->sesiones()->count();
            $sesionesCompletadas = SesionAccessLog::where('capacitacion_id', $this->capacitacion_id)
                ->where('personal_id', auth()->user()->personal_id)
                ->distinct('sesion_id')
                ->count();

            if ($sesionesCompletadas >= $totalSesiones) {
                if (!empty($this->asignacion)) {
                    $prueba = null ;
                    // Verificar si ya existe un registro de prueba
                    $prueba = Prueba::where('capacitacion_id', $this->capacitacion_id)
                        ->where('personal_id', auth()->user()->personal_id)
                        ->orderBy('intento', 'desc')
                        ->where('status_id',"!=", 3) // Asumiendo que 1 es el estado inicial
                        ->first();
                    
                    $capacitacion = Capacitacione::find($this->capacitacion_id);
                    $intentosPermitidos =  $this->asignacion->intentos_de_evaluacion ?? $capacitacion->intentos_de_evaluacion ?? 1;

                    // dd($prueba, $intentosPermitidos);
                    if (!$prueba) {
                        // Crear el registro de la prueba con el segundo intento
                        $prueba = Prueba::create([
                            'capacitacion_id' => $this->capacitacion_id,
                            'personal_id' => auth()->user()->personal_id,
                            'fecha_inicio' => Carbon::now(),
                            'intento' => 1,
                            'status_id' => 1, // Asumiendo que 1 es el estado inicial
                        ]);
                    }

                    if ($prueba->status_id == 2) {
                        if ($prueba->intento < $intentosPermitidos) {
                            // Crear el registro de la prueba con el segundo intento
                            $prueba = Prueba::create([
                                'capacitacion_id' => $this->capacitacion_id,
                                'personal_id' => auth()->user()->personal_id,
                                'fecha_inicio' => Carbon::now(),
                                'intento' => $prueba->intento+1,
                                'status_id' => 1, // Asumiendo que 1 es el estado inicial
                            ]);
    
                        } else {
                            $this->emit('alert', ['type' => 'error', 'message' => 'Ha alcanzado el límite de intentos para esta evaluación.']);
                            return;
                        }
                    }

                    // Verificar si ya existen respuestas guardadas
                    $respuestas = Respuesta::where('prueba_id', $prueba->id)->get();

                    if ($respuestas->isEmpty()) {
                        // Obtener preguntas aleatorias

                        $prueba_anterior = 
                            Prueba::where('capacitacion_id', $this->capacitacion_id)
                            ->where('personal_id', auth()->user()->personal_id)
                            ->where('intento',1)
                            ->where('status_id', 2) // Asumiendo que 2 es el estado final
                            ->first();

                        if($prueba_anterior){
                            $preguntas = $prueba_anterior->preguntas;
                        } else {
                            $preguntas = 
                                Pregunta::where('capacitacion_id', $this->capacitacion_id)
                                ->inRandomOrder()
                                ->limit($capacitacion->cantidad_de_preguntas_a_mostrar??5)
                                ->get();

                            // $preguntas = 
                            // $this->asignacion->capacitacion->preguntas()->inRandomOrder()->limit($capacitacion->cantidad_de_preguntas_a_mostrar??5)->get();

                        }


                        // Crear respuestas con las preguntas aleatorias
                        foreach ($preguntas as $pregunta) {
                            Respuesta::create([
                                'prueba_id' => $prueba->id,
                                'personal_id' => auth()->user()->personal_id,
                                'pregunta_id' => $pregunta->id,
                                'opcion_id' => null, // Inicialmente sin respuesta
                            ]);
                        }
                        
                        // Mezclar las opciones de cada pregunta
                        foreach ($preguntas as $pregunta) {
                            $opciones = $pregunta->opciones->toArray();
                            shuffle($opciones);
                            $pregunta->opciones = collect($opciones);
                        }

                        $this->preguntasAleatorias = $preguntas;

                        // $this->preguntasAleatorias = $preguntas;

                    } else {
                        // Retornar las preguntas guardadas en el modelo Respuesta
                        $preguntasIds = $respuestas->pluck('pregunta_id');
                        $preguntas = Pregunta::whereIn('id', $preguntasIds)->get();
                        
                        // Mezclar las opciones de cada pregunta
                        foreach ($preguntas as $pregunta) {
                            $opciones = $pregunta->opciones->toArray();
                            shuffle($opciones);
                            $pregunta->opciones = collect($opciones);
                        }

                        $this->preguntasAleatorias = $preguntas;
                    }
                    
                    $exists = SesionAccessLog::where('capacitacion_id', $this->capacitacion_id)
                    ->where('personal_id', auth()->user()->personal_id)
                    ->where('ingreso_a_evaluacion', 1)
                    ->exists();

                    if (!$exists) {
                        SesionAccessLog::create([
                            'capacitacion_id' => $this->capacitacion_id,
                            'personal_id' => auth()->user()->personal_id,
                            'ingreso_a_evaluacion' => true,
                            'accessed_at' => Carbon::now(),
                        ]);
                    }

                    $this->viewEvaluation = true;

                    } 

                
            } else {
                $this->emit('alert', ['type' => 'error', 'message' => 'Debe completar todas las sesiones antes de acceder a la evaluación.']);

                // session()->flash('error', 'Debe completar todas las sesiones antes de acceder a la evaluación.');
            }
        } else {
            $this->viewEvaluation = false;
        }
    }

    public function enviarEvaluacion() {
        $prueba = Prueba::where('capacitacion_id', $this->capacitacion_id)
            ->where('personal_id', auth()->user()->personal_id)
            ->latest()
            ->first();

        if (!$prueba) {
            $this->emit('alert', ['type' => 'error', 'message' => 'No se encontró una prueba activa.']);
            return;
        }

        $correctas = 0;
        $incorrectas = 0;

        // Recorrer las preguntas aleatorias y guardar las respuestas
        foreach ($this->respuestas as $preguntaId => $respuestaId) {
            $opcion_correcta_id = Pregunta::find($preguntaId)->solucion->opcion_id;
            if ($respuestaId) {
                // Actualizar o crear la respuesta en el modelo Respuesta
                Respuesta::updateOrCreate(
                    [
                        'prueba_id' => $prueba->id,
                        'pregunta_id' => $preguntaId,
                    ],
                    [
                        'intento' => $prueba->intento,
                        'opcion_id' => $respuestaId,
                        'opcion_correcta_id' => $opcion_correcta_id,
                    ]
                );

                // Contar respuestas correctas e incorrectas
                if ($respuestaId == $opcion_correcta_id) {
                    $correctas++;
                } else {
                    $incorrectas++;
                }
            }
        }

        // Calcular el puntaje (asumiendo 1 punto por respuesta correcta)

        $totalPreguntas = count($this->preguntasAleatorias);
        $puntaje = ($correctas / $totalPreguntas) * 20;

        // Calcular la duración de la prueba
        $fecha_inicio = Carbon::parse($prueba->fecha_inicio);
        $fecha_fin = Carbon::now();
        $duracion = $fecha_fin->diff($fecha_inicio);

        // Formatear la duración en H:i:s
        $duracion_formateada = sprintf('%02d:%02d:%02d', $duracion->h, $duracion->i, $duracion->s);

        // Verificar si la duración excede el límite máximo
        if ($duracion->h > 838 || ($duracion->h == 838 && ($duracion->i > 59 || $duracion->s > 59))) {
            $duracion_formateada = sprintf('%02d:%02d:%02d', 838, 59, 59);
        }

        // Actualizar el estado de la prueba
        $prueba->update([
            'fecha_fin' => Carbon::now(),
            'status_id' => 2, // Asumiendo que 2 es el estado de finalizado
            'puntaje' => $puntaje,
            'correctas' => $correctas,
            'incorrectas' => $incorrectas,
            'duracion' => $duracion_formateada,
        ]);

        // $this->emit('alert', ['type' => 'success', 'message' => '¡Evaluación enviada con éxito!']);
        
        // evaluar cuantas evaluaciones se han realizado para luego incrementar el numero_de_evaluacion
        // $numero_de_evaluacion = SesionAccessLog::where('capacitacion_id', $this->capacitacion_id)
        //     ->where('personal_id', auth()->user()->personal_id)
        //     ->whereNotNull('numero_de_evaluacion')
        //     ->count();

        // if ($numero_de_evaluacion < 2) {
            SesionAccessLog::create([
                'capacitacion_id' => $this->capacitacion_id,
                'personal_id' => auth()->user()->personal_id,
                'numero_de_evaluacion' => $prueba->intento,
                'accessed_at' => Carbon::now(),
            ]);
        // }

        $this->emit('alert', ['type' => 'success', 'message' => 'Evaluación enviada exitosamente.']);

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
