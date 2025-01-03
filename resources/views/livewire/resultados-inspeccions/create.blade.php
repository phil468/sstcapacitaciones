<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDataModalLabel">Create New Resultados Inspeccion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
				<form>
            <div class="form-group">
                <label for="uuid"></label>
                <input wire:model="uuid" type="text" class="form-control" id="uuid" placeholder="Uuid">@error('uuid') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="inspeccion_id"></label>
                <input wire:model="inspeccion_id" type="text" class="form-control" id="inspeccion_id" placeholder="Inspeccion Id">@error('inspeccion_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="descripcion"></label>
                <input wire:model="descripcion" type="text" class="form-control" id="descripcion" placeholder="Descripcion">@error('descripcion') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="nivel_riesgo"></label>
                <input wire:model="nivel_riesgo" type="text" class="form-control" id="nivel_riesgo" placeholder="Nivel Riesgo">@error('nivel_riesgo') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="registro_fotografico"></label>
                <input wire:model="registro_fotografico" type="text" class="form-control" id="registro_fotografico" placeholder="Registro Fotografico">@error('registro_fotografico') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="accion_a_tomar"></label>
                <input wire:model="accion_a_tomar" type="text" class="form-control" id="accion_a_tomar" placeholder="Accion A Tomar">@error('accion_a_tomar') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="responsable_id"></label>
                <input wire:model="responsable_id" type="text" class="form-control" id="responsable_id" placeholder="Responsable Id">@error('responsable_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="cargo_id"></label>
                <input wire:model="cargo_id" type="text" class="form-control" id="cargo_id" placeholder="Cargo Id">@error('cargo_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="estado"></label>
                <input wire:model="estado" type="text" class="form-control" id="estado" placeholder="Estado">@error('estado') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="fecha_ejecucion"></label>
                <input wire:model="fecha_ejecucion" type="text" class="form-control" id="fecha_ejecucion" placeholder="Fecha Ejecucion">@error('fecha_ejecucion') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
                <button type="button" wire:click.prevent="store()" class="btn btn-primary close-modal">Save</button>
            </div>
        </div>
    </div>
</div>
