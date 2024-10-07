@extends('adminlte::page')

@section('title', 'Vista Previa de Importación')

@section('content_header')
    <h1></h1>
@stop

@php
    use Carbon\Carbon;
@endphp

@section('content')

<div class="card rounded-xl">
    <div class="text-white card-header bg-vanguard rounded-t-xl">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div class="float-left">
                <h5 class="h5">Resultado de Importar</h5>
            </div>
            <div>
                <a href="{{ route('capacitaciones') }}" class="btn btn-default rounded-xl" title="Volver a Capacitaciones">
                    <i class="fas fa-sign-in-alt"></i> Capacitaciones
                </a>
                <a href="{{ route('capacitaciones.import.form') }}" class="btn btn-default rounded-xl" title="Volver a Importar Capacitaciones">
                    <i class="fa fa-file-import"></i> Importación
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <th>Resultado</th>
                        <th>Mensaje</th>
                        <th>Identificador Único</th>
                        <th>Es aula virtual</th>
                        <th>Empresa</th>
                        <th>Tipo de Capacitación</th>
                        <th>Tema</th>
                        <th>Sede</th>
                        <th>Fecha de Inicio</th>
                        <th>Fecha de Fin</th>
                        <th>Modalidad</th>
                        <th>DNI de Expositor Interno</th>
                        <th>Nombre de Expositor Interno</th>
                        <th>Nombre de Expositor Externo</th>
                        <th>Habilitada</th>
                        <th>Estado</th>
                        <th>Cantidad de Sesiones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($result as $res)
                        <tr>
                            <td>
                                @if ($res['status'] == 'success')
                                    <span class="badge badge-info">
                                        <i class="fas fa-check"></i>
                                        Importado
                                    </span>
                                
                                    @if($res['estado_importacion'] == 'Editado' )
                                        <span class="badge badge-warning">
                                            <i class="fas fa-pen"></i>
                                            Actualizado
                                        </span>
                                
                                    @elseif($res['estado_importacion'] == 'Ingresado' )
                                        <span class="badge badge-success">
                                            <i class="fas fa-pen"></i>
                                            Ingresado
                                        </span>
                                    @endif
                            
                                @endif
                                @if ($res['status'] == 'error')
                                    <span class="badge badge-danger">
                                        <i class="fas fa-pen"></i>
                                        Error
                                    </span>
                                    {{$res['message']}}
                                @endif
                            </td>
                            <td>{{ $res['message'] ?? '' }}</td>
                            <td>{{ $res['row']['identificador_unico'] }}</td>
                            <td>{{ ($row['es_aula_virtual'] ?? false) ? 'SI' : 'NO' }}</td>
                            <td>{{ $res['row']['empresa'] }}</td>
                            <td>{{ $res['row']['tipo_de_capacitacion'] }}</td>
                            <td>{{ $res['row']['tema'] }}</td>
                            <td>{{ $res['row']['sede'] }}</td>
                            <td>{{ Carbon::parse($res['row']['fecha_de_inicio'])->format('d/m/Y h:m:s a') }}</td>

                            <td>{{ Carbon::parse($res['row']['fecha_de_fin'])->format('d/m/Y h:m:s a') }}</td>
                       
                            <td>{{ $res['row']['modalidad'] }}</td>
                            <td>{{ strtoupper($res['row']['modalidad']) == 'INTERNA' ? $res['row']['dni_de_expositor_interno'] : ''}}</td>
                            <td>{{ strtoupper($res['row']['modalidad']) == 'INTERNA' ? (App\Models\Personal::where('dni', $res['row']['dni_de_expositor_interno'])->first()->name ?? '' ) : '' }}</td>
                            <td>{{ strtoupper($res['row']['modalidad']) == 'EXTERNA' ? $res['row']['nombre_de_expositor_externo'] : ''}}</td>
                            <td>{{ $res['row']['habilitada'] }}</td>
                            <td>{{ $res['row']['estado'] }}</td>
                            <td>{{ $res['row']['cantidad_de_sesiones'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <a href="{{ route('capacitaciones') }}" class="btn btn-vanguard rounded-xl" title="Volver a Capacitaciones">
            <i class="fas fa-sign-in-alt"></i> Ir a Capacitaciones
        </a>
        <a href="{{ route('capacitaciones.import.form') }}" class="btn btn-vanguard rounded-xl" title="Volver a Importar Capacitaciones">
            <i class="fa fa-file-import"></i> Ir a Importación
        </a>
        
    </div>
</div>

@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script type="text/javascript">
    </script>
@stop