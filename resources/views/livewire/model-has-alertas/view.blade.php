@section('title', __('Model Has Alertas'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
            <div class="rounded-2xl card">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4 class="h5">Lista Model Has Alerta </h4>
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
						@can('crear-model-has-alertas')
						<div class="btn btn-sm btn-default rounded-xl" data-toggle="modal" data-target="#createDataModal">
						<i class="fa fa-plus"></i>  Nuevo
						</div>
						@endcan
					</div>
				</div>
				
				<div class="card-body">
						@can('crear-model-has-alertas')
						@include('livewire.model-has-alertas.create')
						@endcan						
						@can('editar-model-has-alertas')
						@include('livewire.model-has-alertas.update')
						@endcan
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm">
						<thead class="thead">
							<tr> 
								<th>#</th> 
								<th>Model Type</th>
								<th>Model Id</th>
								<th>Value</th>
								<th>Alerta Id</th>
																
								@can('editar-model-has-alertas','borrar-model-has-alertas')
								<th>ACCIONES</th>								
								@endcan
							</tr>
						</thead>
						<tbody>
							@foreach($modelHasAlertas as $row)
							<tr>
								<td>{{ $loop->iteration }}</td> 
								<td>{{ $row->model_type }}</td>
								<td>{{ $row->model_id }}</td>
								<td>{{ $row->value }}</td>
								<td>{{ $row->alerta_id }}</td>
																
								@can('editar-model-has-alertas','borrar-model-has-alertas')
								<td width="90">
								<div class="btn-group">
									@can('editar-model-has-alertas')
									<a data-toggle="modal" data-target="#updateModal" class="btn btn-sm btn-primary rounded-xl" wire:click="edit({{$row->id}})">Editar </a>
									@endcan
									@can('borrar-model-has-alertas')							 
									<a class="btn btn-sm btn-danger rounded-xl" onclick="confirm('Confirma borrar Model Has Alerta : {{$row->name}}? \nModel Has Alertas borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"> Borrar </a> 
									@endcan  
								</div>
								</td>
								@endcan
							@endforeach
						</tbody>
					</table>						
					{{ $modelHasAlertas->links() }}
					</div>
				</div>
                <div wire:loading wire:target="store,update,create,edit,destroy">
                    <x-loading-indicator />
                </div>	
			</div>
		</div>
	</div>
</div>
