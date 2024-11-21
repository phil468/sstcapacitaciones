@section('title', __('Alertas Levantamientos'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
            <div class="rounded-2xl card">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4 class="h5">Lista Alertas Levantamiento </h4>
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
						@can('crear-alertas-levantamiento')
						<div class="btn btn-sm btn-default rounded-xl" data-toggle="modal" data-target="#createDataModal">
						<i class="fa fa-plus"></i>  Nuevo
						</div>
						@endcan
					</div>
				</div>
				
				<div class="card-body">
						@can('crear-alertas-levantamiento')
						@include('livewire.alertas-levantamiento.create')
						@endcan						
						@can('editar-alertas-levantamiento')
						@include('livewire.alertas-levantamiento.update')
						@endcan
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm">
						<thead class="thead">
							<tr> 
								<th>#</th> 
								<th>Resultado Inspeccion Id</th>
								<th>Registro Fotografico</th>
								<th>Levantado</th>
																
								@can('editar-alertas-levantamiento','borrar-alertas-levantamiento')
								<th>ACCIONES</th>								
								@endcan
							</tr>
						</thead>
						<tbody>
							@foreach($alertasLevantamientos as $row)
							<tr>
								<td>{{ $loop->iteration }}</td> 
								<td>{{ $row->resultado_inspeccion_id }}</td>
								<td>{{ $row->registro_fotografico }}</td>
								<td>{{ $row->levantado }}</td>
																
								@can('editar-alertas-levantamiento','borrar-alertas-levantamiento')
								<td width="90">
								<div class="btn-group">
									@can('editar-alertas-levantamiento')
									<a data-toggle="modal" data-target="#updateModal" class="btn btn-sm btn-primary rounded-xl" wire:click="edit({{$row->id}})">Editar </a>
									@endcan
									@can('borrar-alertas-levantamiento')							 
									<a class="btn btn-sm btn-danger rounded-xl" onclick="confirm('Confirma borrar Alertas Levantamiento : {{$row->name}}? \nAlertas Levantamientos borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"> Borrar </a> 
									@endcan  
								</div>
								</td>
								@endcan
							@endforeach
						</tbody>
					</table>						
					{{ $alertasLevantamientos->links() }}
					</div>
				</div>
                <div wire:loading wire:target="store,update,create,edit,destroy">
                    <x-loading-indicator />
                </div>	
			</div>
		</div>
	</div>
</div>
