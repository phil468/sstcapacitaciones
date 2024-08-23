<div class="container-fluid">
    @push('styles')
        <style>
            .table-bordered td,
            .table-bordered th {
                border: 5px solid #ffffff;
            }
        </style>
    @endpush
    <div class="row justify-content-center">
        <div class="col-md-12">
            @include('components.adminlte-alerts')
            {{-- usar clases d-none d-block --}}
            @if ($asignacion_id)
                @if ($sesion_id)
                    <div class="card rounded-xl">
                        <div class="text-white card-header bg-vanguard rounded-t-xl">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div class="float-left">
                                    <h5 class="h5">Sesión {{$sesion->numero_de_sesion}}: {{$sesion->name}}</h5>
                                    @section('title', __('Sesion: '.$sesion->name))
                                </div>
                                {{-- boton de volver --}}
                                <button class="rounded-xl btn btn-sm btn-default " wire:click="sesion(0)">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            <video width="100%" controls>
                                <source src="{{ Storage::disk('video_sesiones')->url($sesion->video) }}" type="video/mp4">
                                Tu navegador no soporta la reproducción de videos.
                            </video>
                            {{-- <p class="mt-2 card-text">{{ $sesion->descripcion }}</p> --}}
                        </div>

                    </div>
                @else
                    @if ($viewEvaluation)
                                <h5 class="h4"> Evaluación de {{$asignacion->capacitacion->tema->name}} </h5>
                                <br>
                                @section('title', __('Evaluación de '.$asignacion->capacitacion->tema->name))
                                @if (!empty($preguntasAleatorias))
                                    <form wire:submit.prevent="enviarEvaluacion">

                                        <ol class="pl-3">
                                            @foreach ($preguntasAleatorias as $index => $pregunta)
                                            <li class="mb-4">
                                                <div class="border-0 shadow-sm card">
                                                    <div class="text-white card-header bg-vanguard">
                                                        <strong>Pregunta {{ $index + 1 }}:</strong> {{ $pregunta->pregunta }}
                                                    </div>
                                                    <div class="card-body">
                                                        <ul class="mt-2 list-unstyled">
                                                            @foreach ($pregunta->opciones as $opcion)
                                                                <li class="mb-2 form-check">
                                                                    <input class="form-check-input" type="radio" name="preguntas.{{ $pregunta->id }}" value="{{ $opcion['id'] }}" id="opcion_{{ $opcion['id'] }}" wire:model.defer="respuestas.{{ $pregunta->id }}">
                                                                    <label class="form-check-label" for="opcion_{{ $opcion['id'] }}">
                                                                        {{ $opcion['opcion'] }}
                                                                    </label>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                        </ol>
                                        {{-- boton de enviar evaluacion o cancelar alinear a la derecha --}}
    
                                        <div class="mb-4 d-flex justify-content-end align-items-center">
                                            <button class="mr-2 rounded-xl btn btn-lg btn-vanguard" type="submit" 
                                            {{-- wire:click="enviarEvaluacion()" --}}
                                            >
                                                <i class="fas fa-check"></i> Enviar
                                            </button>
                                            <div class="rounded-xl btn btn-sm btn-default" wire:click="evaluacion(0)">
                                                <i class="fas fa-arrow-left"></i> Volver
                                            </div>
                                        </div>

                                    </form>
                                @else
                                    <p>No hay preguntas disponibles.</p>
                                @endif                          
                            {{-- </div>

                        </div> --}}
                    @else
                        <div class="card rounded-xl">
                                <div class="text-white card-header bg-vanguard rounded-t-xl">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <div class="float-left">
                                            <h5 class="h5">Tema de Capacitación: {{$asignacion->capacitacion->tema->name}}</h5>
                                            @section('title', __('Capacitación: '.$asignacion->capacitacion->tema->name))
                                        </div>
                                        {{-- boton de volver --}}
                                        <a class="rounded-xl btn btn-sm btn-default" href="{{route('mis-capacitaciones')}}"
                                        {{-- wire:click="asignacion(0)" --}}
                                        >
                                            <i class="fas fa-arrow-left"></i> Volver
                                        </a>
                                    </div>
                                </div>

                                <div class="card-body">
                                    @if ($asignacion->capacitacion->sesiones->count() > 0)
                                        <div class="timeline">
                                            
                                            @php
                                                $nextSessionEnabled = false;
                                            @endphp
                                            
                                            @foreach ($asignacion->capacitacion->sesiones as $index => $sesion)
                                                <div class="timeline-item">
                                                        <div class="timeline-icon text-white border border-color-success
                                                            @if ($sesion->accessed)
                                                                bg-vanguard
                                                            @elseif (!$nextSessionEnabled)
                                                                bg-primary   
                                                            @else
                                                                bg-gray
                                                            @endif
                                                            ">{{ $index + 1 }}
                                                        </div>
                                                            
                                                        <div>
                                                        
                                                            <div class="timeline-content text-left
                                                                    @if ($sesion->accessed)
                                                                        btn bg-vanguard
                                                                    @elseif (!$nextSessionEnabled)
                                                                        btn bg-primary   
                                                                    @else
                                                                        bg-gray
                                                                    @endif
                                                                    "
                                                                    @if ($sesion->accessed)
                                                                        wire:click="sesion({{$sesion->id}})"
                                                                    @elseif (!$nextSessionEnabled)
                                                                        wire:click="sesion({{$sesion->id}})"
                                                                    @else
                                                                    @endif
                                                                    >
                                                                
                                                                <h5 class="text-white timeline-title">
                                                                    @if ($sesion->accessed)
                                                                        <i class="fas fa-check-circle fa-lg"></i>                                                                        
                                                                    @else
                                                                        <i class="text-white far fa-circle fa-lg"></i>
                                                                    @endif
                                                                    {{ $sesion->name }}
                                                                </h5>
                                                            </div>
                                                        </div>
                                                </div>
                                                @if ($sesion->accessed)

                                                @elseif (!$nextSessionEnabled)
                                                    @php
                                                        $nextSessionEnabled = true;
                                                    @endphp
                                                @else
                                                
                                                @endif
                                            @endforeach
                                            
                                            @if ($allSessionsCompleted && $intentosRegistrados < $intentosPermitidos)
                                                <div class="timeline-item">
                                                    <div class="text-white align-content-center timeline-icon bg-primary">{{ $asignacion->capacitacion->sesiones->count() + 1 }}</div>
                                                    <div class="text-left align-content-center timeline-content bg-primary btn" wire:click="evaluacion(true)">
                                                        <h5 class="text-white timeline-title">
                                                            <i class="text-white far fa-circle fa-lg"></i>
                                                            Evaluación - {{$intentosRegistrados}} intento(s) de {{$intentosPermitidos}}
                                                            @if ($puntaje)
                                                                <br>
                                                                <br>
                                                                <h4 class="h4">
                                                                    <span class="badge @if ($puntaje >= $nota_minima_aprobatoria) badge-success @elseif ($puntaje > 0) badge-warning @else badge-danger @endif
                                                                        badge-pill ">Nota Intento #{{$intentosRegistrados}}: {{$puntaje}}</span>
                                                                </h4>
                                                            @endif
                                                        </h5>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="timeline-item">
                                                    <div class="text-white align-content-center timeline-icon bg-gray">{{ $asignacion->capacitacion->sesiones->count() + 1 }}</div>
                                                    <div class="text-left align-content-center timeline-content bg-gray">
                                                        <h5 class="text-white timeline-title">
                                                            <i class="text-white far fa-circle fa-lg"></i>
                                                            Evaluación - {{$intentosRegistrados}} intento(s) de {{$intentosPermitidos}}
                                                            @if ($puntaje)
                                                                <br>
                                                                <br>
                                                                <h4 class="h4">
                                                                    <span class="badge @if ($puntaje >= $nota_minima_aprobatoria) badge-primary @elseif ($puntaje > 0) badge-warning @else badge-danger @endif
                                                                        badge-pill ">Nota Intento #{{$intentosRegistrados}}: {{$puntaje}}</span>
                                                                        <br>
                                                                <span class="badge badge-gray badge-pill">Intentos agotados</span>
                                                                </h4>
                                                            @endif
                                                        </h5>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                        </div>
                                    @else
                                        <p>No hay sesiones disponibles para esta capacitación.</p>
                                    @endif
                                </div>

                        </div>
                    @endif
                @endif
            @else
            
                <div class="card rounded-xl">
                    <div class="text-white card-header bg-vanguard rounded-t-xl">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="float-left">
                                <h5 class="h5">Mis Capacitaciones</h5>
                                @section('title', __('Mis Capacitaciones'))
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @isset($misCapacitaciones)
                            @if ($misCapacitaciones->count() > 0)

                                @if ($vistaAlternativa == false)
                                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4">
                                        @foreach ($misCapacitaciones as $row)
                                            <div class="mb-4 col">
                                                <div  wire:click='asignacion({{$row->id}})'>
                                                  
                                                    <div class="h-full bg-gray-100 btn card rounded-2xl"'>
                                                        <div class="text-center align-content-top card-body bg-default">
                                                            <div class="mb-2 h-3/4 align-content-end">
                                                                <p class="align-content-end">
                                                                    <i class="fas fa-user bg-primary rounded-circle"
                                                                        style=
                                                                        "width: 40px;
                                                                        height: 40px;
                                                                        font-size: x-large;
                                                                        align-content: center;">
                                                                    </i>
                                                                    <h5 class="mb-2 text-center"
                                                                        style=
                                                                        "font-size: 1.40rem;
                                                                        font-weight: 700;
                                                                        margin: 0;">
                                                                        {{ $row->capacitacion->tema->name }}
                                                                        <hr class=""
                                                                            style="
                                                                            border-top-width: 3px;
                                                                            border-color: #3c4651;">
                                                                    </h5>
                                                                </p>

                                                                <div class="mb-2 card-subtitle text-muted">
                                                                </div>
                                                                <p class="mb-1 card-text">
                                                                    {{ $row->capacitacion->sesiones->count() }} Sesiones
                                                                </p>
                                                                <p class="mb-1 card-text">
                                                                        <span class="font-weight-bold">Nota: </span>
                                                                        <h5 class="h4">
                                                                            <span class="badge @if ($row->obtenerNota() >= $row->capacitacion->nota_minima_aprobatoria) badge-success @elseif ($puntaje > 0) badge-warning @else badge-danger @endif">{{ $row->obtenerNota() }}</span>
                                                                        </h5>
                                                                </p>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else

                                @endif
                    
                        @else
                        <div class="alert alert-default" role="alert">
                            No tiene capacitaciones pendientes.
                        </div>
                        @endif
                    @endisset
                    </div>

                    <div wire:loading wire:target="sesion,asignacion,evaluacion">
                        <x-loading-indicator />
                    </div>
                </div>
                
            @endif
            
        </div>
    </div>

    @section('css')
        <style>
            .timeline {
                position: relative;
                padding: 20px 0;
                list-style: none;
            }
            .timeline:before {
                content: '';
                position: absolute;
                top: 0;
                bottom: 0;
                width: 2px;
                background: #C5C5C5;
                left: 49.5%;
                margin-left: -1px;
            }
            .timeline-item {
                margin-bottom: 20px;
                position: relative;
            }
            .timeline-item:before,
            .timeline-item:after {
                content: " ";
                display: table;
            }
            .timeline-item:after {
                clear: both;
            }
            .timeline-item .timeline-icon {
                position: absolute;
                left: 50%;
                width: 40px;
                height: 40px;
                margin-left: -20px;
                background: #fff;
                border-radius: 50%;
                border: 2px solid #C5C5C5;
                text-align: center;
                line-height: 40px;
                font-size: 16px;
                color: #C5C5C5;
            }
            .timeline-item .timeline-content {
                width: 45%;
                padding: 20px;
                background: #f5f5f5;
                position: relative;
                border-radius: 5px;
            }
            .timeline-item:nth-child(odd) .timeline-content {
                left: 0;
            }
            .timeline-item:nth-child(even) .timeline-content {
                left: 55%;
            }
            .timeline-item .timeline-title {
                margin-top: 0;
                color: #333;
            }
            .timeline-item .timeline-description {
                margin-bottom: 0;
            }
        </style>
    @stop

</div>
