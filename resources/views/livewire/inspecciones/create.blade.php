<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                <h5 class="modal-title h5" id="createDataModalLabel">Nuevo Inspeccione</h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
				<form>
            <div class="form-group">
                <label for="empresa_id">Empresa Id</label>
                <input wire:model.defer="empresa_id" type="text" class="form-control" id="empresa_id" placeholder="Empresa Id">@error('empresa_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="area_id">Area Id</label>
                <input wire:model.defer="area_id" type="text" class="form-control" id="area_id" placeholder="Area Id">@error('area_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="tipo_inspeccion">Tipo Inspeccion</label>
                <input wire:model.defer="tipo_inspeccion" type="text" class="form-control" id="tipo_inspeccion" placeholder="Tipo Inspeccion">@error('tipo_inspeccion') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="vigencia_inicio">Vigencia Inicio</label>
                <input wire:model.defer="vigencia_inicio" type="text" class="form-control" id="vigencia_inicio" placeholder="Vigencia Inicio">@error('vigencia_inicio') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="vigencia_fin">Vigencia Fin</label>
                <input wire:model.defer="vigencia_fin" type="text" class="form-control" id="vigencia_fin" placeholder="Vigencia Fin">@error('vigencia_fin') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="comentario">Comentario</label>
                <input wire:model.defer="comentario" type="text" class="form-control" id="comentario" placeholder="Comentario">@error('comentario') <span class="error text-danger">{{ $message }}</span> @enderror
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
