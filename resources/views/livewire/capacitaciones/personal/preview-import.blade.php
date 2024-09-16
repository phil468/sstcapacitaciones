@extends('adminlte::page')

@section('title', 'Vista previa de Importación')

@section('content_header')
    <h1></h1>
@stop

@section('content')

<div class="card rounded-xl">
    <div class="text-white card-header bg-vanguard rounded-t-xl">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div class="float-left">
                <h2 class="h5">Previsualización de Importación de Personal</h2>
            </div>
            <div>
                <a href="{{ route('capacitaciones.personal.import.form') }}" class="btn btn-default rounded-xl" title="Volver a Importar Capacitaciones">
                    <i class="fa fa-arrow-left"></i> 
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <form action="{{ route('capacitaciones.personal.confirm-import') }}" method="POST">
            @csrf
            <div class="table-responsive">
                <table class="table table-borderless table-sm">
                    <thead>
                        <tr>
                            <th>Identificador Único de Capacitación</th>
                            <th>DNI de Personal</th>
                            <th style="min-width:200px">Nombre de Personal</th>
                            <th style="min-width:200px">Empresa</th>
                            <th style="min-width:200px">Gerencia</th>
                            <th style="min-width:200px">Sede</th>
                            <th style="min-width:200px">Área</th>
                            <th style="min-width:200px">Errores</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $index => $row)
                            <tr class="{{ $row['valid'] ? '' : 'table-danger' }}">
                                <input class="form-control" type="text" name="data[{{ $index }}][valid]" value="{{ $row['valid'] }}" hidden readonly>

                                <td>{{ $row['identificador_unico_de_capacitacion'] }}
                                    <input class="form-control" type="text" name="data[{{ $index }}][identificador_unico_de_capacitacion]" value="{{ $row['identificador_unico_de_capacitacion'] }}" hidden readonly>
                                </td>
                                <td>{{ $row['dni_de_personal'] }}
                                    <input class="form-control" type="text" name="data[{{ $index }}][dni_de_personal]" value="{{ $row['dni_de_personal'] }}" hidden readonly>
                                </td>
                                <td>{{ $row['nombre_de_personal'] }}
                                    <input class="form-control" type="text" name="data[{{ $index }}][nombre_de_personal]" value="{{ $row['nombre_de_personal'] }}" hidden readonly>

                                </td>
                                <td>
                                    <select class="form-control" name="data[{{ $index }}][empresa]">
                                        @if (empty($row['empresa']))
                                            <option value="" selected>Seleccione</option>
                                        @elseif(!App\Models\Empresa::where('name', $row['empresa'])->exists())
                                            <option value="{{ $row['empresa'] }}" selected>Nuevo: {{ $row['empresa'] }}</option>
                                        @endif
                                        @foreach($empresas as $empresa)
                                            <option value="{{ $empresa->name }}" {{ $empresa->name == strtoupper($row['empresa']) ? 'selected' : '' }}>{{ $empresa->name }}</option>
                                        @endforeach
                                        <small id="empresa_de_personal" class="form-text text-muted">Actual: {{$row['empresa_de_personal']??'No registrado'}}</small>
                                        {{-- @if(!App\Models\Empresa::where('name', $row['empresa'])->exists())
                                            <option value="{{ $row['empresa'] }}" selected>Nuevo: {{ $row['empresa'] }}</option>
                                        @endif --}}
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control" name="data[{{ $index }}][gerencia]">
                                        @if (empty($row['gerencia']))
                                            <option value="" selected>Seleccione</option>
                                        @elseif(!App\Models\Gerencia::where('name', $row['gerencia'])->exists())
                                            <option value="{{ $row['gerencia'] }}" selected>Nuevo: {{ $row['gerencia'] }}</option>
                                        @endif
                                        @foreach($gerencias as $gerencia)
                                            <option value="{{ $gerencia->name }}" {{ $gerencia->name == strtoupper($row['gerencia']) ? 'selected' : '' }}>{{ $gerencia->name }}</option>
                                        @endforeach
                                    </select>
                                    <small id="gerencia_de_personal" class="form-text text-muted">Actual: {{$row['gerencia_de_personal']??'No registrado'}}</small>
                                </td>
                                <td>
                                    <select class="form-control" name="data[{{ $index }}][sede]">
                                        @if (empty($row['sede']))
                                            <option value="" selected>Seleccione</option>
                                        @elseif(!App\Models\Sede::where('name', $row['sede'])->exists())
                                            <option value="{{ $row['sede'] }}" selected>Nuevo: {{ $row['sede'] }}</option>
                                        @endif
                                        @foreach($sedes as $sede)
                                            <option value="{{ $sede->name }}" {{ $sede->name == strtoupper($row['sede']) ? 'selected' : '' }}>{{ $sede->name }}</option>
                                        @endforeach
                                    </select>
                                    <small id="sede_de_personal" class="form-text text-muted">Actual: {{$row['sede_de_personal']??'No registrado'}}</small>
                                </td>
                                <td>
                                    <select class="form-control" name="data[{{ $index }}][area]">
                                        @if (empty($row['area']))
                                            <option value="" selected>Seleccione</option>
                                        @elseif(!App\Models\Area::where('name', $row['area'])->exists())
                                            <option value="{{ $row['area'] }}" selected>Nuevo: {{ $row['area'] }}</option>
                                        @endif
                                        @foreach($areas as $area)
                                            <option value="{{ $area->name }}" {{ $area->name == strtoupper($row['area']) ? 'selected' : '' }}>{{ $area->name }}</option>
                                        @endforeach
                                    </select>
                                    <small id="area_de_personal" class="form-text text-muted">Actual: {{$row['area_de_personal']??'No registrado'}}</small>
                                </td>
                                <td>
                                    @if(!$row['valid'])
                                        <ul>
                                            @foreach($row['errors'] as $error)
                                                <li>{{ $error }}</li>
                                                <input type="hidden" name="data[{{ $index }}][errors][]" value="{{ $error }}">
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button type="submit" class="btn btn-vanguard rounded-2xl">Confirmar Importación</button>
        </form>
    </div>
</div>

{{-- <div class="container">
    <h2>Previsualización de Importación de Personal</h2>
    <form action="{{ route('capacitaciones.personal.confirm-import') }}" method="POST">
        @csrf
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Identificador Único de Capacitación</th>
                        <th>DNI de Personal</th>
                        <th>Nombre de Personal</th>
                        <th>Empresa</th>
                        <th>Gerencia</th>
                        <th>Sede</th>
                        <th>Área</th>
                        <th>Errores</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $index => $row)
                        <tr class="{{ $row['valid'] ? '' : 'table-danger' }}">
                            <td>{{ $row['identificador_unico_de_capacitacion'] }}</td>
                            <td>{{ $row['dni_de_personal'] }}</td>
                            <td>{{ $row['nombre_de_personal'] }}</td>
                            <td>
                                <select class="form-control" name="data[{{ $index }}][empresa]">
                                    @foreach($empresas as $empresa)
                                        <option value="{{ $empresa->name }}" {{ $empresa->name == strtoupper($row['empresa']) ? 'selected' : '' }}>{{ $empresa->name }}</option>
                                    @endforeach
                                    @if(!App\Models\Empresa::where('name', $row['empresa'])->exists())
                                        <option value="{{ $row['empresa'] }}"><span class="badge badge-warning">Nuevo</span> {{ $row['empresa'] }}</option>
                                    @endif
                                </select>
                            </td>
                            <td>
                                <select class="form-control" name="data[{{ $index }}][gerencia]">
                                    @foreach($gerencias as $gerencia)
                                        <option value="{{ $gerencia->name }}" {{ $gerencia->name == strtoupper($row['gerencia']) ? 'selected' : '' }}>{{ $gerencia->name }}</option>
                                    @endforeach
                                    @if(!App\Models\Gerencia::where('name', $row['gerencia'])->exists())
                                        <option value="{{ $row['gerencia'] }}"><span class="badge badge-warning">Nuevo</span> {{ $row['gerencia'] }}</option>
                                    @endif
                                </select>
                            </td>
                            <td>
                                <select class="form-control" name="data[{{ $index }}][sede]">
                                    @foreach($sedes as $sede)
                                        <option value="{{ $sede->name }}" {{ $sede->name == strtoupper($row['sede']) ? 'selected' : '' }}>{{ $sede->name }}</option>
                                    @endforeach
                                    @if(!App\Models\Sede::where('name', $row['sede'])->exists())
                                        <option value="{{ $row['sede'] }}"><span class="badge badge-warning">Nuevo</span> {{ $row['sede'] }}</option>
                                    @endif
                                </select>
                            </td>
                            <td>
                                <select class="form-control" name="data[{{ $index }}][area]">
                                    @foreach($areas as $area)
                                        <option value="{{ $area->name }}" {{ $area->name == strtoupper($row['area']) ? 'selected' : '' }}>{{ $area->name }}</option>
                                    @endforeach
                                    @if(!App\Models\Area::where('name', $row['area'])->exists())
                                        <option value="{{ $row['area'] }}"><span class="badge badge-warning">Nuevo</span> {{ $row['area'] }}</option>
                                    @endif
                                </select>
                            </td>
                            <td>
                                @if(!$row['valid'])
                                    <ul>
                                        @foreach($row['errors'] as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-primary">Confirmar Importación</button>
    </form>
</div> --}}

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