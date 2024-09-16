<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateSesionModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">                <h5 class="modal-title" id="updateModalLabel">Actualizar Sesione</h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
					<input type="hidden" wire:model="selected_id">
                        <div class="form-group">
                            <label for="capacitacion_id">Capacitacion*</label>
                            @isset($capacitacion)
                                <input type="text" class="form-control" name="capacitacion" id="capacitacion" value="{{ $capacitacion->tema->name }}" disabled >
                            @else
                                <input wire:model="capacitacion_id" type="text" class="form-control" id="capacitacion_id" placeholder="Capacitacion Id">@error('capacitacion_id') <span class="error text-danger">{{ $message }}</span> @enderror                   
                            @endisset
                        </div>
                        <div class="form-group">
                            <label for="numero_de_sesion">Número De Sesión*</label>
                            <input wire:model.defer="numero_de_sesion" type="text" class="form-control" id="numero_de_sesion" placeholder="Numero De Sesion">@error('numero_de_sesion') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        {{--camp name--}}

                        <div class="form-group">
                            <label for="name">Nombre*</label>
                            <input wire:model.defer="name" type="text" class="form-control" id="name" placeholder="Nombre">@error('name') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        
                        
                        <div class="form-group">
                            <label for="video">Video * máx.(20 MB): </label>
                            {{--aquí se va a subir el archivo--}}
                            <input type="file" name="video" id="video" wire:model="video" class="form-control">
                            @error('video')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror 
                            @if ($video)
                                    <video width="100%" height="240" controls>
                                        <source src="{{ Storage::disk('video_sesiones')->url($video) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                            @endif
                        </div>
                        
                        
                        {{-- <div class="form-group">
                            <label for="fecha">Fecha</label>
                            <input wire:model="fecha" type="text" class="form-control" id="fecha" placeholder="Fecha">@error('fecha') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="hora_inicio">Hora Inicio</label>
                            <input wire:model="hora_inicio" type="text" class="form-control" id="hora_inicio" placeholder="Hora Inicio">@error('hora_inicio') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="hora_fin">Hora Fin</label>
                            <input wire:model="hora_fin" type="text" class="form-control" id="hora_fin" placeholder="Hora Fin">@error('hora_fin') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div> --}}

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary"
                    data-dismiss="modal">Cerrar</button>

                @if ($this->selected_id == 0)
                    <button type="button" wire:click.prevent="store()"
                        class="btn btn-vanguard close-modal">Guardar</button>
                @else
                    <button type="button" wire:click.prevent="update()" class="btn btn-vanguard"
                        @if (!$this->updateMode) disabled @endif>Guardar</button>
                @endif
            </div>
       </div>
    </div>
</div>
