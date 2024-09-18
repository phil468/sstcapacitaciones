<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                <h5 class="h5 modal-title" id="updateModalLabel">Actualizar Notificaciones Enviada</h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
					<input type="hidden" wire:model="selected_id">
            <div class="form-group">
                <label for="capacitacion_id">Capacitacion Id</label>
                <input wire:model.defer="capacitacion_id" type="text" class="form-control" id="capacitacion_id" placeholder="Capacitacion Id">@error('capacitacion_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="personal_id">Personal Id</label>
                <input wire:model.defer="personal_id" type="text" class="form-control" id="personal_id" placeholder="Personal Id">@error('personal_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary rounded-xl" data-dismiss="modal">Cerrar</button>
                <button type="button" wire:click.prevent="update()" class="btn btn-vanguard rounded-xl">Guardar</button>
            </div>
       </div>
    </div>
</div>
