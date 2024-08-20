<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateRegistroModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
       <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                <div class="float-left">
                    <h5 class="modal-title" id="updateModalLabel">
                    @if ($this->selected_id == 0)                    
                        Nuevo Personal
                    @else
                        Actualizar Personal
                    @endif
                    </h5>
                </div>
                {{-- @if (session()->has('message'))
                    <div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
                @endif --}}
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span wire:click="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <fieldset class="row" 
                    @if (!$this->updateMode)                    
                        disabled
                    @endif
                    >
                        @if (!$this->updateMode)
                            <div class="col-12 alert alert-warning" role="alert">
                                Cargando ...
                            </div>
                        @endif

                        <input type="hidden" wire:model="selected_id">
                        
                        <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                            <label for="Nombre">NOMBRE</label>
                            <br>
                            {{ $name_personal }}
                        </div>
                        
                        <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                            @if($selectedFromRegistroTable)
                                <label>
                                    <input type="checkbox" wire:model="edit_gerencia"> Editar Gerencia
                                </label>
                                <br>
                            @endif
                                <label for="gerencia_id">Gerencia *</label>
                                <div wire:ignore>
                                    <select 
                                        name="gerencia_id"
                                        class="form-control" 
                                        id="gerencia_id" placeholder="Gerencias">
                                    </select>
                                </div>
                                @error('gerencia_id')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                        </div>

                        <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                            @if($selectedFromRegistroTable)
                                <label>
                                    <input type="checkbox" wire:model="edit_sede"> Editar Sede
                                </label>
                                <br>
                            @endif
                            <label for="sede_id">Sede *</label>
                            <div wire:ignore>
                                <select 
                                    name="sede_id"
                                    class="form-control" id="sede_id"
                                    placeholder="Sede">
                                </select>
                            </div>
                            @error('sede_id')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-sm-6 col-md-6 col-lg-4 col-xl-3">
                            @if($selectedFromRegistroTable)
                                <label>
                                    <input type="checkbox" wire:model="edit_area"> Editar Área
                                </label>
                                <br>
                            @endif
                            <label for="area_id">Area *</label>
                            <div wire:ignore>
                            <select 
                            name="area_id"
                                class="form-control" id="area_id"
                                placeholder="Area">
                            </select>
                            </div>
                            @error('area_id')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        @if ($es_aula_virtual)
                            <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                @if($selectedFromRegistroTable)
                                    <label>
                                        <input type="checkbox" wire:model="edit_fecha_inicio"> Editar Fecha Inicio
                                    </label>
                                    <br>
                                @endif
                                <label for="fecha_inicio">Fecha Inicio*</label>
                                <input wire:model="fecha_inicio" type="datetime-local" class="form-control" id="fecha_inicio" placeholder="Fecha Inicio">@error('fecha_inicio') <span class="error text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                @if($selectedFromRegistroTable)
                                    <label>
                                        <input type="checkbox" wire:model="edit_fecha_fin"> Editar Fecha Fin
                                    </label>
                                    <br>
                                @endif
                                <label for="fecha_fin">Fecha Fin*</label>
                                <input wire:model="fecha_fin" type="datetime-local" class="form-control" id="fecha_fin" placeholder="Fecha Fin">@error('fecha_fin') <span class="error text-danger">{{ $message }}</span> @enderror
                            </div>
                        @endif
                        
                        @if (session()->has('errorEdicionMasiva'))
                        
                            <div class="col-12">
                                <div wire:poll.4s class="btn btn-sm btn-danger" style="margin-top:0px; margin-bottom:0px;"> {{ session('errorEdicionMasiva') }} </div>
                            </div>
                        
                        @endif
                        
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="cancel()" class="btn btn-secondary"
                data-dismiss="modal"
                >Cerrar</button>

                
                @if ($selectedFromRegistroTable)
                    <button type="button" wire:click.prevent="updateMasivo()" class="btn btn-primary" 
                    @if (!$this->updateMode)                    
                        disabled
                    @endif >Guardar</button>                     
                @else
                    @if ($this->selected_id != 0)                    
                        <button type="button" wire:click.prevent="store()" class="btn btn-primary close-modal">Guardar</button>
                    @else
                        <button type="button" wire:click.prevent="update()" class="btn btn-primary" 
                        @if (!$this->updateMode)                    
                            disabled
                        @endif >Guardar</button>   
                    @endif
                @endif
            </div>
       </div>
    </div>
</div>
