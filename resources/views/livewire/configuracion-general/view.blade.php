@section('title', __('Configuracion Generals'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
            <div class="rounded-2xl card">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4 class="h5">Lista Configuracion General </h4>
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
						@can('crear-configuracion-general')
						<div class="btn btn-sm btn-default rounded-xl" data-toggle="modal" data-target="#createDataModal">
						<i class="fa fa-plus"></i>  Nuevo
						</div>
						@endcan
					</div>
				</div>
				
				<div class="card-body">
						@can('crear-configuracion-general')
						@include('livewire.configuracion-general.create')
						@endcan						
						@can('editar-configuracion-general')
						@include('livewire.configuracion-general.update')
						@endcan
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm">
						<thead class="thead">
							<tr> 
								<th>#</th> 
								<th>Name</th>
								<th>Valor</th>
								{{-- <th>Tipo De Dato Id</th>
								<th>Created By</th>
								<th>Updated By</th>
								<th>Deleted By</th> --}}
																
								@can('editar-configuracion-general','borrar-configuracion-general')
								<th>ACCIONES</th>								
								@endcan
							</tr>
						</thead>
						<tbody>
							@foreach($configuracionGenerals as $row)
							<tr>
								<td>{{ $loop->iteration }}</td> 
								<td>{{ $row->name }}</td>
								<td>{{ $row->valor }}</td>
								{{-- <td>{{ $row->tipo_de_dato_id }}</td>
								<td>{{ $row->created_by }}</td>
								<td>{{ $row->updated_by }}</td>
								<td>{{ $row->deleted_by }}</td> --}}
																
								@can('editar-configuracion-general','borrar-configuracion-general')
								<td width="90">
								<div class="btn-group">
									@can('editar-configuracion-general')
									<a data-toggle="modal" data-target="#updateModal" class="btn btn-sm btn-primary rounded-xl" wire:click="edit({{$row->id}})">Editar </a>
									@endcan
									@can('borrar-configuracion-general')							 
									<a class="btn btn-sm btn-danger rounded-xl" onclick="confirm('Confirma borrar Configuracion General : {{$row->name}}? \nConfiguracion Generals borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"> Borrar </a> 
									@endcan  
								</div>
								</td>
								@endcan
							@endforeach
						</tbody>
					</table>						
					{{ $configuracionGenerals->links() }}
					</div>
				</div>
                <div wire:loading wire:target="store,update,create,edit,destroy">
                    <x-loading-indicator />
                </div>	
			</div>
		</div>
	</div>
</div>
