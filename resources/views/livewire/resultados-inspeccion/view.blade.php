@section('title', __('Resultados Inspeccions'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
            <div class="rounded-2xl card">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4 class="h5">Lista Resultados Inspeccion </h4>
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
						@can('crear-resultados-inspeccion')
						<div class="btn btn-sm btn-default rounded-xl" data-toggle="modal" data-target="#createDataModal">
						<i class="fa fa-plus"></i>  Nuevo
						</div>
						@endcan
					</div>
				</div>
				
				<div class="card-body">
						@can('crear-resultados-inspeccion')
						@include('livewire.resultados-inspeccion.create')
						@endcan						
						@can('editar-resultados-inspeccion')
						@include('livewire.resultados-inspeccion.update')
						@endcan
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm">
						<thead class="thead">
							<tr> 
								<th>#</th> 
								<th>Inspeccion Id</th>
								<th>Descripcion</th>
								<th>Nivel Riesgo</th>
								<th>Registro Fotografico</th>
								<th>Accion A Tomar</th>
								<th>Responsable Id</th>
								<th>Estado</th>
								<th>Fecha Ejecucion</th>
																
								@can('editar-resultados-inspeccion','borrar-resultados-inspeccion')
								<th>ACCIONES</th>								
								@endcan
							</tr>
						</thead>
						<tbody>
							@foreach($resultadosInspeccions as $row)
							<tr>
								<td>{{ $loop->iteration }}</td> 
								<td>{{ $row->inspeccion_id }}</td>
								<td>{{ $row->descripcion }}</td>
								<td>{{ $row->nivel_riesgo }}</td>
								<td>{{ $row->registro_fotografico }}</td>
								<td>{{ $row->accion_a_tomar }}</td>
								<td>{{ $row->responsable_id }}</td>
								<td>{{ $row->estado }}</td>
								<td>{{ $row->fecha_ejecucion }}</td>
																
								@can('editar-resultados-inspeccion','borrar-resultados-inspeccion')
								<td width="90">
								<div class="btn-group">
									@can('editar-resultados-inspeccion')
									<a data-toggle="modal" data-target="#updateModal" class="btn btn-sm btn-primary rounded-xl" wire:click="edit({{$row->id}})">Editar </a>
									@endcan
									@can('borrar-resultados-inspeccion')							 
									<a class="btn btn-sm btn-danger rounded-xl" onclick="confirm('Confirma borrar Resultados Inspeccion : {{$row->name}}? \nResultados Inspeccions borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"> Borrar </a> 
									@endcan  
								</div>
								</td>
								@endcan
							@endforeach
						</tbody>
					</table>						
					{{ $resultadosInspeccions->links() }}
					</div>
				</div>
                <div wire:loading wire:target="store,update,create,edit,destroy">
                    <x-loading-indicator />
                </div>	
			</div>
		</div>
	</div>
</div>
