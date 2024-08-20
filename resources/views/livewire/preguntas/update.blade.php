<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updatePreguntaModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">                <h5 class="modal-title" id="updateModalLabel">Actualizar Pregunta</h5>
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
                            <label for="pregunta">Pregunta*</label>
                            <textarea wire:model.defer="pregunta" class="form-control" id="pregunta" placeholder="Pregunta"></textarea>
                            @error('pregunta') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="opciones">Opciones*</label>
                            {{-- {{dd($opciones)}} --}}
                            @foreach($opciones as $index => $opcion)
                                <div class="mb-2 input-group">
                                    <input wire:model="opciones.{{ $index }}.opcion" type="text" class="form-control" placeholder="Opción {{ $index + 1 }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-danger" type="button" wire:click="removeOpcion({{ $index }})">Eliminar</button>
                                    </div>                                
                                </div>
                            @endforeach
                            <button class="btn btn-primary" type="button" wire:click="addOpcion" @if(count($opciones) >= 5) disabled @endif>Añadir Opción</button>
                            @error('opciones') <span class="error text-danger">{{ $message }}</span> @enderror

                        </div>
                    
                        <div class="form-group">
                            <label for="solucion_id" class="form-label">Opción Correcta</label>
                            <select id="solucion_id" wire:model="solucion_id" class="form-select">
                                @foreach($opciones as $index => $opcion)
                                    <option value="{{ $index }}">{{ $opcion['opcion'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary"
                    data-dismiss="modal">Cerrar</button>

                @if ($this->selected_id == 0)
                    <button type="button" wire:click.prevent="store()"
                        class="btn btn-primary close-modal">Guardar</button>
                @else
                    <button type="button" wire:click.prevent="update()" class="btn btn-primary"
                        @if (!$this->updateMode) disabled @endif>Guardar</button>
                @endif
            </div>
       </div>
    </div>
</div>
