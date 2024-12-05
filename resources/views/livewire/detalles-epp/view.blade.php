@section('title', __('Detalles Epps'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
            <div class="rounded-2xl card">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4 class="h5">Lista Detalles Epp </h4>
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
						@can('crear-detalles-epp')
						<div class="btn btn-sm btn-default rounded-xl" data-toggle="modal" data-target="#createDataModal">
						<i class="fa fa-plus"></i>  Nuevo
						</div>
						@endcan
					</div>
				</div>
				
				<div class="card-body">
						@can('crear-detalles-epp')
						@include('livewire.detalles-epp.create')
						@endcan						
						@can('editar-detalles-epp')
						@include('livewire.detalles-epp.update')
						@endcan
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm">
						<thead class="thead">
							<tr> 
								<th>#</th> 
								<th>Inspeccion Id</th>
								<th>Item</th>
								<th>Nombre Trabajador</th>
								<th>Dni</th>
								<th>Cargo</th>
								<th>Casco Tiene</th>
								<th>Casco Uso</th>
								<th>Casco Condicion</th>
								<th>Zapatos Tiene</th>
								<th>Zapatos Uso</th>
								<th>Zapatos Condicion</th>
								<th>Lentes Tiene</th>
								<th>Lentes Uso</th>
								<th>Lentes Condicion</th>
								<th>Respirador Tiene</th>
								<th>Respirador Uso</th>
								<th>Respirador Condicion</th>
								<th>Protector Auditivo Tiene</th>
								<th>Protector Auditivo Uso</th>
								<th>Protector Auditivo Condicion</th>
								<th>Guantes Tiene</th>
								<th>Guantes Uso</th>
								<th>Guantes Condicion</th>
								<th>Otros</th>
																
								@can('editar-detalles-epp','borrar-detalles-epp')
								<th>ACCIONES</th>								
								@endcan
							</tr>
						</thead>
						<tbody>
							@foreach($detallesEpps as $row)
							<tr>
								<td>{{ $loop->iteration }}</td> 
								<td>{{ $row->inspeccion_id }}</td>
								<td>{{ $row->item }}</td>
								<td>{{ $row->nombre_trabajador }}</td>
								<td>{{ $row->dni }}</td>
								<td>{{ $row->cargo }}</td>
								<td>{{ $row->casco_tiene }}</td>
								<td>{{ $row->casco_uso }}</td>
								<td>{{ $row->casco_condicion }}</td>
								<td>{{ $row->zapatos_tiene }}</td>
								<td>{{ $row->zapatos_uso }}</td>
								<td>{{ $row->zapatos_condicion }}</td>
								<td>{{ $row->lentes_tiene }}</td>
								<td>{{ $row->lentes_uso }}</td>
								<td>{{ $row->lentes_condicion }}</td>
								<td>{{ $row->respirador_tiene }}</td>
								<td>{{ $row->respirador_uso }}</td>
								<td>{{ $row->respirador_condicion }}</td>
								<td>{{ $row->protector_auditivo_tiene }}</td>
								<td>{{ $row->protector_auditivo_uso }}</td>
								<td>{{ $row->protector_auditivo_condicion }}</td>
								<td>{{ $row->guantes_tiene }}</td>
								<td>{{ $row->guantes_uso }}</td>
								<td>{{ $row->guantes_condicion }}</td>
								<td>{{ $row->otros }}</td>
																
								@can('editar-detalles-epp','borrar-detalles-epp')
								<td width="90">
								<div class="btn-group">
									@can('editar-detalles-epp')
									<a data-toggle="modal" data-target="#updateModal" class="btn btn-sm btn-primary rounded-xl" wire:click="edit({{$row->id}})">Editar </a>
									@endcan
									@can('borrar-detalles-epp')							 
									<a class="btn btn-sm btn-danger rounded-xl" onclick="confirm('Confirma borrar Detalles Epp : {{$row->name}}? \nDetalles Epps borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"> Borrar </a> 
									@endcan  
								</div>
								</td>
								@endcan
							@endforeach
						</tbody>
					</table>						
					{{ $detallesEpps->links() }}
					</div>
				</div>
                <div wire:loading wire:target="store,update,create,edit,destroy">
                    <x-loading-indicator />
                </div>	
			</div>
		</div>
	</div>
</div>
