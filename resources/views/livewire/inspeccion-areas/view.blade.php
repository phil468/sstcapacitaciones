@section('title', __('Inspeccion Areas'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4><i class="bi-house-fill text-info"></i>
							Inspeccion Area Listing </h4>
						</div>
						<div wire:poll.60s>
							<code><h5>{{ now()->format('H:i:s') }} UTC</h5></code>
						</div>
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
						<div>
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Search Inspeccion Areas">
						</div>
						<div class="btn btn-sm btn-info" data-toggle="modal" data-target="#createDataModal">
						<i class="bi-plus-lg"></i>  Add Inspeccion Areas
						</div>
					</div>
				</div>
				
				<div class="card-body">
						@include('livewire.inspeccionAreas.create')
						@include('livewire.inspeccionAreas.update')
				<div class="table-responsive">
					<table class="table table-bordered table-sm">
						<thead class="thead">
							<tr> 
								<td>#</td> 
								<th>Inspeccion Id</th>
								<th>Area Id</th>
								<td>ACTIONS</td>
							</tr>
						</thead>
						<tbody>
							@foreach($inspeccionAreas as $row)
							<tr>
								<td>{{ $loop->iteration }}</td> 
								<td>{{ $row->inspeccion_id }}</td>
								<td>{{ $row->area_id }}</td>
								<td width="90">
								<div class="btn-group">
									<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Actions
									</button>
									<div class="dropdown-menu dropdown-menu-right">
									<a data-toggle="modal" data-target="#updateModal" class="dropdown-item" wire:click="edit({{$row->id}})"><i class="bi-pencil-fill"></i> Edit </a>							 
									<a class="dropdown-item" onclick="confirm('Confirm Delete Inspeccion Area id {{$row->id}}? \nDeleted Inspeccion Areas cannot be recovered!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"><i class="bi-trash3-fill"></i> Delete </a>   
									</div>
								</div>
								</td>
							@endforeach
						</tbody>
					</table>						
					{{ $inspeccionAreas->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
