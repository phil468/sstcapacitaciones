<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                <h5 class="modal-title h5" id="createDataModalLabel">Nuevo Asignacione</h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
				<form>
            <div class="form-group">
                <label for="personal_id">Personal Id</label>
                <input wire:model.defer="personal_id" type="text" class="form-control" id="personal_id" placeholder="Personal Id">@error('personal_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="capacitacion_id">Capacitacion Id</label>
                <input wire:model.defer="capacitacion_id" type="text" class="form-control" id="capacitacion_id" placeholder="Capacitacion Id">@error('capacitacion_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="fecha_inicio">Fecha Inicio</label>
                <input wire:model.defer="fecha_inicio" type="text" class="form-control" id="fecha_inicio" placeholder="Fecha Inicio">@error('fecha_inicio') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="fecha_fin">Fecha Fin</label>
                <input wire:model.defer="fecha_fin" type="text" class="form-control" id="fecha_fin" placeholder="Fecha Fin">@error('fecha_fin') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="intentos_de_evaluacion">Intentos De Evaluacion</label>
                <input wire:model.defer="intentos_de_evaluacion" type="text" class="form-control" id="intentos_de_evaluacion" placeholder="Intentos De Evaluacion">@error('intentos_de_evaluacion') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="realizado">Realizado</label>
                <input wire:model.defer="realizado" type="text" class="form-control" id="realizado" placeholder="Realizado">@error('realizado') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="finalizado">Finalizado</label>
                <input wire:model.defer="finalizado" type="text" class="form-control" id="finalizado" placeholder="Finalizado">@error('finalizado') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="created_by">Created By</label>
                <input wire:model.defer="created_by" type="text" class="form-control" id="created_by" placeholder="Created By">@error('created_by') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="updated_by">Updated By</label>
                <input wire:model.defer="updated_by" type="text" class="form-control" id="updated_by" placeholder="Updated By">@error('updated_by') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="deleted_by">Deleted By</label>
                <input wire:model.defer="deleted_by" type="text" class="form-control" id="deleted_by" placeholder="Deleted By">@error('deleted_by') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn rounded-xl" data-dismiss="modal">Cerrar</button>
                <button type="button" wire:click.prevent="store()" class="btn btn-vanguard close-modal rounded-xl">Guardar</button>
            </div>
        </div>
    </div>
</div>
