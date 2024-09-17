<div>
	@include('components.adminlte-alerts')
    <div class="card rounded-xl">
        <div class="text-white card-header bg-vanguard rounded-t-xl">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div class="float-left">
                    <h5 class="h5">Detalles de la Capacitación</h5>
                </div>
                <div>
                    @if ($capacitacion->activo && ($capacitacion->estado->name !== 'cancelada'))
                        <a class="mx-2 btn btn-default rounded-xl" title="Enviar Notificación" wire:click='notificar({{$capacitacion->id}})'>
                            <i class="fa fa-bell"></i>
                        </a>
                    @endif
                    <a href="{{ route('capacitaciones') }}" class="btn btn-default rounded-xl" title="Volver a lista de Capacitaciones">
                        <i class="fa fa-arrow-left"></i> 
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <table class="table table-striped table-borderless table-sm">
                <tr class="align-left">
                    <td style="max-width: 100px" class="text-right" class="text-right"><strong>Tema</strong></td>
                    <td style="max-width: 0.5px"><strong>:</strong></td>
                    <td class="text-left">{{ $capacitacion->tema->name }}</td>
                </tr>
                <tr>
                    <td style="max-width: 100px" class="text-right"><strong>Sesiones</strong></td>
                    <td style="max-width: 0.5px"><strong>:</strong></td>
                    <td class="text-left">{{ $capacitacion->sesiones->count() }}</td>
                </tr>
                <tr>
                    <td style="max-width: 100px" class="text-right"><strong>Cantidad de Preguntas a Mostrar</strong></td>
                    <td style="max-width: 0.5px"><strong>:</strong></td>
                    <td class="text-left">{{ $capacitacion->cantidad_de_preguntas_a_mostrar }}</td>
                </tr>
                <tr>
                    <td style="max-width: 100px" class="text-right"><strong>Preguntas Ingresadas</strong></td>
                    <td style="max-width: 0.5px"><strong>:</strong></td>
                    <td class="text-left">{{ $capacitacion->preguntas->count() }}</td>
                </tr>
                <tr>
                    <td style="max-width: 100px" class="text-right"><strong>Activo</strong></td>
                    <td style="max-width: 0.5px"><strong>:</strong></td>
                    <td class="text-left">{{ $capacitacion->activo ? 'Si' : 'No' }}</td>
                </tr>
                <tr>
                    <td style="max-width: 100px" class="text-right"><strong>Onboarding</strong></td>
                    <td style="max-width: 0.5px"><strong>:</strong></td>
                    <td class="text-left">{{ $capacitacion->es_onboarding ? 'Si' : 'No' }}</td>
                </tr>
                <tr>
                    <td style="max-width: 100px" class="text-right"><strong>Fecha de Inicio</strong></td>
                    <td style="max-width: 0.5px"><strong>:</strong></td>
                    <td class="text-left">{{ $capacitacion->fecha_inicio }}</td>
                </tr>
                <tr>
                    <td style="max-width: 100px" class="text-right"><strong>Fecha de Fin</strong></td>
                    <td style="max-width: 0.5px"><strong>:</strong></td>
                    <td class="text-left">{{ $capacitacion->fecha_fin }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>