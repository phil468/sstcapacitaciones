<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                <h5 class="modal-title h5" id="createDataModalLabel">Nuevo Gabinete</h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
				<form>
            <div class="form-group">
                <label for="numero_gabinete">Numero Gabinete</label>
                <input wire:model.defer="numero_gabinete" type="text" class="form-control" id="numero_gabinete" placeholder="Numero Gabinete">@error('numero_gabinete') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="ubicacion">Ubicacion</label>
                <input wire:model.defer="ubicacion" type="text" class="form-control" id="ubicacion" placeholder="Ubicacion">@error('ubicacion') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="inspeccion_id">Inspeccion Id</label>
                <input wire:model.defer="inspeccion_id" type="text" class="form-control" id="inspeccion_id" placeholder="Inspeccion Id">@error('inspeccion_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="enrollada_correctamente">Enrollada Correctamente</label>
                <input wire:model.defer="enrollada_correctamente" type="text" class="form-control" id="enrollada_correctamente" placeholder="Enrollada Correctamente">@error('enrollada_correctamente') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="acoples_estado">Acoples Estado</label>
                <input wire:model.defer="acoples_estado" type="text" class="form-control" id="acoples_estado" placeholder="Acoples Estado">@error('acoples_estado') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="limpieza_manguera">Limpieza Manguera</label>
                <input wire:model.defer="limpieza_manguera" type="text" class="form-control" id="limpieza_manguera" placeholder="Limpieza Manguera">@error('limpieza_manguera') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="empaques_estado">Empaques Estado</label>
                <input wire:model.defer="empaques_estado" type="text" class="form-control" id="empaques_estado" placeholder="Empaques Estado">@error('empaques_estado') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="pintura_gabinete">Pintura Gabinete</label>
                <input wire:model.defer="pintura_gabinete" type="text" class="form-control" id="pintura_gabinete" placeholder="Pintura Gabinete">@error('pintura_gabinete') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="limpieza_gabinete">Limpieza Gabinete</label>
                <input wire:model.defer="limpieza_gabinete" type="text" class="form-control" id="limpieza_gabinete" placeholder="Limpieza Gabinete">@error('limpieza_gabinete') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="vidrio_estado">Vidrio Estado</label>
                <input wire:model.defer="vidrio_estado" type="text" class="form-control" id="vidrio_estado" placeholder="Vidrio Estado">@error('vidrio_estado') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="senalizacion">Senalizacion</label>
                <input wire:model.defer="senalizacion" type="text" class="form-control" id="senalizacion" placeholder="Senalizacion">@error('senalizacion') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="piton_obstruido">Piton Obstruido</label>
                <input wire:model.defer="piton_obstruido" type="text" class="form-control" id="piton_obstruido" placeholder="Piton Obstruido">@error('piton_obstruido') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="piton_estado">Piton Estado</label>
                <input wire:model.defer="piton_estado" type="text" class="form-control" id="piton_estado" placeholder="Piton Estado">@error('piton_estado') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="valvula_principal_estado">Valvula Principal Estado</label>
                <input wire:model.defer="valvula_principal_estado" type="text" class="form-control" id="valvula_principal_estado" placeholder="Valvula Principal Estado">@error('valvula_principal_estado') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="valvula_principal_abierta">Valvula Principal Abierta</label>
                <input wire:model.defer="valvula_principal_abierta" type="text" class="form-control" id="valvula_principal_abierta" placeholder="Valvula Principal Abierta">@error('valvula_principal_abierta') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="manometro_estado">Manometro Estado</label>
                <input wire:model.defer="manometro_estado" type="text" class="form-control" id="manometro_estado" placeholder="Manometro Estado">@error('manometro_estado') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="valvula_angular_estado">Valvula Angular Estado</label>
                <input wire:model.defer="valvula_angular_estado" type="text" class="form-control" id="valvula_angular_estado" placeholder="Valvula Angular Estado">@error('valvula_angular_estado') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="observaciones">Observaciones</label>
                <input wire:model.defer="observaciones" type="text" class="form-control" id="observaciones" placeholder="Observaciones">@error('observaciones') <span class="error text-danger">{{ $message }}</span> @enderror
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
