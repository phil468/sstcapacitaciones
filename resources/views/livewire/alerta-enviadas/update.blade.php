<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                <h5 class="h5 modal-title" id="updateModalLabel">Actualizar Alerta Enviada</h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
					<input type="hidden" wire:model="selected_id">
            <div class="form-group">
                <label for="capacitacion_has_personal_id">Capacitacion Has Personal Id</label>
                <input wire:model.defer="capacitacion_has_personal_id" type="text" class="form-control" id="capacitacion_has_personal_id" placeholder="Capacitacion Has Personal Id">@error('capacitacion_has_personal_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="fecha_envio">Fecha Envio</label>
                <input wire:model.defer="fecha_envio" type="text" class="form-control" id="fecha_envio" placeholder="Fecha Envio">@error('fecha_envio') <span class="error text-danger">{{ $message }}</span> @enderror
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
