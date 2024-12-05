@section('title', __('Gabinetes'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
            <div class="rounded-2xl card">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4 class="h5">Lista Gabinete </h4>
						</div>
						{{--<div wire:poll.1s>
							<code><h5>{{ now()->format('H:i:s') }}</h5></code>
						</div>--}}
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success rounded-xl" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
						<div>
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
						</div>
						@can('crear-gabinetes')
						<div class="btn btn-sm btn-default rounded-xl" data-toggle="modal" data-target="#createDataModal">
						<i class="fa fa-plus"></i>  Nuevo
						</div>
						@endcan
					</div>
				</div>
				
				<div class="card-body">
						@can('crear-gabinetes')
						@include('livewire.gabinetes.create')
						@endcan						
						@can('editar-gabinetes')
						@include('livewire.gabinetes.update')
						@endcan
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm">
						<thead class="thead">
							<tr> 
								<th>#</th> 
								<th>Numero Gabinete</th>
								<th>Ubicacion</th>
								<th>Inspeccion Id</th>
								<th>Enrollada Correctamente</th>
								<th>Acoples Estado</th>
								<th>Limpieza Manguera</th>
								<th>Empaques Estado</th>
								<th>Pintura Gabinete</th>
								<th>Limpieza Gabinete</th>
								<th>Vidrio Estado</th>
								<th>Senalizacion</th>
								<th>Piton Obstruido</th>
								<th>Piton Estado</th>
								<th>Valvula Principal Estado</th>
								<th>Valvula Principal Abierta</th>
								<th>Manometro Estado</th>
								<th>Valvula Angular Estado</th>
								<th>Observaciones</th>
																
								@can('editar-gabinetes','borrar-gabinetes')
								<th>ACCIONES</th>								
								@endcan
							</tr>
						</thead>
						<tbody>
							@foreach($gabinetes as $row)
							<tr>
								<td>{{ $loop->iteration }}</td> 
								<td>{{ $row->numero_gabinete }}</td>
								<td>{{ $row->ubicacion }}</td>
								<td>{{ $row->inspeccion_id }}</td>
								<td>{{ $row->enrollada_correctamente }}</td>
								<td>{{ $row->acoples_estado }}</td>
								<td>{{ $row->limpieza_manguera }}</td>
								<td>{{ $row->empaques_estado }}</td>
								<td>{{ $row->pintura_gabinete }}</td>
								<td>{{ $row->limpieza_gabinete }}</td>
								<td>{{ $row->vidrio_estado }}</td>
								<td>{{ $row->senalizacion }}</td>
								<td>{{ $row->piton_obstruido }}</td>
								<td>{{ $row->piton_estado }}</td>
								<td>{{ $row->valvula_principal_estado }}</td>
								<td>{{ $row->valvula_principal_abierta }}</td>
								<td>{{ $row->manometro_estado }}</td>
								<td>{{ $row->valvula_angular_estado }}</td>
								<td>{{ $row->observaciones }}</td>
																
								@can('editar-gabinetes','borrar-gabinetes')
								<td width="90">
								<div class="btn-group">
									@can('editar-gabinetes')
									<a data-toggle="modal" data-target="#updateModal" class="btn btn-sm btn-primary rounded-xl" wire:click="edit({{$row->id}})">Editar </a>
									@endcan
									@can('borrar-gabinetes')							 
									<a class="btn btn-sm btn-danger rounded-xl" onclick="confirm('Confirma borrar Gabinete : {{$row->name}}? \nGabinetes borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"> Borrar </a> 
									@endcan  
								</div>
								</td>
								@endcan
							@endforeach
						</tbody>
					</table>						
					{{ $gabinetes->links() }}
					</div>
				</div>
                <div wire:loading wire:target="store,update,create,edit,destroy">
                    <x-loading-indicator />
                </div>	
			</div>
		</div>
	</div>
</div>
