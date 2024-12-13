@section('title', __('Asistenciums'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h5 class="h5">
								Asistencia
							</h4>
						</div>
						{{--<divwire:poll.1s>
							<code><h5>{{ now()->format('H:i:s') }}</h5></code>
						</divwire:poll.1s>--}}
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
						
						<a class="btn btn-sm btn-default" href={{route('capacitaciones')}}>
							<i class="fa fa-arrow-left"></i>
							Volver a la lista
						</a>
						
						{{-- <div>
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
						</div> --}}
						{{-- @can('crear-asistencia')
						<div class="btn btn-sm btn-default" data-toggle="modal" data-target="#createDataModal">
						<i class="fa fa-plus"></i>  Nuevo
						</div>
						@endcan --}}
					</div>
				</div>
				
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover table-sm">
							<thead class="thead">
								<tr> 
									{{-- <th>ID</th>  --}}
									<th>TIPO</th>
									<th>FECHA</th>
									<th>EMPRESA</th>
									<th>MODALIDAD</th>
									<th>EXPOSITOR</th>
									<th>SEDE</th>
									{{-- <th>REGISTRADOR</th> --}}
									{{-- <th>SESIONES</th> --}}
								</tr>
							</thead>
							<tbody>
								@foreach($capacitacion as $row)
								<tr>
									{{-- {{dd($capacitacion)}} --}}
									{{-- <td>{{ $row['id'] }}</td> --}}
									<td>{{ $row['tipo_capacitacion']['name'] }}</td>
									<td>{{ $row['fecha_capacitacion'] }}</td>
									<td>{{ $row['empresa']['name'] }}</td>
									<td>{{ $row['modalidad']['name'] }}</td>
									<td>
										@if ($row['expositor_externo'])
											{{$row['nombre_expositor_externo']}}
										@else
											{{ $row['expositor']['name'] ??'' }}
										@endif											
									 </td>
									<td>{{ $row['sede']['name'] }}</td>
									{{-- <td>{{ $row['registrador']['name'] }}</td> --}}
									{{-- <td>{{ $row['cantidad_de_sesiones'] }}</td> --}}
								</tr>
								@endforeach
							</tbody>
						</table>						
					</div>

					<div class="mx-0 row">
						<div class="form-group col-sm-8 col-md-6 col-lg-6 col-xl-4">
							<label class="" for="numero_de_sesion">Sesión</label>
							<div class="input-group">
								<select class="form-control" name="numero_sesion_id" id="numero_sesion_id" wire:model="numero_sesion_id">
									<option value="0">Seleccione</option>
									@for ($i = 1; $i <= $capacitacion[0]['cantidad_de_sesiones']; $i++)
										<option value="{{$i}}">{{$i}}</option>
									@endfor
								</select>								
								<a wire:click="agregarSesion()" type="button" class="btn btn-vanguard"
								onclick="confirm('Confirma agregar Sesion? \nSesiones agregadas no pueden ser eliminadas!')||event.stopImmediatePropagation()"
								>
									<i class="fa fa-plus"></i>Agregar Sesion
								</a>
							</div>
						</div>
						@if ($numero_sesion_id>0)
							<div class="form-group col-sm-4 col-md-3 col-lg-3 col-xl-2">
								<label for="fecha">Fecha</label>
								<input wire:model="fecha" type="date" class="form-control" id="fecha" placeholder="Fecha">@error('fecha') <span class="error text-danger">{{ $message }}</span> @enderror
							</div>
							<div class="form-group col-sm-4 col-md-3 col-lg-3 col-xl-2">
								<label for="hora_inicio">Hora Inicio</label>
								<input wire:model.defer="hora_inicio" type="time" class="form-control" id="hora_inicio" placeholder="Hora Inicio">@error('hora_inicio') <span class="error text-danger">{{ $message }}</span> @enderror
							</div>
							<div class="form-group col-sm-4 col-md-3 col-lg-3 col-xl-2">
								<label for="hora_fin">Hora Fin</label>
								<input wire:model.defer="hora_fin" type="time" class="form-control" id="hora_fin" placeholder="Hora Fin">@error('hora_fin') <span class="error text-danger">{{ $message }}</span> @enderror
							</div>
                            <div class="form-group col-sm-8 col-md-6 col-lg-6 col-xl-4">
                                <label for="photo">Foto (Asistencia)</label>
								<div class="custom-file">
									<input wire:model="photo" type="file" class="custom-file-input" id="photo" placeholder="Foto" style="display: none;">
									<label class="custom-upload-button btn btn-outline-vanguard" for="photo">Seleccionar archivo</label>
								</div>

								@error('photo') <span class="error text-danger">{{ $message }}</span> @enderror
								
								<!-- Botón para tomar foto -->
								<button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#cameraModal">
									Tomar Foto
								</button>

								<div wire:loading wire:target="photo">
									<div class="progress" style="height: 25px;">
										<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">Cargando foto...</div>
									</div>
								</div>

								<!-- Vista previa de la imagen -->
    							<div wire:loading.remove wire:target="photo" class="mt-2">
									@if ($photo)
										<div style="position: relative; display: inline-block;">
											<img src="{{ $photo->temporaryUrl() }}" alt="Foto de la sesión" width="200" style="cursor: pointer;">
											<a onclick="openModal('{{ $photo->temporaryUrl() }}')" data-toggle="modal" data-target="#photoModal" style="position: absolute; top: 0; left: 0; width: 100%; height: 75%; background: rgba(0, 0, 0, 0.5); color: white; display: flex; justify-content: center; align-items: center; opacity: 0; transition: opacity 0.3s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0">
												<i class="fa fa-eye"></i>
											</a>
											<a href="{{ $photo->temporaryUrl() }}" download style="position: absolute; top: 75%; left: 0; width: 100%; height: 25%; background: rgba(0, 0, 0, 0.5); color: white; display: flex; justify-content: center; align-items: center; opacity: 0; transition: opacity 0.3s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0">
												<i class="fa fa-download"></i>
											</a>
										</div>
									@elseif ($sesion->photo)
										<div style="position: relative; display: inline-block;">
											<img src="{{ asset('' . $sesion->photo) }}" alt="Foto de la sesión" width="200" style="cursor: pointer;">
											<a onclick="openModal('{{ asset('' . $sesion->photo) }}')" data-toggle="modal" data-target="#photoModal" style="position: absolute; top: 0; left: 0; width: 100%; height: 75%; background: rgba(0, 0, 0, 0.5); color: white; display: flex; justify-content: center; align-items: center; opacity: 0; transition: opacity 0.3s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0">
												<i class="fa fa-eye"></i>
											</a>
											<a href="{{ asset('' . $sesion->photo) }}" download style="position: absolute; top: 75%; left: 0; width: 100%; height: 25%; background: rgba(0, 0, 0, 0.5); color: white; display: flex; justify-content: center; align-items: center; opacity: 0; transition: opacity 0.3s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0">
												<i class="fa fa-download"></i>
											</a>
										</div>
									@endif
								</div>

                            </div>
						@endif
					</div>
					<div class="mx-0 row">
					@if ($numero_sesion_id>0)
						<div class="form-group col-xs-12">
								<label for="dni_search">Buscar por DNI</label>
								<div>
									<div class="input-group">
									<input type="text"
									name="dni_search" 
									wire:model.defer="dni_search"
									inputmode="numeric" 
									wire:keydown.enter='buscar_dni' 
									wire:keydown.tab="buscar_dni"
									wire:keydown.arrow-right="buscar_dni"
									id="dni_search" class="form-control"
									placeholder="DNI" 
									autofocus>
									<a wire:click="buscar_dni()" type="button" class="btn btn-vanguard"><i class="fas fa-search"></i></a>
									</div>								
								</div>
								@error('dni_search') <span class="error text-danger">{{ $message }}</span> @enderror
						</div>

						<div class="form-group col-sm-4 col-md-3 col-lg-3 col-xl-2">
								<label for="">Filtro Asistencia</label>
								<div class="form-row">
									<a type="" class="btn btn-md {{$filtro_asistencia ? 'btn-success text-white' : 'btn-default text-success' }}" wire:click="filtro_asistencia()">
										<i class="fas fa-check-circle"></i>
									</a>
									<a type="" class="btn btn-md {{$filtro_no_asistencia ? 'btn-success' : 'btn-default text-success' }}" wire:click="filtro_no_asistencia()">
										<i class="far fa-circle"></i>
									</a>
								</div>
						</div>
						
						<div class="table-responsive">
								<table class="table table-striped table-hover table-sm">
									<thead class="thead">
										<tr>
											{{-- <th>#</th>  --}}
											{{--<th>Sesion</th>--}}
											<th class="border-0"></th>
											<th>DNI</th>
											<th>Personal</th>
											<th>Empresa</th>
											<th>Sede</th>
											<th>Gerencia</th>
											<th>Area</th>
											<th>Cargo</th>
											<th>Planilla</th>
											<th>Tipo De Trabajador</th>
											<th>Tipo De Personal</th>
											{{-- <th>Capacitacion</th> --}}
											{{-- <th>Observaciones</th> --}}
																			
											{{-- @can('editar-asistencia','borrar-asistencia')
											<th>ACCIONES</th>								
											@endcan --}}
										</tr>
									</thead>
									<tbody>
										@foreach($asistencia as $row)
										{{-- {{dd($row->capacitacion_has_personal->personal->dni)}} --}}
										<tr
										@if (($filtro_asistencia == $filtro_no_asistencia))
										@else
											@if ( 	(!($row->active) && $filtro_asistencia) 
													|| 
													(($row->active) && $filtro_no_asistencia)
												)
												style="display: none;"
											@endif
											
										@endif
										
										> 
											{{-- <td>{{ $loop->iteration }}</td>  --}}
											{{-- <td>{{ $row->sesion->numero_de_sesion }}</td>  --}}
											
											
											<td class="bg-white border-0">
												@if ($row->active)
												<span class="text-success">										
													<i class="fas fa-check-circle"></i> 
												</span>
												@else
												<span class="text-success">
													<i class="far fa-circle"></i>  
												</span>
												@endif
											</td>
											<td>{{ $row->capacitacion_has_personal->personal->dni??'' }}</td>
											<td>{{ $row->capacitacion_has_personal->personal->name??'' }}</td>
											<td>{{ $row->capacitacion_has_personal->empresa->name??'' }}</td>
											<td>{{ $row->capacitacion_has_personal->sede->name??'' }}</td>
											<td>{{ $row->capacitacion_has_personal->gerencia->name??'' }}</td>
											<td>{{ $row->capacitacion_has_personal->area->name??'' }}</td>
											<td>{{ $row->capacitacion_has_personal->cargo->name??'' }}</td>
											<td>{{ $row->capacitacion_has_personal->planilla->name??'' }}</td>
											<td>{{ $row->capacitacion_has_personal->tipo_de_trabajador->name??'' }}</td>
											<td>{{ $row->capacitacion_has_personal->tipo_de_personal->name??'' }}</td>
											{{-- <td>{{ $row->capacitacion->name??'' }}</td> --}}
											{{-- <td>{{ $row->observaciones }}</td> --}}
																			
											{{-- @can('editar-asistencia','borrar-asistencia')
											<td width="90">
											<div class="btn-group">
												@can('editar-asistencia')
												<a data-toggle="modal" data-target="#updateModal" class="btn btn-sm btn-vanguard rounded-xl" wire:click="edit({{$row->id}})">Editar </a>
												@endcan
												@can('borrar-asistencia')							 
												<a class="btn btn-sm btn-danger rounded-xl" onclick="confirm('Confirma borrar Asistencium : {{$row->name}}? \nAsistenciums borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"> Borrar </a> 
												@endcan  
											</div>
											</td>
											@endcan --}}
											
										@endforeach
									</tbody>
								</table>
						</div>
					@endif
					</div>
					
						{{-- @can('crear-asistencia')
						@include('livewire.asistencia.create')
						@endcan						
						@can('editar-asistencia')
						@include('livewire.asistencia.update')
						@endcan --}}					
						{{-- @can('editar-asistencia') --}}
					@include('livewire.asistenciums.confirmarIngresoDNI')
						{{-- @endcan --}}
				</div>
			
				<div wire:loading wire:target="importar,exportar,create,edit,destroy,generarPdf,store,update,buscar_dni,numero_sesion_id,agregarSesion">
					<x-loading-indicator />
				</div>
			</div>
		</div>
	</div>

	<!-- Modal para tomar foto -->
<div id="cameraModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="cameraModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cameraModalLabel">Tomar Foto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <video id="video" width="100%" autoplay></video>
                <button id="snap" class="btn btn-primary mt-2">Capturar</button>
                <canvas id="canvas" style="display: none;"></canvas>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="savePhoto()">Guardar Foto</button>
            </div>
        </div>
    </div>
</div>
	

	<!-- Modal -->
	<div class="modal fade" id="photoModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="videoModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl" role="document">
		<div class="rounded-2xl modal-content">
			<div class="text-white modal-header bg-vanguard rounded-t-2xl">                
			<h5 class="modal-title" id="videoModalLabel">Ver Foto</h5>
				<button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body container">  
				<img id="modalImage" class="mx-auto" src="" style="max-width: 90%; max-height: 90%;">
			</div>
		</div>
		</div>
	</div>

	@push('js')

	<script>

    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = document.getElementById("photo").files[0].name;
        var nextSibling = e.target.nextElementSibling
        nextSibling.innerText = fileName
    });

		function openModal(url) {
			document.getElementById('modalImage').src = url;
			document.getElementById('photoModal').style.display = 'flex';
		}
		
		// function closeModal() {
		// 	document.getElementById('photoModal').style.display = 'none';
		// }

		// Configurar la cámara
		
		// Configurar la cámara cuando se abre el modal
		$('#cameraModal').on('shown.bs.modal', function () {
			const video = document.getElementById('video');
			const canvas = document.getElementById('canvas');
			const snap = document.getElementById('snap');

			navigator.mediaDevices.getUserMedia({ video: true })
				.then(stream => {
					video.srcObject = stream;
				})
				.catch(err => {
					console.error("Error accessing the camera: " + err);
				});

			snap.addEventListener('click', () => {
				const context = canvas.getContext('2d');
				canvas.width = video.videoWidth;
				canvas.height = video.videoHeight;
				context.drawImage(video, 0, 0, canvas.width, canvas.height);
				canvas.style.display = 'block';
			});
		});

		function savePhoto() {
			const dataUrl = canvas.toDataURL('image/png');
			const blob = dataURLtoBlob(dataUrl);
			const file = new File([blob], 'photo.png', { type: 'image/png' });

			@this.upload('photo', file, (uploadedFilename) => {
				console.log('File uploaded successfully: ' + uploadedFilename);
			}, () => {
				console.error('File upload failed.');
			});
		}

		function dataURLtoBlob(dataUrl) {
			const arr = dataUrl.split(','), mime = arr[0].match(/:(.*?);/)[1];
			const bstr = atob(arr[1]);
			let n = bstr.length;
			const u8arr = new Uint8Array(n);
			while (n--) {
				u8arr[n] = bstr.charCodeAt(n);
			}
			return new Blob([u8arr], { type: mime });
		}
	</script>
	
	@endpush


</div>
