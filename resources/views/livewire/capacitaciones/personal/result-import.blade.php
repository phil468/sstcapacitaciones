@extends('adminlte::page')

@section('title', 'Resultado de Importación de Personal')

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
                <h5 class="h5">Resultado de Importar Personal</h5>
            </div>
            <div>
                <a href="{{ route('capacitaciones') }}" class="btn btn-default rounded-xl" title="Volver a Capacitaciones">
                    <i class="fas fa-sign-in-alt"></i> Capacitaciones
                </a>
                <a href="{{ route('capacitaciones.personal.import.form') }}" class="btn btn-default rounded-xl" title="Volver a Importar Personal">
                    <i class="fa fa-file-import"></i> Importación
                </a>
            </div>
            
            {{-- <div>
                <a href="{{ route('capacitaciones.personal.import.form') }}" class="btn btn-default rounded-xl" title="Volver a Importar Personal">
                    <i class="fa fa-arrow-left"></i> 
                </a>
            </div> --}}
        </div>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <th>Resultado</th>
                        <th>Mensaje</th>
                        <th>Identificador Único de Capacitación</th>
                        <th>DNI de Personal</th>
                        <th>Nombre de Personal</th>
                        <th>Empresa</th>
                        <th>Gerencia</th>
                        <th>Sede</th>
                        <th>Área</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($result as $res)
                        <tr>
                            <td
                            @if ($res['status'] == 'success')
                                style="background-color: #d4edda;"                
                            @endif
                            @if ($res['status'] == 'error')
                                style="background-color: #f8d7da;"                
                            @endif>{{ $res['estado_importacion'] }} </td>
                            <td>{{ $res['message'] ?? '' }}</td>
                            <td>{{ $res['row']['identificador_unico_de_capacitacion'] }}</td>
                            <td>{{ $res['row']['dni_de_personal'] }}</td>
                            <td>{{ $res['row']['nombre_de_personal'] }}</td>
                            <td>{{ $res['row']['empresa'] }}</td>
                            <td>{{ $res['row']['gerencia'] }}</td>
                            <td>{{ $res['row']['sede'] }}</td>
                            <td>{{ $res['row']['area'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <a href="{{ route('capacitaciones') }}" class="btn btn-vanguard rounded-xl" title="Volver a Capacitaciones">
                <i class="fas fa-sign-in-alt"></i>Ir a Capacitaciones
            </a>
            <a href="{{ route('capacitaciones.personal.import.form') }}" class="btn btn-vanguard rounded-xl" title="Volver a Importar Personal">
                <i class="fa fa-file-import"></i>Ir a Importación
            </a>
            
        </div>
    </div>
</div>

@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script type="text/javascript">
    </script>
    
    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.on('alert', param => {
                Swal.fire({
                    icon: param.type,
                    // title: 'Notificación',
                    title: param.message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
            });
        });
    </script>
@stop