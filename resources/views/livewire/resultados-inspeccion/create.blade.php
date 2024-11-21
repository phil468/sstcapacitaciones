<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                <h5 class="modal-title h5" id="createDataModalLabel">Nuevo Resultados Inspeccion</h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
				<form>
            <div class="form-group">
                <label for="inspeccion_id">Inspeccion Id</label>
                <input wire:model.defer="inspeccion_id" type="text" class="form-control" id="inspeccion_id" placeholder="Inspeccion Id">@error('inspeccion_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="descripcion">Descripcion</label>
                <input wire:model.defer="descripcion" type="text" class="form-control" id="descripcion" placeholder="Descripcion">@error('descripcion') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="nivel_riesgo">Nivel Riesgo</label>
                <input wire:model.defer="nivel_riesgo" type="text" class="form-control" id="nivel_riesgo" placeholder="Nivel Riesgo">@error('nivel_riesgo') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="registro_fotografico">Registro Fotografico</label>
                <input wire:model.defer="registro_fotografico" type="text" class="form-control" id="registro_fotografico" placeholder="Registro Fotografico">@error('registro_fotografico') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="accion_a_tomar">Accion A Tomar</label>
                <input wire:model.defer="accion_a_tomar" type="text" class="form-control" id="accion_a_tomar" placeholder="Accion A Tomar">@error('accion_a_tomar') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="responsable_id">Responsable Id</label>
                <input wire:model.defer="responsable_id" type="text" class="form-control" id="responsable_id" placeholder="Responsable Id">@error('responsable_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="estado">Estado</label>
                <input wire:model.defer="estado" type="text" class="form-control" id="estado" placeholder="Estado">@error('estado') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="fecha_ejecucion">Fecha Ejecucion</label>
                <input wire:model.defer="fecha_ejecucion" type="text" class="form-control" id="fecha_ejecucion" placeholder="Fecha Ejecucion">@error('fecha_ejecucion') <span class="error text-danger">{{ $message }}</span> @enderror
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
