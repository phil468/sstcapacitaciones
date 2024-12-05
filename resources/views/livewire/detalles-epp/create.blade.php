<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                <h5 class="modal-title h5" id="createDataModalLabel">Nuevo Detalles Epp</h5>
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
                <label for="item">Item</label>
                <input wire:model.defer="item" type="text" class="form-control" id="item" placeholder="Item">@error('item') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="nombre_trabajador">Nombre Trabajador</label>
                <input wire:model.defer="nombre_trabajador" type="text" class="form-control" id="nombre_trabajador" placeholder="Nombre Trabajador">@error('nombre_trabajador') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="dni">Dni</label>
                <input wire:model.defer="dni" type="text" class="form-control" id="dni" placeholder="Dni">@error('dni') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="cargo">Cargo</label>
                <input wire:model.defer="cargo" type="text" class="form-control" id="cargo" placeholder="Cargo">@error('cargo') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="casco_tiene">Casco Tiene</label>
                <input wire:model.defer="casco_tiene" type="text" class="form-control" id="casco_tiene" placeholder="Casco Tiene">@error('casco_tiene') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="casco_uso">Casco Uso</label>
                <input wire:model.defer="casco_uso" type="text" class="form-control" id="casco_uso" placeholder="Casco Uso">@error('casco_uso') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="casco_condicion">Casco Condicion</label>
                <input wire:model.defer="casco_condicion" type="text" class="form-control" id="casco_condicion" placeholder="Casco Condicion">@error('casco_condicion') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="zapatos_tiene">Zapatos Tiene</label>
                <input wire:model.defer="zapatos_tiene" type="text" class="form-control" id="zapatos_tiene" placeholder="Zapatos Tiene">@error('zapatos_tiene') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="zapatos_uso">Zapatos Uso</label>
                <input wire:model.defer="zapatos_uso" type="text" class="form-control" id="zapatos_uso" placeholder="Zapatos Uso">@error('zapatos_uso') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="zapatos_condicion">Zapatos Condicion</label>
                <input wire:model.defer="zapatos_condicion" type="text" class="form-control" id="zapatos_condicion" placeholder="Zapatos Condicion">@error('zapatos_condicion') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="lentes_tiene">Lentes Tiene</label>
                <input wire:model.defer="lentes_tiene" type="text" class="form-control" id="lentes_tiene" placeholder="Lentes Tiene">@error('lentes_tiene') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="lentes_uso">Lentes Uso</label>
                <input wire:model.defer="lentes_uso" type="text" class="form-control" id="lentes_uso" placeholder="Lentes Uso">@error('lentes_uso') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="lentes_condicion">Lentes Condicion</label>
                <input wire:model.defer="lentes_condicion" type="text" class="form-control" id="lentes_condicion" placeholder="Lentes Condicion">@error('lentes_condicion') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="respirador_tiene">Respirador Tiene</label>
                <input wire:model.defer="respirador_tiene" type="text" class="form-control" id="respirador_tiene" placeholder="Respirador Tiene">@error('respirador_tiene') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="respirador_uso">Respirador Uso</label>
                <input wire:model.defer="respirador_uso" type="text" class="form-control" id="respirador_uso" placeholder="Respirador Uso">@error('respirador_uso') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="respirador_condicion">Respirador Condicion</label>
                <input wire:model.defer="respirador_condicion" type="text" class="form-control" id="respirador_condicion" placeholder="Respirador Condicion">@error('respirador_condicion') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="protector_auditivo_tiene">Protector Auditivo Tiene</label>
                <input wire:model.defer="protector_auditivo_tiene" type="text" class="form-control" id="protector_auditivo_tiene" placeholder="Protector Auditivo Tiene">@error('protector_auditivo_tiene') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="protector_auditivo_uso">Protector Auditivo Uso</label>
                <input wire:model.defer="protector_auditivo_uso" type="text" class="form-control" id="protector_auditivo_uso" placeholder="Protector Auditivo Uso">@error('protector_auditivo_uso') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="protector_auditivo_condicion">Protector Auditivo Condicion</label>
                <input wire:model.defer="protector_auditivo_condicion" type="text" class="form-control" id="protector_auditivo_condicion" placeholder="Protector Auditivo Condicion">@error('protector_auditivo_condicion') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="guantes_tiene">Guantes Tiene</label>
                <input wire:model.defer="guantes_tiene" type="text" class="form-control" id="guantes_tiene" placeholder="Guantes Tiene">@error('guantes_tiene') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="guantes_uso">Guantes Uso</label>
                <input wire:model.defer="guantes_uso" type="text" class="form-control" id="guantes_uso" placeholder="Guantes Uso">@error('guantes_uso') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="guantes_condicion">Guantes Condicion</label>
                <input wire:model.defer="guantes_condicion" type="text" class="form-control" id="guantes_condicion" placeholder="Guantes Condicion">@error('guantes_condicion') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="otros">Otros</label>
                <input wire:model.defer="otros" type="text" class="form-control" id="otros" placeholder="Otros">@error('otros') <span class="error text-danger">{{ $message }}</span> @enderror
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
