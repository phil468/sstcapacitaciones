<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                <h5 class="modal-title h5" id="createDataModalLabel">Nuevo Alerta</h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input wire:model.defer="name" type="text" class="form-control" id="name" placeholder="Name">@error('name') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <input wire:model.defer="estado" type="checkbox" class="form-control" id="estado">@error('estado') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="dias">Días</label>
                        <input wire:model.defer="dias" type="number" class="form-control" id="dias" placeholder="Días">@error('dias') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="condicion">Condición (antes/despues)</label>
                        <input wire:model.defer="condicion" type="text" class="form-control" id="condicion" placeholder="Condición">@error('condicion') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="campo">Campo (fecha_inicio/fecha_fin)</label>
                        <input wire:model.defer="campo" type="text" class="form-control" id="campo" placeholder="Campo">@error('campo') <span class="error text-danger">{{ $message }}</span> @enderror
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