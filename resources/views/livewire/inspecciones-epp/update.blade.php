<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                <h5 class="h5 modal-title" id="updateModalLabel">Actualizar Inspecciones Epp</h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
					<input type="hidden" wire:model="selected_id">
            <div class="form-group">
                <label for="numero_inspeccion">Numero Inspeccion</label>
                <input wire:model.defer="numero_inspeccion" type="text" class="form-control" id="numero_inspeccion" placeholder="Numero Inspeccion">@error('numero_inspeccion') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="inspector">Inspector</label>
                <input wire:model.defer="inspector" type="text" class="form-control" id="inspector" placeholder="Inspector">@error('inspector') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="firma_inspector">Firma Inspector</label>
                <input wire:model.defer="firma_inspector" type="text" class="form-control" id="firma_inspector" placeholder="Firma Inspector">@error('firma_inspector') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="turno">Turno</label>
                <input wire:model.defer="turno" type="text" class="form-control" id="turno" placeholder="Turno">@error('turno') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="condicion">Condicion</label>
                <input wire:model.defer="condicion" type="text" class="form-control" id="condicion" placeholder="Condicion">@error('condicion') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="riesgo">Riesgo</label>
                <input wire:model.defer="riesgo" type="text" class="form-control" id="riesgo" placeholder="Riesgo">@error('riesgo') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="actividad">Actividad</label>
                <input wire:model.defer="actividad" type="text" class="form-control" id="actividad" placeholder="Actividad">@error('actividad') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="fecha">Fecha</label>
                <input wire:model.defer="fecha" type="text" class="form-control" id="fecha" placeholder="Fecha">@error('fecha') <span class="error text-danger">{{ $message }}</span> @enderror
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
