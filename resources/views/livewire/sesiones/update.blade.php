<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateSesionModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <style>
        .custom-upload-button {
            display: inline-block;
            padding: 10px 20px;
            /* background-color: #; */
            /* color: white; */
            border-radius: 5px;
            cursor: pointer;
        }
        .custom-upload-button:hover {
            /* background-color: #0056b3; */
        }
        .custom-file-input {
            display: none;
        }
    </style>
    
    <div class="modal-dialog" role="document">
       <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">                
                <h5 class="modal-title" id="updateModalLabel">Actualizar Sesión</h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="update({{$selected_id}})">
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
                            <label for="video">Video * máx.(60 MB): </label>
                            {{--aquí se va a subir el archivo--}}
                            <div>
                                <input type="file" name="video" id="video" wire:model="video" class="custom-file-input" wire:change="resetVideoPreview">
                                <label class="custom-upload-button btn btn-outline-vanguard" for="video">Seleccionar archivo</label>
                            </div>
                            @error('video')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                            @if ($updateMode)
                            
                                @if ($video)
                                    <video width="100%" height="240" controls>
                                        <source src="{{ $video->temporaryUrl() }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @elseif ($selected_id && $videoUrl)
                                    <video width="100%" height="240" controls>
                                        <source src="{{ Storage::disk('video_sesiones')->url($videoUrl) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @endif
                                
                            @endif
                            <div wire:loading wire:target="video" class="mt-2">
                                <span class="text-info">Cargando video...</span>
                            </div>
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

                        <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary"
                        data-dismiss="modal">Cerrar</button>
    
                        @if ($this->selected_id == 0)
                            <button 
                                type="submit"
                                class="btn btn-vanguard close-modal"
                                @if (!$this->video) disabled @endif
                                 {{-- wire:loading.attr="disabled" wire:target="video" --}}
                                >Guardar
                            </button>
                        @else
                            <button 
                                type="submit" 
                                class="btn btn-vanguard"
                                @if (!$this->video && !$videoUrl) disabled @endif
                                {{-- wire:loading.attr="disabled" wire:target="video" --}}
                                >Guardar
                            </button>
                        @endif

                </form>
            </div>
            {{-- <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary"
                    data-dismiss="modal">Cerrar</button>

                @if ($this->selected_id == 0)
                    <button type="submit"
                        class="btn btn-vanguard close-modal">Guardar</button>
                @else
                    <button type="submit" 
                    class="btn btn-vanguard"
                        @if (!$this->updateMode) disabled @endif>Guardar</button>
                @endif
            </div> --}}
       </div>
    </div>
</div>
