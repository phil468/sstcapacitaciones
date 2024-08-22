@extends('adminlte::page')

@section('title', 'Capacitaciones')

@section('content_header')
    <h1></h1>
@stop

@section('content')
@livewire('capacitacion-has-personals', ['capacitacion_id' => $capacitacion_id])

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


