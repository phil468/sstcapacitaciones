<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                <h5 class="h5 modal-title" id="updateModalLabel">Actualizar Audit</h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
					<input type="hidden" wire:model="selected_id">
            <div class="form-group">
                <label for="user_type">User Type</label>
                <input wire:model.defer="user_type" type="text" class="form-control" id="user_type" placeholder="User Type">@error('user_type') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="user_id">User Id</label>
                <input wire:model.defer="user_id" type="text" class="form-control" id="user_id" placeholder="User Id">@error('user_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="event">Event</label>
                <input wire:model.defer="event" type="text" class="form-control" id="event" placeholder="Event">@error('event') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="auditable_type">Auditable Type</label>
                <input wire:model.defer="auditable_type" type="text" class="form-control" id="auditable_type" placeholder="Auditable Type">@error('auditable_type') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="auditable_id">Auditable Id</label>
                <input wire:model.defer="auditable_id" type="text" class="form-control" id="auditable_id" placeholder="Auditable Id">@error('auditable_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="old_values">Old Values</label>
                <input wire:model.defer="old_values" type="text" class="form-control" id="old_values" placeholder="Old Values">@error('old_values') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="new_values">New Values</label>
                <input wire:model.defer="new_values" type="text" class="form-control" id="new_values" placeholder="New Values">@error('new_values') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="url">Url</label>
                <input wire:model.defer="url" type="text" class="form-control" id="url" placeholder="Url">@error('url') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="ip_address">Ip Address</label>
                <input wire:model.defer="ip_address" type="text" class="form-control" id="ip_address" placeholder="Ip Address">@error('ip_address') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="user_agent">User Agent</label>
                <input wire:model.defer="user_agent" type="text" class="form-control" id="user_agent" placeholder="User Agent">@error('user_agent') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="tags">Tags</label>
                <input wire:model.defer="tags" type="text" class="form-control" id="tags" placeholder="Tags">@error('tags') <span class="error text-danger">{{ $message }}</span> @enderror
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
