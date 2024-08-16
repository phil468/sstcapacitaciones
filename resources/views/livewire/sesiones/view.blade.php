@section('title', __('Sesiones'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h5 class="h5">Sesiones </h5>
						</div>
						{{--<div wire:poll.1s>
							<code><h5>{{ now()->format('H:i:s') }}</h5></code>
						</div>--}}
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
						{{-- <div>
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
						</div> --}}
						@can('crear-sesion')
						<div 
						class="btn btn-default rounded-xl" 
						wire:click="edit(0)" 
						data-toggle="modal" 
						data-target="#updateSesionModal" 
						title="Nueva Sesión">
						<i class="fa fa-plus"></i>
						</div>
						@endcan
					</div>
				</div>
				
				<div class="card-body">
						{{-- @can('crear-sesion')
							@include('livewire.sesiones.create')
						@endcan --}}
						@can('editar-sesion')
							@include('livewire.sesiones.update')
						@endcan
						@can('editar-sesion')
							@include('livewire.sesiones.viewVideo')
						@endcan
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm">
						<thead class="thead">
							<tr> 
								{{-- <th>#</th>  --}}
								<th>Capacitacion</th>
								<th>Número De Sesión</th>
								<th>Sesión</th>
								<th>Vídeo</th>
								{{-- <th>Fecha</th> --}}
								{{-- <th>Hora Inicio</th> --}}
								{{-- <th>Hora Fin</th> --}}
																
								@can('editar-sesion','borrar-sesion')
								<th>ACCIONES</th>								
								@endcan
							</tr>
						</thead>
						<tbody>
							@foreach($sesiones as $row)
							<tr>
								{{-- <td>{{ $loop->iteration }}</td>  --}}
								<td>{{ $row->capacitacion->tema->name }}</td>
								<td>{{ $row->numero_de_sesion }}</td>
								<td>{{ $row->name }}</td>

								<td>
									@if($row->video)
										<a wire:click='download({{$row->id}})' 
											{{-- href="{{ asset('storage/' . $row->video) }}" download  --}}
											class="btn btn-link">
											<i class="fa fa-download"></i> Descargar
										</a>
										<a class="btn btn-link" data-toggle="modal" data-target="#videoModal" wire:click="showVideo({{$row->id}})">
											<i class="fa fa-eye"></i> Ver
										</a>
									@endif
									
									{{-- <a href="{{ route('download', $row->video) }}" class="btn btn-link">
										<i class="fa fa-download"></i> Descargar										
									</a> --}}
									{{--ver--}}
								</td>
								

								{{-- <td>{{ $row->fecha }}</td> --}}
								{{-- <td>{{ $row->hora_inicio }}</td> --}}
								{{-- <td>{{ $row->hora_fin }}</td> --}}
																
								@can('editar-sesion','borrar-sesion')
									<td width="90">
										<div class="btn-group">
											@can('editar-sesion')
											<a data-toggle="modal" data-target="#updateSesionModal" class="btn btn-outline-vanguard rounded-xl" wire:click="edit({{$row->id}})">
												<i class="fa fa-edit"></i>
											</a>
											@endcan
											@can('borrar-sesion')							 
											<a class="btn btn-outline-danger rounded-xl" onclick="confirm('Confirma borrar Sesione : {{$row->name}}? \nSesiones borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"> 
												<i class="fa fa-trash"></i>
											</a> 
											@endcan  
										</div>
									</td>
								@endcan
							@endforeach
						</tbody>
					</table>						
					{{ $sesiones->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
