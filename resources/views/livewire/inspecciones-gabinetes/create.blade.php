<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                <h5 class="modal-title h5" id="createDataModalLabel">Nuevo Inspecciones Gabinete</h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
				<form>
            <div class="form-group">
                <label for="fecha_inspeccion">Fecha Inspeccion</label>
                <input wire:model.defer="fecha_inspeccion" type="text" class="form-control" id="fecha_inspeccion" placeholder="Fecha Inspeccion">@error('fecha_inspeccion') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="hora_inspeccion">Hora Inspeccion</label>
                <input wire:model.defer="hora_inspeccion" type="text" class="form-control" id="hora_inspeccion" placeholder="Hora Inspeccion">@error('hora_inspeccion') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="inspector">Inspector</label>
                <input wire:model.defer="inspector" type="text" class="form-control" id="inspector" placeholder="Inspector">@error('inspector') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="lugar">Lugar</label>
                <input wire:model.defer="lugar" type="text" class="form-control" id="lugar" placeholder="Lugar">@error('lugar') <span class="error text-danger">{{ $message }}</span> @enderror
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
