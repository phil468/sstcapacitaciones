<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                <h5 class="h5 modal-title" id="updateModalLabel">Actualizar Alertas Levantamiento</h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
					<input type="hidden" wire:model="selected_id">
            <div class="form-group">
                <label for="resultado_inspeccion_id">Resultado Inspeccion Id</label>
                <input wire:model.defer="resultado_inspeccion_id" type="text" class="form-control" id="resultado_inspeccion_id" placeholder="Resultado Inspeccion Id">@error('resultado_inspeccion_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="registro_fotografico">Registro Fotografico</label>
                <input wire:model.defer="registro_fotografico" type="text" class="form-control" id="registro_fotografico" placeholder="Registro Fotografico">@error('registro_fotografico') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="levantado">Levantado</label>
                <input wire:model.defer="levantado" type="text" class="form-control" id="levantado" placeholder="Levantado">@error('levantado') <span class="error text-danger">{{ $message }}</span> @enderror
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
