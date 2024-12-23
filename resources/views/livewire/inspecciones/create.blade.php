<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createDataModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDataModalLabel">Create New Inspeccione</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
				<form>
            <div class="form-group">
                <label for="empresa_id"></label>
                <input wire:model="empresa_id" type="text" class="form-control" id="empresa_id" placeholder="Empresa Id">@error('empresa_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="area_id"></label>
                <input wire:model="area_id" type="text" class="form-control" id="area_id" placeholder="Area Id">@error('area_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="tipo_inspeccion"></label>
                <input wire:model="tipo_inspeccion" type="text" class="form-control" id="tipo_inspeccion" placeholder="Tipo Inspeccion">@error('tipo_inspeccion') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="vigencia_inicio"></label>
                <input wire:model="vigencia_inicio" type="text" class="form-control" id="vigencia_inicio" placeholder="Vigencia Inicio">@error('vigencia_inicio') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="vigencia_fin"></label>
                <input wire:model="vigencia_fin" type="text" class="form-control" id="vigencia_fin" placeholder="Vigencia Fin">@error('vigencia_fin') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="comentario"></label>
                <input wire:model="comentario" type="text" class="form-control" id="comentario" placeholder="Comentario">@error('comentario') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="razon_social"></label>
                <input wire:model="razon_social" type="text" class="form-control" id="razon_social" placeholder="Razon Social">@error('razon_social') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="ruc"></label>
                <input wire:model="ruc" type="text" class="form-control" id="ruc" placeholder="Ruc">@error('ruc') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="domicilio"></label>
                <input wire:model="domicilio" type="text" class="form-control" id="domicilio" placeholder="Domicilio">@error('domicilio') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="actividad_economica"></label>
                <input wire:model="actividad_economica" type="text" class="form-control" id="actividad_economica" placeholder="Actividad Economica">@error('actividad_economica') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="numero_registro"></label>
                <input wire:model="numero_registro" type="text" class="form-control" id="numero_registro" placeholder="Numero Registro">@error('numero_registro') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="tipo_inspeccion_otro"></label>
                <input wire:model="tipo_inspeccion_otro" type="text" class="form-control" id="tipo_inspeccion_otro" placeholder="Tipo Inspeccion Otro">@error('tipo_inspeccion_otro') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="fecha_inspeccion"></label>
                <input wire:model="fecha_inspeccion" type="text" class="form-control" id="fecha_inspeccion" placeholder="Fecha Inspeccion">@error('fecha_inspeccion') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="hora_inspeccion"></label>
                <input wire:model="hora_inspeccion" type="text" class="form-control" id="hora_inspeccion" placeholder="Hora Inspeccion">@error('hora_inspeccion') <span class="error text-danger">{{ $message }}</span> @enderror
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
