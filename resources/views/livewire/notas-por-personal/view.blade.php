@section('title', __('Avance / Notas por Personal'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h5 class="h5">Avance / Notas por Personal</h5>
						</div>
					</div>
				</div>
				
				<div class="card-body">
					@livewire('notas-por-personal-table')
				</div>
			</div>

		</div>
	</div>
	
	@push('js')

	@endpush
</div>
