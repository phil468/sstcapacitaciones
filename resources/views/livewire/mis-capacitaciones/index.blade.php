@extends('adminlte::page')

@section('content_header')
    <h1></h1>
@stop

@section('content')

@livewire('mis-capacitaciones')

@stop

@section('css')
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