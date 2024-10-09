@extends('adminlte::page')

@section('title', 'Vista Previa de Importación')

@section('content_header')
    <h1></h1>
@stop

@section('content')

<div class="card rounded-xl">
    <div class="text-white card-header bg-vanguard rounded-t-xl">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div class="float-left">
                <h5 class="h5">Previsualización de Importación</h5>
            </div>
            {{--ir atrás--}}
            <div>
                <a href="{{ route('capacitaciones.import.form') }}" class="btn btn-default rounded-xl" title="Volver a Importar Capacitaciones">
                    <i class="fa fa-arrow-left"></i> 
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body">

        <form action="{{ route('capacitaciones.confirm-import') }}" method="POST">
            @csrf
            <div class="table-responsive">
                <table class="table table-borderless table-sm">
                    <thead>
                        <tr>
                            <th style="min-width:100px">Acción</th>
                            <th>Identificador Único</th>
                            <th>Es Aula Virtual</th>
                            <th style="min-width:200px">Empresa</thmin-vw-100>
                            <th style="min-width:200px">Tipo de Capacitación</th>
                            <th style="min-width:200px">Tema</th>
                            <th style="min-width:150px">Sede</th>
                            <th>Fecha de Inicio</th>
                            <th>Fecha de Fin</th>
                            <th>Modalidad</th>
                            <th style="min-width:120px">DNI de Expositor Interno</th>
                            <th style="min-width:200px">Nombre de Expositor Interno</th>
                            <th style="min-width:200px">Nombre de Expositor Externo</th>
                            <th>Habilitada</th>
                            <th style="min-width:120px">Estado</th>
                            <th>Cantidad de Sesiones</th>
                            <th style="min-width:200px">Áreas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $row)
                            <tr>
                                <td>
                                    @if(App\Models\Capacitacione::where('identificador_unico', $row['identificador_unico'])->where(
                                        'es_aula_virtual', $row['es_aula_virtual'] ?? false
                                    )->exists())
                                        <span class="badge badge-warning">
                                            <i class="fas fa-pen"></i>
                                            Actualizará
                                        </span>
                                    @else
                                        <span class="badge badge-success">
                                            <i class="fas fa-plus"></i>
                                            Ingresará
                                        </span>
                                    @endif
                                </td>
                                <td><input class="form-control" type="text" name="data[{{ $loop->index }}][identificador_unico]" value="{{ $row['identificador_unico'] }}" readonly></td>
                                <td>{{ ($row['es_aula_virtual'] ?? false) ? 'SI' : 'NO' }}</td>
                                <td>
                                    <select class="form-control" name="data[{{ $loop->index }}][empresa]">
                                        @foreach(App\Models\Empresa::all() as $empresa)
                                            <option value="{{ $empresa->name }}" {{ $empresa->name == strtoupper($row['empresa']) ? 'selected' : '' }}>{{ $empresa->name }}</option>
                                        @endforeach
                                        @if(!App\Models\Empresa::where('name', $row['empresa'])->exists())
                                            <option value="{{ $row['empresa'] }}" selected>Nuevo: {{ $row['empresa'] }}</option>
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control" name="data[{{ $loop->index }}][tipo_de_capacitacion]">
                                        @foreach(App\Models\TipoDeCapacitacione::all() as $tipo)
                                            <option value="{{ $tipo->name }}" {{ $tipo->name == strtoupper($row['tipo_de_capacitacion']) ? 'selected' : '' }}>{{ $tipo->name }}</option>
                                        @endforeach
                                        @if(!App\Models\TipoDeCapacitacione::where('name', $row['tipo_de_capacitacion'])->exists())
                                            <option value="{{ $row['tipo_de_capacitacion'] }}" selected>Nuevo: {{ $row['tipo_de_capacitacion'] }}</option>
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control" name="data[{{ $loop->index }}][tema]">
                                        @foreach(App\Models\Tema::all() as $tema)
                                            <option value="{{ $tema->name }}" {{ $tema->name == strtoupper($row['tema']) ? 'selected' : '' }}>{{ $tema->name }}</option>
                                        @endforeach
                                        @if(!App\Models\Tema::where('name', $row['tema'])->exists())
                                            <option value="{{ $row['tema'] }}" selected>Nuevo: {{ $row['tema'] }}</option>
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control" name="data[{{ $loop->index }}][sede]">
                                        @foreach(App\Models\Sede::all() as $sede)
                                            <option value="{{ $sede->name }}" {{ $sede->name == strtoupper($row['sede']) ? 'selected' : '' }}>{{ $sede->name }}</option>
                                        @endforeach                        
                                        @if(!App\Models\Sede::where('name', $row['sede'])->exists())
                                            <option value="{{$row['sede']}}" selected>Nuevo: {{ $row['sede'] }}</option>
                                        @endif
                                    </select>
                                </td>
                                <td><input class="form-control" type="datetime-local" name="data[{{ $loop->index }}][fecha_de_inicio]" value="{{ $row['fecha_de_inicio'] }}"></td>
                                <td><input class="form-control" type="datetime-local" name="data[{{ $loop->index }}][fecha_de_fin]" value="{{ $row['fecha_de_fin'] }}"></td>
                                <td>
                                    <select class="form-control" name="data[{{ $loop->index }}][modalidad]">
                                        <option value="INTERNA" {{ $row['modalidad'] == 'INTERNA' ? 'selected' : '' }}>INTERNA</option>
                                        <option value="EXTERNA" {{ $row['modalidad'] == 'EXTERNA' ? 'selected' : '' }}>EXTERNA</option>
                                    </select>
                                </td>
                                {{-- <td>
                                                                        
                                    <select class="form-control" name="data[{{ $loop->index }}][modalidad]">
                                        @foreach(App\Models\Modalidade::all() as $modalidad)
                                            <option value="{{ $modalidad->name }}" {{ $modalidad->name ==  strtoupper($row['modalidad']) ? 'selected' : '' }}>{{ $modalidad->name }}</option>
                                        @endforeach
                                    </select>
                                </td> --}}
                                    {{-- <input class="form-control" type="text" name="data[{{ $loop->index }}][modalidad]" value="{{ $row['modalidad'] }}"></td> --}}
                                <td><input class="form-control" type="text" name="data[{{ $loop->index }}][dni_de_expositor_interno]" value="{{strtoupper($row['modalidad']) == 'INTERNA' ? $row['dni_de_expositor_interno'] : '' }}"></td>
                                <td><input class="form-control" type="text" value="{{ strtoupper($row['modalidad']) == 'INTERNA' ? (App\Models\Personal::where('dni', $row['dni_de_expositor_interno'])->first()->name ?? '' ) : '' }}" readonly></td>
                                <td><input class="form-control" type="text" name="data[{{ $loop->index }}][nombre_de_expositor_externo]" value="{{ strtoupper($row['modalidad']) == 'EXTERNA' ? $row['nombre_de_expositor_externo'] : '' }}"></td>
                                {{-- <td><input class="form-control" type="text" name="data[{{ $loop->index }}][habilitada]" value="{{ $row['habilitada'] }}"></td> --}}
                                <td>
                                    <select class="form-control" name="data[{{ $loop->index }}][habilitada]">
                                        <option value="SI" {{ $row['habilitada'] == 'SI' ? 'selected' : '' }}>SI</option>
                                        <option value="NO" {{ $row['habilitada'] == 'NO' ? 'selected' : '' }}>NO</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control" name="data[{{ $loop->index }}][estado]">
                                        <option value="PENDIENTE" {{ $row['estado'] == 'PENDIENTE' ? 'selected' : '' }}>PENDIENTE</option>
                                        <option value="CANCELADA" {{ $row['estado'] == 'CANCELADA' ? 'selected' : '' }}>CANCELADA</option>
                                        <option value="REALIZADA" {{ $row['estado'] == 'REALIZADA' ? 'selected' : '' }}>REALIZADA</option>
                                    </select>
                                </td>
                                {{-- <td>
                                    <select class="form-control" name="data[{{ $loop->index }}][estado]">
                                        @foreach(App\Models\Status::all() as $estado)
                                            <option value="{{ $estado->name }}" {{ $estado->name ==  strtoupper($row['estado']) ? 'selected' : '' }}>{{ $estado->name }}</option>
                                        @endforeach
                                    </select>
                                </td> --}}
                                <td><input class="form-control" type="number" name="data[{{ $loop->index }}][cantidad_de_sesiones]" value="{{ $row['cantidad_de_sesiones'] }}"></td>
                                <td>
                                    <div>
                                        <select class="form-control chosen-select" name="data[{{ $loop->index }}][areas][]" multiple="multiple">
                                            @foreach($row['all_areas'] as $area)
                                                <option value="{{ $area }}" {{ in_array($area, $row['areas']) ? 'selected' : '' }}>{{ $area }}</option>
                                            @endforeach
                                        </select>                                        
                                    </div>                                    
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button class="btn btn-vanguard" type="submit">Confirmar Importación</button>
        </form>

    </div>
</div>

@stop

@section('css')
    <!-- Incluir Chosen CSS desde CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" rel="stylesheet" />
    {{-- <style>
        /* Ajustar el CSS para permitir múltiples líneas en las opciones seleccionadas */
        .chosen-container-multi .chosen-choices li.search-choice {
            white-space: normal !important;
            word-wrap: break-word !important;
        }
    </style> --}}
@stop

@section('js')
    <!-- Incluir Chosen JS desde CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.chosen-select').chosen({
                no_results_text: "No se encontraron resultados",
                width: "100%",
                allow_single_deselect: true,
                placeholder_text_multiple: "Selecciona áreas",
                placeholder_text_single: "Selecciona un área"
            });
        });
    </script>
@stop