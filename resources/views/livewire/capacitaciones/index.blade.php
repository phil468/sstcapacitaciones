@extends('adminlte::page')

@section('title', 'Capacitaciones')

@section('content_header')
    <h1></h1>
@stop

@section('content')

@livewire('capacitaciones',['id' => $id ?? null])

@if (isset($id))
    @php
        // verificar si capacitaciuon exite con el id
        $capacitacion = App\Models\Capacitacione::find($id);
    @endphp 
        @if ($capacitacion)
            @livewire('sesiones',['capacitacion_id' => $id])
            @livewire('preguntas', ['capacitacion_id' => $id])
            {{-- @livewire('asignaciones', ['capacitacion_id' => $id]) --}}
            @livewire('capacitacion-has-personals', ['capacitacion_id' => $id,'es_aula_virtual' => $capacitacion->es_aula_virtual])
        @endif
@endif


@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script type="text/javascript">
        // window.livewire.on('dataReturned', () => {
        //     location.hash = "#busqueda";
        //     location.hash = "#resultados";
        // });
    </script>
@stop