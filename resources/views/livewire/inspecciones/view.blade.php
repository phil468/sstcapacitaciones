@section('title', __('Inspecciones'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
            <div class="rounded-2xl card">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4 class="h5">Lista Inspeccione </h4>
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
						@can('crear-inspecciones')
						<div class="btn btn-sm btn-default rounded-xl" data-toggle="modal" data-target="#createDataModal">
						<i class="fa fa-plus"></i>  Nuevo
						</div>
						@endcan
					</div>
				</div>
				
				<div class="card-body">
						@can('crear-inspecciones')
						@include('livewire.inspecciones.create')
						@endcan						
						@can('editar-inspecciones')
						@include('livewire.inspecciones.update')
						@endcan
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm">
						<thead class="thead">
							<tr> 
								<th>#</th> 
								<th>Empresa Id</th>
								<th>Area Id</th>
								<th>Tipo Inspeccion</th>
								<th>Vigencia Inicio</th>
								<th>Vigencia Fin</th>
								<th>Comentario</th>
																
								@can('editar-inspecciones','borrar-inspecciones')
								<th>ACCIONES</th>								
								@endcan
							</tr>
						</thead>
						<tbody>
							@foreach($inspecciones as $row)
							<tr>
								<td>{{ $loop->iteration }}</td> 
								<td>{{ $row->empresa_id }}</td>
								<td>{{ $row->area_id }}</td>
								<td>{{ $row->tipo_inspeccion }}</td>
								<td>{{ $row->vigencia_inicio }}</td>
								<td>{{ $row->vigencia_fin }}</td>
								<td>{{ $row->comentario }}</td>
																
								@can('editar-inspecciones','borrar-inspecciones')
								<td width="90">
								<div class="btn-group">
									@can('editar-inspecciones')
									<a data-toggle="modal" data-target="#updateModal" class="btn btn-sm btn-primary rounded-xl" wire:click="edit({{$row->id}})">Editar </a>
									@endcan
									@can('borrar-inspecciones')							 
									<a class="btn btn-sm btn-danger rounded-xl" onclick="confirm('Confirma borrar Inspeccione : {{$row->name}}? \nInspecciones borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"> Borrar </a> 
									@endcan  
								</div>
								</td>
								@endcan
							@endforeach
						</tbody>
					</table>						
					{{ $inspecciones->links() }}
					</div>
				</div>
                <div wire:loading wire:target="store,update,create,edit,destroy">
                    <x-loading-indicator />
                </div>	
			</div>
		</div>
	</div>
</div>
