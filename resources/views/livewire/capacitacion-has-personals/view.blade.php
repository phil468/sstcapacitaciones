@section('title', __('Capacitacion Has Personals'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							@if (!$es_aula_virtual)
								<h5 class="h5">Personal Inscrito</h5>
							@else
								<h5 class="h5">Asignaciones</h5>
							@endif
						</div>
						{{-- @if (session()->has('message'))
							<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif --}}
						
						@if (!$es_aula_virtual)
							<a class="btn btn-default rounded-xl" title="Volver" href={{route('capacitaciones')}}>
								<i class="fa fa-arrow-left"></i> 
							</a>
						@endif
						
						{{-- <div>
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Buscar">
						</div> --}}
						@can('crear-capacitacionHasPersonal')
						<div class="btn btn-sm btn-default" data-toggle="modal" data-target="#createDataModal">
						<i class="fa fa-plus"></i>  Nuevo
						</div>
						@endcan
					</div>
				</div>
				
				<div class="card-body">
						{{-- @can('crear-capacitacionHasPersonal')
						@include('livewire.capacitacionHasPersonals.create')
						@endcan --}}

												
					@can('editar-capacitacion')
						@include('livewire.capacitacion-has-personals.update-registro')
					@endcan

					@if (!$es_aula_virtual)
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
					@endif
										
					@if (!$es_aula_virtual)
						<h5 class="h5">Personal Inscrito</h5>
					@else

					@endif
										
					<div>
						@livewire('registros-table', ['exportable' => false,'capacitacion_id' => $capacitacion_id])
					</div>
						
				{{-- @livewire('registros-table', ['exportable' => false,'listaParaAgregar' => true]) --}}
						{{-- {{dd(count($capacitacionHasPersonals))}} --}}
						{{-- @if (isset($capacitacionHasPersonals) && count($capacitacionHasPersonals))
						<div class="table-responsive">
							<table class="table table-striped table-hover table-sm">
								<thead class="thead">
									<tr> 
										<th>#</th> 
										<th>Personal</th>
										@can('editar-capacitacion')
											<th>Botón Quitar</th>
										@endauth
									</tr>
								</thead>
								<tbody>
									
									@foreach($capacitacionHasPersonals as $row)
									<tr>
										<td>{{ $loop->iteration }}</td> 
										<td>{{ $row->personal->name}}</td>
										@can('editar-capacitacion')

										<td>
										<a class="btn btn-sm btn-danger" 
											onclick="confirm('Confirma quitar Personal : {{$row->personal->name}}? \nPersonal borrados no pueden ser recuperados!')||event.stopImmediatePropagation()" wire:click="quitarAsistente({{$row->id}})"> 
											Quitar 
										</a>
										</td>
										@endcan
									@endforeach
								</tbody>
							</table>						
						</div>
						@endif --}}

					@can('editar-capacitacion')
						<br>
						<div class="h5">Seleccione personas para agregar a la capacitación</div>
						<button title="Agregar Seleccionados" 
							class="ml-4 btn btn-outline-vanguard"
							@if (count($selectedFromPersonalTable) == 0)
							disabled
							@endif
							wire:click="agregarSeleccionados()">
							
							<i class="fa fa-plus"></i> Agregar Seleccionados 
							
							@if (count($selectedFromPersonalTable) != 0)
								<span class="badge badge-dark">{{count($selectedFromPersonalTable)}}</span>
							@endif
						</button>
						<br>
						<br>
						
						<div class="ml-4">
							@livewire('personal-table', ['exportable' => false,'listaParaAgregar' => true])
						</div>
					@endcan
				</div>				
			</div>
		</div>
	</div>
	@push('js')
	<script>
		document.addEventListener('livewire:load', function () {
			console.log('Inicializar Choice.js');
			
			const placeholder = [ { value: '', label: ' - Seleccione - '} ];
					
			const opciones = {
				removeItemButton: true,
				itemSelectText: 'Seleccione',
				noChoicesText: 'No hay opciones para elegir',
				
				searchPlaceholderValue: 'Buscar',
				placeholderValue: 'Selecciona una opción',
				noResultsText: 'Resultados no encontrados',
				
				placeholder: true, // Activa el placeholder		
    			placeholderValue: null,	
				allowHTML: false,
				shouldSort: true,
    			searchResultLimit: 15,
    			searchFields: ['label'],

				searchFloor: 1,
				renderChoiceLimit: 15
			}
			
        // Verificar que los elementos existan en el DOM
        const gerenciaElement = document.querySelector('#gerencia_id');
        const sedeElement = document.querySelector('#sede_id');
        const areaElement = document.querySelector('#area_id');

        if (gerenciaElement && sedeElement && areaElement) {
            const gerencia_id_select = new Choices(gerenciaElement, opciones);
            const sede_id_select = new Choices(sedeElement, opciones);
            const area_id_select = new Choices(areaElement, opciones);
                    
            gerencia_id_select.passedElement.element.addEventListener('change', function (event) {
                @this.set('gerencia_id', gerencia_id_select.getValue(true));
            });
            
            sede_id_select.passedElement.element.addEventListener('change', function (event) {
                @this.set('sede_id', sede_id_select.getValue(true));
            });
            
            area_id_select.passedElement.element.addEventListener('change', function (event) {
                @this.set('area_id', area_id_select.getValue(true));
            });
    
            Livewire.on('listar_selects_personal', function (gerencias, sedes, areas, gerencia_id, sede_id, area_id) {
                gerencia_id_select.hideDropdown();
                sede_id_select.hideDropdown();
                area_id_select.hideDropdown();
                
                gerencia_id_select.setChoices(gerencias, 'value', 'label', true);
                sede_id_select.setChoices(sedes, 'value', 'label', true);
                area_id_select.setChoices(areas, 'value', 'label', true);
                
                gerencia_id_select.setChoiceByValue(gerencia_id ?? null);
                sede_id_select.setChoiceByValue(sede_id ?? null);
                area_id_select.setChoiceByValue(area_id ?? null);
            });

            Livewire.on('limpiarDatosP', function () {
                gerencia_id_select.removeActiveItems();
                sede_id_select.removeActiveItems();
                area_id_select.removeActiveItems();
            });
        } else {
            console.error('Uno o más elementos select no se encontraron en el DOM.');
        }
		});
	</script>
	@endpush
</div>
