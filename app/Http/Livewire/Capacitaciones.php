<?php

namespace App\Http\Livewire;

use App\Models\Alerta;
use App\Models\Area;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Capacitacione;
use App\Models\CapacitacionHasPersonal;
use App\Models\Cargo;
use App\Models\ConfiguracionGeneral;
use App\Models\Empresa;
use App\Models\Modalidade;
use App\Models\NotificacionEnviada;
use App\Models\Personal;
use App\Models\Sede;
use App\Models\SesionAccessLog;
use App\Models\Status;
use App\Models\Tema;
use App\Models\TipoDeCapacitacione;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CapacitacionNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Capacitaciones extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id,
	$keyWord,
	$empresa_id,
	$capacitaciones_tipo_id,
	$tema_id,
	$sede_id,
	$fecha_capacitacion,
	$hora_inicio,
	$hora_fin,
	$expositor_id,
	$cargo_expositor_id,
	$registrador_id,
	$cargo_registrador_id,
	$fecha_registro,
	$activo,
	$status_id,
	$empresas,
	$capacitaciones_tipos, 
	$temas, 				
	$sedes, 				
	$expositors, 			
	$cargos, 	
	$registradors, 		
	$cargo_registradors, 	
	$statuss,
	$estado_realizado,
	$modalidades,
	$modalidad_id,
	$areas,
	$area_id=[],
	$cantidad_de_sesiones,
	$expositor_externo,
	$nombre_expositor_externo,
	$tema_id_add,
	$capacitacion,

	$es_onboarding,
	$cantidad_de_preguntas_a_mostrar,
	$es_aula_virtual,
	$nota_minima_aprobatoria,
	$intentos_de_evaluacion,
	$fecha_inicio,
	$fecha_fin,
	$identificador_unico,

	$ingresar_capacitaciones_de_aula_virtual,
	$ingresar_capacitaciones_de_no_aula_virtual,

	$cantidad_de_preguntas_a_mostrar_general,
	$nota_minima_aprobatoria_general,
	$intentos_de_evaluacion_general;
		
    public $updateMode = false;
	
	public $cargando = false;

    protected $listeners = [
		'edit' => 'edit',
		'selectedUpdated' => 'updateSelected'
	];
	
	public $selectedFromPersonalTable = [];

	protected $rules = [
		'activo' => 'required|boolean',
		'status_id' => 'required|exists:statuses,id',
		'empresa_id' => 'required|exists:empresas,id',
		'modalidad_id' => 'required|exists:modalidades,id',
		'capacitaciones_tipo_id' => 'required|exists:tipo_de_capacitaciones,id',
		'tema_id' => 'required|exists:temas,id',
		'fecha_inicio' => 'required|date|before_or_equal:fecha_fin',
		'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
		'identificador_unico' => 'required|max:10|unique:capacitaciones,identificador_unico',

        'es_aula_virtual' => 'required|boolean',
        'es_onboarding' => 'required_if:es_aula_virtual,true|exclude_unless:es_aula_virtual,true',
        'cantidad_de_preguntas_a_mostrar' => 'required_if:es_aula_virtual,true|exclude_unless:es_aula_virtual,true|integer|min:1',
        'nota_minima_aprobatoria' => 'required_if:es_aula_virtual,true|exclude_unless:es_aula_virtual,true|numeric|min:0|max:20',
        'intentos_de_evaluacion' => 'required_if:es_aula_virtual,true|exclude_unless:es_aula_virtual,true|integer|min:1|max:10',

		'sede_id' => 'required_if:es_aula_virtual,false|exclude_unless:es_aula_virtual,false|exists:sedes,id',
		'expositor_externo' => 'required_if:es_aula_virtual,false|exclude_unless:es_aula_virtual,false',
		'expositor_id' => 'required_if:expositor_externo,0|exclude_unless:expositor_externo,0|exists:personal,id',
		'cargo_expositor_id' => 'required_if:expositor_externo,0|exclude_unless:expositor_externo,0|exists:cargos,id',
		
		'nombre_expositor_externo' => 'required_if:expositor_externo,1|exclude_unless:expositor_externo,1',
    ];

    protected $validationAttributes = [
		'nombre_expositor_externo' => 'nombre expositor externo',
		'cargo_expositor_externo' => 'cargo expositor externo',
		'empresa_id' => 'empresa',
		'capacitaciones_tipo_id' => 'tipo de capacitación',
		'tema_id' => 'tema',
		'sede_id' => 'sede',
		'fecha_capacitacion' => 'fecha de capacitación',
		'hora_inicio' => 'hora de inicio',
		'hora_fin' => 'hora de fin',
		'expositor_id' => 'expositor',
		'cargo_expositor_id' => 'cargo expositor',
		'registrador_id' => 'registrador',
		'cargo_registrador_id' => 'cargo registrador',
		'fecha_registro' => 'fecha de registro',
		'activo' => 'activo',
		'status_id' => 'status',
		'modalidad_id' => 'modalidad',
		'cantidad_de_sesiones' => 'cantidad de sesiones',
        'area_id' => 'área',
        'es_aula_virtual' => 'es aula virtual',
        'es_onboarding' => 'es onboarding',
        'cantidad_de_preguntas_a_mostrar' => 'cantidad de preguntas a mostrar',
        'nota_minima_aprobatoria' => 'nota mínima aprobatoria',
		'intentos_de_evaluacion' => 'intentos de evaluación',
		'expositor_externo' => 'expositor interno/externo',
		'expositor_id' => 'expositor',
		'cargo_expositor_id' => 'cargo expositor',
		'nombre_expositor_externo' => 'nombre expositor externo',
    ];

    protected $messages = [
        'area_id.required' => 'El área es obligatoria.',
        'area_id.exists' => 'El área seleccionada no es válida.',
        'es_aula_virtual.boolean' => 'El campo es aula virtual debe ser verdadero o falso.',
        'es_onboarding.boolean' => 'El campo es onboarding debe ser verdadero o falso.',
        'cantidad_de_preguntas_a_mostrar.required_if' => 'El campo cantidad de preguntas a mostrar es obligatorio cuando es aula virtual.',
        'cantidad_de_preguntas_a_mostrar.integer' => 'La cantidad de preguntas a mostrar debe ser un número entero.',
        'cantidad_de_preguntas_a_mostrar.min' => 'La cantidad de preguntas a mostrar debe ser al menos 1.',
        'nota_minima_aprobatoria.required_if' => 'El campo nota mínima aprobatoria es obligatorio cuando es aula virtual.',
        'nota_minima_aprobatoria.integer' => 'La nota mínima aprobatoria debe ser un número entero.',
        'nota_minima_aprobatoria.min' => 'La nota mínima aprobatoria debe ser al menos 0.',
        'nota_minima_aprobatoria.max' => 'La nota mínima aprobatoria no puede ser mayor a 20.',
		'intentos_de_evaluacion.required_if' => 'El campo intentos de evaluación es obligatorio cuando es aula virtual.',
		'intentos_de_evaluacion.integer' => 'El campo intentos de evaluación debe ser un número entero.',
		'intentos_de_evaluacion.min' => 'El campo intentos de evaluación debe ser al menos 1.',
		'intentos_de_evaluacion.max' => 'El campo intentos de evaluación no puede ser mayor a 10.',
		'expositor_externo.required_if' => 'El campo expositor interno/externo es obligatorio.',
		'expositor_externo.boolean' => 'El campo expositor interno/externo debe ser verdadero o falso.',
		'expositor_id.required_if' => 'El campo expositor es obligatorio cuando el expositor es interno.',
		'cargo_expositor_id.required_if' => 'El campo cargo expositor es obligatorio cuando el expositor es interno.',
		'nombre_expositor_externo.required_if' => 'El campo nombre expositor externo es obligatorio cuando el expositor es externo.',
		'sede_id.required_if' => 'El campo sede es obligatorio cuando no es aula virtual.',
	];


    public function updateSelected($value){
        $this->selectedFromPersonalTable = $value;
    }

	public function listarSelects() {
		$this->empresas 				= 	Empresa::				orderBy('name')->where('estado',1)->whereNotNull('name')->select('name as label', 'id as value')->get()->toArray();
		$this->capacitaciones_tipos 	= 	TipoDeCapacitacione::	orderBy('name')->where('estado',1)->whereNotNull('name')->select('name as label', 'id as value')->get()->toArray();
		$this->temas 					= 	Tema::					orderBy('name')->where('estado',1)->whereNotNull('name')->select('name as label', 'id as value')->get()->toArray();
		$this->sedes 					= 	Sede::					orderBy('name')->where('estado',1)->whereNotNull('name')->select('name as label', 'id as value')->get()->toArray();
		$this->expositors 				=	Personal::				orderBy('name')->where('estado',1)->whereNotNull('name')->select('name as label', 'id as value')->get()->toArray();
		$this->cargos 					=	Cargo::					orderBy('name')->where('estado',1)->whereNotNull('name')->select('name as label', 'id as value')->get()->toArray();
		$this->registradors 			=	Personal::				orderBy('personal.name')->where('personal.estado',1)
																	->join('users', 'personal.id', 'users.personal_id')
																	->select('personal.name as label', 'personal.id as value')
																	->where('users.registrador',1)
																	->get()->toArray();
		$this->cargo_registradors 		=	Cargo::					orderBy('name')->where('estado',1)->whereNotNull('name')->select('name as label', 'id as value')->get()->toArray();
		$this->statuss 					=	Status::				orderBy('name')->where('estado',1)->whereNotNull('name')->select('name as label', 'id as value')->get()->toArray();
		$this->areas 					=	Area::					orderBy('name')->where('estado',1)->whereNotNull('name')->select('name as label', 'id as value')->get()->toArray();
		$this->modalidades 				= 	Modalidade::			orderBy('name')->where('estado',1)->whereNotNull('name')->select('name as label', 'id as value')->get()->toArray();
		
		$this->emit('listarSelects',
			$this->empresas,
			$this->capacitaciones_tipos,
			$this->temas,
			$this->sedes,
			$this->expositors,
			$this->cargos,
			$this->registradors,
			$this->cargo_registradors,
			$this->statuss,
			$this->areas,
			$this->modalidades
		);
		$this->actualizarSelects();
	}
			
	public function actualizarSelects () {
		$this->emit('actualizarSelects',
			$this->empresa_id,
			$this->capacitaciones_tipo_id,
			$this->tema_id,
			$this->sede_id,
			$this->expositor_id,
			$this->cargo_expositor_id,
			$this->registrador_id,
			$this->cargo_registrador_id,
			$this->status_id,
			$this->area_id,
			$this->modalidad_id
		);
	}

	public function updatedExpositorId($value) {
		$this->cargo_expositor_id = null;
		$personal = Personal::find($value) ?? null;
		if ($personal) {
			$this->cargo_expositor_id = $personal->cargo_id;	
		}
		$this->actualizarDatosExpositor();
	}
	
	public function actualizarDatosExpositor () {
		$this->emit('actualizarDatosExpositor',
			$this->cargo_expositor_id,
			$this->expositor_id,
			$this->expositor_externo
		);
	}

	public function updatedModalidadId($value) {
		if($value == 1) {
			$this->expositor_externo = 1;
			// $this->updatedExpositorExterno(1);
		} else {
			$this->expositor_externo = null;
			// $this->updatedExpositorExterno(null);
		}
	}

	public function updatedExpositorExterno($value) {
		if($value == 1) {
			$this->expositor_id = null;
			$this->cargo_expositor_id = null;
			$this->actualizarDatosExpositor();
		} else {
			$this->nombre_expositor_externo = null;
			$this->actualizarDatosExpositor();
		}
	}
	
	public function updatedEsAulaVirtual($value) {
		if($value == 1) {
			$this->cantidad_de_preguntas_a_mostrar = $this->cantidad_de_preguntas_a_mostrar_general;
			$this->nota_minima_aprobatoria = $this->nota_minima_aprobatoria_general;
			$this->intentos_de_evaluacion = $this->intentos_de_evaluacion_general;
		} else {
			$this->cantidad_de_preguntas_a_mostrar = null;
			$this->nota_minima_aprobatoria = null;
			$this->intentos_de_evaluacion = null;
		}
	}

	public function agregar_tema() {
		$this->validate([			
			'tema_id_add' => 'required'
		],[],['tema_id_add' => 'Tema para agregar']);

		$tema = Tema::firstOrCreate(
			['name' => $this->tema_id_add],
			['estado' => 1]
		);

		$this->temas = 	Tema::orderBy('name')->where('estado',1)->whereNotNull('name')->select('name as label', 'id as value')->get()->toArray();
		
		$this->emit('listarTemas',
			$this->temas,$tema->id
		);

		$this->tema_id = $tema->id;
		$this->tema_id_add = null;
	}

	public function mount($id = null) {
		
		// $now = Carbon::now();
        // $alertas = Alerta::where('estado', 1)->get();

        // foreach ($alertas as $alerta) {
        //     $dias = $alerta->dias;
        //     $campo = $alerta->campo;
        //     $condicion = $alerta->condicion;

		// 	dd($now->copy()->addDays($dias)->toDateString());

		// 	$capacitaciones = CapacitacionHasPersonal::where(function ($query) use ($now, $dias, $campo, $condicion) {
		// 		if ($condicion == 'antes') {
		// 			$query->whereDate($campo, '=', $now->copy()->addDays($dias)->toDateString());
		// 		} elseif ($condicion == 'despues') {
		// 			$query->whereDate($campo, '=', $now->copy()->subDays($dias)->toDateString());
		// 		}
		// 	})->get();

		// 	dd($capacitaciones);

		// }


		$this->estado_realizado = Status::where('name', '=', 'REALIZADA')->first()->id ?? null;
		if ($this->estado_realizado == null) {
			session()->flash('message-danger', 'No se encuentra definido el estado "REALIZADA"
			. Por favor revisar el nombre de los estados');
			$this->emit('alert-danger');
		}
		
		if ($id != null) {
			$this->capacitacion = Capacitacione::findOrFail($id);
		}

		$this->ingresar_capacitaciones_de_aula_virtual 		= Auth::user()->can('ingresar-capacitaciones-de-aula-virtual');
		$this->ingresar_capacitaciones_de_no_aula_virtual 	= Auth::user()->can('ingresar-capacitaciones-de-no-aula-virtual');
		$this->cantidad_de_preguntas_a_mostrar_general = ConfiguracionGeneral::where('name', 'cantidad_de_preguntas_a_mostrar')->first()->valor ?? 5;
		$this->nota_minima_aprobatoria_general = ConfiguracionGeneral::where('name', 'nota_minima_aprobatoria')->first()->valor ?? 10.50;
		$this->intentos_de_evaluacion_general = ConfiguracionGeneral::where('name', 'intentos_de_evaluacion')->first()->valor ?? 2;

	}

	public function render()
    {
		// $keyWord = '%'.$this->keyWord .'%';
        return view('livewire.capacitaciones.view');
    }
	
    public function cancel()
    {
		$this->resetValidation();
        $this->resetInput();
		$this->emit('limpiarDatos');
        $this->cargando = false;
        // $this->actualizandoVista = true;
    }
	
    private function resetInput()
    {		
		$this->empresa_id = null;
		$this->capacitaciones_tipo_id = null;
		$this->tema_id = null;
		$this->sede_id = null;
		$this->fecha_capacitacion = null;
		$this->hora_inicio = null;
		$this->hora_fin = null;
		$this->expositor_id = null;
		$this->cargo_expositor_id = null;
		$this->registrador_id = null;
		$this->cargo_registrador_id = null;
		$this->fecha_registro = null;
		$this->activo = null;
		$this->status_id = null;
		$this->area_id = [];
		$this->modalidad_id = null;
		$this->cantidad_de_sesiones = null;
		$this->expositor_externo = null;
		$this->nombre_expositor_externo = null;
		$this->es_onboarding = null;
		$this->cantidad_de_preguntas_a_mostrar = null;
		$this->es_aula_virtual = null;
		$this->nota_minima_aprobatoria = null;
		$this->intentos_de_evaluacion = null;
		$this->fecha_inicio = null;
		$this->fecha_fin = null;
		$this->identificador_unico = null;
    }

	// public function create() 
	// {
    //     $this->listarSelects();

	// 	if($this->ingresar_capacitaciones_de_aula_virtual 	&&	
	// 	$this->ingresar_capacitaciones_de_no_aula_virtual 	) {
	// 		$this->es_aula_virtual = 0;
	// 	}
	// 	else if($this->ingresar_capacitaciones_de_aula_virtual) {
	// 		$this->es_aula_virtual = 1;
	// 		$this->cantidad_de_preguntas_a_mostrar = $this->cantidad_de_preguntas_a_mostrar_general;
	// 		$this->nota_minima_aprobatoria = $this->nota_minima_aprobatoria_general;
	// 		$this->intentos_de_evaluacion = $this->intentos_de_evaluacion_general;
	// 	}
	// 	else if($this->ingresar_capacitaciones_de_no_aula_virtual) {
	// 		$this->es_aula_virtual = 0;
	// 	}
	// 	else {
	// 		$this->emit('alert', ['type' => 'danger', 'message' => 'No tiene permisos para ingresar capacitaciones.']);
	// 		return;
	// 	}
	// 	$this->activo = true;
	// 	$this->empresa_id = 1;
	// 	$this->cantidad_de_sesiones = 1;
	// 	$this->modalidad_id = 2;
    //     $this->updateMode = true;
    //     $this->cargando = true;

	// }

    public function store()
    {
		if ($this->expositor_externo != 1) {
			$this->expositor_externo = 0;
		}
		
        $rules = $this->rules;

		$this->validate($rules);

        // $this->validate([
		// 	'empresa_id' => 'required',
		// 	'capacitaciones_tipo_id' => 'required',
		// 	'tema_id' => 'required',
		// 	'sede_id' => 'required',
		// 	'expositor_id' => 'required_if:expositor_externo,0',
		// 	'cargo_expositor_id' => 'required_if:expositor_externo,0',
		// 	// 'expositor_id' => 'required',
		// 	// 'cargo_expositor_id' => 'required',
		// 	'registrador_id' => 'required',
		// 	'cargo_registrador_id' => 'required',
		// 	// 'fecha_registro' => 'required',
		// 	'status_id' => 'required',
		// 	'cantidad_de_sesiones' =>  'required',
		// 	'nombre_expositor_externo' => 'required_if:expositor_externo,1',
		// ],[
		// 	'nombre_expositor_externo.required_if' => 'El campo nombre expositor externo es obligatorio cuando el expositor es externo.',
		// 	'expositor_id.required_if' => 'El campo expositor es obligatorio cuando el expositor es interno.',
		// 	'cargo_expositor_id.required_if' => 'El campo cargo expositor es obligatorio cuando el expositor es interno.',
		// ]);

		if ($this->expositor_externo == 1) {
			$this->expositor_id = null;
			$this->cargo_expositor_id = null;
		} else {
			$this->nombre_expositor_externo = null;
		}

        $record = Capacitacione::create([
			'empresa_id' => $this-> empresa_id,
			'capacitaciones_tipo_id' => $this-> capacitaciones_tipo_id,
			'tema_id' => $this-> tema_id,
			'sede_id' => $this-> sede_id,
			'fecha_capacitacion' => $this-> fecha_capacitacion,
			'hora_inicio' => $this-> hora_inicio,
			'hora_fin' => $this-> hora_fin,
			'expositor_id' => $this-> expositor_id,
			'cargo_expositor_id' => $this-> cargo_expositor_id,
			'registrador_id' => $this-> registrador_id,
			'cargo_registrador_id' => $this-> cargo_registrador_id,
			'fecha_registro' => $this-> fecha_registro,
			'activo' => $this-> activo,
			'status_id' => $this-> status_id,
			'modalidad_id' => $this-> modalidad_id,
			'cantidad_de_sesiones' => $this-> cantidad_de_sesiones??1,
			'expositor_externo' => $this-> expositor_externo,
			'nombre_expositor_externo' => $this-> nombre_expositor_externo,
			'synced' =>false,
			'intentos_de_evaluacion' => $this->intentos_de_evaluacion,
			'cantidad_de_preguntas_a_mostrar' => $this->cantidad_de_preguntas_a_mostrar,
			'nota_minima_aprobatoria' => $this->nota_minima_aprobatoria,
			'es_aula_virtual' => $this-> es_aula_virtual,
			'es_onboarding' => $this-> es_onboarding,
			'fecha_inicio' => $this-> fecha_inicio,
			'fecha_fin' => $this-> fecha_fin,
			'identificador_unico' => $this-> identificador_unico

        ]);

		if($record) {
			$record->areas()->sync($this->area_id);
		}
        
        $this->resetInput();
        $this->cargando = true;
		$this->emit('closeModal');
		$this->emit('alert', ['type' => 'success', 'message' => 'Capacitación creada correctamente.']);
    }

    public function edit($id)
    {
		if ($id != 0) {
			$this->resetValidation();
			$this->resetInput();

			$record = Capacitacione::findOrFail($id);

			$this->selected_id = $id; 
			$this->empresa_id = $record-> empresa_id;
			$this->capacitaciones_tipo_id = $record-> capacitaciones_tipo_id;
			$this->tema_id = $record-> tema_id;
			$this->sede_id = $record-> sede_id;
			$this->fecha_capacitacion = $record-> fecha_capacitacion;
			$this->hora_inicio = $record-> hora_inicio;
			$this->hora_fin = $record-> hora_fin;
			$this->expositor_id = $record-> expositor_id;
			$this->cargo_expositor_id = $record-> cargo_expositor_id;
			$this->registrador_id = $record-> registrador_id;
			$this->cargo_registrador_id = $record-> cargo_registrador_id;
			$this->fecha_registro = $record-> fecha_registro;
			$this->activo = $record-> activo;
			$this->status_id = $record-> status_id;
			$this->modalidad_id = $record-> modalidad_id;
			$this->area_id = $record->areas()->pluck('area_id')->toArray();
			$this->cantidad_de_sesiones = $record-> cantidad_de_sesiones;
			$this->expositor_externo = $record-> expositor_externo;
			$this->nombre_expositor_externo = $record-> nombre_expositor_externo;

			$this->es_onboarding = $record->es_onboarding;
			$this->cantidad_de_preguntas_a_mostrar = $record->cantidad_de_preguntas_a_mostrar;
			$this->es_aula_virtual = $record->es_aula_virtual;
			$this->nota_minima_aprobatoria = $record->nota_minima_aprobatoria;
			$this->intentos_de_evaluacion = $record->intentos_de_evaluacion;
			$this->fecha_inicio = $record->fecha_inicio ? $record->fecha_inicio->format('Y-m-d\TH:i') : null;
			$this->fecha_fin = $record->fecha_fin ? $record->fecha_fin->format('Y-m-d\TH:i') : null;
			$this->identificador_unico = $record->identificador_unico;

		} else {
			$this->resetValidation();
			$this->resetInput();
			$this->evaluar_aula_virtual();

			$this->selected_id = 0;
			$this->activo = true;			
			$this->cantidad_de_sesiones = 1;
			$this->modalidad_id = 2;
			$this->empresa_id = 1;
			$this->status_id = 1;

			$this->es_onboarding =  false;
		}
		
		$this->listarSelects();
        $this->updateMode = true;
        $this->cargando = true;
    }

	public function evaluar_aula_virtual() {
		if( $this->ingresar_capacitaciones_de_aula_virtual && 
			$this->ingresar_capacitaciones_de_no_aula_virtual) {
			$this->es_aula_virtual = 0;
		}
		else if($this->ingresar_capacitaciones_de_aula_virtual) {
			$this->es_aula_virtual = 1;
			$this->cantidad_de_preguntas_a_mostrar = $this->cantidad_de_preguntas_a_mostrar_general;
			$this->nota_minima_aprobatoria = $this->nota_minima_aprobatoria_general;
			$this->intentos_de_evaluacion = $this->intentos_de_evaluacion_general;
		}
		else if($this->ingresar_capacitaciones_de_no_aula_virtual) {
			$this->es_aula_virtual = 0;
		}
		else {
			$this->emit('alert', ['type' => 'danger', 'message' => 'No tiene permisos para ingresar capacitaciones.']);
			return;
		}
	}

    public function update()
    {   
		if ($this->expositor_externo != 1) {
			$this->expositor_externo = 0;
		}

        $rules = $this->rules;
		$rules['identificador_unico'] = 'required|unique:capacitaciones,identificador_unico,'.$this->selected_id;

		$this->validate($rules);

        // $this->validate([
		// 	'empresa_id' => 'required',
		// 	'capacitaciones_tipo_id' => 'required',
		// 	'tema_id' => 'required',
		// 	'sede_id' => 'required',
		// 	'expositor_id' => 'required_if:expositor_externo,0',
		// 	'cargo_expositor_id' => 'required_if:expositor_externo,0',
		// 	// 'expositor_id' => 'required',
		// 	// 'cargo_expositor_id' => 'required',
		// 	'registrador_id' => 'required',
		// 	'cargo_registrador_id' => 'required',
		// 	// 'fecha_registro' => 'required',
		// 	'status_id' => 'required',
		// 	'cantidad_de_sesiones' =>  'required',
		// 	'nombre_expositor_externo' => 'required_if:expositor_externo,1',
		// ],[
		// 	'nombre_expositor_externo.required_if' => 'El campo nombre expositor externo es obligatorio cuando el expositor es externo.',
		// 	'expositor_id.required_if' => 'El campo expositor es obligatorio cuando el expositor es interno.',
		// 	'cargo_expositor_id.required_if' => 'El campo cargo expositor es obligatorio cuando el expositor es interno.',
		// ]);

		if ($this->expositor_externo == 1) {
			$this->expositor_id = null;
			$this->cargo_expositor_id = null;
		} else {
			$this->nombre_expositor_externo = null;
		}
		
        if ($this->selected_id) {
			$record = Capacitacione::find($this->selected_id);
            $record->update([ 
				'empresa_id' => $this-> empresa_id,
				'capacitaciones_tipo_id' => $this-> capacitaciones_tipo_id,
				'tema_id' => $this-> tema_id,
				'sede_id' => $this-> sede_id,
				'fecha_capacitacion' => $this-> fecha_capacitacion,
				'hora_inicio' => $this-> hora_inicio,
				'hora_fin' => $this-> hora_fin,
				'expositor_id' => $this-> expositor_id,
				'cargo_expositor_id' => $this-> cargo_expositor_id,
				'registrador_id' => $this-> registrador_id,
				'cargo_registrador_id' => $this-> cargo_registrador_id,
				'fecha_registro' => $this-> fecha_registro,
				'activo' => $this-> activo,
				'status_id' => $this-> status_id,
				'modalidad_id' => $this-> modalidad_id,
				'cantidad_de_sesiones' => $this-> cantidad_de_sesiones??1,
				'expositor_externo' => $this-> expositor_externo,
				'nombre_expositor_externo' => $this-> nombre_expositor_externo,
				'synced'=>false,

				'es_onboarding' => $this->es_onboarding,
				'cantidad_de_preguntas_a_mostrar' => $this->cantidad_de_preguntas_a_mostrar,
				'es_aula_virtual' => $this->es_aula_virtual,
				'nota_minima_aprobatoria' => $this->nota_minima_aprobatoria,
				'intentos_de_evaluacion' => $this->intentos_de_evaluacion,
				'fecha_inicio' => $this->fecha_inicio,
				'fecha_fin' => $this->fecha_fin,
				'identificador_unico' => $this->identificador_unico

            ]);
			
			$record->areas()->sync($this->area_id);

            $this->resetInput();
            $this->updateMode = false;			
			$this->cargando = true;
		    $this->emit('closeModal');
			// session()->flash('message', 'Capacitacion actualizado correctamente.');
			$this->emit('alert', ['type' => 'success', 'message' => 'Capacitación actualizada correctamente.']);

        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Capacitacione::where('id', $id);
            $record->delete();
			$this->emit('alert', ['type' => 'success', 'message' => 'Capacitación eliminada correctamente.']);
        }

    }
	
	public function notificar($id)
	{
		$capacitaciones = [];
		$messages = [];

		// 0: notificar todas las capacitaciones
		// n: notificar solo la capacitacion con id = n

		if ($id == 0) {
			$capacitaciones = Capacitacione::where('activo', 1)
				->where('status_id', '!=', 3)
				->where('es_aula_virtual', '=', 1)
				->get();
		} else {
			$capacitaciones = Capacitacione::where('id', $id)
				->where('activo', 1)
				->where('status_id', '!=', 3)
				->where('es_aula_virtual', '=', 1)
				->get();
		}

		// TODO: Enviar notificaciones a los usuarios que no han ingresado a la capacitación
		$notificacionesEnviadas = 0;

		foreach($capacitaciones as $capacitacion) {

			if ($capacitacion->activo && $capacitacion->estado->name !== 'cancelada') {
				$personal = CapacitacionHasPersonal::where('capacitacion_id', $capacitacion->id)
					->where('fecha_inicio', '<=', now())
					->where('fecha_fin', '>=', now())
					->get();

				$notificacionesEnviadas = 0;

				foreach ($personal as $persona) {
					$sesionLog = SesionAccessLog::where('capacitacion_id', $capacitacion->id)
						->where('personal_id', $persona->personal_id)
						->whereNotNull('numero_de_evaluacion')
						->count();

					if (!$sesionLog) {
						if ($persona->personal->user) {
							Notification::send($persona->personal->user, new CapacitacionNotification($capacitacion));
						
							NotificacionEnviada::create([
								'capacitacion_id' => $capacitacion->id,
								'personal_id' => $persona->personal_id,
							]);
							
							$notificacionesEnviadas++;

						} else {
							$messages [] = [
								'type' => 'danger',
								'message' => 'El usuario ' . $persona->personal->name . ' no tiene un usuario asociado. No se ha enviado su notificación.'
							];
						}

					}
				}
				
				if ($notificacionesEnviadas > 0) {
					$message_notificacion = 'Notificaciones enviadas correctamente.'
						. ' Se han enviado ' . $notificacionesEnviadas . ' notificaciones.';
						foreach ($messages as $message) {
							$message_notificacion .= '<br><br>' . $message['message'];
						}
					$this->emit('alert', ['type' => 'success', 'message' => $message_notificacion ]);
				} else {
					$message_notificacion = 'No se han enviado notificaciones nuevas.';
						foreach ($messages as $message) {
							$message_notificacion .= '<br><br>' . $message['message'];
						}
					$this->emit('alert', ['type' => 'info', 'message' => $message_notificacion]);
				}

			}
		}
	}

}
