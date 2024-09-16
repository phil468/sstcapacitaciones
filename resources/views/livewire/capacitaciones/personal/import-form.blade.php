@extends('adminlte::page')

@section('title', 'Importar Personal de capacitaciones')

@section('content_header')
    <h1></h1>
@stop

@section('content')

<div class="card rounded-xl">
    <div class="text-white card-header bg-vanguard rounded-t-xl">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div class="float-left">
                <h2 class="h5"> Importar Personal para Capacitaciones</h2>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        @if(session('success'))
            <div>{{ session('success') }}</div>
        @endif

        <form action="{{ route('capacitaciones.personal.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="file" class="btn btn-success btn-block btn-lg">
                    <i class="fa fa-upload"></i> Seleccionar archivo
                </label>
                <input 
                type="file" 
                class="form-control-file d-none" 
                id="file" 
                name="file" 
                required>
            </div>
            <div class="alert alert-info " id="file-name">Ningún archivo seleccionado</div>
            <div class="row">
                <div class="col-sm-6">
                    <button type="submit" class="btn btn-vanguard btn-lg btn-block col-xs-6" id="upload-button" disabled>
                        <i class="fa fa-check"></i> Cargar
                    </button>
                </div>
                <div class="col-sm-6">
                    <button type="button" class="btn btn-default btn-lg btn-block col-xs-6" onclick="window.location='{{ route('capacitaciones') }}'">
                        <i class="fa fa-times"></i>
                        Cancelar
                    </button>
                </div>
            </div>
        </form>
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

<script>
    document.getElementById('file').addEventListener('change', function() {
        var fileName = this.files[0].name;
        document.getElementById('file-name').innerText = fileName;
        document.getElementById('upload-button').disabled = !fileName;
    });
</script>

@stop