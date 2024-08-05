@section('title', __('Asignaciones'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
            <div class="rounded-2xl card">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4 class="h5">Asignaciones </h4>
						</div>
						{{--<div wire:poll.1s>
							<code><h5>{{ now()->format('H:i:s') }}</h5></code>
						</div>--}}
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success rounded-xl" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
						{{-- <div>
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
						</div> --}}
						@can('crear-asignaciones')
						<div class="btn btn-default rounded-xl" data-toggle="modal" data-target="#createDataModal" title="Nueva Asignación">
						<i class="fa fa-plus"></i>
						</div>
						@endcan
					</div>
				</div>
				
				<div class="card-body">
						@can('crear-asignaciones')
						@include('livewire.asignaciones.create')
						@endcan						
						@can('editar-asignaciones')
						@include('livewire.asignaciones.update')
						@endcan
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm">
						<thead class="thead">
							<tr> 
								{{-- <th>#</th>  --}}
								<th>Capacitacion</th>
								<th>Personal</th>
								<th>Fecha Inicio</th>
								<th>Fecha Fin</th>
								<th>Intentos De Evaluación</th>
								<th>Realizado</th>
								<th>Finalizado</th>
								<th>Creado por</th>
								<th>Actualizado por</th>
								<th>Eliminado por</th>
																
								@can('editar-asignaciones','borrar-asignaciones')
								<th>ACCIONES</th>								
								@endcan
							</tr>
						</thead>
						<tbody>
							@foreach($asignaciones as $row)
							<tr>
								{{-- <td>{{ $loop->iteration }}</td>  --}}
								<td>{{ $row->capacitacion->tema->name }}</td>
								<td>{{ $row->personal->name }}</td>
								<td>{{ $row->fecha_inicio }}</td>
								<td>{{ $row->fecha_fin }}</td>
								<td>{{ $row->intentos_de_evaluacion }}</td>
								<td>
									@include('livewire.custom-boolean', ['modelId' => $row->id, 'field' => 'realizado', 'value' => $realizado])
								</td>
								<td>
									@include('livewire.custom-boolean', ['modelId' => $row->id, 'field' => 'finalizado', 'value' => $finalizado])
								</td>
								<td>{{ $row->creado_por->name ?? '' }}</td>
								<td>{{ $row->actualizado_por->name ?? '' }}</td>
								<td>{{ $row->eliminado_por->name ?? '' }}</td>
																
								@can('editar-asignaciones','borrar-asignaciones')
								<td width="90">
								<div class="btn-group">
									@can('editar-asignaciones')
									<a data-toggle="modal" data-target="#updateModal" class="btn btn-vanguard rounded-xl" wire:click="edit({{$row->id}})">
										<i class="fa fa-edit"></i>
									</a>
									@endcan
									@can('borrar-asignaciones')							 
									<a class="btn btn-danger rounded-xl" onclick="confirm('Confirma borrar Asignacione : {{$row->name}}? \nAsignaciones borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})">
										<i class="fa fa-trash"></i>
									</a> 
									@endcan  
								</div>
								</td>
								@endcan
							@endforeach
						</tbody>
					</table>						
					{{ $asignaciones->links() }}
					</div>
				</div>
                <div wire:loading wire:target="store,update,create,edit,destroy">
                    <x-loading-indicator />
                </div>	
			</div>
		</div>
	</div>
</div>
